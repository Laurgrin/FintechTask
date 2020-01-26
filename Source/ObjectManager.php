<?php declare(strict_types = 1);

namespace Source;

use Closure;
use JsonException;
use ReflectionClass;
use ReflectionException;
use Source\Exception\ContainerException;
use Source\Exception\FileNotFoundException;

class ObjectManager
{
    /**
     * @var \Source\ObjectManager
     */
    protected static $instance = null;
    
    /**
     * @var array
     */
    protected $instances = [];
    
    /**
     * @var array
     */
    protected $implementations;
    
    /**
     * Gets the DI config data.
     *
     * @return array
     * @throws \Source\Exception\FileNotFoundException
     */
    public function getDiConfig(): array
    {
        if (empty($this->implementations)) {
            if (is_readable(DI_PATH)) {
                $this->implementations = json_decode(
                    file_get_contents(DI_PATH),
                    true,
                    512,
                    JSON_THROW_ON_ERROR
                );
            } else {
                throw new FileNotFoundException('Dependency injection configuration file not found');
            }
        }
        
        return $this->implementations;
    }
    
    /**
     * Returns an instance of object manager.
     *
     * @return \Source\ObjectManager
     */
    public static function getInstance(): ObjectManager
    {
        if (self::$instance === null) {
            self::$instance = new ObjectManager();
        }
        
        return self::$instance;
    }
    
    /**
     * Sets the implementation for an abstract.
     *
     * @param      $abstract
     * @param null $concrete
     */
    public function set($abstract, $concrete = NULL)
    {
        if ($concrete === NULL) {
            $concrete = $abstract;
        }
        $this->instances[$abstract] = $concrete;
    }
    
    /**
     * Gets the implementation of an abstract
     *
     * @param       $abstract
     * @param array $parameters
     *
     * @return mixed|null|object
     * @throws ReflectionException
     * @throws ContainerException
     * @throws JsonException
     * @throws \Source\Exception\FileNotFoundException
     */
    public function get($abstract, $parameters = [])
    {
        
        
        if (!isset($this->instances[$abstract])) {
            $this->set($abstract);
        }
        
        return $this->resolve($this->instances[$abstract], $parameters);
    }
    
    /**
     * Resolve a single object instantiation.
     *
     * @param $concrete
     * @param $parameters
     *
     * @return mixed|object
     * @throws ReflectionException
     * @throws ContainerException
     * @throws JsonException
     * @throws \Source\Exception\FileNotFoundException
     */
    public function resolve($concrete, $parameters)
    {
        $implementations = $this->getDiConfig();
        
        if ($concrete instanceof Closure) {
            return $concrete($this, $parameters);
        }
        $reflector = new ReflectionClass($concrete);
        
        if (!$reflector->isInstantiable()) {
            if (array_key_exists($concrete, $implementations)) {
                $reflector = new ReflectionClass($implementations[$reflector->getName()]);
            } else {
                throw new ContainerException(sprintf('Class %s is not instantiable', $concrete));
            }
        }
        
        $constructor = $reflector->getConstructor();
        if ($constructor === null) {
            return $reflector->newInstance();
        }
        
        $parameters   = $constructor->getParameters();
        $dependencies = $this->getDependencies($parameters);
        
        return $reflector->newInstanceArgs($dependencies);
    }
    
    /**
     * Resolves dependencies recursively.
     *
     * @param \ReflectionParameter[] $parameters
     *
     * @return array
     * @throws JsonException
     * @throws \ReflectionException
     * @throws \Source\Exception\ContainerException
     * @throws \Source\Exception\FileNotFoundException
     */
    public function getDependencies($parameters): array
    {
        $dependencies = [];
        foreach ($parameters as $parameter) {
            $dependency = $parameter->getClass();
            if ($dependency === NULL) {
                if ($parameter->isDefaultValueAvailable()) {
                    $dependencies[] = $parameter->getDefaultValue();
                } else {
                    throw new ContainerException(sprintf('Can not resolve class dependency %s', $parameter->name));
                }
            } else {
                $dependencies[] = $this->get($dependency->name);
            }
        }
        
        return $dependencies;
    }
    
    /**
     * Left as a stub, we don't create this object directly.
     *
     * ObjectManager constructor.
     */
    protected function __construct() { }
}
<?php

namespace Source;

use ReflectionClass;
use Source\Exception\DefinitionNotFoundException;
use Source\Exception\FileNotFoundException;

class ObjectManager
{
    /**
     * @var array
     */
    protected $diConfig;
    
    /**
     * @var array
     */
    protected $servicesConfig;
    
    /**
     * ObjectManager constructor.
     * @throws \Source\Exception\FileNotFoundException
     */
    public function __construct()
    {
        if (!file_exists(DI_PATH)) {
            throw new FileNotFoundException("The DI config file was not found under " . DI_PATH);
        }
        
        if (!file_exists(SERVICES_PATH)) {
            throw new FileNotFoundException("The services config file was not found under " . SERVICES_PATH);
        }
        
        $this->diConfig       = json_decode(file_get_contents(DI_PATH), true);
        $this->servicesConfig = json_decode(file_get_contents(SERVICES_PATH), true);
    }
    
    /**
     * Returns an instance of the requested class, as defined in services.json
     *
     * @param string $service
     *
     * @return mixed
     * @throws \Source\Exception\DefinitionNotFoundException
     * @throws \ReflectionException
     */
    public function build(string $service)
    {
        if (!array_key_exists($service, $this->servicesConfig)) {
            throw new DefinitionNotFoundException("Service $service not defined in " . SERVICES_PATH);
        }
        
        $fqn = $this->servicesConfig[$service];
        return $this->create($fqn);
    }
    
    /**
     * Creates an object, recursively checking it's constructor arguments. This, of course, relies on those arguments
     * being interfaces and being defined in di.json
     *
     * @param string $fqn
     *
     * @return mixed
     * @throws \ReflectionException
     * @throws \Source\Exception\DefinitionNotFoundException
     */
    protected function create(string $fqn)
    {
        $implementationFqn = $this->getImplementationClassName($fqn);
        $constructorArgumentTypes = $this->getConstructorArgumentTypes($implementationFqn);
        $constructorArguments = [];
        if (!empty($constructorArgumentTypes)) {
            foreach ($constructorArgumentTypes as $constructorArgumentType) {
                $constructorArguments[] = $this->create($constructorArgumentType);
            }
            
            return new $implementationFqn(...$constructorArguments);
        }
        
        return new $implementationFqn();
    }
    
    /**
     * Finds and returns the FQN of the interface implementation.
     *
     * @param string $fqn
     *
     * @return string
     * @throws \Source\Exception\DefinitionNotFoundException
     */
    protected function getImplementationClassName(string $fqn): string
    {
        if (!array_key_exists($fqn, $this->diConfig)) {
            throw new DefinitionNotFoundException("Implementation for $fqn not set in " . DI_PATH);
        }
        
        return $this->diConfig[$fqn];
    }
    
    
    /**
     * Gets the argument types of class constructor
     *
     * @param string $fqn
     *
     * @return array
     * @throws \ReflectionException
     */
    protected function getConstructorArgumentTypes(string $fqn): array
    {
        $argumentTypes = [];
        
        $reflection = new ReflectionClass($fqn);
        $args = $reflection->getConstructor()->getParameters();
        
        foreach ($args as $arg) {
            $argumentTypes[] = $arg->getClass()->getName();
        }
        
        return $argumentTypes;
    }
}
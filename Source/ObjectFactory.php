<?php

namespace Source;

use ReflectionClass;
use Source\Exception\DefinitionNotFoundException;
use Source\Exception\FileNotFoundException;

class ObjectFactory
{
    /**
     * @var array
     */
    protected static $diConfig;
    
    /**
     * @var array
     */
    protected static $servicesConfig;
    
    /**
     * @var bool
     */
    protected static $initialized = false;
    
    /**
     * ObjectFactory constructor.
     * @throws \Source\Exception\FileNotFoundException
     */
    protected static function init()
    {
        if (!file_exists(DI_PATH)) {
            throw new FileNotFoundException("The DI config file was not found under " . DI_PATH);
        }
        
        if (!file_exists(OBJECTS_PATH)) {
            throw new FileNotFoundException("The services config file was not found under " . OBJECTS_PATH);
        }
    
        ObjectFactory::$diConfig       = json_decode(file_get_contents(DI_PATH), true);
        ObjectFactory::$servicesConfig = json_decode(file_get_contents(OBJECTS_PATH), true);
    }
    
    /**
     * Returns an instance of the requested class, as defined in objects.json
     *
     * @param string $object
     *
     * @return mixed
     * @throws \Source\Exception\DefinitionNotFoundException
     * @throws \ReflectionException
     * @throws \Source\Exception\FileNotFoundException
     */
    public static function build(string $object)
    {
        if (!ObjectFactory::$initialized) {
            ObjectFactory::init();
        }
        
        if (!array_key_exists($object, ObjectFactory::$servicesConfig)) {
            throw new DefinitionNotFoundException("Service $object not defined in " . OBJECTS_PATH);
        }
        
        $fqn = ObjectFactory::$servicesConfig[$object];
        return ObjectFactory::create($fqn);
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
    protected static function create(string $fqn)
    {
        $implementationFqn = ObjectFactory::getImplementationClassName($fqn);
        $constructorArgumentTypes = ObjectFactory::getConstructorArgumentTypes($implementationFqn);
        $constructorArguments = [];
        if (!empty($constructorArgumentTypes)) {
            foreach ($constructorArgumentTypes as $constructorArgumentType) {
                $constructorArguments[] = ObjectFactory::create($constructorArgumentType);
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
    protected static function getImplementationClassName(string $fqn): string
    {
        if (!array_key_exists($fqn, ObjectFactory::$diConfig)) {
            throw new DefinitionNotFoundException("Implementation for $fqn not set in " . DI_PATH);
        }
        
        return ObjectFactory::$diConfig[$fqn];
    }
    
    
    /**
     * Gets the argument types of class constructor
     *
     * @param string $fqn
     *
     * @return array
     * @throws \ReflectionException
     */
    protected static function getConstructorArgumentTypes(string $fqn): array
    {
        $argumentTypes = [];
        
        $reflection = new ReflectionClass($fqn);
        if ($args = $reflection->getConstructor()) {
            $args = $args->getParameters();
    
            foreach ($args as $arg) {
                $argumentTypes[] = $arg->getClass()->getName();
            }
        }
        
        return $argumentTypes;
    }
    
    /**
     * Left as a stub, we don't create this object directly.
     *
     * ObjectFactory constructor.
     */
    protected function __construct() { }
}
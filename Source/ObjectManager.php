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
     * ObjectManager constructor.
     * @throws \Source\Exception\FileNotFoundException
     */
    protected static function init()
    {
        if (!file_exists(DI_PATH)) {
            throw new FileNotFoundException("The DI config file was not found under " . DI_PATH);
        }
        
        if (!file_exists(SERVICES_PATH)) {
            throw new FileNotFoundException("The services config file was not found under " . SERVICES_PATH);
        }
    
        ObjectManager::$diConfig       = json_decode(file_get_contents(DI_PATH), true);
        ObjectManager::$servicesConfig = json_decode(file_get_contents(SERVICES_PATH), true);
    }
    
    /**
     * Returns an instance of the requested class, as defined in services.json
     *
     * @param string $service
     *
     * @return mixed
     * @throws \Source\Exception\DefinitionNotFoundException
     * @throws \ReflectionException
     * @throws \Source\Exception\FileNotFoundException
     */
    public static function build(string $service)
    {
        if (!ObjectManager::$initialized) {
            ObjectManager::init();
        }
        
        if (!array_key_exists($service, ObjectManager::$servicesConfig)) {
            throw new DefinitionNotFoundException("Service $service not defined in " . SERVICES_PATH);
        }
        
        $fqn = ObjectManager::$servicesConfig[$service];
        return ObjectManager::create($fqn);
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
        $implementationFqn = ObjectManager::getImplementationClassName($fqn);
        $constructorArgumentTypes = ObjectManager::getConstructorArgumentTypes($implementationFqn);
        $constructorArguments = [];
        if (!empty($constructorArgumentTypes)) {
            foreach ($constructorArgumentTypes as $constructorArgumentType) {
                $constructorArguments[] = ObjectManager::create($constructorArgumentType);
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
        if (!array_key_exists($fqn, ObjectManager::$diConfig)) {
            throw new DefinitionNotFoundException("Implementation for $fqn not set in " . DI_PATH);
        }
        
        return ObjectManager::$diConfig[$fqn];
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
        $args = $reflection->getConstructor()->getParameters();
        
        foreach ($args as $arg) {
            $argumentTypes[] = $arg->getClass()->getName();
        }
        
        return $argumentTypes;
    }
    
    /**
     * Left as a stub, we don't create this object directly.
     *
     * ObjectManager constructor.
     */
    protected function __construct() { }
}
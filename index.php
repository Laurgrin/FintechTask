<?php declare(strict_types = 1);
define('ROOT_PATH', realpath(__DIR__));
define('DI_PATH', ROOT_PATH . '/Config/di.json');
define('OBJECTS_PATH', ROOT_PATH . '/Config/objects.json');
define('CURRENCY_PATH', ROOT_PATH . '/Config/currency.json');

require_once(__DIR__ . '/vendor/autoload.php');
try {
    $object = \Source\ObjectFactory::build('parser');
} catch (\Source\Exception\FileNotFoundException $e) {
    die($e->getMessage());
} catch (ReflectionException $e) {
    die($e->getMessage());
} catch (\Source\Exception\DefinitionNotFoundException $e) {
    die($e->getMessage());
}

/** @var $object \Source\Parser\OperationParserInterface */
$object->parseOperations($argv[1]);
<?php declare(strict_types = 1);
define('ROOT_PATH', realpath(__DIR__));
define('DI_PATH', ROOT_PATH . '/Config/di.json');
define('OBJECTS_PATH', ROOT_PATH . '/Config/objects.json');
define('CURRENCY_PATH', ROOT_PATH . '/Config/currency.json');

require_once(__DIR__ . '/vendor/autoload.php');
try {
    $objectManager = \Source\ObjectManager::getInstance();
    $parser = $objectManager->get(\Source\Parser\OperationParser::class);
    
    /** @var $parser \Source\Parser\OperationParserInterface */
    echo $parser->parseOperations($argv[1])->getOutput();
} catch (\Source\Exception\FileNotFoundException $e) {
    die($e->getMessage());
} catch (ReflectionException $e) {
    die($e->getMessage());
} catch (JsonException $e) {
    die($e->getMessage());
} catch (\Source\Exception\ContainerException $e) {
    die($e->getMessage());
} catch (\Source\Exception\OperationTypeException $e) {
    die($e->getMessage());
} catch (\Source\Exception\UserTypeException $e) {
    die($e->getMessage());
}


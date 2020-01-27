<?php declare(strict_types = 1);
require_once (__DIR__ . './bootstrap.php');

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


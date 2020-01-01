<?php
define("DI_PATH", __DIR__ . "/Config/di.json");
define("SERVICES_PATH", __DIR__ . "/Config/services.json");

require_once(__DIR__ . "/vendor/autoload.php");
try {
    $objectManager = new \Source\ObjectManager();
} catch (\Source\Exception\FileNotFoundException $e) {
    die($e->getMessage());
} catch (ReflectionException $e) {
    die($e->getMessage());
} catch (\Source\Exception\DefinitionNotFoundException $e) {
    die($e->getMessage());
}
<?php declare(strict_types = 1);
define('ROOT_PATH', realpath(__DIR__));
define('DI_PATH', ROOT_PATH . '/Config/di.json');
define('OBJECTS_PATH', ROOT_PATH . '/Config/objects.json');
define('CURRENCY_PATH', ROOT_PATH . '/Config/currency.json');

require_once(__DIR__ . '/vendor/autoload.php');
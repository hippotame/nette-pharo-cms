<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../paths.php';
require_once __DIR__ . '/../src/helper.php';
$configurator = new Nette\Configurator();

// $configurator->setDebugMode('23.75.345.200'); // enable for your remote IP

$configurator->enableDebugger(__DIR__ . '/../log');

$configurator->setTempDirectory(__DIR__ . '/../temp');

$configurator->createRobotLoader()
        ->addDirectory(__DIR__)
        ->addDirectory(__DIR__ . '/../src')
        ->register();

$configurator->addConfig(__DIR__ . '/config/config.neon');
$configurator->addConfig(__DIR__ . '/config/config.local.neon');

$container = $configurator->createContainer();

return $container;

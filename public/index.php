<?php

use Core\Http\ResponseSender;
use Core\Application\Application;
use DI\Container;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

### Initialization

$builder = new DI\ContainerBuilder();
$builder->addDefinitions('./config/container.php');
$container = $builder->build();

$app = new Application($container);

### Routing

require 'config/routes.php';

### Running

$response = $app->run();

### Sending

$emitter = new ResponseSender();
$emitter->send($response);

$stop = microtime(true);
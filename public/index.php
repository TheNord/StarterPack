<?php

use Core\Http\ResponseSender;
use Core\Application\Application;
use Zend\Diactoros\ServerRequestFactory;

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

$request = ServerRequestFactory::fromGlobals();
$response = $app->run($request);

### Sending

$emitter = new ResponseSender();
$emitter->send($response);

$stop = microtime(true);
<?php

use Core\Http\ResponseSender;
use Core\Application\Application;
use DI\Container;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

### Initialization

$container = new Container();

require 'config/container.php';

$container->get(Core\Http\Router\Router::class);

$app = new Application($container);

### Routing

require 'config/routes.php';

### Running

$response = $app->run();

### Sending

$emitter = new ResponseSender();
$emitter->send($response);

$stop = microtime(true);
<?php

use Core\Application\Renderer;
use Core\Http\Router\Router;
use Core\Http\Router\AuraRouterAdapter;
use Aura\Router\RouterContainer;
use Core\Application\Template\Extension\RouteExtension;

/** @var \DI\Container $container */

$container->set('settings', [
	'twig' => [
        'template_path' => 'templates',
        'cache_path' => 'storage/cache',
    ],

    'database' => [
    	'pdo' => [
	        'name' => 'app',
	        'host' => '127.0.0.1',
	        'username' => 'root',
	        'password' => '',
	        'options' => [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION],
   		],
    ],
]);

$container->set(Renderer::class, function ($container) {
	$settings = $container->get('settings')['twig'];
		
    $loader = new Twig_Loader_Filesystem($settings['template_path']);
	$twig = new Twig_Environment($loader, [
	    'cache' => $settings['cache_path'],
	]);

	$twig->addExtension($container->get(RouteExtension::class));

	$renderer = new Renderer($twig);

    return $renderer;
});

$container->set(\PDO::class, function ($container) {
	$settings = $container->get('settings')['database']['pdo'];

    $dsn = 'mysql:host=' . $settings['host'] . ';dbname=' . $settings['name'];
	return new \PDO($dsn, $settings['username'], $settings['password'], $settings['options']); 
});

$container->set(Router::class, function ($container) {
	return new AuraRouterAdapter($container->get(RouterContainer::class));
});

$container->set(RouterContainer::class, function ($container) {
	return new RouterContainer();
});
<?php

use Core\Application\Renderer;
use Core\Http\Router\Router;
use Core\Http\Router\AuraRouterAdapter;
use Aura\Router\RouterContainer;
use Core\Application\Template\Extension\RouteExtension;
use Symfony\Component\Dotenv\Dotenv;

/** @var \DI\Container $container */

if (file_exists('.env')) {
    (new Dotenv())->load('.env');
} else {
    throw new \Exception('You need to configure the env file');
}

return [
	'settings' => [
		'twig' => [
	        'template_path' => 'templates',
	        'cache_path' => 'storage/cache',
	    ],

	    'database' => [
	    	'pdo' => [
		        'name' => getenv('DB_NAME'),
		        'host' => getenv('DB_HOST'),
		        'username' => getenv('DB_USERNAME'),
		        'password' => getenv('DB_PASSWORD'),
		        'options' => function () {
		        	return [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION];
		        },
	   		],
	    ],
	],

	Renderer::class => function ($container) {
		$settings = $container->get('settings')['twig'];
			
	    $loader = new Twig_Loader_Filesystem($settings['template_path']);
		$twig = new Twig_Environment($loader, [
		    'cache' => $settings['cache_path'],
		]);

		$twig->addExtension($container->get(RouteExtension::class));

		$renderer = new Renderer($twig);

	    return $renderer;
	},

	\PDO::class => function ($container) {
		$settings = $container->get('settings')['database']['pdo'];
	    $dsn = 'mysql:host=' . $settings['host'] . ';dbname=' . $settings['name'];
		return new \PDO($dsn, $settings['username'], $settings['password'], $settings['options']); 
	},

	Router::class => function ($container) {
		return new AuraRouterAdapter($container->get(RouterContainer::class));
	},
];

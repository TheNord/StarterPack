<?php

namespace Core\Application;

use Zend\Diactoros\Response\HtmlResponse;
use Core\Http\ActionResolver;
use Core\Http\Router\Router;
use Core\Http\Router\Exception\RequestNotMatchedException;
use Psr\Container\ContainerInterface;
use Aura\Router\RouterContainer;
use Psr\Http\Message\ServerRequestInterface;

class Application
{
	public $router;
	public $routeMap;
	public $container;

	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
		$this->router = $container->get(Router::class);
		$routeContainer = $container->get(RouterContainer::class);
		$this->routeMap = $routeContainer->getMap();
	}

	public function get($name, $url, $action, array $tokens = []) 
	{
		
		$this->routeMap->get($name, $url, $action)->tokens($tokens);
	}

	public function post($name, $url, $action, array $tokens = []) 
	{
		$this->routeMap->post($name, $url, $action)->tokens($tokens);
	}

	public function run(ServerRequestInterface $request) 
	{
		$resolver = new ActionResolver($this->container);
		
		try {
		    $result = $this->router->match($request);
		    foreach ($result->getAttributes() as $attribute => $value) {
		        $request = $request->withAttribute($attribute, $value);
		    }
		    $action = $resolver->resolve($result->getHandler());
		    $response = $action->handle($request);
		} catch (RequestNotMatchedException $e){
		    $response = new HtmlResponse('Undefined page', 404);
		} catch (\Exception $e) {
			$response = new HtmlResponse($e->getMessage(), $e->getCode());
		} 

		return $response;
	}
}
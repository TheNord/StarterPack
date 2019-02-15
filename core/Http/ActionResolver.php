<?php

namespace Core\Http;

use Psr\Container\ContainerInterface;

class ActionResolver
{
	public $container;

	public function __construct(ContainerInterface $container) 
	{
		$this->container = $container;
	}

    public function resolve($handler)
    {
    	$action = 'App\Http\Action\\' . $handler;
    	$res = $this->container->get($action);
    	//$v = $res->res();
		return $res;


        //return new $res();
    }
}

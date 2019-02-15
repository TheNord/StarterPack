<?php

declare(strict_types=1);

namespace Test\Feature;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Uri;
use Zend\Diactoros\Stream;
use DI\ContainerBuilder;
use Core\Application\Application;

class WebTestCase extends TestCase
{
    private $fixtures = [];

    protected function get(string $uri, array $headers = []): ResponseInterface
    {
        return $this->method($uri, 'GET', [], $headers);
    }

    protected function post(string $uri, array $params = [], array $headers = []): ResponseInterface
    {
        return $this->method($uri, 'POST', $params, $headers);
    }

    protected function method(string $uri, $method, array $params = [], array $headers = []): ResponseInterface
    {
        $body = new Stream('php://temp', 'r+');
        $body->write(json_encode($params));
        $body->rewind();

        $request = (new ServerRequest())
            ->withUri(new Uri('http://test' . $uri))
            ->withMethod($method)
            ->withBody($body);
        foreach ($headers as $name => $value) {
            $request = $request->withHeader($name, $value);
        }
        return $this->request($request);
    }

    protected function request(ServerRequestInterface $request): ResponseInterface
    {
        $response = $this->app()->run($request);
        $response->getBody()->rewind();
        return $response;
    }

    protected function loadFixtures(array $fixtures): void
    {
        $container = $this->container();
        $em = $container->get(EntityManagerInterface::class);
        $loader = new Loader();

        foreach ($fixtures as $name => $class) {
            if ($container->has($class)) {
                $fixture = $container->get($class);
            } else {
                $fixture = new $class;
            }
            $loader->addFixture($fixture);
            $this->fixtures[$name] = $fixture;
        }

        $executor = new ORMExecutor($em, new ORMPurger($em));
        $executor->execute($loader->getFixtures());
    }

    protected function getFixture($name)
    {
        if (!array_key_exists($name, $this->fixtures)) {
            throw new \InvalidArgumentException('Undefined fixture ' . $name);
        }
        return $this->fixtures[$name];
    }

    private function app(): Application
    {
        $container = $this->container();
        $app = new Application($container);
        require 'config/routes.php';
        return $app;
    }

    private function container(): ContainerInterface
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions('./config/container.php');
        $container = $builder->build();

        return $container;
    }
}
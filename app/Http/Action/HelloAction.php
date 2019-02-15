<?php

namespace App\Http\Action;

use Psr\Http\Message\ServerRequestInterface;
use Core\Application\Renderer;
use App\ReadModel\PostReadRepository;

class HelloAction
{
	public $renderer;
	public $posts;

	public function __construct(Renderer $renderer, PostReadRepository $posts) 
	{
		$this->renderer = $renderer;
		$this->posts = $posts;
	}

	public function handle(ServerRequestInterface $request)
    {
    	$name = $request->getQueryParams()['name'] ?? 'Guest';

        return $this->renderer->render('app/index', ['name' => $name]);
    }
}

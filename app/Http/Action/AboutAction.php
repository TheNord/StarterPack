<?php

namespace App\Http\Action;

use Psr\Http\Message\ServerRequestInterface;
use Core\Application\Renderer;

class AboutAction
{
	public $renderer;

	public function __construct(Renderer $renderer) 
	{
		$this->renderer = $renderer;
	}

	public function handle(ServerRequestInterface $request)
    {
        return $this->renderer->render('app/about');
    }
}

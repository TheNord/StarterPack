<?php

namespace Core\Application;

use Zend\Diactoros\Response\HtmlResponse;

class Renderer
{
	public $renderer;

	public function __construct($renderer)
	{
		$this->renderer = $renderer;
	}

	public function render(string $template, array $data = [], string $extension = '.html.twig')
	{
		$result = $this->renderer->render($template . $extension, $data);
		return new HtmlResponse($result);
	}
}
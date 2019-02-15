<?php

namespace App\Http\Action\Blog;

use Psr\Http\Message\ServerRequestInterface;
use Core\Application\Renderer;
use App\ReadModel\PostReadRepository;
use Zend\Diactoros\Response\EmptyResponse;
use Core\Http\Exception\NotFoundException;

class ShowAction
{
    private $posts;
    private $renderer;

    public function __construct(PostReadRepository $posts, Renderer $renderer)
    {
        $this->posts = $posts;
        $this->renderer = $renderer;
    }

    public function handle(ServerRequestInterface $request)
    {
        if (!$post = $this->posts->find($request->getAttribute('id'))) {
            throw new NotFoundException("This post is not found.", 404);
            
        }

        return $this->renderer->render('app/blog/show', [
            'post' => $post
        ]);
    }
}

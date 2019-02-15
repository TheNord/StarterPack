<?php

namespace App\Http\Action\Blog;

use Psr\Http\Message\ServerRequestInterface;
use Core\Application\Renderer;
use App\ReadModel\PostReadRepository;
use App\ReadModel\Pagination;

class IndexAction
{
    private const PER_PAGE = 5;

    private $posts;
    private $renderer;

    public function __construct(PostReadRepository $posts, Renderer $renderer)
    {
        $this->posts = $posts;
        $this->renderer = $renderer;
    }

    public function handle(ServerRequestInterface $request)
    {
        $pager = new Pagination(
            $this->posts->countAll(),
            $request->getAttribute('page') ?: 1,
            self::PER_PAGE
        );

        $posts = $this->posts->all(
            $pager->getOffset(),
            $pager->getLimit()
        );

        return $this->renderer->render('app/blog/index', [
            'posts' => $posts,
            'pager' => $pager,
        ]);
    }
}

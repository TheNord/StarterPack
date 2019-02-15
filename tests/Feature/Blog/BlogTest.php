<?php

declare(strict_types=1);

namespace Test\Feature\Blog;

use Test\Feature\WebTestCase;

class BlogTest extends WebTestCase
{
    public function testSuccess(): void
    {
        $response = $this->get('/blog');
        self::assertEquals(200, $response->getStatusCode());
        $content = $response->getBody()->getContents();
        self::assertStringContainsString('Blog', $content);
    }
}
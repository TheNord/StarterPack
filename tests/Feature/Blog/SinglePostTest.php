<?php

declare(strict_types=1);

namespace Test\Feature\Blog;

use Test\Feature\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

class SinglePostTest extends WebTestCase
{
    public function testSuccess(): void
    {
        $response = $this->get('/blog');

        self::assertEquals(200, $response->getStatusCode());

        $content = $response->getBody()->getContents();
        $crawler = new Crawler($content);
        $postName = $crawler->filter('div.card-header a')->first()->text();
        $postUrl = $crawler->filter('div.card-header a')->attr('href');

        $firstPost = $this->get($postUrl);
 
        self::assertEquals(200, $firstPost->getStatusCode());
        self::assertStringContainsString($postName, $firstPost->getBody()->getContents());
    }
}
<?php

/** @var \Framework\Http\Application $app */

$app->get('home', '/', HelloAction::class);
$app->get('about', '/about', AboutAction::class);
$app->get('blog', '/blog', Blog\IndexAction::class);
$app->get('blog_show', '/blog/{id}', Blog\ShowAction::class, ['id' => '\d+']);
$app->get('blog_page', '/blog/page/{page}', Blog\IndexAction::class, ['page' => '\d+']);

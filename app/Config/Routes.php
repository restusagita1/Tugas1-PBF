<?php

// ...

use App\Controllers\News; // Add this line
use App\Controllers\Pages;
use App\Controllers\Test;
use App\Controllers\Blog;

$routes->get('news', [News::class, 'index']);
$routes->get('news/new', [News::class, 'new']); // Add this line
$routes->post('news', [News::class, 'create']); // Add this line
$routes->get('news/(:segment)', [News::class, 'show']);
$routes->get('test', [Test::class, 'index']);

$routes->get('blog', [Blog::class, 'index']);
$routes->get('pages', [Pages::class, 'index']);
$routes->get('(:segment)', [Pages::class, 'view']);

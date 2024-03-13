<?php

use CodeIgniter\Router\RouteCollection;

use App\Controllers\News; // Add this line
use App\Controllers\Pages;
use App\Controllers\Blog;
use App\Controllers\Test;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('news', [News::class, 'index']);
$routes->get('news/new', [News::class, 'new']); // Add this line
$routes->post('news', [News::class, 'create']); // Add this line           // Add this line
$routes->get('news/(:segment)', [News::class, 'show']); // Add this line

$routes->get('blog', [Blog::class, 'index']);
$routes->get('test', [Test::class, 'index']);
$routes->get('pages', [Pages::class, 'index']);
$routes->get('(:segment)', [Pages::class, 'view']);

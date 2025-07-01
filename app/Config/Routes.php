<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

use App\Controllers\Pages;
use App\Controllers\Password;

$routes->get('pages', [Pages::class, 'index']);
$routes->get('(:segment)', [Pages::class, 'view']);

$routes->get('password/forgot', [Password::class, 'forgotPasswordForm']);
$routes->post('forgot', [Password::class, 'forgot']);

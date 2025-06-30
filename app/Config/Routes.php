<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

use App\Controllers\Pages;
use App\Controllers\AuthController;

$routes->get('pages', [Pages::class, 'index']);
$routes->get('(:segment)', [Pages::class, 'view']);

$routes->get('/forgot-password', 'AuthController::forgotPassword');
$routes->post('/send-reset-link', 'AuthController::sendResetLink');
<?php
//Routes.php
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

use App\Controllers\AuthController;
use App\Controllers\Pages;

$routes->get('pages', [Pages::class, 'index']);
$routes->get('(:segment)', [Pages::class, 'view']);
$routes->get('login', 'AuthController::index');        // shows login form
$routes->post('login', 'AuthController::authenticate'); // handles login POST
$routes->get('home', 'AuthController::home'); // Show home page
$routes->get('logout', 'AuthController::logout'); // Logout user


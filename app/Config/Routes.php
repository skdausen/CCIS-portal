<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

use App\Controllers\Pages;
use App\Controllers\Password;
use App\Controllers\AuthController;



$routes->get('pages', [Pages::class, 'index']);
$routes->get('(:segment)', [Pages::class, 'view']);

// Authentication routes
$routes->get('login', [AuthController::class, 'index']);           // Show login form
$routes->post('login', [AuthController::class, 'authenticate']);   // Handle login
$routes->get('home', [AuthController::class, 'home']);             // Home page after login
$routes->get('logout', [AuthController::class, 'logout']);         // Logout


$routes->get('password/forgot', [Password::class, 'forgot']);
$routes->post('password/send', [Password::class, 'sendOTP']);
$routes->get('password/verify', [Password::class, 'verifyForm']);
$routes->post('password/verify', [Password::class, 'verifyOTP']);
$routes->get('password/reset', [Password::class, 'resetForm']);
$routes->post('password/reset', [Password::class, 'resetPassword']);


$routes->get('admin/add-account', [Password::class, 'addAccount']);
$routes->post('admin/register', [Password::class, 'forgot']);



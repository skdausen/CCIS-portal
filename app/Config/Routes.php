<?php
//Routes.php
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

use App\Controllers\AuthController;
use App\Controllers\Pages;
use App\Controllers\Password;

$routes->get('pages', [Pages::class, 'index']);
$routes->get('(:segment)', [Pages::class, 'view']);
<<<<<<< HEAD

$routes->get('password/forgot', [Password::class, 'forgot']);
$routes->post('password/send', [Password::class, 'sendOTP']);
$routes->get('password/verify', [Password::class, 'verifyForm']);
$routes->post('password/verify', [Password::class, 'verifyOTP']);
$routes->get('password/reset', [Password::class, 'resetForm']);
$routes->post('password/reset', [Password::class, 'resetPassword']);



// $routes->get('password/forgot', [Password::class, 'forgotPasswordForm']);
// $routes->post('send_otp', [Password::class, 'sendOtp']);
// $routes->get('password/(:segment)', [Password::class, 'forgotPasswordForm']);
=======
$routes->get('login', 'AuthController::index');        // shows login form
$routes->post('login', 'AuthController::authenticate'); // handles login POST
$routes->get('home', 'AuthController::home'); // Show home page
$routes->get('logout', 'AuthController::logout'); // Logout user

>>>>>>> ce20a10836f03b5275b077672777553e944e4778

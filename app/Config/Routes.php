<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\Pages;
use App\Controllers\Password;
use App\Controllers\AuthController;

/**
 * @var RouteCollection $routes
 */

// Default route
$routes->get('/', 'Home::index');

// Pages routes
$routes->get('pages', [Pages::class, 'index']);
$routes->get('(:segment)', [Pages::class, 'view']);

// Authentication routes
$routes->get('login', [AuthController::class, 'index']);           // Show login form
$routes->post('login', [AuthController::class, 'authenticate']);   // Handle login
$routes->get('home', [AuthController::class, 'home']);             // Home page after login
$routes->get('logout', [AuthController::class, 'logout']);         // Logout

// Password recovery routes
$routes->get('password/forgot', [Password::class, 'forgot']);         // Forgot password form
$routes->post('password/send', [Password::class, 'sendOTP']);         // Send OTP
$routes->get('password/verify', [Password::class, 'verifyForm']);     // OTP verification form
$routes->post('password/verify', [Password::class, 'verifyOTP']);     // Verify OTP
$routes->get('password/reset', [Password::class, 'resetForm']);       // Reset password form
$routes->post('password/reset', [Password::class, 'resetPassword']);  // Handle password reset

// Optional: Legacy or alternate routes (commented for clarity)
// $routes->get('password/(:segment)', [Password::class, 'forgotPasswordForm']);
// $routes->post('send_otp', [Password::class, 'sendOtp']);

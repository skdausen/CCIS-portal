<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

use App\Controllers\Pages;
use App\Controllers\Password;
use App\Controllers\AuthController;

// Static Pages
$routes->get('pages', [Pages::class, 'index']);
$routes->get('(:segment)', [Pages::class, 'view']);

// Authentication routes
$routes->get('auth/login', [AuthController::class, 'index']);           // Show login form
$routes->post('auth/login', [AuthController::class, 'authenticate']);   // Handle login
$routes->get('home', [AuthController::class, 'home']);                  // Home page after login
$routes->get('auth/logout', [AuthController::class, 'logout']);         // Logout

// Password Reset routes
$routes->get('password/forgot', [Password::class, 'forgot']);
$routes->post('password/send', [Password::class, 'sendOTP']);
$routes->get('password/verify', [Password::class, 'verifyForm']);
$routes->post('password/verify', [Password::class, 'verifyOTP']);
$routes->get('password/reset', [Password::class, 'resetForm']);
$routes->post('password/reset', [Password::class, 'resetPassword']);

// Admin routes (grouped)
$routes->group('admin', function ($routes) {
    $routes->get('home', 'AdminController::adminHome');

    // User management
    $routes->get('users', 'AdminController::users');
    $routes->get('add-user', 'AdminController::addUserForm');
    $routes->post('create-user', 'AdminController::createUser');
    $routes->get('user/(:num)', 'AdminController::viewUser/$1');

    // 📢 Announcement management
    $routes->post('saveAnnouncement', 'AdminController::saveAnnouncement');
    $routes->post('updateAnnouncement', 'AdminController::updateAnnouncement'); // ✅ Add this line
    $routes->post('deleteAnnouncement', 'AdminController::deleteAnnouncement');

});





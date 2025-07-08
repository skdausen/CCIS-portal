<?php

use App\Controllers\AdminController;
use CodeIgniter\Router\RouteCollection;
use App\Controllers\Pages;
use App\Controllers\Password;
use App\Controllers\AuthController;

/**
 * @var RouteCollection $routes
 */

// ---------------------
// HOME
// ---------------------
$routes->get('/', 'Home::index');

// ---------------------
// STATIC PAGES
// ---------------------
$routes->get('pages', [Pages::class, 'index']);
$routes->get('(:segment)', [Pages::class, 'view']);

// ---------------------
// AUTHENTICATION ROUTES
// ---------------------
$routes->get('auth/login', [AuthController::class, 'index']);
$routes->post('auth/login', [AuthController::class, 'authenticate']);
$routes->get('auth/logout', [AuthController::class, 'logout']);
$routes->get('home', [AuthController::class, 'home']);

// ---------------------
// PASSWORD RESET ROUTES
// ---------------------
$routes->get('password/forgot', [Password::class, 'forgot']);
$routes->post('password/send', [Password::class, 'sendOTP']);
$routes->get('password/verify', [Password::class, 'verifyForm']);
$routes->post('password/verify', [Password::class, 'verifyOTP']);
$routes->get('password/reset', [Password::class, 'resetForm']);
$routes->post('password/reset', [Password::class, 'resetPassword']);

// ---------------------
// ADMIN ROUTES
// ---------------------
$routes->group('admin', function ($routes) {

    // ✅ Admin Home & Users
    $routes->get('home', 'AdminController::adminHome');

    // User management
    $routes->get('users', 'AdminController::users');
    $routes->get('add-user', 'AdminController::addUserForm');
    $routes->post('create-user', 'AdminController::createUser');
    $routes->get('user/(:num)', 'AdminController::viewUser/$1');

    // ✅ Academics Home
    $routes->get('academics', [AdminController::class, 'index']);

    // ✅ SEMESTERS
    $routes->get('academics/semesters', [AdminController::class, 'view_semesters']);
    $routes->post('academics/semesters/create', [AdminController::class, 'createSemester']);
    $routes->post('academics/semesters/update/(:num)', 'AdminController::updateSemester/$1');
    $routes->post('academics/semesters/delete/(:num)', 'AdminController::deleteSemester/$1');

    // ✅ COURSES
    $routes->get('academics/courses', [AdminController::class, 'view_courses']);
    $routes->post('academics/courses/create', [AdminController::class, 'createCourse']);
    $routes->post('academics/courses/update/(:num)', [AdminController::class, 'updateCourse/$1']);
    $routes->post('academics/courses/delete/(:num)', [AdminController::class, 'deleteCourse/$1']);

    // ✅ CLASSES
    $routes->get('academics/classes', [AdminController::class, 'view_classes']);
    $routes->post('academics/classes/add', [AdminController::class, 'createClass']);  // ✅ FIXED: Removed duplicate 'admin/'
    $routes->post('academics/classes/update/(:num)', [AdminController::class, 'updateClass/$1']);
    $routes->post('academics/classes/delete/(:num)', [AdminController::class, 'deleteClass/$1']);


    // ✅ Other Academics Sections
    $routes->get('academics/curriculums', [AdminController::class, 'view_curriculums']);
    $routes->get('academics/teaching_loads', [AdminController::class, 'view_teaching_loads']);
    $routes->get('academics/add_courses', [AdminController::class, 'add_courses']);

    

    // 📢 Announcement management
    $routes->post('saveAnnouncement', 'AdminController::saveAnnouncement');
    $routes->post('updateAnnouncement', 'AdminController::updateAnnouncement'); // ✅ Add this line
    $routes->post('deleteAnnouncement', 'AdminController::deleteAnnouncement');

});





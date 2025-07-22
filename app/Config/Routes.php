<?php

use App\Controllers\AdminController;
use App\Controllers\FacultyController;
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

// ---------------------
// STATIC PAGES
// ---------------------
// ---------------------
// AUTHENTICATION ROUTES
// ---------------------
$routes->get('auth/login', [AuthController::class, 'index']);
$routes->post('auth/login', [AuthController::class, 'authenticate']);
$routes->get('auth/logout', [AuthController::class, 'logout']);


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
// PROFILE ROUTES                   
// ---------------------
$routes->get('profile', 'ProfileController::index');
$routes->post('profile/update', 'ProfileController::update');
$routes->post('profile/update_password', 'ProfileController::update_password');

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
    $routes->get('user/(:num)', 'AdminController::getUser/$1');

    // Academics Home
    $routes->get('academics', [AdminController::class, 'index']);

    // SEMESTERS
    $routes->get('academics/semesters', [AdminController::class, 'view_semesters']);
    $routes->post('academics/semesters/create', [AdminController::class, 'createSemester']);
    $routes->post('academics/semesters/update/(:num)', [AdminController::class, 'updateSemester/$1']);
    $routes->post('academics/semesters/delete/(:num)', [AdminController::class, 'deleteSemester/$1']);


    // SUBJECTS
    $routes->get('academics/subjects', [AdminController::class, 'view_subjects']);
    $routes->post('academics/subjects/create', [AdminController::class, 'createSubject']);
    $routes->post('academics/subjects/update/(:num)', [AdminController::class, 'updateSubject/$1']);
    $routes->post('academics/subjects/delete/(:num)', [AdminController::class, 'deleteSubject/$1']);

    // CLASSES
    $routes->get('academics/classes', [AdminController::class, 'view_classes']);
    $routes->post('academics/classes/add', [AdminController::class, 'createClass']); 
    $routes->post('academics/classes/update/(:num)', [AdminController::class, 'updateClass/$1']);
    $routes->post('academics/classes/delete/(:num)', [AdminController::class, 'deleteClass/$1']);

    // CURRICULUM
    $routes->get('academics/curriculums', [AdminController::class, 'view_curriculums']);
    $routes->post('academics/curriculums/create', 'AdminController::create');
    $routes->post('academics/curriculums/update/(:num)', 'AdminController::update_curriculum/$1');
    $routes->get('academics/curriculums/view/(:num)', 'AdminController::view_curriculum_detail/$1');

    // 📢 Announcement management
    $routes->post('saveAnnouncement', 'AdminController::saveAnnouncement');
    $routes->post('updateAnnouncement', 'AdminController::updateAnnouncement'); 
    $routes->post('deleteAnnouncement', 'AdminController::deleteAnnouncement');

});



// ---------------------
// FACULTY ROUTES
// ---------------------
$routes->group('faculty', function ($routes) {

    // Faculty Home & Users
    $routes->get('home', 'FacultyController::facultyHome');
    // Classes
    $routes->get('classes', 'FacultyController::classes');
    $routes->get('classes/ajax', 'FacultyController::getClassesBySemester'); // AJAX handler
    $routes->get('class/(:num)', 'FacultyController::viewClass/$1');
    $routes->post('class/(:num)/enroll', 'FacultyController::enrollStudents/$1');
    $routes->post('class/(:num)/remove-student/(:num)', 'FacultyController::removeStudent/$1/$2');

    // Grades Management
    $routes->get('class/(:num)/grades', 'FacultyController::manageGrades/$1');
    $routes->post('class/(:num)/grades/save', 'FacultyController::saveGrades/$1');
    $routes->post('class/(:num)/grades/upload', 'FacultyController::uploadGrades/$1');
    $routes->post('class/(:num)/grades/confirm-upload', 'FacultyController::confirmUpload/$1');
    $routes->get('class/(:num)/grades/download-template', 'FacultyController::downloadGradeTemplate/$1'); //download template

});


// ---------------------
// STUDENT ROUTES
// ---------------------
$routes->group('student', function ($routes) {

    // Student Home & Users
    $routes->get('home', 'StudentController::studentHome');
    $routes->get('curriculum', 'StudentController::studentCurriculum');
    $routes->get('grades/grades', 'StudentController::studentGrades');
    $routes->get('grades/grades', 'StudentController::getGrades');
    $routes->get('grades/download', 'StudentController::downloadPDF');
    $routes->get('grades/curriculum_planview', 'StudentController::curriculumPlanView');
    $routes->get('grades/curriculum/download', 'Student\Curriculum::curriculumDownload');




    
});
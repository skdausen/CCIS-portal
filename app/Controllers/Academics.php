<?php

namespace App\Controllers;

class Academics extends BaseController
{
    public function index()
    {
        return view('admin/academics');  // -> Views/admin/academics.php
    }

    public function schoolyear()
    {
        return view('admin/academics_schoolyear');
    }

    public function semester()
    {
        return view('admin/academics_semester');
    }

    public function courses()
    {
        return view('admin/add_courses');
    }

    public function classes()
    {
        return view('admin/academics_classes');
    }

    public function teachingloads()
    {
        return view('admin/academics_teachingloads');
    }
}

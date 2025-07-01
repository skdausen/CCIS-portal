<?php

namespace App\Controllers;

// use App\Models\ForgotPasswordModel;
// use CodeIgniter\Exceptions\PageNotFoundException;

class Password extends BaseController
{
    // create a method to display the HTML form you have created
    public function forgotPasswordForm()
    {
        // We load the Form helper with the helper() function. Most helper functions require the helper to be loaded before use.
        helper('form');

        // Then it returns the created form view.
        return view('templates/header', ['title' => 'Forgot Password'])
            . view('password/forgotPasswordForm')
            . view('templates/footer');
    }

    public function forgot()
    {
        
    }
}
<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\UserModel;

class SessionExpiryCheck implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // If the session does not exist but user_id was set before
        if (!$session->has('isLoggedIn') && $session->has('user_id')) {
            $userId = $session->get('user_id');
            $userModel = new UserModel();

            // Set status to inactive
            $userModel->update($userId, ['status' => 'inactive']);

            // Make sure session is cleared
            $session->destroy();

            // Optionally redirect to login
            return redirect()->to('auth/login')->with('error', 'Session expired. Please login again.');
        }

        // No issue, continue
        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing to do after
    }
}

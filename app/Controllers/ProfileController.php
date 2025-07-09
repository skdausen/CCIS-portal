<?php

namespace App\Controllers;

use App\Models\UserModel;

class ProfileController extends BaseController
{
    public function update()
    {
        $session = session();
        $userId = $session->get('user_id');
        $model = new UserModel();

        $file = $this->request->getFile('profile_img');
        $newFilename = $session->get('profile_img'); // Default to existing filename

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newFilename = $file->getRandomName(); // Random unique name
            $file->move('rsc/assets/uploads', $newFilename); // Upload to public folder
        }

        $data = [
            'fname'          => $this->request->getPost('fname'),
            'mname'          => $this->request->getPost('mname'),
            'lname'          => $this->request->getPost('lname'),
            'email'          => $this->request->getPost('email'),
            'contact_number' => $this->request->getPost('contact_number'),
            'address'        => $this->request->getPost('address'),
            'sex'            => $this->request->getPost('sex'),
            'birthday'       => $this->request->getPost('birthday'),
            'profile_img'    => $newFilename
        ];

        $model->update($userId, $data);

        // Update session data too
        $session->set($data);

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }


}

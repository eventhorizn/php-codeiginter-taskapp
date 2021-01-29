<?php

namespace App\Controllers;

use \App\Models\UserModel;

class Login extends BaseController
{
    public function new()
    {
        return view('Login/new');
    }

    public function create()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $model = new UserModel;

        $user = $model->where('email', $email)
                      ->first();

        if ($user === null) {
            return redirect()->back()
                             ->withInput()
                             ->with('warning', 'User not found');
        } else {
            if(password_verify($password, $user->password_hash)) {
                $session = session();
                $session->regenerate(); // session fixation attacks
                $session->set('user_id', $user->id);

                return redirect()->to("/")
                                 ->with('info', 'Login successful');
            } else {
                return redirect()->back()
                                 ->withInput()
                                 ->with('warning', 'Incorrect password');
            }
        }
    }

    public function delete()
    {
        session()->destroy();

        return redirect()->to('/login/showLogoutMessage');
    }

    // This is a new request, so will new a new session
    // Otherwise (if this was in the delete function)
    // We wouldn't see the flash message
    public function showLogoutMessage()
    {
        return redirect()->to('/')
                         ->with('info', 'Logout Successful');
    }
}
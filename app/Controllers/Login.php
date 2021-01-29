<?php

namespace App\Controllers;

use \App\Libraries\Authentication;

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

        $auth = service('auth');
        
        if ($auth->login($email, $password)) {
            return redirect()->to("/")
                             ->with('info', 'Login successful');
        } else {
            return redirect()->back()
                             ->withInput()
                             ->with('warning', 'Invalid login');
        }
    }

    public function delete()
    {
        service('auth')->logout();

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
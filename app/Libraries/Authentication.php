<?php

namespace App\Libraries;

use \App\Models\UserModel;

class Authentication
{
    public function login($email, $password)
    {
        $model = new UserModel;

        $user = $model->where('email', $email)
                      ->first();

        if ($user === null) {
            return false;
        }

        if (!password_verify($password, $user->password_hash)) {
            return false;
        }

        $session = session();
        $session->regenerate(); // session fixation attacks
        $session->set('user_id', $user->id);

        return true;
    }

    public function logout()
    {
        session()->destroy();
    }

    public function getCurrentUser()
    {
        if (!session()->has('user_id')) {
            return null;
        }

        $model = new UserModel;

        return $model->find(session()->get('user_id'));

    }
}
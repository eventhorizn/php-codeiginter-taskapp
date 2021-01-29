<?php

namespace App\Libraries;

use \App\Models\UserModel;

class Authentication
{
    private $user;

    public function login($email, $password)
    {
        $model = new UserModel;

        $user = $model->findByEmail($email);

        if ($user === null) {
            return false;
        }

        if (!$user->verifyPassword($password)) {
            return false;
        }

        if (!$user->is_active) {
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

        if ($this->user === null) {
            $model = new UserModel;

            $user = $model->find(session()->get('user_id'));

            if ($user && $user->is_active) {
                $this->user = $user;
            }
        }

        return $this->user;
    }

    public function isLoggedIn()
    {
        return $this->getCurrentUser() !== null;
    }
}
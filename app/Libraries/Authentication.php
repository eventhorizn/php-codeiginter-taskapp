<?php

namespace App\Libraries;

use App\Models\RememberedLoginModel;
use \App\Models\UserModel;

class Authentication
{
    private $user;

    public function login($email, $password, $remember_me)
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

        $this->logInUser($user);

        if ($remember_me) {
            $this->rememberLogin($user->id);
        }

        return true;
    }

    public function logout()
    {
        $token = service('request')->getCookie('remember_me');

        if ($token !== null) {
            $model = new RememberedLoginModel();

            $model->deleteByToken($token);
        }

        service('response')->deleteCookie('remember_me');

        session()->destroy();
    }

    public function getCurrentUser()
    {
        if ($this->user === null) {
            $this->user = $this->getUserFromSession();
        }

        if ($this->user === null) {
            $this->user = $this->getUserFromRememberCookie();
        }

        return $this->user;
    }

    public function isLoggedIn()
    {
        return $this->getCurrentUser() !== null;
    }

    private function logInUser($user)
    {
        $session = session();
        $session->regenerate(); // session fixation attacks
        $session->set('user_id', $user->id);
    }

    private function rememberLogin($user_id)
    {
        $model = new RememberedLoginModel();

        list($token, $expiry) = $model->rememberUserLogin($user_id);

        $response = service('response');

        $response->setCookie('remember_me', $token, $expiry);
    }

    private function getUserFromSession()
    {
        if (!session()->has('user_id')) {
            return null;
        }

        $model = new UserModel;

        $user = $model->find(session()->get('user_id'));

        if ($user && $user->is_active) {
            return $user;
        }
    }

    private function getUserFromRememberCookie()
    {
        $token = service('request')->getCookie('remember_me');

        if ($token === null) {
            return null;
        }

        $remembered_login_model = new RememberedLoginModel();

        $remembered_login = $remembered_login_model->findByToken($token);

        if ($remembered_login === null) {
            return null;
        }

        $user_model = new UserModel();

        $user = $user_model->find($remembered_login->user_id);

        if ($user && $user->is_active) {
            $this->logInUser($user);

            return $user;
        }
    }
}
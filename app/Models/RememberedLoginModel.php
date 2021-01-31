<?php

namespace App\Models;

use App\Libraries\Token;
use \CodeIgniter\Model;

class RememberedLoginModel extends Model
{
    protected $table = 'remembered_login';

    protected $allowedFields = ['token_hash', 'user_id', 'expires_at'];

    protected $returnType = 'App\Entities\RememberedLogin';

    public function rememberUserLogin($user_id)
    {
        $token = new Token();

        $token_hash = $token->getHash();

        $expiry = time() + 864000;

        $data = [
            'token_hash' => $token_hash,
            'user_id'    => $user_id,
            'expires_at' => date('Y-m-d H:i:s', $expiry)
        ];

        $this->insert($data);

        return [
            $token->getValue(),
            $expiry
        ];
    }

    public function findByToken($token)
    {
        $token = new Token($token);

        $token_hash = $token->getHash();

        $remembered_login = $this->where('token_hash', $token_hash)
                                 ->first();

        // No entity class, so return is an array
        // Can create if we want to
        if ($remembered_login) {
            if ($remembered_login->expires_at > date('Y-m-d H:i:s')) {
                return $remembered_login;
            }
        }
    }
}
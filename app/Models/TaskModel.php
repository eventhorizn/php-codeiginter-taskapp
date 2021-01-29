<?php

namespace App\Models;

use \CodeIgniter\Model;

class TaskModel extends Model
{
    protected $table = 'task';

    protected $allowedFields = ['description', 'user_id'];

    protected $returnType = 'App\Entities\Task';

    protected $useTimestamps = true;

    protected $validationRules = [
        'description' => 'required'
    ];

    protected $validationMessages = [
        'description' => [
            'required' => 'Please enter a description'
        ]
    ];

    public function getTasksByUserId($id)
    {
        return $this->where('user_id', $id)->findAll();
    }

    public function getTaskByUserId($id, $user_id)
    {
        return $this->where('id', $id)
                    ->where('user_id', $user_id)
                    ->first();
    }
}
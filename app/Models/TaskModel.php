<?php

namespace App\Models;

use \CodeIgniter\Model;

class TaskModel extends Model
{
    protected $table = 'task';

    protected $allowedFields = ['description'];

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
}
<?php namespace App\Commands;

use App\Models\RememberedLoginModel;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class DeleteExpiredRememberedLogins extends BaseCommand
{
    protected $group       = 'Auth';
    protected $name        = 'auth:cleanup';
    protected $description = 'Clears expired remembered login records.';

    public function run(array $params)
    {
        $model = new RememberedLoginModel();

        $rows = $model->deleteExpired();

        echo "$rows rows deleted.\n";
    }
}
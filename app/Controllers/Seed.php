<?php

namespace App\Controllers;

use \Config\Database;

class Seed extends BaseController
{
    public function index()
    {
        $seeder = Database::seeder();
        $seeder->call('UserSeeder');

        echo "Seeded";
    }
}
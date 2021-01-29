<?php namespace App\Database\Seeds;

use App\Models\UserModel;
use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
	public function run()
	{
		$model = new UserModel();

		$data = [
			'name'		=> 'Admin',
			'email'		=> 'admin@example.com',
			'password'	=> 'secret',
			'is_admin'	=> true
		];
		
		$model->skipValidation(true)
			  ->protect(false)
			  ->insert($data);
	}
}

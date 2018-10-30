<?php

use App\Model\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name'      => 'admin',
            'email'     => 'admin@admin.com',
            'password'  => bcrypt('12345678'),
            'confirmed' => 1
        ]);
    }
}

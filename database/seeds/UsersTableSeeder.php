<?php

use App\Model\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name'      => '曹婉婉',
            'email'     => '787021752@qq.com',
            'password'  => bcrypt('cc102394'),
            'confirmed' => 1
        ]);
    }
}


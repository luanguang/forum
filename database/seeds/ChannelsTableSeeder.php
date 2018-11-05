<?php

use Illuminate\Database\Seeder;
use App\Model\Channel;

class ChannelsTableSeeder extends Seeder
{
    public function run()
    {
        $names = ['心情', '日记', '工作', '技术', '总结'];
        foreach ($names as $name) {
            Channel::create([
                'name' => $name,
                'slug' => $name
            ]);
        }
    }
}

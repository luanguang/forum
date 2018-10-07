<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Model\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name'           => $faker->name,
        'email'          => $faker->unique()->safeEmail,
        'password'       => $password ?: $password = bcrypt('123456'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Model\Thread::class, function ($faker) {
    return [
        'user_id' => function () {
            return factory('App\Model\User')->create()->id;
        },
        'title' => $faker->sentence,
        'body'  => $faker->paragraph,
    ];
});

$factory->define(App\Model\Reply::class, function ($faker) {
    return [
        'thread_id' => function () {
            return factory('App\Model\Thread')->create()->id;
        },
        'user_id' => function () {
            return factory('App\Model\User')->create()->id;
        },
        'body' => $faker->paragraph,
    ];
});

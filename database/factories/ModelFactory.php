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
        'confirmed'      => true,
    ];
});

$factory->state(App\Model\User::class, 'unconfirmed', function () {
    return [
        'confirmed' => false
    ];
});

$factory->state(App\Model\User::class, 'administrator', function () {
    return [
        'name' => 'admin'
    ];
});

$factory->define(App\Model\Thread::class, function ($faker) {
    $title = $faker->sentence;
    return [
        'user_id' => function () {
            return factory('App\Model\User')->create()->id;
        },
        'channel_id' => function () {
            return factory('App\Model\Channel')->create()->id;
        },
        'title'  => $title,
        'body'   => $faker->paragraph,
        'visits' => 0,
        'slug'   => str_slug($title),
        'locked' => false,
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

$factory->define(App\Model\Channel::class, function ($faker) {
    $name = $faker->word;

    return [
        'name' => $name,
        'slug' => $name
    ];
});

$factory->define(Illuminate\Notifications\DatabaseNotification::class, function ($faker) {
    return [
        'id'            => Ramsey\Uuid\Uuid::uuid4()->toString(),
        'type'          => 'App\Notifications\ThreadWasUpdated',
        'notifiable_id' => function () {
            return auth()->id() ?: factory('App\Model\User')->create()->id;
        },
        'notifiable_type' => 'App\Model\User',
        'data'            => ['foo' => 'bar']
    ];
});

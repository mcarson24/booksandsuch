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
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Book::class, function (Faker\Generator $faker) {
    return [
        'title' 		=> $faker->sentence,
        'author' 		=> 'Thomas Author',
        'release_date'	=> \Carbon\Carbon::parse('January 1, 2017'),
        'description' 	=> $faker->paragraph,
        'price' 	  	=> '2500',
        'user_id'       => function() {
            return factory(App\User::class)->create()->id;
        }
    ];
});

$factory->state(App\Book::class, 'published', function (Faker\Generator $faker) {
    return [
        'published_at'  => \Carbon\Carbon::now()->subWeek()
    ];
});

$factory->state(App\Book::class, 'unpublished', function (Faker\Generator $faker) {
    return [
        'published_at'  => null
    ];
});

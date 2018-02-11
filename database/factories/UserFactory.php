<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Album::class, function (Faker $faker) {
    return [
        'name' => $faker->words($nb = 2, $asText = true),
        'description' => $faker->sentence($nbWords = 8, $variableNbWords = true),
        'cover_image' => $faker->imageUrl($width = 800, $height = 600, 'cats')
    ];
});

$factory->define(App\Photo::class, function (Faker $faker) {

    $albumIds = App\Album::all()->pluck('id')->toArray();

    return [
        'image' => $faker->imageUrl($width = 800, $height = 600, 'cats'),
        'caption' => $faker->sentence($nbWords = 6, $variableNbWords = true),
        'notes' => $faker->text($maxNbChars = 200),
        'album_id' => $faker->randomElement($albumIds)
    ];
});

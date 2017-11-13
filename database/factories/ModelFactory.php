<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(\App\Models\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});


$factory->define(\App\Models\Mob::class, function (Faker\Generator $faker) {

    $jsonObject = [
        'featureName' => $faker->name,
        "participants" => [
            [
                "name" => $faker->name,
                "contributor" => true,
                "active" => true
            ],
            [
                "name" => $faker->name,
                "contributor" => true,
                "active" => false
            ],
        ],
    ];

    $name = $faker->safeColorName . ' ' . $faker->firstName;

    return [
        'name' => $name,
        'slug' => str_slug($name),
        'storage' => json_encode($jsonObject)
    ];
});
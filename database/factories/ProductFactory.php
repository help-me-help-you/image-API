<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {

    $name = $faker->sentence(2);
    $slug = Str::slug($name);

    return [
        'name' => $name,
        'slug' =>  $slug,
        'sku' => $faker->numberBetween(1, 1000),
        'price' => $faker->numberBetween(100, 1000),
        'description' => $faker->paragraph(1),
        'quantity' => $faker->numberBetween(1, 10),
        'status' => $faker->randomElement([Product::AVAILABLE_PRODUCT, Product::UNAVAILABLE_PRODUCT]),
    ];
});

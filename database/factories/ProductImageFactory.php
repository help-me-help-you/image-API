<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ProductImage;
use App\Product;
use Faker\Generator as Faker;

$factory->define(ProductImage::class, function (Faker $faker) {

    $product = Product::all()->random()->id;
    $product_id = Product::where('id', '=', $product)->first();

    if (ProductImage::where(['product_id' => $product_id->product_id, 'is_main' => true])->count() == 1) {
        $is_main = false;
    } else {
        $is_main = true;
    }
    if (ProductImage::where(['product_id' => $product_id->product_id, 'is_main' => true])->count() == 0) {
        $is_main = true;
    } else {
        $is_main = false;
    }


    $filepath = storage_path('app/public/img/') . $product_id->id;

    if (!File::exists($filepath)) {
        File::makeDirectory($filepath);
    }

    return [
        'product_id' => $product,
        'name' => $faker->image(storage_path('app/public/img/') . "{$product_id->id}", 400, 300, null, false),
        'extension' => '.jpg',
        'is_main' => $is_main,
    ];
});

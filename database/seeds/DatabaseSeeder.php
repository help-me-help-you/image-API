<?php

use App\Product;
use App\ProductImage;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        User::truncate();
        Product::truncate();
        ProductImage::truncate();

        User::flushEventListeners();
        Product::flushEventListeners();
        ProductImage::flushEventListeners();

        $usersQuantity = 5;
        $productQuantity = 5;
        $productImageQuantity = 2;

        factory(User::class, $usersQuantity)->create();
        factory(Product::class, $productQuantity)->create();
        factory(ProductImage::class, $productImageQuantity)->create();
    }
}

<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Photo;
use App\Seller;
use App\Product;
use App\Category;
use App\Subcategory;
use App\Transaction;
use Illuminate\Support\Str;
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

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => password_hash('123456', PASSWORD_DEFAULT), // password
        'verified' => $verificado = $faker->randomElement([User::USUARIO_VERIFICADO, User::USUARIO_NO_VERIFICADO]),
        'verification_token' => $verificado == User::USUARIO_VERIFICADO ? null : User::generarVerificationToken(),
        'admin' => $faker->randomElement([User::USUARIO_ADMINISTRADOR, User::USUARIO_REGULAR]),
        'remember_token' => Str::random(10),
    ];
});

$factory->define(Category::class, function (Faker $faker) {
    return [
        'name' => 'categoria_'.$faker->word,
        'description' => $faker->paragraph(1),
        'image' => $faker->randomElement(['1.jpg','c2.jpg']),
    ];
});

$factory->define(Subcategory::class, function (Faker $faker) {
    return [
        'name' => 'Subcategoria_'.$faker->word,
        'description' => $faker->paragraph(1),
        'category_id' => Category::all()->random()->id,
    ];
});

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => 'Producto_'.$faker->word,
        'description' => $faker->paragraph(1),
        'quantity' => $faker->numberBetween(1,10),
        'weight' => $faker->randomNumber(2),
        'status' => $faker->randomElement([Product::PRODUCTO_NO_DISPONIBLE, Product::PRODUCTO_DISPONIBLE]),
        'price' => $faker->randomNumber(3),
        'image' => $faker->randomElement(['p1.jpg','p2.jpg','p3.jpg']),
        'seller_id' => User::all()->random()->id,
        'subcategory_id' => Subcategory::all()->random()->id,
    ];
});

$factory->define(Transaction::class, function (Faker $faker) {
    $vendedor = Seller::has('products')->get()->random();
    $comprador = User::all()->except($vendedor->id)->random();
    return [
        'quantity' => $faker->numberBetween(1,3),
        'buyer_id' => $comprador->id,
        'product_id' => $vendedor->products->random()->id,
    ];
});

$factory->define(Photo::class, function (Faker $faker) {
    return [
        'image' => $faker->randomElement(['p1.jpg','p2.jpg','p3.jpg']),
        'product_id' => Product::all()->random()->id,
    ];
});

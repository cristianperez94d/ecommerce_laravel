<?php

use App\User;
use App\Photo;
use App\Product;
use App\Category;
use App\Subcategory;
use App\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Descativar las claves foranea temporalmente para evitar errores al borrar
        Schema::disableForeignKeyConstraints();
        // Borrar la informacion de la tablas de la bd
        User::truncate();
        Category::truncate();
        Subcategory::truncate();
        Product::truncate();
        Transaction::truncate();
        Photo::truncate();

        // Evitar que se disparen eventos de los modelos cuando se creen instancias de estos al correr los seed
        User::flushEventListeners();
        Category::flushEventListeners();
        Photo::flushEventListeners();
        Product::flushEventListeners();
        Transaction::flushEventListeners();
        Subcategory::flushEventListeners();
        
        $cantidadUsuarios = 100;
        $cantidadCategorias = 10;
        $cantidadSubcategorias = 10;
        $cantidadProductos = 200;
        $cantidadTransacciones = 50;
        $cantidadFotos = 10;

        factory(User::class, $cantidadUsuarios)->create();
        factory(Category::class, $cantidadCategorias)->create();
        factory(Subcategory::class, $cantidadSubcategorias)->create();
        factory(Product::class, $cantidadProductos)->create();
        factory(Transaction::class, $cantidadTransacciones)->create();
        factory(Photo::class, $cantidadFotos)->create();

    }
}

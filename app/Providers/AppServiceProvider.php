<?php

namespace App\Providers;

use App\User;
use App\Product;
use App\Mail\UserCreated;
use App\Mail\UserMailChanged;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Builder::defaultStringLength(191);

        // Comprobar si un producto se actualizo
        Product::updated(function($product){
            
            if( $product->quantity == 0 && $product->estaDisponible() ){
                $product->status = Product::PRODUCTO_NO_DISPONIBLE;

                $product->save();
            }

        });

        // Comprobar si un usuario fue creado para enviar el email de verificacion
        User::created(function($user){
            
            // intentar reenviar 5 veces en caso de fallar el evio
            retry( 5, function() use($user) {
                Mail::to($user)->send( new UserCreated($user) );  
            }, 100 );

        });
        // Comprobar si SOLO se actualiza el correo
        User::updated(function($user){
            
            if( $user->isDirty('email') ){
                // intentar reenviar 5 veces en caso de fallar el evio
                retry( 5, function() use($user) {
                    Mail::to($user)->send( new UserMailChanged($user) );
                }, 100 );
            }

        });

    }
}

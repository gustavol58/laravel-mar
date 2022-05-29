<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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
        // para evitar el error: 
        // #1709 - Index column size too large. The maximum column size is 767 bytes
        // cuando la base de datos se vaya a subir a un hosting compartido
        // Schema::defaultStringLength(191);

        // se detectó que esto no arreglaba nada y que el cambio habia que 
        // hacerlo en config/database.php: cambiar utf8mb4 por utf8
    }
}

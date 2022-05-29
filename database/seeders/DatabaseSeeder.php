<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\Cliente::factory(50)->create();
        // \App\Models\Recaudo::factory(800)->create();

        $this->call([
            FormuHtmlElementoSeeder::class,
            FormuTipoProductoSeeder::class,
            FormuTablaSeeder::class,
            FormuCampoSeeder::class,
            PalabrasReservadaSeeder::class,
        ]);
    }

}

<?php

namespace Database\Factories;

use App\Models\Recaudo;
use App\Models\Cliente;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecaudoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Recaudo::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $clientes = Cliente::pluck('id')->toArray();
        $users = User::pluck('id')->toArray();
        $noid = $this->faker->randomElement($users);
        // para que no escoja el id 1:
        while($noid == 1){
            $noid = $this->faker->randomElement($users);
        }
        return [
            'cliente_id' => $this->faker->randomElement($clientes),
            'fec_pago' => $this->faker->dateTimeBetween('-180 days', now())->format('Y-m-d'),
            'valor' => $this->faker->numberBetween(50000, 5000000),
            'tipo' => $this->faker->randomElement([1,2]),
            // 'asentado' => $this->faker->boolean(),
            'asentado' => 0,
            'user_id' => $noid,
        ];
    }
}

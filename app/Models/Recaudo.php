<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recaudo extends Model
{
    use HasFactory;

    // en fillable hay que listar todos los campos que se usen en CREATE al modelo
    protected $fillable = ['categoria' , 'cliente_id' , 'fec_pago' , 'valor' , 'tipo' , 'obs' , 'estado' , 'user_id'];

}

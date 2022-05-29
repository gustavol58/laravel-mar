<?php

namespace App\Models\Pedidos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FormuContenidosMultivariable extends Model
{
    use HasFactory;

    public $timestamps = false;

    // en fillable hay que listar todos los campos que se usen en CREATE al modelo
    protected $fillable = ['campo_detalle_id' , 'formu__id' , 'fila' , 'col' , 'valor' ];

}

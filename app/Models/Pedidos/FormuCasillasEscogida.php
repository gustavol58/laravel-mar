<?php

namespace App\Models\Pedidos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormuCasillasEscogida extends Model
{
    use HasFactory;

    protected $fillable = ['formu_lista_valores_id' , 'formu__campo_casilla'];

    public $timestamps = false;    
}

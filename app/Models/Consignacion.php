<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consignacion extends Model
{
    protected $table = 'consignaciones';
    public $timestamps = false;
    
    use HasFactory;
    // en fillable hay que listar todos los campos que se usen en CREATE al modelo
    protected $fillable = ['original' , 'fecha' , 'valor' , 'estado' , 
        'user_importo_id' , 'created_importo_at' ,
        'documento' , 'oficina' , 'descripcion' , 'referencia'];

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecaudosHistoriaModificados extends Model
{
    use HasFactory;

    // todos los campos serán fillable, menos el campo id: 
    protected $guarded = ['id'];
}

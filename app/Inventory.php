<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AlphaERP extends Model
{
    protected $table = 'AlphaERP'; // Especifica el nombre exacto de la tabla
    
    protected $fillable = [
        'name' // Añade aquí todos los campos que quieras asignar masivamente
    ];
}
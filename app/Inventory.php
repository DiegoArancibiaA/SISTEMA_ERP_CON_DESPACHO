<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'inventory'; // Especifica el nombre exacto de la tabla
    
    protected $fillable = [
        'name' // Añade aquí todos los campos que quieras asignar masivamente
    ];
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    public function calificaciones()
    {
        return $this->hasMany(Calificacion::class);
    }
}

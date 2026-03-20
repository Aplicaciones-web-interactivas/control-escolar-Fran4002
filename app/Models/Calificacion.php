<?php

namespace App\Models;

use App\Models\User;
use App\Models\Materia;
use Illuminate\Database\Eloquent\Model;

class Calificacion extends Model
{
    protected $fillable = [
        'alumno_id',
        'materia_id',
        'calificacion',
    ];

    public function alumno()
    {
        return $this->belongsTo(User::class, 'alumno_id');
    }

    public function materia()
    {
        return $this->belongsTo(Materia::class);
    }
}

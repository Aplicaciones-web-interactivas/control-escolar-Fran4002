<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    protected $fillable = [
        'maestro_id',
        'materia_id',
        'dias',
        'hora_inicio',
        'hora_fin',
    ];

    public function maestro()
    {
        return $this->belongsTo(User::class, 'maestro_id');
    }

    public function materia()
    {
        return $this->belongsTo(Materia::class);
    }

    public function grupos()
    {
        return $this->hasMany(Grupo::class);
    }
}

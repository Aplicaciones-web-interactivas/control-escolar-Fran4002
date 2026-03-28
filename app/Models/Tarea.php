<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    protected $fillable = [
        'horario_id',
        'maestro_id',
        'titulo',
        'descripcion',
        'fecha_limite',
    ];

    public function horario()
    {
        return $this->belongsTo(Horario::class);
    }

    public function maestro()
    {
        return $this->belongsTo(User::class, 'maestro_id');
    }

    public function entregas()
    {
        return $this->hasMany(EntregaTarea::class);
    }
}

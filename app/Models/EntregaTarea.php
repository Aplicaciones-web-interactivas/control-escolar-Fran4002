<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EntregaTarea extends Model
{
    protected $table = 'entregas_tareas';

    protected $fillable = [
        'tarea_id',
        'alumno_id',
        'archivo_path',
        'archivo_nombre_original',
        'archivo_mime',
        'archivo_size',
        'entregado_en',
    ];

    protected $casts = [
        'entregado_en' => 'datetime',
    ];

    public function tarea()
    {
        return $this->belongsTo(Tarea::class);
    }

    public function alumno()
    {
        return $this->belongsTo(User::class, 'alumno_id');
    }
}

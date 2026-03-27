<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'clave_institucional',
        'password',
        'role',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function horariosMaestro()
    {
        return $this->hasMany(Horario::class, 'maestro_id');
    }

    public function gruposAlumno()
    {
        return $this->hasMany(Grupo::class, 'alumno_id');
    }

    public function tareasMaestro()
    {
        return $this->hasMany(Tarea::class, 'maestro_id');
    }

    public function entregasTareasAlumno()
    {
        return $this->hasMany(EntregaTarea::class, 'alumno_id');
    }

}

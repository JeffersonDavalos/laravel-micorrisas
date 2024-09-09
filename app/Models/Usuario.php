<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;

    protected $table = 'tbm_usuario';

    public $timestamps = false;

    protected $fillable = [
        'id_perfil',
        'usuario',
        'nombre',
        'apellido',
        'cedula',
        'contraseña',
        'correo',
        'estado',
        'ip',
        'fecha_creacion'
    ];
}

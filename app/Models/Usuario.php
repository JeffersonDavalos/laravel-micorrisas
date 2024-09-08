<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;

    // Especifica la tabla asociada
    protected $table = 'tbm_usuario';

    // Si no estás usando los campos timestamps, asegúrate de desactivarlos
    public $timestamps = false;

    // Campos que pueden ser llenados masivamente
    protected $fillable = [
        'id_perfil',
        'Usuario',
        'nombre',
        'cedula',
        'correo',
        'estado',
        'ip',
        'fecha_creacion'
    ];
}

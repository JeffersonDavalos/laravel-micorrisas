<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perfilusuario extends Model
{
    use HasFactory;

    protected $table = 'tbm_perfil';

    public $timestamps = false;

    protected $fillable = [
        'id_perfil',
        'descripcion',
        'estado',
        'ip',
        'fecha_creacion'
    ];}

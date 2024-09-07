<?php

namespace App\Services;

use App\Models\Usuario;
use Illuminate\Database\QueryException;

class UsuarioService
{
    /**
     * Obtener todos los usuarios de la tabla tbm_usuario.
     *
     * @return array
     */
    public function obtenerTodosLosUsuarios()
    {
        try {
            return Usuario::all();
        } catch (QueryException $e) {
            // Capturamos los errores relacionados con la base de datos
            return ['error' => 'Error al consultar los usuarios: ' . $e->getMessage()];
        }
    }
}

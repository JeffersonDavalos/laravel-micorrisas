<?php

namespace App\Services;

use App\Models\Usuario;
use Illuminate\Database\QueryException;

class UsuarioService
{
    /**
     * Obtener la contraseña del usuario filtrado por usuario y cédula.
     *
     * @param string $usuario
     * @param string $cedula
     * @return array
     */
    public function obtenerContraseñaPorUsuarioYCedula(string $usuario, string $cedula)
    {
        try {
            // Filtrar por usuario y cedula y obtener solo la columna "contraseña"
            $usuarioData = Usuario::where('usuario', $usuario)
                                  ->select('contraseña')
                                  ->first();

            if ($usuarioData) {
                return ['contraseña' => $usuarioData->contraseña];
            } else {
                return ['error' => 'Usuario no encontrado'];
            }

        } catch (QueryException $e) {
            // Capturamos los errores relacionados con la base de datos
            return ['error' => 'Error al consultar los usuarios: ' . $e->getMessage()];
        }
    }
}

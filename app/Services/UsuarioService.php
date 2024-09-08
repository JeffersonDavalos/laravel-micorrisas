<?php

namespace App\Services;

use App\Models\Usuario;
use App\Models\Perfilusuario;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;


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
            $usuarioData = Usuario::where('usuario', $usuario)
                                  ->select('contraseña')
                                  ->first();

            if ($usuarioData) {
                return ['contraseña' => $usuarioData->contraseña];
            } else {
                return ['error' => 'Usuario no encontrado'];
            }

        } catch (QueryException $e) {
            return ['error' => 'Error al consultar los usuarios: ' . $e->getMessage()];
        }
    }

    public function obtener_perfil($usuario)
    {
        try {
            log::alert("Entro aquí con el usuario: " . $usuario);
    
            // Verifica si el usuario está llegando correctamente
            if (empty($usuario)) {
                return ['error' => 'El nombre de usuario no fue proporcionado'];
            }
    
            // Depuración antes de ejecutar la consulta
            log::alert("Preparando la consulta...");
    
            // Realizamos la consulta
            $perfilData = Perfilusuario::join('tbm_usuario', 'tbm_usuario.id_perfil', '=', 'tbm_perfil.id_perfil')
                                ->where('tbm_usuario.usuario', $usuario)
                                ->select('tbm_perfil.id_perfil', 'tbm_perfil.descripcion')
                                ->first();
    
            // Verifica si los datos se obtuvieron correctamente
            log::alert("Resultado de la consulta: " . collect($perfilData));
    
            if ($perfilData) {
                return [
                    'id_perfil' => $perfilData->id_perfil,
                    'descripcion' => $perfilData->descripcion
                ];
            } else {
                return ['error' => 'Usuario no encontrado'];
            }
    
        } catch (\Exception $e) {
            // Loguea cualquier excepción
            log::error('Error al consultar el perfil: ' . $e->getMessage());
            return ['error' => 'Error al consultar el perfil: ' . $e->getMessage()];
        }
    }
    
    
    
}

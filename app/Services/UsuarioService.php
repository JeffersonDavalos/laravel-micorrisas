<?php

namespace App\Services;

use App\Models\Usuario;
use App\Models\Perfilusuario;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str; 
use App\Mail\NotificacionUsuario;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\JsonResponse;
use Exception;

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
            ->where('estado', 'A')
            ->first();


            log::alert($usuarioData);
            if ($usuarioData) {
                return  $usuarioData;
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
    
            if (empty($usuario)) {
                return ['error' => 'El nombre de usuario no fue proporcionado'];
            }
            log::alert("Preparando la consulta...");
            $perfilData = Perfilusuario::join('tbm_usuario', 'tbm_usuario.id_perfil', '=', 'tbm_perfil.id_perfil')
                                ->where('tbm_usuario.usuario', $usuario)
                                ->where('tbm_usuario.estado', 'A')
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
    
    
    public function reporteUsuario($usuario, $cedula = null, $fechaInicio = null, $fechaFin = null)
    {
        try {
            log::alert("Entro aquí con el usuario: " . $usuario);
            $query = Perfilusuario::join('tbm_usuario as u', 'u.id_perfil', '=', 'tbm_perfil.id_perfil')
                                  ->select('u.usuario', 'u.nombre', 'u.apellido', 'u.cedula', 'u.correo', 'u.fecha_creacion','u.estado', 'tbm_perfil.descripcion');
            if (!empty($usuario)) {
                $query->where('u.usuario', $usuario);
            }
    
            if (!empty($cedula)) {
                $query->where('u.cedula', $cedula);
            }
    
            if (!empty($fechaInicio) && !empty($fechaFin)) {
                $fechaFin = date('Y-m-d 23:59:59', strtotime($fechaFin));
                $query->whereBetween('u.fecha_creacion', [$fechaInicio, $fechaFin]);
            }
            
    
            log::alert("Ejecutando la consulta...");
            $perfilData = $query->get();
    
            log::alert("Resultado de la consulta: " . collect($perfilData));
    
            if ($perfilData->isNotEmpty()) {
                return $perfilData; 
            } else {
                return ['error' => 'No se encontraron resultados con los filtros proporcionados'];
            }
    
        } catch (\Exception $e) {
            log::error('Error al consultar el perfil: ' . $e->getMessage());
            return ['error' => 'Error al consultar el perfil: ' . $e->getMessage()];
        }
    }
    
    public function registrarUsuario($data): JsonResponse
    {
        try {
            $usuario = Usuario::where('usuario', $data['usuario'])->first();

            if ($usuario) {
                Log::warning('El usuario ya está registrado.', ['usuario' => $data['usuario']]);
                return response()->json(['error' => 'El usuario ya está registrado.'], 409);
            }
            $nuevoUsuario = Usuario::create([
                'id_perfil' => 3,
                'usuario' => $data['usuario'], 
                'nombre' => $data['nombre'],
                'apellido' => $data['apellido'],
                'cedula' => $data['cedula'],
                'contraseña' => $data['contraseña'],  
                'correo' => $data['correo'],
                'estado' => 'A',
                'fecha_creacion' => now(),
            ]);

            Log::info('Usuario creado exitosamente', ['nuevoUsuario' => $nuevoUsuario]);

            return response()->json(['message' => 'Usuario registrado con éxito'], 200);
        } catch (Exception $e) {
            Log::error('Error al registrar usuario: ' . $e->getMessage());
            return response()->json(['error' => 'Ha ocurrido un error inesperado: ' . $e->getMessage()], 500);
        }
    }

    public function actualizarUsuario($data): JsonResponse
    {
        try {
            $usuario = Usuario::where('id_usuario', $data['id_usuario'])->first();
            if (!$usuario) {
                Log::warning('Usuario no encontrado.', ['id_usuario' => $data['id_usuario']]);
                return response()->json(['error' => 'Usuario no encontrado.'], 404);
            }
    
            $datosAActualizar = [];
            if (!empty($data['usuario'])) {
                $datosAActualizar['usuario'] = $data['usuario'];
            }
    
            if (!empty($data['nombre'])) {
                $datosAActualizar['nombre'] = $data['nombre'];
            }
    
            if (!empty($data['apellido'])) {
                $datosAActualizar['apellido'] = $data['apellido'];
            }
    
            if (!empty($data['cedula'])) {
                $datosAActualizar['cedula'] = $data['cedula'];
            }
    
            if (!empty($data['contraseña'])) {
                $datosAActualizar['contraseña'] =$data['contraseña'];
            }
    
            if (!empty($data['correo'])) {
                $datosAActualizar['correo'] = $data['correo'];
            }
    
            if (!empty($data['estado'])) {
                $datosAActualizar['estado'] = $data['estado'];
            }
            $datosAActualizar['fecha_actualizacion'] = now();
            if (!empty($datosAActualizar)) {
                Usuario::where('id_usuario', $data['id_usuario'])->update($datosAActualizar);
                Log::info('Usuario actualizado exitosamente', ['id_usuario' => $data['id_usuario']]);
                return response()->json(['message' => 'Usuario actualizado con éxito'], 200);
            } else {
                return response()->json(['message' => 'No hay datos para actualizar.'], 400);
            }
        } catch (Exception $e) {
            Log::error('Error al actualizar usuario: ' . $e->getMessage());
            return response()->json(['error' => 'Ha ocurrido un error inesperado: ' . $e->getMessage()], 500);
        }
    }
    
    
    public function listarPerfiles()
    {
        try {
            $resultado = Perfilusuario::select('id_perfil as value', 'descripcion as label')->get();

            return $resultado;
        } catch (Exception $e) {
            \Log::error('Error al listar perfiles: ' . $e->getMessage());
            throw new Exception('Error al listar perfiles.');
        }
    }

    public function actualizarEstado($data): JsonResponse
    {
        try {
            $usuario = Usuario::where('usuario', $data['usuario'])
                              ->where('cedula', $data['cedula'])
                              ->first();
            
            if (!$usuario) {
                Log::warning('Usuario no encontrado.', [
                    'usuario' => $data['usuario'], 
                    'cedula' => $data['cedula']
                ]);
                return response()->json(['error' => 'Usuario no encontrado.'], 404);
            }
            $datosAActualizar = [];

            if (isset($data['estado'])) {
                $datosAActualizar['estado'] = $data['estado'];
            }
            if (isset($data['perfil'])) {  
                $datosAActualizar['id_perfil'] = $data['perfil'];
            }
            $datosAActualizar['fecha_actualizacion'] = now();

            if (!empty($datosAActualizar)) {
                Usuario::where('usuario', $data['usuario'])
                       ->where('cedula', $data['cedula'])
                       ->update($datosAActualizar);
                
                Log::info('Usuario actualizado exitosamente', [
                    'usuario' => $data['usuario'],
                    'cedula' => $data['cedula']
                ]);
                return response()->json(['message' => 'Usuario actualizado con éxito'], 200);
            } else {
                return response()->json(['message' => 'No hay datos para actualizar.'], 400);
            }
        } catch (Exception $e) {
            Log::error('Error al actualizar estado e id_perfil: ' . $e->getMessage());
            return response()->json(['error' => 'Ha ocurrido un error inesperado: ' . $e->getMessage()], 500);
        }
    }
    
}

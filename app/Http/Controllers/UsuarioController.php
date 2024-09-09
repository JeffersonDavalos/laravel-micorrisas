<?php

namespace App\Http\Controllers;

use App\Services\UsuarioService;
use Illuminate\Routing\Controller; 
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;

class UsuarioController extends Controller
{
    protected $usuarioService;

    public function __construct(UsuarioService $usuarioService)
    {
        $this->usuarioService = $usuarioService;
    }

    /**
     *
     * @param Request 
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            Log::alert('Entró aquí');
            Log::alert('Datos recibidos: ' . json_encode($request->all()));

            $usuario = $request->input('usuario');
            $cedula = $request->input('cedula');

            $resultado = $this->usuarioService->obtenerContraseñaPorUsuarioYCedula($usuario, $cedula);
            if (isset($resultado['error'])) {
                return response()->json(['error' => $resultado['error']], 500);
            }

            return response()->json($resultado, 200);

        } catch (Exception $e) {
            Log::error('Error al obtener usuarios: ' . $e->getMessage());
            return response()->json([
                'message' => 'Ha ocurrido un error inesperado: ' . $e->getMessage()
            ], 500);
        }
    }
    public function perfil(Request $request): JsonResponse
    {
        try {
            Log::alert('Datos recibidos: ' . json_encode($request->all()));

            $usuario = $request->input('usuario');

            $resultado = $this->usuarioService->obtener_perfil($usuario);
            if (isset($resultado['error'])) {
                return response()->json(['error' => $resultado['error']], 500);
            }

            return response()->json($resultado, 200);

        } catch (Exception $e) {
            Log::error('Error al obtener usuarios: ' . $e->getMessage());
            return response()->json([
                'message' => 'Ha ocurrido un error inesperado: ' . $e->getMessage()
            ], 500);
        }
    }

    public function reporUsuario(Request $request): JsonResponse
    {
        try {
            log::alert("entro aqui");
            Log::alert('Datos recibidos: ' . json_encode($request->all()));
    
            // Obtén los valores del request
            $usuario = $request->input('usuario');
            $cedula = $request->input('cedula');
            $fechaInicio = $request->input('fechaInicio');
            $fechaFin = $request->input('fechaFin');
    
            // Pasa los valores al servicio
            $resultado = $this->usuarioService->reporteUsuario($usuario, $cedula, $fechaInicio, $fechaFin);
    
            if (isset($resultado['error'])) {
                return response()->json(['error' => $resultado['error']], 500);
            }
    
            return response()->json($resultado, 200);
    
        } catch (Exception $e) {
            Log::error('Error al obtener usuarios: ' . $e->getMessage());
            return response()->json([
                'message' => 'Ha ocurrido un error inesperado: ' . $e->getMessage()
            ], 500);
        }
    }

    public function registrarUsuario(Request $request): JsonResponse
    {
        try {
            Log::info('Entrando al controlador registrarUsuario', ['request' => $request->all()]);
    
            $data = $request->all();
            
            Log::info('Llamando al servicio UsuarioService->registrarUsuario');
            $resultado = $this->usuarioService->registrarUsuario($data);
    
            Log::info('Respuesta del servicio UsuarioService', ['resultado' => $resultado]);
    
            return $resultado;
        } catch (Exception $e) {
            Log::error('Error en el controlador al registrar usuario: ' . $e->getMessage());
            return response()->json([
                'message' => 'Ha ocurrido un error inesperado: ' . $e->getMessage()
            ], 500);
        }
    }

    public function actualizarUsuario(Request $request): JsonResponse
    {
        try {
            Log::info('Entrando al controlador registrarUsuario', ['request' => $request->all()]);
    
            $data = $request->all();
            
            Log::info('Llamando al servicio UsuarioService->registrarUsuario');
            $resultado = $this->usuarioService->actualizarUsuario($data);
    
            Log::info('Respuesta del servicio UsuarioService', ['resultado' => $resultado]);
    
            return $resultado;
        } catch (Exception $e) {
            Log::error('Error en el controlador al registrar usuario: ' . $e->getMessage());
            return response()->json([
                'message' => 'Ha ocurrido un error inesperado: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function listarPerfiles()
    {
        try {
    
            
            $resultado = $this->usuarioService->listarPerfiles();
    
            Log::info('Respuesta del servicio UsuarioService', ['resultado' => $resultado]);
    
            return $resultado;
        } catch (Exception $e) {
            Log::error('Error en el controlador al registrar usuario: ' . $e->getMessage());
            return response()->json([
                'message' => 'Ha ocurrido un error inesperado: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function actualizarEstado(Request $request): JsonResponse
    {
        try {
            Log::info('Entrando al controlador actualizarEstado', ['request' => $request->all()]);
    
            $data = $request->all();
            
            Log::info('Llamando al servicio UsuarioService->actualizarEstado');
            $resultado = $this->usuarioService->actualizarEstado($data);
    
            Log::info('Respuesta del servicio UsuarioService', ['resultado' => $resultado]);
    
            return $resultado;
        } catch (Exception $e) {
            Log::error('Error en el controlador al registrar usuario: ' . $e->getMessage());
            return response()->json([
                'message' => 'Ha ocurrido un error inesperado: ' . $e->getMessage()
            ], 500);
        }
    }
    
}

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
     * Método para manejar la solicitud de obtener usuarios filtrados por usuario y cedula.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            Log::alert('Entró aquí');
            Log::alert('Datos recibidos: ' . json_encode($request->all()));

            // Enviar los parámetros al servicio
            $usuario = $request->input('usuario');
            $cedula = $request->input('cedula');

            // Llamar al servicio para obtener los usuarios filtrados
            $resultado = $this->usuarioService->obtenerContraseñaPorUsuarioYCedula($usuario, $cedula);

            // Verificar si hubo un error en el servicio
            if (isset($resultado['error'])) {
                return response()->json(['error' => $resultado['error']], 500);
            }

            return response()->json($resultado, 200);

        } catch (Exception $e) {
            // Manejo de errores generales
            Log::error('Error al obtener usuarios: ' . $e->getMessage());
            return response()->json([
                'message' => 'Ha ocurrido un error inesperado: ' . $e->getMessage()
            ], 500);
        }
    }
}

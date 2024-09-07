<?php

namespace App\Http\Controllers;

use App\Models\Usuario;  
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Support\Facades\Log;  

class UsuarioController extends Controller
{
    /**
     * Método para manejar la solicitud de obtener todos los usuarios.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            Log::alert('entro aqui');

            $usuarios = Usuario::all();

            return response()->json($usuarios, 200);

        } catch (Exception $e) {
            // Manejo de errores
            Log::error('Error al obtener usuarios: ' . $e->getMessage());
            return response()->json([
                'message' => 'Ha ocurrido un error inesperado: ' . $e->getMessage()
            ], 500);
        }
    }
}

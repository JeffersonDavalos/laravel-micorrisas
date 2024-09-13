<?php

namespace App\Http\Controllers;

use App\Services\UsuarioService;
use Illuminate\Routing\Controller; 
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

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

    public function predict(Request $request)
    {
        if (!$request->has('image')) {
            return response()->json(['error' => 'No se ha proporcionado una imagen'], 400);
        }
    
        try {
            $base64Image = $request->input('image');
            $imageData = explode(',', $base64Image)[1];
            if (!Storage::exists('public/images')) {
                Storage::makeDirectory('public/images');
            }
            $imageName = 'image_' . time() . '.png';
            $imagePath = storage_path('app/public/images/' . $imageName);
            File::put($imagePath, base64_decode($imageData));
            $pythonScript = base_path('predict_micorriza.py');
            $command = escapeshellcmd('C:\Users\ASUS\AppData\Local\Programs\Python\Python312\python.exe ' . $pythonScript . ' ' . $imagePath);
            $output = shell_exec($command);
            if (!$output) {
                return response()->json(['error' => 'Error al ejecutar el script de predicción'], 500);
            }
            $outputLines = preg_split('/\r\n|\r|\n/', trim($output));
            $prediction = end($outputLines); 
            Log::alert("Predicción final: " . $prediction);
            return response()->json([
                'prediccion' => trim($prediction),
            ]);
    
        } catch (\Exception $e) {
            Log::error('Error en el proceso de predicción: ' . $e->getMessage());
            return response()->json(['error' => 'Error en el servidor, por favor intente nuevamente'], 500);
        }
    }
    
    
    
    
    
    
}

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;

Route::get('/students',function(){
    return 'holi';
});
Route::post('/usuarios', [UsuarioController::class, 'index']);
Route::post('/perfil', [UsuarioController::class, 'perfil']);
Route::post('/reporteUsuario', [UsuarioController::class, 'reporUsuario']);
Route::post('/registrarUsuario', [UsuarioController::class, 'registrarUsuario']);
Route::post('/actualizarUsuario', [UsuarioController::class, 'actualizarUsuario']);
Route::get('/listarPerfiles', [UsuarioController::class, 'listarPerfiles']);
Route::post('/actualizarEstado', [UsuarioController::class, 'actualizarEstado']);
Route::post('/predict', [UsuarioController::class, 'predict']);







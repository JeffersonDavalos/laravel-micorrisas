<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;

Route::get('/students',function(){
    return 'holi';
});
Route::post('/usuarios', [UsuarioController::class, 'index']);

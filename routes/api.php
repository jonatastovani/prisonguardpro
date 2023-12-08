<?php

use App\Http\Controllers\RefArtigoController;
use Illuminate\Http\Request;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Controllers\SanctumController;

// Rota para autenticação
Route::post('/auth', [LoginController::class, 'auth']);

// Grupo de rotas protegidas pelo Sanctum
Route::group(['middleware' => 'auth:sanctum'], function () {
    // Rotas relacionadas ao RefArtigoController
    Route::get('/ref/artigos', [RefArtigoController::class, 'index']);
    Route::post('/ref/artigos', [RefArtigoController::class, 'store']);
    Route::delete('/ref/artigos/{id}', [RefArtigoController::class, 'destroy']);
    
    // Rota de logout
    Route::post('/logout', [SanctumController::class, 'logout']);
});
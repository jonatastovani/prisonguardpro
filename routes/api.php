<?php

use App\Http\Controllers\RefArtigoController;
use Illuminate\Http\Request;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserPermissaoController;
use App\Models\RefArtigo;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Controllers\SanctumController;


// Rotas relacionadas ao RefArtigoController
Route::controller(LoginController::class)->group(function () {
    // Rota para autenticação web com sessão
    Route::post('/auth', 'auth');
    // Rota geração de token
    Route::post('/authToken', 'authToken');
});

route::prefix('/v1')->group(function() {
    Route::group(['middleware' => 'auth:sanctum'], function () {
    
        // Rota de logout
        Route::post('/logout', [SanctumController::class, 'logout']);
        
        // Rotas relacionadas ao RefArtigoController
        Route::controller(RefArtigoController::class)->group(function() {
            Route::get('/ref/artigos', 'index');
            Route::get('/ref/artigos/{id}', 'show');
            Route::post('/ref/artigos', 'store');
            Route::delete('/ref/artigos/{id}', 'destroy');
        });
        
        
        // Rotas relacionadas ao UserPermissaoController
        Route::controller(UserPermissaoController::class)->group(function() {
            Route::prefix('/userPermissao')->group(function () {
                Route::get('', 'index');
                Route::get('/{id}', 'show');
                Route::post('', 'store');
                Route::delete('/{id}', 'destroy');
            });
        });
    });
});


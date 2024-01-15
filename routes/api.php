<?php

use App\Http\Controllers\RefArtigoController;
use Illuminate\Http\Request;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RefCidadeController;
use App\Http\Controllers\RefDocumentoTipoController;
use App\Http\Controllers\RefEscolaridadeController;
use App\Http\Controllers\RefEstadoCivilController;
use App\Http\Controllers\RefEstadoController;
use App\Http\Controllers\RefGeneroController;
use App\Http\Controllers\RefIncOrigemController;
use App\Http\Controllers\RefNacionalidadeController;
use App\Http\Controllers\RefProfissaoController;
use App\Http\Controllers\UserPermissaoController;
use App\Models\RefCidade;
use Illuminate\Support\Facades\Route;

// Rotas relacionadas ao LoginController
Route::controller(LoginController::class)->group(function () {
    // Rota para autenticação web com sessão
    Route::post('/auth', 'auth');
    // Rota geração de token
    Route::post('/authToken', 'authToken');
});

route::prefix('/v1')->group(function () {
    Route::group(['middleware' => 'auth:sanctum'], function () {

        // Rota de logout
        Route::post('/logout', [LoginController::class, 'logout']);

        Route::prefix('/ref')->group(function () {

            // Rotas relacionadas ao RefArtigoController
            Route::controller(RefArtigoController::class)->group(function () {
                Route::prefix('/artigo')->group(function () {
                    Route::get('', 'index');
                    Route::get('/{id}', 'show');
                    Route::post('', 'store');
                    Route::put('/{id}', 'update');
                    Route::delete('/{id}', 'destroy');
                });
            });

            // Rotas relacionadas ao RefCidadeController
            Route::controller(RefCidadeController::class)->group(function () {
                Route::prefix('/cidade')->group(function () {
                    Route::get('', 'index');
                    Route::get('/{id}', 'show');
                    Route::post('', 'store');
                    Route::put('/{id}', 'update');
                    Route::delete('/{id}', 'destroy');
                });
            });

            // Rotas relacionadas ao RefDocumentoTipoController
            Route::controller(RefDocumentoTipoController::class)->group(function () {
                Route::prefix('/documentotipo')->group(function () {
                    Route::get('', 'index');
                    Route::get('/{id}', 'show');
                    Route::post('', 'store');
                    Route::put('/{id}', 'update');
                    Route::delete('/{id}', 'destroy');
                });
            });

            // Rotas relacionadas ao RefEscolaridadeController
            Route::controller(RefEscolaridadeController::class)->group(function () {
                Route::prefix('/escolaridade')->group(function () {
                    Route::get('', 'index');
                    Route::get('/{id}', 'show');
                    Route::post('', 'store');
                    Route::put('/{id}', 'update');
                    Route::delete('/{id}', 'destroy');
                });
            });

            // Rotas relacionadas ao RefEstadoCivilController
            Route::controller(RefEstadoCivilController::class)->group(function () {
                Route::prefix('/estadocivil')->group(function () {
                    Route::get('', 'index');
                    Route::get('/{id}', 'show');
                    Route::post('', 'store');
                    Route::put('/{id}', 'update');
                    Route::delete('/{id}', 'destroy');
                });
            });

            // Rotas relacionadas ao RefGeneroController
            Route::controller(RefGeneroController::class)->group(function () {
                Route::prefix('/genero')->group(function () {
                    Route::get('', 'index');
                    Route::get('/{id}', 'show');
                    Route::post('', 'store');
                    Route::put('/{id}', 'update');
                    Route::delete('/{id}', 'destroy');
                });
            });

            // Rotas relacionadas ao RefIncOrigemController
            Route::controller(RefIncOrigemController::class)->group(function () {
                Route::prefix('/origem')->group(function () {
                    Route::get('', 'index');
                    Route::get('/{id}', 'show');
                    Route::post('', 'store');
                    Route::put('/{id}', 'update');
                    Route::delete('/{id}', 'destroy');
                });
            });

            // Rotas relacionadas ao RefEstadoController
            Route::controller(RefEstadoController::class)->group(function () {
                Route::prefix('/estado')->group(function () {
                    Route::get('', 'index');
                    Route::get('/{id}', 'show');
                    Route::post('', 'store');
                    Route::put('/{id}', 'update');
                    Route::delete('/{id}', 'destroy');
                });
            });

            // Rotas relacionadas ao RefNacionalidadeController
            Route::controller(RefNacionalidadeController::class)->group(function () {
                Route::prefix('/nacionalidade')->group(function () {
                    Route::get('', 'index');
                    Route::get('/{id}', 'show');
                    Route::post('', 'store');
                    Route::put('/{id}', 'update');
                    Route::delete('/{id}', 'destroy');
                });
            });

            // Rotas relacionadas ao RefProfissaoController
            Route::controller(RefProfissaoController::class)->group(function () {
                Route::prefix('/profissao')->group(function () {
                    Route::get('', 'index');
                    Route::get('/{id}', 'show');
                    Route::post('', 'store');
                    Route::put('/{id}', 'update');
                    Route::delete('/{id}', 'destroy');
                });
            });
            
        });

        // Rotas relacionadas ao RefIncOrigemController
        Route::controller(RefIncOrigemController::class)->group(function () {
            Route::prefix('/origem')->group(function () {
                Route::get('', 'index');
                Route::get('/{id}', 'show');
                Route::post('', 'store');
                Route::put('/{id}', 'update');
                Route::delete('/{id}', 'destroy');
            });
        });

        // Rotas relacionadas ao UserPermissaoController
        Route::controller(UserPermissaoController::class)->group(function () {
            Route::prefix('/userPermissao')->group(function () {
                Route::get('/{id}', 'index');
                Route::get('/{id}/idPermissao/{idPermissao}', 'show');
                Route::post('', 'store');
                Route::put('/{id}', 'update');
                Route::delete('/{id}', 'destroy');
            });
        });
    });
});

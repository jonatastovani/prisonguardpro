<?php

use App\Http\Controllers\IncEntradaController;
use App\Http\Controllers\IncEntradaPresoController;
use App\Http\Controllers\IncQualificativaProvisoriaController;
use App\Http\Controllers\RefArtigoController;
use Illuminate\Http\Request;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PessoaController;
use App\Http\Controllers\PresoController;
use App\Http\Controllers\RefCabeloCorController;
use App\Http\Controllers\RefCabeloTipoController;
use App\Http\Controllers\RefCidadeController;
use App\Http\Controllers\RefCoresController;
use App\Http\Controllers\RefCrencaController;
use App\Http\Controllers\RefCutisController;
use App\Http\Controllers\RefDocumentoOrgaoEmissorController;
use App\Http\Controllers\RefDocumentoTipoController;
use App\Http\Controllers\RefEscolaridadeController;
use App\Http\Controllers\RefEstadoCivilController;
use App\Http\Controllers\RefEstadoController;
use App\Http\Controllers\RefGeneroController;
use App\Http\Controllers\RefIncOrigemController;
use App\Http\Controllers\RefNacionalidadeController;
use App\Http\Controllers\RefOlhoCorController;
use App\Http\Controllers\RefOlhoTipoController;
use App\Http\Controllers\RefPresoConvivioTipoController;
use App\Http\Controllers\RefProfissaoController;
use App\Http\Controllers\RefStatusController;
use App\Http\Controllers\UserPermissaoController;
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

            // Rotas relacionadas ao RefCabeloCorController
            Route::controller(RefCabeloCorController::class)->group(function () {
                Route::prefix('/cabelocor')->group(function () {
                    Route::get('', 'index');
                    Route::get('/{id}', 'show');
                    Route::post('', 'store');
                    Route::put('/{id}', 'update');
                    Route::delete('/{id}', 'destroy');
                });
            });

            // Rotas relacionadas ao RefCabeloTipoController
            Route::controller(RefCabeloTipoController::class)->group(function () {
                Route::prefix('/cabelotipo')->group(function () {
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

            // Rotas relacionadas ao RefCoresController
            Route::controller(RefCoresController::class)->group(function () {
                Route::prefix('/cores')->group(function () {
                    Route::get('', 'index');
                    // Route::get('/{id}', 'show');
                    // Route::post('', 'store');
                    // Route::put('/{id}', 'update');
                    // Route::delete('/{id}', 'destroy');
                });
            });

            // Rotas relacionadas ao RefCutisController
            Route::controller(RefCrencaController::class)->group(function () {
                Route::prefix('/crenca')->group(function () {
                    Route::get('', 'index');
                    Route::get('/{id}', 'show');
                    Route::post('', 'store');
                    Route::put('/{id}', 'update');
                    Route::delete('/{id}', 'destroy');
                });
            });

            // Rotas relacionadas ao RefCutisController
            Route::controller(RefCutisController::class)->group(function () {
                Route::prefix('/cutis')->group(function () {
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

            // Rotas relacionadas ao RefDocumentoOrgaoEmissor
            Route::controller(RefDocumentoOrgaoEmissorController::class)->group(function () {
                Route::prefix('/documentoemissor')->group(function () {
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
                Route::prefix('/inclusao/origem')->group(function () {
                    Route::get('', 'index');
                    Route::post('/busca/select', 'indexBusca');
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

            Route::prefix('/presos')->group(function(){

                // Rotas relacionadas ao RefPresoConvivioTipoController
                Route::controller(RefPresoConvivioTipoController::class)->group(function () {
                    Route::prefix('/convivios/tipos')->group(function () {
                        Route::get('', 'index');
                        // Route::get('/{id}', 'show');
                        // Route::post('', 'store');
                        // Route::put('/{id}', 'update');
                        // Route::delete('/{id}', 'destroy');
                    });
                });

            });


            // Rotas relacionadas ao RefOlhoCorController
            Route::controller(RefOlhoCorController::class)->group(function () {
                Route::prefix('/olhocor')->group(function () {
                    Route::get('', 'index');
                    Route::get('/{id}', 'show');
                    Route::post('', 'store');
                    Route::put('/{id}', 'update');
                    Route::delete('/{id}', 'destroy');
                });
            });

            // Rotas relacionadas ao RefOlhoTipoController
            Route::controller(RefOlhoTipoController::class)->group(function () {
                Route::prefix('/olhotipo')->group(function () {
                    Route::get('', 'index');
                    Route::get('/{id}', 'show');
                    Route::post('', 'store');
                    Route::put('/{id}', 'update');
                    Route::delete('/{id}', 'destroy');
                });
            });

            // Rotas relacionadas ao RefStatusController
            Route::controller(RefStatusController::class)->group(function () {
                Route::prefix('/status')->group(function () {
                    Route::get('', 'index');
                    // Route::get('/{id}', 'show');
                    // Route::post('', 'store');
                    // Route::put('/{id}', 'update');
                    // Route::delete('/{id}', 'destroy');

                    Route::prefix('/busca')->group(function () {
                        Route::post('/select', 'preencherSelect');
                    });
                });
            });
        });

        Route::prefix('/inclusao')->group(function () {

            Route::prefix('/entradas')->group(function () {

                // Rotas relacionadas ao IncEntradaController
                Route::controller(IncEntradaController::class)->group(function () {
                    Route::get('/{id}', 'show');
                    Route::post('', 'store');
                    Route::put('/{id}', 'update');
                    Route::delete('/{id}', 'destroy');

                    // Route::post('/busca', 'indexBusca');
                    // Route::prefix('/busca')->group(function() {
                    //     Route::post('/entradas', 'indexBusca');
                    // });
                });

                // Rotas relacionadas ao IncEntradaPresoController
                Route::controller(IncEntradaPresoController::class)->group(function () {
                    Route::prefix('/presos')->group(function () {
                        Route::get('/{id}', 'show');
                        // Route::post('', 'store');
                        // Route::put('/{id}', 'update');
                        Route::delete('/{id}', 'destroy');

                        Route::post('/busca', 'indexBusca');
                    });
                });
            });

            Route::prefix('/qualificativa')->group(function () {

                Route::prefix('/provisoria')->group(function () {

                    // Rotas relacionadas ao IncQualificativaProvisoriaController
                    Route::controller(IncQualificativaProvisoriaController::class)->group(function () {
                        Route::get('/{id}', 'show');
                        Route::post('', 'store');
                        Route::put('/{id}', 'update');
                        Route::delete('/{id}', 'destroy');
                    });

                });

            });

        });

        // Rotas relacionadas ao PessoaController
        Route::controller(PessoaController::class)->group(function () {
            Route::prefix('/pessoa')->group(function () {
                Route::get('', 'index');
                Route::get('/{id}', 'show');
                Route::post('', 'store');
                Route::put('/{id}', 'update');
                Route::delete('/{id}', 'destroy');
            });
        });

        // Rotas relacionadas ao PessoaController
        Route::controller(PresoController::class)->group(function () {
            Route::prefix('/preso')->group(function () {
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

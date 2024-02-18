<?php

use App\Http\Controllers\IncEntradaController;
use App\Http\Controllers\IncEntradaPresoController;
use App\Http\Controllers\IncQualificativaController;
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

        // Defina uma regra de padrão para todos os parâmetros {id} e {passagem_id}
        // Route::pattern(['id', 'passagem_id'], '[0-9]+');
        Route::pattern('passagem_id', '[0-9]+');
        Route::pattern('id', '[0-9]+');

        // Rota de logout
        Route::post('/logout', [LoginController::class, 'logout']);

        Route::prefix('/ref')->group(function () {

            // Rotas relacionadas ao RefArtigoController
            Route::controller(RefArtigoController::class)->group(function () {
                Route::prefix('/artigos')->group(function () {
                    Route::get('', 'index');
                    Route::match(['get', 'post'], '/{id}', 'show');
                    Route::post('', 'store');
                    Route::put('/{id}', 'update');
                    Route::delete('/{id}', 'destroy');

                    Route::prefix('/search')->group(function () {
                        Route::post('/select2', 'indexSelect2');
                        Route::post('/all', 'indexSearchAll');
                    });
                });
            });

            // Rotas relacionadas ao RefCabeloCorController
            Route::controller(RefCabeloCorController::class)->group(function () {
                Route::prefix('/cabelocores')->group(function () {
                    Route::get('', 'index');
                    Route::get('/{id}', 'show');
                    Route::post('', 'store');
                    Route::put('/{id}', 'update');
                    Route::delete('/{id}', 'destroy');

                    Route::prefix('/search')->group(function () {
                        Route::post('/all', 'indexSearchAll');
                    });
                });
            });

            // Rotas relacionadas ao RefCabeloTipoController
            Route::controller(RefCabeloTipoController::class)->group(function () {
                Route::prefix('/cabelotipos')->group(function () {
                    Route::get('', 'index');
                    Route::get('/{id}', 'show');
                    Route::post('', 'store');
                    Route::put('/{id}', 'update');
                    Route::delete('/{id}', 'destroy');

                    Route::prefix('/search')->group(function () {
                        Route::post('/all', 'indexSearchAll');
                    });
                });
            });

            // Rotas relacionadas ao RefCidadeController
            Route::controller(RefCidadeController::class)->group(function () {
                Route::prefix('/cidades')->group(function () {
                    Route::get('', 'index');
                    Route::get('/{id}', 'show');
                    Route::post('', 'store');
                    Route::put('/{id}', 'update');
                    Route::delete('/{id}', 'destroy');

                    Route::prefix('/search')->group(function () {
                        Route::post('/select2', 'indexSelect2');
                        Route::post('/all', 'indexSearchAll');
                    });
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
                Route::prefix('/crencas')->group(function () {
                    Route::get('', 'index');
                    Route::get('/{id}', 'show');
                    Route::post('', 'store');
                    Route::put('/{id}', 'update');
                    Route::delete('/{id}', 'destroy');

                    Route::prefix('/search')->group(function () {
                        Route::post('/all', 'indexSearchAll');
                    });
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

                    Route::prefix('/search')->group(function () {
                        Route::post('/all', 'indexSearchAll');
                    });
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
                Route::prefix('/escolaridades')->group(function () {
                    Route::get('', 'index');
                    Route::get('/{id}', 'show');
                    Route::post('', 'store');
                    Route::put('/{id}', 'update');
                    Route::delete('/{id}', 'destroy');

                    Route::prefix('/search')->group(function () {
                        Route::post('/select2', 'indexSelect2');
                        Route::post('/all', 'indexSearchAll');
                    });
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

                    Route::prefix('/search')->group(function () {
                        Route::post('/all', 'indexSearchAll');
                    });
                });
            });

            // Rotas relacionadas ao RefEstadoController
            Route::controller(RefEstadoController::class)->group(function () {
                Route::prefix('/estados')->group(function () {
                    Route::get('', 'index');
                    Route::get('/{id}', 'show');
                    Route::post('', 'store');
                    Route::put('/{id}', 'update');
                    Route::delete('/{id}', 'destroy');

                    Route::prefix('/search')->group(function () {
                        Route::post('/select2', 'indexSelect2');
                        Route::post('/all', 'indexSearchAll');
                    });
                });
            });

            // Rotas relacionadas ao RefGeneroController
            Route::controller(RefGeneroController::class)->group(function () {
                Route::prefix('/generos')->group(function () {
                    Route::get('', 'index');
                    Route::get('/{id}', 'show');
                    Route::post('', 'store');
                    Route::put('/{id}', 'update');
                    Route::delete('/{id}', 'destroy');

                    Route::prefix('/search')->group(function () {
                        Route::post('/all', 'indexSearchAll');
                    });
                });
            });

            // Rotas relacionadas ao RefIncOrigemController
            Route::controller(RefIncOrigemController::class)->group(function () {
                Route::prefix('/inclusao/origem')->group(function () {
                    Route::get('', 'index');
                    Route::get('/{id}', 'show');
                    Route::post('', 'store');
                    Route::put('/{id}', 'update');
                    Route::delete('/{id}', 'destroy');

                    Route::prefix('/search')->group(function () {
                        Route::post('/select2', 'indexSelect2');
                    });
                });
            });

            // Rotas relacionadas ao RefNacionalidadeController
            Route::controller(RefNacionalidadeController::class)->group(function () {
                Route::prefix('/nacionalidades')->group(function () {
                    Route::get('', 'index');
                    Route::get('/{id}', 'show');
                    Route::post('', 'store');
                    Route::put('/{id}', 'update');
                    Route::delete('/{id}', 'destroy');

                    Route::prefix('/search')->group(function () {
                        Route::post('/select2', 'indexSelect2');
                        Route::post('/all', 'indexSearchAll');
                    });
                });
            });

            // Rotas relacionadas ao RefOlhoCorController
            Route::controller(RefOlhoCorController::class)->group(function () {
                Route::prefix('/olhocores')->group(function () {
                    Route::get('', 'index');
                    Route::get('/{id}', 'show');
                    Route::post('', 'store');
                    Route::put('/{id}', 'update');
                    Route::delete('/{id}', 'destroy');

                    Route::prefix('/search')->group(function () {
                        Route::post('/all', 'indexSearchAll');
                    });
                });
            });

            // Rotas relacionadas ao RefOlhoTipoController
            Route::controller(RefOlhoTipoController::class)->group(function () {
                Route::prefix('/olhotipos')->group(function () {
                    Route::get('', 'index');
                    Route::get('/comdescricao', 'indexNomeDescricao');
                    Route::get('/{id}', 'show');
                    Route::post('', 'store');
                    Route::put('/{id}', 'update');
                    Route::delete('/{id}', 'destroy');

                    Route::prefix('/search')->group(function () {
                        Route::post('/all', 'indexSearchAll');
                    });
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

            Route::prefix('/presos')->group(function () {

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
                Route::prefix('/olhocores')->group(function () {
                    Route::get('', 'index');
                    Route::get('/{id}', 'show');
                    Route::post('', 'store');
                    Route::put('/{id}', 'update');
                    Route::delete('/{id}', 'destroy');

                    Route::prefix('/search')->group(function () {
                        Route::post('/all', 'indexSearchAll');
                    });
                });
            });

            // Rotas relacionadas ao RefOlhoTipoController
            Route::controller(RefOlhoTipoController::class)->group(function () {
                Route::prefix('/olhotipos')->group(function () {
                    Route::get('', 'index');
                    Route::get('/comdescricao', 'indexNomeDescricao');
                    Route::get('/{id}', 'show');
                    Route::post('', 'store');
                    Route::put('/{id}', 'update');
                    Route::delete('/{id}', 'destroy');

                    Route::prefix('/search')->group(function () {
                        Route::post('/all', 'indexSearchAll');
                    });
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

                    Route::prefix('/search')->group(function () {
                        Route::post('/select', 'fillSelect');
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

                // Rotas relacionadas ao IncQualificativaController
                Route::controller(IncQualificativaController::class)->group(function () {
                    Route::get('/{passagem_id}', 'show');
                    Route::post('', 'store');
                    Route::put('/{passagem_id}', 'update');
                    Route::delete('/{id}', 'destroy');
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

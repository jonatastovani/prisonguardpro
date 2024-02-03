<?php

use App\Http\Controllers\CarrinhoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InclusaoController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;
use Laravel\Sanctum\Http\Controllers\SanctumController;

Route::view('/login', 'login.login')->name('login.login');

Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::get('/', [SiteController::class, 'index'])->name('site.index')->middleware('auth');

    // Rotas relacionadas ao InclusaoController
    Route::controller(InclusaoController::class)->group(function () {
        Route::prefix('/inclusao')->group(function () {
            Route::get('', 'home')->name('inclusao.home');

            Route::prefix('/entradas')->group(function () {
                Route::match(['get','post'],'', 'entradasPresos')->name('inclusao.entradasPresos');
                Route::get('/criar', 'cadastroEntradasPresos')->name('inclusao.criarEntradasPresos');
                Route::match(['get','post'],'/{id}','cadastroEntradasPresos')->name('inclusao.editarEntradasPresos');
            });

            Route::prefix('/qualificativa')->group(function () {
                // Route::match(['get','post'],'', 'entradasPresos')->name('inclusao.entradasPresos');
                // Route::get('/criar', 'cadastroEntradasPresos')->name('inclusao.criarEntradasPresos');
                Route::match(['get','post'],'/{id}','cadastroQualificativa')->name('inclusao.qualificativa.cadastroQualificativa');
            });
        });
    });
});

Route::get('/logout', [LoginController::class, 'logout'])->name('login.logout');

// Route::get('test', [TestController::class, 'test']);
Route::view('teste', 'teste.pagina');

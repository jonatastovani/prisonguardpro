<?php

use App\Http\Controllers\CarrinhoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InclusaoController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;
use Laravel\Sanctum\Http\Controllers\SanctumController;

// Route::post('/sanctum/csrf-cookie', CsrfCookieController::class);

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::resource('produtos', ProdutoController::class);
// Route::resource('users', UserController::class);

Route::view('/login','login.login')->name('login.login');

Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::get('/', [SiteController::class, 'index'])->name('site.index')->middleware('auth');

    // Rotas relacionadas ao InclusaoController
    Route::controller(InclusaoController::class)->group(function() {
        Route::prefix('/inclusao')->group(function () {
            Route::get('', 'home')->name('inclusao.home');
            Route::get('/entradaspresos', 'entradaspresos')->name('inclusao.entradaspresos');
        });
    });
    
});


// Route::get('/produto/{slug}', [SiteController::class, 'details'])->name('site.details');

// Route::get('/categoria/{id}', [SiteController::class, 'categoria'])->name('site.categoria');

// Route::get('/carrinho', [CarrinhoController::class, 'carrinhoLista'])->name('site.carrinho');
// Route::post('/carrinho', [CarrinhoController::class, 'adicionaCarrinho'])->name('site.addCarrinho');
// Route::post('/remover', [CarrinhoController::class, 'removeCarrinho'])->name('site.removeCarrinho');
// Route::post('/atualizar', [CarrinhoController::class, 'atualizaCarrinho'])->name('site.atualizaCarrinho');
// Route::get('/limpar', [CarrinhoController::class, 'limpaCarrinho'])->name('site.limpaCarrinho');

// Route::post('/auth', [LoginController::class, 'auth'])->name('login.auth');
Route::get('/logout', [LoginController::class, 'logout'])->name('login.logout');
// Route::get('/register', [LoginController::class, 'create'])->name('login.create');

// Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard')->middleware(['auth']);
// Route::get('/admin/produtos', [ProdutoController::class, 'index'])->name('admin.produtos');



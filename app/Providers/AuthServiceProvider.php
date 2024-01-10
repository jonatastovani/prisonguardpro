<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Produto;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Produto' => 'App\Policies\ProdutoPolicy',
        \App\Models\RefArtigo::class => \App\Policies\RefArtigoPolicy::class,
        \App\Models\RefIncOrigem::class => \App\Policies\RefIncOrigemPolicy::class,
        \App\Models\RefDocumentoTipo::class => \App\Policies\RefDocumentoTipoPolicy::class,

    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Gate::define('ver-produto', function(User $user, Produto $produto) {
        //     return $user->id === $produto->id_user;
        // });
    }
}

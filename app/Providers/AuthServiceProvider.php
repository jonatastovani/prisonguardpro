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
        \App\Models\RefProfissao::class => \App\Policies\RefProfissaoPolicy::class,
        \App\Models\RefEstado::class => \App\Policies\RefEstadoPolicy::class,
        \App\Models\RefNacionalidade::class => \App\Policies\RefNacionalidadePolicy::class,
        \App\Models\RefGenero::class => \App\Policies\RefGeneroPolicy::class,
        \App\Models\RefEscolaridade::class => \App\Policies\RefEscolaridadePolicy::class,
        \App\Models\RefCidade::class => \App\Policies\RefCidadePolicy::class,
        \App\Models\RefEstadoCivil::class => \App\Policies\RefEstadoCivilPolicy::class,
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

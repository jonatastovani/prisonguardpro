<?php

namespace App\Policies;

use App\Common\PermissaoService;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Access\HandlesAuthorization;

class RefArtigoPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function update(User $user)
    {
        $permissaoIdsPermitidas = [19];
    
        // Verifica se o usuário possui pelo menos uma das permissões permitidas
        return $user->permissoes->pluck('permissao_id')->intersect($permissaoIdsPermitidas)->isNotEmpty();
    }

    // public function delete(User $user)
    // {
    //     $permissaoIdsPermitidas = [20];
    
    //     if (!$user->permissoes->pluck('permissao_id')->intersect($permissaoIdsPermitidas)->isNotEmpty()) {
    //         throw new AuthorizationException();
    //     }

    //     return true;
    // }
    
    public function delete(User $user)
    {
        $permissaoIdsPermitidas = [20];
    
        if (!PermissaoService::temPermissaoRecursivaAcima($user, 20)) {
            throw new AuthorizationException();
        }

        return true;
    }    
}

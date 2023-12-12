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
    
        if (!PermissaoService::temPermissaoRecursivaAcima($user, $permissaoIdsPermitidas)) {
            throw new AuthorizationException();
        }

        return true;
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
    
        if (!PermissaoService::temPermissaoRecursivaAcima($user, $permissaoIdsPermitidas)) {
            throw new AuthorizationException();
        }

        return true;
    }    
}

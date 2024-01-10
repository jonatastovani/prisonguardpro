<?php

namespace App\Policies;

use App\Common\PermissaoService;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Access\HandlesAuthorization;

class RefEstadoPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function store(User $user)
    {
        $permissaoIdsPermitidas = [76];
    
        if (!PermissaoService::temPermissaoRecursivaAcima($user, $permissaoIdsPermitidas)) {
            throw new AuthorizationException();
        }

        return true;
    }
    
    public function update(User $user)
    {
        $permissaoIdsPermitidas = [77];
    
        if (!PermissaoService::temPermissaoRecursivaAcima($user, $permissaoIdsPermitidas)) {
            throw new AuthorizationException();
        }

        return true;
    }
    
    public function delete(User $user)
    {
        $permissaoIdsPermitidas = [78];
    
        if (!PermissaoService::temPermissaoRecursivaAcima($user, $permissaoIdsPermitidas)) {
            throw new AuthorizationException();
        }

        return true;
    }    
}

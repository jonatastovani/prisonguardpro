<?php

namespace App\Policies;

use App\Common\PermissaoService;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Access\HandlesAuthorization;

class RefEstadoCivilPolicy
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
        $permissaoIdsPermitidas = [91];
    
        if (!PermissaoService::temPermissaoRecursivaAcima($user, $permissaoIdsPermitidas)) {
            throw new AuthorizationException();
        }

        return true;
    }
    
    public function update(User $user)
    {
        $permissaoIdsPermitidas = [92];
    
        if (!PermissaoService::temPermissaoRecursivaAcima($user, $permissaoIdsPermitidas)) {
            throw new AuthorizationException();
        }

        return true;
    }
    
    public function delete(User $user)
    {
        $permissaoIdsPermitidas = [93];
    
        if (!PermissaoService::temPermissaoRecursivaAcima($user, $permissaoIdsPermitidas)) {
            throw new AuthorizationException();
        }

        return true;
    }    
}

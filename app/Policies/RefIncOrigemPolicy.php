<?php

namespace App\Policies;

use App\Common\PermissaoService;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Access\HandlesAuthorization;

class RefIncOrigemPolicy
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
        $permissaoIdsPermitidas = [69];
    
        if (!PermissaoService::temPermissaoRecursivaAcima($user, $permissaoIdsPermitidas)) {
            throw new AuthorizationException();
        }

        return true;
    }
    
    public function delete(User $user)
    {
        $permissaoIdsPermitidas = [70];
    
        if (!PermissaoService::temPermissaoRecursivaAcima($user, $permissaoIdsPermitidas)) {
            throw new AuthorizationException();
        }

        return true;
    }    
}

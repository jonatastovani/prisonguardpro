<?php

namespace App\Common;

use App\Models\RefPermissao;
use App\Models\User;

class PermissaoService
{
    // public static function temPermissao(User $user, $permissaoId)
    // {
    //     // Verifica se o usuário tem a permissão diretamente
    //     if ($user->permissoes->pluck('permissao_id')->contains($permissaoId)) {
    //         return true;
    //     }

    //     // Verifica se o usuário tem a permissão através de permissões escalonadas
    //     $permissao = RefPermissao::find($permissaoId);

    //     if ($permissao && $permissao->permissao_pai_id) {
    //         // Realiza uma busca recursiva para verificar as permissões pai
    //         return self::temPermissao($user, $permissao->permissao_pai_id);
    //     }

    //     return false;
    // }

    public static function temPermissaoRecursivaAcima(User $user, $permissaoId)
    {
        // Verifica se o usuário tem a permissão diretamente
        if ($user->permissoes->pluck('permissao_id')->contains($permissaoId)) {
            return true;
        }

        // Verifica se o usuário tem a permissão através de permissões escalonadas
        $permissao = RefPermissao::find($permissaoId);

        if ($permissao && $permissao->permissao_pai_id) {
            // Realiza uma busca recursiva para verificar as permissões pai
            return self::temPermissaoRecursivaAcima($user, $permissao->permissao_pai_id);
        }

        return false;
    }
    
}

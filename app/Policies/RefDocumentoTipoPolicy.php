<?php

namespace App\Policies;

use App\Common\PermissaoService;
use App\Common\Permissoes;
use App\Common\RestResponse;
use App\Http\Controllers\RefDocumentoTipoController;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Http\Request;

class RefDocumentoTipoPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function store(User $user, Request $request)
    {
        $permissaoIdsPermitidas = [6];
        $messagePermissaoNegada = '';

        if ($request->has('bloqueado_perm_adm_bln') && $request->input('bloqueado_perm_adm_bln')) {
            $permissaoIdsPermitidas = Permissoes::getRegistrosBloqueados();
            $messagePermissaoNegada = "Você não possui permissão para criar um Tipo de Documento com essas configurações.";
        }

        if (!PermissaoService::temPermissaoRecursivaAcima($user, $permissaoIdsPermitidas)) {
            throw new AuthorizationException($messagePermissaoNegada);
        }

        return true;
    }

    public function update(User $user, Request $request)
    {
        $permissaoIdsPermitidas = [6];
        $messagePermissaoNegada = '';
        $controller = new RefDocumentoTipoController();

        $resource = $controller->buscarRecurso($request->id);
        if ($resource->bloqueado_perm_adm_bln || $request->has('bloqueado_perm_adm_bln') && $request->input('bloqueado_perm_adm_bln')) {
            $permissaoIdsPermitidas = Permissoes::getRegistrosBloqueados();
            $messagePermissaoNegada = "Você não possui permissão para editar este Tipo de Documento com essas configurações.";
        }

        if (!PermissaoService::temPermissaoRecursivaAcima($user, $permissaoIdsPermitidas)) {
            throw new AuthorizationException($messagePermissaoNegada);
        }

        return true;
    }

    public function delete(User $user, $id)
    {
        $permissaoIdsPermitidas = [6];
        $messagePermissaoNegada = '';
        $controller = new RefDocumentoTipoController();

        $resource = $controller->buscarRecurso($id);
        if ($resource->bloqueado_perm_adm_bln) {
            $permissaoIdsPermitidas = Permissoes::getRegistrosBloqueados();
            $messagePermissaoNegada = "Você não possui permissão para excluir este Tipo de Documento.";
        }

        if (!PermissaoService::temPermissaoRecursivaAcima($user, $permissaoIdsPermitidas)) {
            throw new AuthorizationException($messagePermissaoNegada);
        }

        return true;
    }
}

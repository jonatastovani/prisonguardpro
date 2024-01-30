<?php

namespace App\Services;

use App\Events\PrivadoEntradaPreso;
use Illuminate\Support\Facades\Broadcast;

class ServiceEventoPrivadoEntradaPreso
{
    public function inscreverUsuario($idUsuario)
    {
        // Lógica para inscrever o usuário

        // Notifica todos os inscritos com a lista atualizada
        $inscritos = $this->getListaInscritos($idUsuario);
        $this->notificarTodos($idUsuario, $inscritos);
    }

    public function desinscreverUsuario($idUsuario)
    {
        // Lógica para remover a inscrição do usuário

        // Notifica todos os inscritos com a lista atualizada
        $inscritos = $this->getListaInscritos($idUsuario);
        $this->notificarTodos($idUsuario, $inscritos);
    }

    private function getListaInscritos($idUsuario)
    {
        // Lógica para obter a lista de inscritos (substitua pelo que for necessário)
        return []; // Por exemplo, um array de IDs de usuários inscritos
    }

    private function notificarTodos($idUsuario, $inscritos)
    {
        // Notifica todos os inscritos com a lista atualizada
        foreach ($inscritos as $inscrito) {
            Broadcast::event(new PrivadoEntradaPreso($idUsuario, $inscritos))->toOthers();
        }
    }
}

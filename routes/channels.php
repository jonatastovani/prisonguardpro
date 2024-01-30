<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('PrivadoEntradaPreso.Entrada.{id}', function ($user, $idEntrada) {
    // Aqui você deve implementar sua lógica de autorização
    // Por exemplo, você pode verificar se o usuário tem permissão para acessar esta entrada de presos

    // Supondo que o usuário sempre pode acessar o canal por enquanto
    return true;
});

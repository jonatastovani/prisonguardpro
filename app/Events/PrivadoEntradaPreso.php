<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PrivadoEntradaPreso implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $idUsuario;
    public $inscritos;

    /**
     * Create a new event instance.
     */
    public function __construct($idUsuario, $inscritos)
    {
        $this->idUsuario = $idUsuario;
        $this->inscritos = $inscritos;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("PrivadoEntradaPreso.Entrada.{$this->idUsuario}"),
        ];
    }

}


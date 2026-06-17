<?php

namespace App\Notifications;

use App\Models\Opinion;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class NuevaOpinionNotification extends Notification
{
    use Queueable;

    public function __construct(private Opinion $opinion) {}

    /**
     * Canales de entrega: solo base de datos (notificaciones internas del sistema).
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Datos guardados en la tabla notifications.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'tipo'        => 'nueva_opinion',
            'titulo'      => 'Nueva opinión ciudadana',
            'opinion_id'  => $this->opinion->id,
            'nombre'      => $this->opinion->name,
            'route'       => $this->opinion->route,
            'extracto'    => Str::limit($this->opinion->message, 90),
            'url'         => route('opinions.show', $this->opinion->id),
        ];
    }
}

<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailVerificationCodeNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly string $code,
        private readonly int $minutesToExpire
    ) {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Codigo de verificacion - Kusay.pe')
            ->greeting('Hola '.$notifiable->name.',')
            ->line('Usa este codigo para verificar tu correo en Kusay.pe:')
            ->line('Codigo: '.$this->code)
            ->line('Este codigo vence en '.$this->minutesToExpire.' minutos.')
            ->line('Por favor no compartes tu codigo de verificacion con terceros.')
            ->line('Si no solicitaste esta accion, ignora este mensaje.');
    }
}

<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Skud\Entities\Terminal;
use NotificationChannels\Telegram\TelegramMessage;

class TerminalStatusProblemNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(protected $statusLog)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['telegram'];
    }


    public function toTelegram($notifiable)
    {
        return TelegramMessage::create()
            ->line('Terminal Name: ' . $this->statusLog->name)
            ->line("Terminal Ip: " . $this->statusLog->ip)
            ->line("Terminal SN: " . $this->statusLog->serial_number)
            ->line("Message: " . $this->statusLog->message);

    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}

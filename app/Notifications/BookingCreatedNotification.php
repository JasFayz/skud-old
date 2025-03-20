<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Modules\Booking\Entities\Booking;
use NotificationChannels\Telegram\TelegramMessage;

class BookingCreatedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(private Booking $booking)
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
            // Optional recipient user id.
            ->to($notifiable->current_telegram_id)
            // Markdown supported.
            ->content("Здравствуйте! ")
            ->line("У Вас забронирована встреча")
            ->line('Название: ' . $this->booking->name)
            ->line("Дата: " . Carbon::parse($this->booking->day)->format('d-m-Y') . ' ' . $this->booking->start)
            ->line("Распололжение: " . $this->booking->room->name)
            ->line("Организатор: " . $this->booking->organizer);

        // (Optional) Blade template for the content.
        // ->view('notification', ['url' => $url])

        // (Optional) Inline Buttons
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

<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\UploadedFile;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Modules\Booking\Entities\Booking;
use Modules\Booking\Entities\BookingFile;
use NotificationChannels\Telegram\TelegramFile;

class AttachingFileToBookingNotification extends Notification
{
    use Queueable;

    public function __construct(private Booking $booking, private BookingFile $bookingFile)
    {
        //
    }

    public function via($notifiable): array
    {
        return ['telegram'];
    }


    public function toTelegram($notifiable): TelegramFile
    {


        $filePath = new UploadedFile(Storage::path($this->bookingFile->path), $this->bookingFile->file_name);
        $telegramFile = TelegramFile::create()
            ->to($notifiable->current_telegram_id)
            ->content('Файлы для встречи: ' . $this->booking->name . PHP_EOL .
                "Дата: " . Carbon::parse($this->booking->day)->format('d-m-Y') . ' ' . $this->booking->start)
            ->document($filePath, $this->booking->name . '-' . $this->bookingFile->file_name);

        return $telegramFile;
    }

}

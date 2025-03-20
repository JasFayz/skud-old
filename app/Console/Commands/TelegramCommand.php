<?php

namespace App\Console\Commands;

use App\Notifications\BookingCreatedNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Booking\Entities\Booking;
use Modules\Skud\Jobs\SendUserNotificationAboutBooking;
use Modules\User\Entities\User;
use NotificationChannels\Telegram\TelegramMessage;
use NotificationChannels\Telegram\TelegramUpdates;

class TelegramCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start telegram command';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $booking = Booking::latest()->first();

        dispatch(new SendUserNotificationAboutBooking($booking))->onQueue('notification');

    }

}

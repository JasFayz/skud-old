<?php

namespace Modules\Skud\Jobs;

use App\Notifications\AttachingFileToBookingNotification;
use App\Notifications\BookingCreatedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Booking\Actions\Booking\CreateBookingAction;
use Modules\Booking\Entities\Booking;

class SendUserNotificationAboutBooking implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private Booking $booking)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $users = $this->booking->load('invitedUsers', 'bookingFiles')->invitedUsers;

        foreach ($users as $user) {
            if ($user->telegrams) {
                foreach ($user->telegrams as $telegram) {
                    $user->current_telegram_id = $telegram->telegram_id;

                    $user->notify(new BookingCreatedNotification($this->booking));

                    if ($this->booking->has('bookingFiles')) {

                        foreach ($this->booking->load('bookingFiles')->bookingFiles as $file) {

                            $user->notify(new AttachingFileToBookingNotification($this->booking, $file));

                        }
                    }
                }
            }
        }
    }
}

<?php

namespace Modules\Visitor\Actions\Guest;

use Modules\Visitor\DTOs\GuestDTO;
use Modules\Visitor\Entities\Guest;

class UpdateGuestAction
{
    public function handle(Guest $guest, GuestDTO $guestDTO)
    {
        $guest->update($guestDTO->toArray());

        return $guest;

    }
}

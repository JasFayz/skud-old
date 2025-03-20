<?php

namespace Modules\Visitor\Actions\Guest;

use Modules\Visitor\Entities\Guest;

class GuestZoneAction
{
    public function handle(Guest $guest, array $zones)
    {
        return $guest->sync($zones);
    }
}

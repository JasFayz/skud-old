<?php

namespace Modules\Visitor\Actions\Guest;

use Modules\Visitor\Entities\Guest;

class DeleteGuestAction
{
    public function handle(Guest $guest)
    {
        return $guest->delete();
    }
}

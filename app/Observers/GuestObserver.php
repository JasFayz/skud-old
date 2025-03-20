<?php

namespace App\Observers;

use Modules\Skud\Entities\TerminalUserIdentifier;
use Modules\Visitor\Entities\Guest;

class GuestObserver
{
    public function created(Guest $guest)
    {
        $identifier = new TerminalUserIdentifier();
        $guest->identifier()->save($identifier);
    }

}

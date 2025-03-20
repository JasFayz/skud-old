<?php

namespace App\Observers;

use Modules\Skud\Entities\TerminalUserIdentifier;
use Modules\DoorManage\Entities\DoorKey;

class DoorKeyObserver
{
    public function created(DoorKey $doorKey)
    {
        $identifier = new TerminalUserIdentifier();
        $identifier->identifier_number = TerminalUserIdentifier::generateIdentifier();
        $doorKey->identifier()->save($identifier);
    }

    public function updated(DoorKey $doorKey)
    {
    }

    public function deleted(DoorKey $doorKey)
    {
    }

    public function restored(DoorKey $doorKey)
    {
    }

    public function forceDeleted(DoorKey $doorKey)
    {
    }
}

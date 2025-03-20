<?php

namespace Modules\Visitor\Actions\Guest;

use Illuminate\Database\Eloquent\Model;
use Modules\Visitor\DTOs\GuestDTO;
use Modules\Visitor\Entities\Guest;

class CreateGuestAction
{
    public function handle(GuestDTO $guestDTO): Model|Guest
    {
        return Guest::create($guestDTO->toArray());
    }
}

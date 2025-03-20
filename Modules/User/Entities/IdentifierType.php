<?php

namespace Modules\User\Entities;

use Modules\DoorManage\Entities\DoorKey;
use Modules\Visitor\Entities\Guest;

class IdentifierType
{
    const USER = "User";
    const DOOR_KEY = "DoorKey";
    const GUEST = "Guest";

    const TYPES = [
        User::class => self::USER,
        DoorKey::class => self::DOOR_KEY,
        Guest::class => self::GUEST
    ];

    public static function getType(string $model_type)
    {
        return self::TYPES[$model_type];
    }


}

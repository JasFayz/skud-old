<?php

namespace Modules\HR\Entities;

class DayOffType
{

    const VACATION = 1;
    const SICK = 2;
    const TRIP = 3;
    const REMOTE = 4;
    const UNPAID_VACATION = 5;

    const symbols = [
        self::VACATION => 'О',
        self::SICK => 'Б',
        self::TRIP => 'К',
        self::REMOTE => 'У',
        self::UNPAID_VACATION => 'О_б',
    ];

    public static function getTypes()
    {
        return [
            ['id' => self::VACATION, 'name' => self::getName(self::VACATION)],
            ['id' => self::SICK, 'name' => self::getName(self::SICK)],
            ['id' => self::TRIP, 'name' => self::getName(self::TRIP)],
            ['id' => self::REMOTE, 'name' => self::getName(self::REMOTE)],
            ['id' => self::UNPAID_VACATION, 'name' => self::getName(self::UNPAID_VACATION)],
        ];
    }

    private static function getName($type)
    {
        $names = [
            self::VACATION => 'Vacation',
            self::SICK => 'Sick',
            self::TRIP => 'Trip',
            self::REMOTE => 'Remote',
            self::UNPAID_VACATION => 'Unpaid Vacation',
        ];

        return $names[$type];
    }

    public static function getSymbol($type)
    {
        return self::symbols[$type];
    }
}

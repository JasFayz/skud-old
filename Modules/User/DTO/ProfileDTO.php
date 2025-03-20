<?php

namespace Modules\User\DTO;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\User\Entities\User;
use Spatie\LaravelData\Data;

class ProfileDTO extends Data
{
    public function __construct(
        public string  $first_name,
        public string  $last_name,
        public ?string $patronymic,
        public ?string $photo,
        public ?int    $department_id,

        public ?int    $position_id,
        public ?string $mobile_phone,
        public ?string $work_phone,
        public ?string $car_number,
        public ?Carbon $birthday,
        public ?string $watchlist_photo
    )
    {
    }

    public static function fromCreateRequest(Request $request): self
    {
        return new self(
            first_name: str($request->input('first_name'))->trim()->upper()->toString(),
            last_name: str($request->input('last_name'))->trim()->upper()->toString(),
            patronymic: str($request->input('patronymic'))->trim()->upper()->toString(),
            photo: $request->hasFile('photo') ? $request->file('photo')
                ->store(User::USER_PHOTO_PATH) : null,
            department_id: $request->input('department_id') ?? null,
            position_id: $request->input('position_id') ?? null,
            mobile_phone: str($request->input('mobile_phone'))->trim()->toString() ?? null,
            work_phone: str($request->input('work_phone'))->trim()->toString() ?? null,
            car_number: str($request->input('car_number'))->trim()->toString() ?? null,
            birthday: Carbon::parse($request->get('birthday')) ?? null,
            watchlist_photo: $request->hasFile('watchlist_photo') ? $request->file('watchlist_photo')
                ->store(User::USER_PHOTO_PATH) : null,
        );
    }

    public static function fromUpdateRequest(Request $request): self
    {
        return new self(
            first_name: str($request->input('first_name'))->trim()->upper()->toString(),
            last_name: str($request->input('last_name'))->trim()->upper()->toString(),
            patronymic: $request->get('patronymic') ? str($request->input('patronymic'))->trim()->upper()->toString() : '',
            photo: $request->hasFile('photo') ? $request->file('photo')
                ->store(User::USER_PHOTO_PATH) : $request->user->profile->photo,
            department_id: $request->input('department_id') ?? $request->user->profile->department_id,
            position_id: $request->input('position_id') ?? $request->user->profile->position_id,
            mobile_phone: str($request->input('mobile_phone') ?? $request->user->profile->mobile_phone)->trim()->toString(),
            work_phone: str($request->input('work_phone') ?? $request->user->profile->work_phone)->trim()->toString(),
            car_number: str($request->input('car_number') ?? $request->user->profile->car_number)->trim()->toString(),
            birthday: Carbon::parse($request->get('birthday')) ?? $request->user->profile->birthday,
            watchlist_photo: $request->hasFile('watchlist_photo') ? $request->file('watchlist_photo')
                ->store(User::USER_PHOTO_PATH) : $request->user->profile->watchlist_photo,
        );
    }

}

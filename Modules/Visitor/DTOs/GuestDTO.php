<?php

namespace Modules\Visitor\DTOs;

use Modules\Visitor\Http\Requests\CreateGuestRequest;
use Modules\Visitor\Http\Requests\UpdateGuestRequest;
use Spatie\LaravelData\Data;

class GuestDTO extends Data
{

    public function __construct(
        public string  $first_name,
        public string  $last_name,
        public ?string $patronymic,
        public ?string $full_name,
        public string  $company_name,
        public string  $phone_number,
        public ?string $photo,
        public ?int    $company_id,
        public ?string $passport_number,
        public bool    $is_vip
    )
    {
    }


    public static function fromCreateRequest(CreateGuestRequest $request)
    {

        return new self(
            first_name: trim($request->get('first_name')),
            last_name: trim($request->get('last_name')),
            patronymic: trim($request->get('patronymic')),
            full_name: $request->get('first_name') . ' ' . $request->get('last_name'),
            company_name: trim($request->get('company_name')),
            phone_number: trim($request->get('phone_number')),
            photo: $request->hasFile('photo') ? $request->file('photo')->store('/uploads/guests/photos') : null,
            company_id: $request->get('company_id') ?? $request->user()->company_id,
            passport_number: (bool)!filter_var($request->get('is_vip'), FILTER_VALIDATE_BOOLEAN) ? $request->get('passport_number') : null,
            is_vip: (bool)filter_var($request->get('is_vip'), FILTER_VALIDATE_BOOLEAN)
        );
    }

    public static function fromUpdateRequest(UpdateGuestRequest $request)
    {
        return new self(
            first_name: trim($request->get('first_name')),
            last_name: trim($request->get('last_name')),
            patronymic: trim($request->get('patronymic')),
            full_name: $request->get('first_name') . ' ' . $request->get('last_name'),
            company_name: trim($request->get('company_name')),
            phone_number: trim($request->get('phone_number')),
            photo: $request->hasFile('photo') ? $request->file('photo')->store('/uploads/guests/photos') : $request->guest->photo,
            company_id: $request->get('company_id') ?? $request->user()->company_id,
            passport_number: (bool)!filter_var($request->get('is_vip'), FILTER_VALIDATE_BOOLEAN) ? $request->get('passport_number') : null,
            is_vip: (bool)filter_var($request->get('is_vip'), FILTER_VALIDATE_BOOLEAN)
        );
    }

    public function makeFullName()
    {
        $this->full_name = $this->first_name . ' ' . $this->last_name;
    }
}

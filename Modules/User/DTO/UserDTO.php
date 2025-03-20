<?php

namespace Modules\User\DTO;

use Modules\HR\Http\Requests\CreateStaffRequest;
use Modules\User\Http\Requests\CreateUserRequest;
use Modules\User\Http\Requests\UpdateUserRequest;
use Modules\HR\Http\Requests\UpdateStaffRequest;
use Spatie\LaravelData\Data;
use function str;

class UserDTO extends Data
{
    public function __construct(
        public string     $name,
        public string     $password,
        public string     $email,
        public ?int       $role_id,
        public ?int       $company_id,
        public ProfileDTO $profile,
        public ?int       $created_by,
        public ?int       $edited_by,
        public ?string    $created_by_name,
        public ?string    $edited_by_name,
        public ?string    $pinfl
    )
    {
    }

    public static function fromCreateRequest(CreateUserRequest|CreateStaffRequest $request): self
    {
        return new self(
            name: strtoupper(trim($request->get('last_name')) . ' ' . trim($request->get('first_name'))),
            password: str($request->input('password'))->trim()->toString(),
            email: str($request->input('email'))->trim()->toString(),
            role_id: $request->get('role_id'),
            company_id: $request->input('company_id'),
            profile: ProfileDTO::fromCreateRequest($request),
            created_by: \Auth::id(),
            edited_by: \Auth::id(),
            created_by_name: \Auth::user()->getName(),
            edited_by_name: \Auth::user()->getName(),
            pinfl: trim($request->get('pinfl'))
        );
    }

    public static function fromUpdateRequest(UpdateUserRequest|UpdateStaffRequest $request): self
    {
        return new self(
            name: strtoupper(trim($request->get('last_name')) . ' ' . trim($request->get('first_name'))),
            password: str($request->input('password'))->trim()->toString(),
            email: str($request->input('email'))->trim()->toString(),
            role_id: $request->get('role_id'),
            company_id: $request->get('company_id'),
            profile: ProfileDTO::fromUpdateRequest($request),
            created_by: \Auth::id(),
            edited_by: \Auth::id(),
            created_by_name: \Auth::user()->getName(),
            edited_by_name: \Auth::user()->getName(),
            pinfl: $request->get('pinfl') ? trim($request->get('pinfl')) : $request->user->pinfl
        );
    }
}

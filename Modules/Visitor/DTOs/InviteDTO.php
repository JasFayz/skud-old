<?php

namespace Modules\Visitor\DTOs;

use Carbon\Carbon;
use Modules\Visitor\Http\Requests\CreateInviteRequest;
use Modules\Visitor\Http\Requests\UpdateInviteRequest;
use Spatie\LaravelData\Data;

class InviteDTO extends Data
{

    public function __construct(
        public Carbon  $start,
        public Carbon  $end,
        public int     $responsible_user_id,
        public int     $target_user_id,
        public ?string $comment,
//        public array   $zones,
        public ?array  $terminals,
        public int     $guest_id,
        public ?int    $company_id,
        public int     $created_by,
        public int     $updated_by
    )
    {
    }

    public static function fromCreateRequest(CreateInviteRequest $request): InviteDTO
    {
        return new self(
            start: Carbon::parse($request->get('start')),
            end: Carbon::parse($request->get('end')),
            responsible_user_id: $request->get('responsible_user'),
            target_user_id: $request->get('target_user'),
            comment: $request->get('comment'),
            terminals: $request->get('terminals'),
            guest_id: $request->get('guest_id'),
            company_id: $request->get('company_id') ?? $request->user()->company_id,
            created_by: auth()->id(),
            updated_by: auth()->id()
        );
    }

    public static function fromUpdateRequest(UpdateInviteRequest $request): InviteDTO
    {
        return new self(
            start: Carbon::parse($request->get('start')),
            end: Carbon::parse($request->get('end')),
            responsible_user_id: $request->get('responsible_user'),
            target_user_id: $request->get('target_user'),
            comment: $request->get('comment'),
            terminals: $request->get('terminals'),
            guest_id: $request->get('guest_id'),
            company_id: $request->get('company_id') ?? $request->user()->company_id,
            created_by: auth()->id(),
            updated_by: auth()->id()
        );
    }
}

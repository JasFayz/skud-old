<?php

namespace Modules\Visitor\Actions\Invite;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Skud\Entities\Terminal;
use Modules\Skud\Entities\Zone;
use Modules\Skud\Services\TerminalConnectionService;
use Modules\Visitor\DTOs\InviteDTO;
use Modules\Visitor\Entities\Invite;

class CreateInviteAction
{

    public function __construct(private GenerateQrCode              $generateQrCode,
                                protected SetInviteStatusAction     $setInviteStatusAction,
                                protected TerminalConnectionService $terminalConnectionService
    )
    {
    }

    public function handle(InviteDTO $inviteDTO): Model|Invite
    {
        $publicTerminals = Terminal::query()->with('zone')
            ->active()
            ->whereZoneType(Zone::TYPE_PUBLIC)
            ->pluck('id')->toArray();
        DB::beginTransaction();

        $invite = Invite::create([...$inviteDTO->toArray(), 'status' => Invite::STATUS_APPROVED, 'is_editable' => false]);

        $invite->terminals()->attach(array_merge($inviteDTO->terminals, $publicTerminals));

        ($this->generateQrCode)($invite);

        if ($invite->guest->hasPhoto()
            && $invite->isApproved()
        ) {
            $terminalIds = $invite->terminals;
            $guest = $invite->guest;

            $this->terminalConnectionService->create($guest, $terminalIds, $invite->id);
            $invite->update(['is_editable' => false]);
        }
        DB::commit();

        return $invite;
    }
}

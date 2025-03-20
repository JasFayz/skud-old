<?php

namespace Modules\Visitor\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Modules\Skud\Action\CreateUserOnTerminalAction;
use Modules\Skud\Action\DeleteUserOnTerminalAction;
use Modules\Skud\Entities\Terminal;
use Modules\Skud\Entities\Zone;
use Modules\Skud\Services\TerminalConnectionService;
use Modules\Visitor\Actions\Invite\CreateInviteAction;
use Modules\Visitor\Actions\Invite\DeleteInviteAction;
use Modules\Visitor\Actions\Invite\SetInviteStatusAction;
use Modules\Visitor\Actions\Invite\UpdateInviteAction;
use Modules\Visitor\DTOs\InviteDTO;
use Modules\Visitor\Entities\Guest;
use Modules\Visitor\Entities\GuestDeletedLog;
use Modules\Visitor\Entities\Invite;
use Modules\Visitor\Http\Requests\CreateInviteRequest;
use Modules\Visitor\Http\Requests\UpdateInviteRequest;

class InviteController extends Controller
{

    public function indexWeb()
    {
        return view('visitor::invite');
    }

    public function getInvites(Request $request)
    {
        $invites = Invite::query()
            ->with(['guest', 'responsibleUser', 'targetUser', 'creator', 'zones', 'guest.zones', 'attachedZones', 'terminals', 'attachedTerminals'])
            ->whereGuestName($request->query('guest_full_name'))
            ->wherePassportNumber($request->query('passport_number'))
            ->whereCreator($request->query('creator'))
            ->company()
//            ->owner()
            ->latest()
            ->paginate();


        return response()->json($invites);
    }

    public function store(CreateInviteRequest $request, CreateInviteAction $createInviteAction,
    )
    {
        $invite = $createInviteAction->handle(InviteDTO::fromCreateRequest($request));

        return response()->json([
            'data' => $invite,
            'success' => true,
            'message' => "Success",

        ]);
    }

    public function update(Invite $invite, UpdateInviteRequest $request, UpdateInviteAction $updateInviteAction)
    {
        $invite = $updateInviteAction->handle($invite, InviteDTO::fromUpdateRequest($request));

        return response()->json([
            'data' => $invite,
            'success' => true,
            'message' => __('notification.message.success')
        ]);
    }

    public function destroy(Invite $invite, DeleteInviteAction $deleteInviteAction)
    {
        $this->authorize('destroy', $invite);

        return response()->json([
            'success' => $deleteInviteAction->handle($invite),
            'message' => 'Success'
        ]);
    }

    public function attachImage(Invite $invite, Request $request, TerminalConnectionService $terminalConnectionService)
    {

        $terminals = collect();
        $validate = $request->validate([
            'image' => ['required', 'image']
        ]);
        $terminals = $invite->terminals;

        $terminalResponse = [];
        if ($validate) {
            $status = $invite->guest()->update([
                'photo' => $request->file('image')->store('/uploads/guests/photos')
            ]);

            if ($invite->isApproved()) {
                $invite->update([
                    'is_editable' => false
                ]);
                $terminalResponse = $terminalConnectionService->create($invite->guest, $terminals, $invite->id);
            }
        }

        return response()->json(['terminalResponse' => $terminalResponse, 'imageStatus' => $status]);
    }


    public function setStatus(Invite                    $invite, Request $request,
                              SetInviteStatusAction     $setInviteStatusAction,
                              TerminalConnectionService $terminalConnectionService)
    {
        $requestData = [
            'status' => $request->get('status'),
            'comment' => $request->get('comment')
        ];
        $responseTerminal = [];

        $setInviteStatusAction->handle($invite, $requestData);

        if ($invite->guest->hasPhoto()
            && $invite->isApproved()
        ) {
            $terminalIds = $invite->terminals;
            $guest = $invite->guest;

            $responseTerminal = $terminalConnectionService->create($guest, $terminalIds, $invite->id);
        }

        return response()->json(['responseTerminal' => $responseTerminal]);
    }

    public function getExpiredInvitesQuantity()
    {
        return Invite::query()->where('status', '=', Invite::STATUS_EXPIRED)->count();
    }


    public function retryDeleteGuest(GuestDeletedLog $guestDeletedLog, DeleteUserOnTerminalAction $deleteUserOnTerminalAction)
    {
        $guest = $guestDeletedLog->guest;
        $terminal = $guestDeletedLog->terminal;

        $terminalStatus = $deleteUserOnTerminalAction->execute($guest, $terminal);
        if ($terminalStatus->success) {
            $guestDeletedLog->update([
                'status' => $terminalStatus->success,
                'message' => $terminalStatus->msg,
            ]);
            $guestDeletedLog->guest->detachTerminal($terminal->id, $guest->invite->id);
        }


        return $response = ['status' => $terminalStatus, 'terminal_id' => $terminal->id];
    }
}

<?php

namespace Modules\Skud\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\DoorManage\Entities\DoorKey;
use Modules\Skud\Action\CreateUserOnTerminalAction;
use Modules\Skud\Action\DeleteUserOnTerminalAction;
use Modules\Skud\Entities\Terminal;
use Modules\Skud\Entities\Zone;
use Modules\Skud\Services\TerminalConnectionService;
use Modules\User\Entities\User;
use Modules\Visitor\Entities\Guest;
use function response;
use function view;

class AccessController extends Controller
{
    public function __construct(public TerminalConnectionService $terminalConnectionService)
    {
        $this->middleware('permission:access.indexWeb', ['only' => 'indexWeb']);
        $this->middleware('permission:access.control', ['only' => 'addZone|removeZone|setAccess']);
    }

    public function indexWeb()
    {
        return view('management::access');
    }

    public function getUsers()
    {
        return User::initQuery()
            ->with('profile', 'company', 'zones', 'profile.company')
            ->paginate();
    }

    public function getZones()
    {
        return Zone::initQuery()->with(['floor', 'floor.company', 'terminals'])->get();
    }

    public function addZoneUser(User $user, Request $request)
    {
        $zoneId = $request->post('zoneId');
        $status = $request->post('status');
        $response = [];
        $terminals = Zone::where('id', '=', (int)$zoneId)->first()->terminals;
        if ($status && $terminals) {
            $response = $this->terminalConnectionService->create($user, $terminals);

            foreach ($response as $key => $res) {
                if ($res && $res['status']->success) {
                    $user->terminals()->sync($res['terminal_id']);
                }
            }
            $user->zones()->sync($zoneId);
        }

        return $response;
    }

    public function removeZoneUser(User $user, Request $request, DeleteUserOnTerminalAction $deleteTerminalAction)
    {
        $zoneId = $request->post('zoneId');
        $status = $request->post('status');
        $response = [];
        $terminals = Zone::where('id', '=', (int)$zoneId)->first()->terminals;
        if (!$status && $terminals) {
            $response = $this->terminalConnectionService->delete($user, $terminals, $deleteTerminalAction);

            foreach ($response as $key => $res) {

                if ($res && $res['status'] === \stdClass::class) {
                    if ($res['status']->success) {
                        $user->terminals()->detach($res['terminal_id']);
                    }
                }
            }
            $user->zones()->detach($zoneId);
        }

        return $response;
    }

    public function addZoneDoorKey(DoorKey $doorKey, Request $request, CreateUserOnTerminalAction $createTerminalAction)
    {

        $zoneId = $request->post('zoneId');
        $status = $request->post('status');
        $response = [];
        $terminals = Zone::where('id', '=', (int)$zoneId)->first()->terminals;
        if ($status && $terminals) {
            $response = $this->terminalConnectionService->create($doorKey, $terminals);
            if ($response) {
                $doorKey->zones()->attach($zoneId);
                return ['success' => true, ...$response];
            }
        }

//        return ['success' => false, 'message' => 'Something went wrong'];
    }

    public function removeZoneDoorKey(DoorKey $doorKey, Request $request, DeleteUserOnTerminalAction $deleteTerminalAction)
    {
        $zoneId = $request->post('zoneId');
        $status = $request->post('status');
        $response = [];
        $terminals = Zone::where('id', '=', (int)$zoneId)->first()->terminals;
        if (!$status && $terminals) {
            $response = $this->terminalConnectionService->delete($doorKey, $terminals, $deleteTerminalAction);
            if ($response) {
                $doorKey->zones()->detach($zoneId);
                return ['success' => true, ...$response];
            }
        }

        return ['success' => false, 'message' => $response];
    }

    public function setBatchZoneUser(Request $request, TerminalConnectionService $terminalConnectionService)
    {
        $userIds = $request->get('userIds');
        $zonesRequest = $request->get('zones');
        $zoneIds = array_filter($zonesRequest, function ($status, $id) {
            return $status ? $id : null;
        }, ARRAY_FILTER_USE_BOTH);
        $users = User::whereIn('id', $userIds)
            ->hasPhoto(true)->get();
        $zones = Zone::whereIn('id', array_keys($zoneIds))->get();
        $response = [];
        foreach ($users as $user) {
            foreach ($zones as $zone) {
                if (!$user->zones->contains($zone->id)) {
                    $response[] = [
                        'user_id' => $user->id,
                        'zone_id' => $zone->id,
                        'response' => $terminalConnectionService->create($user, $zone->terminals)
                    ];

                }
            }
        }

        foreach ($response as $data) {
            foreach ($data['response'] as $id => $status) {
                if ($status->success) {
                    User::find($data['user_id'])->zones()->sync($data['zone_id']);
                    User::find($data['user_id'])->terminals()->sync($id);
                }
            }
        }
        return $response;

    }

    public function checkZoneTerminalStatus(User $user, Request $request)
    {
        $zoneIds = $request->get('zoneIds');
        $zoneType = $request->get('zoneType');

        $response = $this->terminalConnectionService->checkZoneTerminalStatus($user, $zoneIds, $zoneType);

        return response()->json($response);
    }

    public function processingUserZones(User $user, Request $request)
    {
        $zoneIds = $request->get('zoneIds');
        $zoneType = $request->get('zoneType');

        $response = $this->terminalConnectionService->processingZones($user, $zoneIds, $zoneType);

        return response()->json($response);
    }

    public function processingGuestZones(Guest $guest, Request $request)
    {
        $zoneIds = $request->get('zoneIds');
        $zoneType = $request->get('zoneType');
        $inviteId = $request->get('selectedInvite');

        $response = $this->terminalConnectionService->processingZones($guest, $zoneIds, $zoneType, $inviteId);

        return response()->json($response);
    }


    public function checkTerminalStatus(Request $request)
    {
        $terminals = Terminal::query()
            ->whereIn('id', $request->get('terminals'))
            ->active()
            ->get();

        return $this->terminalConnectionService->checkTerminalStatus($terminals);
    }

    public function setAccess(User $user, Request $request)
    {
        $terminals = $request->get('terminals');
        $type = $request->get('zone_type');
        $belongedTerminals = $user->load('terminals.zone')->terminals()
            ->active()
            ->get();

        $belongedFilteredTerminals = $belongedTerminals->filter(function ($terminal) use ($type) {
//            return $terminal->zone->zone_type === $type;
            return $terminal;
        })->pluck('id')->toArray();


        $different = array_diff($belongedFilteredTerminals, $terminals);

        if ($terminals == 0) {
            $different = $belongedFilteredTerminals;
        }

        $removedTerminals = Terminal::query()->whereIn('id', $different)->get();
        $response['removed'] = $this->terminalConnectionService->delete($user, $removedTerminals);

        $response ['added'] = [];
        if (count($terminals) > 0) {
            $addedTerminals = Terminal::query()->whereIn('id', $terminals)->get();

            $response['added'] = $this->terminalConnectionService->create($user, $addedTerminals);
        }

        return $response;
    }

}

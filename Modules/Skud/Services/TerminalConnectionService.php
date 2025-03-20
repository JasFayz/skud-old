<?php

namespace Modules\Skud\Services;

use Illuminate\Support\Collection;
use Modules\Skud\Action\CheckTerminalAction;
use Modules\Skud\Action\CreateUserOnTerminalAction;
use Modules\Skud\Action\DeleteUserOnTerminalAction;
use Modules\Skud\Action\GetTerminalInfoAction;
use Modules\Skud\Action\SyncTimeTerminalAction;
use Modules\Skud\Action\UpdateUserOnTerminalAction;
use Modules\Skud\Contracts\TerminalCreatable;
use Modules\Skud\Entities\Terminal;

class TerminalConnectionService
{

    public function __construct(private CheckTerminalAction        $checkTerminalAction,
                                private CreateUserOnTerminalAction $createTerminalAction,
                                private UpdateUserOnTerminalAction $updateTerminalAction,
                                private DeleteUserOnTerminalAction $deleteTerminalAction,
                                private SyncTimeTerminalAction     $syncTimeTerminalAction,
                                private GetTerminalInfoAction      $getTerminalInfoAction
    )
    {
    }

    public function create(TerminalCreatable $user, Collection $terminals, int $invite_id = null)
    {

        $responses = [];

        foreach ($terminals as $terminal) {
            if ($this->checkTerminalAction->execute($user, $terminal)) {
//                \Log::alert('This user already in terminal');
                continue;
            }

            $terminalStatus = $this->createTerminalAction->execute($user, $terminal);

            if ($terminalStatus->success) {
                $user->attachingTerminal($terminal->id, $invite_id);
            }

            $responses[] = [
                'status' => $terminalStatus,
                'terminal_id' => $terminal->id,
                'terminal_name' => $terminal->name,
                'terminal_ip' => $terminal->ip,
                'terminal_sn' => $terminal->serial_number,
                'message' => $terminalStatus->msg
            ];
        }

        return $responses;
    }

    public function update(TerminalCreatable $user, Collection $terminals, $old_name)
    {
        $responses = [];
        foreach ($terminals as $terminal) {
            $responses[] = [
                'status' => $this->updateTerminalAction->execute($user, $terminal, $old_name),
                'terminal_id' => $terminal->id,
                'terminal_name' => $terminal->name
            ];
        }
        return $responses;
    }

    public function delete(TerminalCreatable $user, Collection $terminals)
    {
        $responses = [];

        foreach ($terminals as $terminal) {
            $terminalStatus = $this->deleteTerminalAction->execute($user, $terminal);

            if ($terminalStatus->success) {
                $user->terminals()->detach($terminal->id);
            }
            $responses[] = ['status' => $terminalStatus, 'terminal_id' => $terminal->id, 'terminal_name' => $terminal->name];
        }

        return $responses;
    }


    public function checkZoneTerminalStatus(TerminalCreatable $user, array $zoneIds, string $zoneType)
    {

        $userZonesIds = $user->zones()->where('zone_type', '=', $zoneType)->pluck('id')->toArray();

        $responseTerminal = [];
        $terminals = [];

        if (!empty($zoneIds)) {

            if (count($zoneIds) > count($userZonesIds)) {
                $diffZone = array_diff($zoneIds, $userZonesIds);
                $terminals = Terminal::query()->filterByZoneIds($diffZone)->active()->get();
            }
            if (count($userZonesIds) > count($zoneIds)) {
                $diffZone = array_diff($userZonesIds, $zoneIds);
                $terminals = Terminal::query()->filterByZoneIds($diffZone)->active()->get();
            }
            if (count($zoneIds) === count($userZonesIds)) {
                $addZones = array_diff($zoneIds, $userZonesIds);
                if ($addZones) {
                    $terminals = Terminal::query()->filterByZoneIds($addZones)->active()->get();
                }
                $removeZones = array_diff($userZonesIds, $zoneIds);
                if ($removeZones) {
                    $terminals->add(...Terminal::query()->filterByZoneIds($removeZones)->active()->get());
                }
            }
        }
        if (empty($zoneIds) && !empty($userZonesIds)) {
            $terminals = Terminal::query()->filterByZoneIds($userZonesIds)->active()->get();
        }

        foreach ($terminals as $terminal) {
            $responseTerminal[] = [
                'status' => $this->getTerminalInfoAction->handle($terminal),
                'terminal_id' => $terminal->id,
                'name' => $terminal->name
            ];
        }

        return $responseTerminal;
    }


    public function processingZones(TerminalCreatable $user, array $zoneIds, string $zoneType, $inviteId = null)
    {

        $userZonesIds = $user->zones()->where('zone_type', '=', $zoneType)->pluck('id')->toArray();
        $responseTerminal = [];
        $processingZones = null;

        if (!empty($zoneIds)) {
            $diffZone = array_diff($zoneIds, $userZonesIds);
            $terminals = Terminal::query()->filterByZoneIds($diffZone)->active()->get();
            $processingZones = $diffZone;

            if (count($zoneIds) > count($userZonesIds)) {
                $responseTerminal['add'] = $this->create($user, $terminals);
                if ($inviteId) {
                    $user->zones()->syncWithPivotValues($diffZone, ['invite_id' => $inviteId]);
                } else {
                    $user->zones()->attach($diffZone);
                }
            }
            if (count($userZonesIds) > count($zoneIds)) {
                $diffZone = array_diff($userZonesIds, $zoneIds);
                $terminals = Terminal::query()->filterByZoneIds($diffZone)->active()->get();
                $responseTerminal['remove'] = $this->delete($user, $terminals);
                $user->zones()->detach($diffZone);
            }
            if (count($zoneIds) === count($userZonesIds)) {
                $addZones = array_diff($zoneIds, $userZonesIds);
                if ($addZones) {
                    $addTerminals = Terminal::query()->filterByZoneIds($addZones)->active()->get();
                    $responseTerminal['add'] = $this->create($user, $addTerminals);
                    if ($inviteId) {
                        $user->zones()->syncWithPivotValues($diffZone, ['invite_id' => $inviteId]);
                    } else {
                        $user->zones()->attach($addZones);
                    }
                }
                $removeZones = array_diff($userZonesIds, $zoneIds);
                if ($removeZones) {
                    $removeTerminals = Terminal::query()->filterByZoneIds($removeZones)->active()->get();
                    $responseTerminal['remove'] = $this->delete($user, $removeTerminals);
                    $user->zones()->detach($removeZones, ['invite_id' => $inviteId]);
                }
            }

        } elseif (empty($zoneIds) && !empty($userZonesIds)) {
            $terminals = Terminal::query()->filterByZoneIds($userZonesIds)->active()->get();
            $processingZones = $userZonesIds;
            $responseTerminal['remove'] = $this->delete($user, $terminals);
            $user->zones()->detach($userZonesIds, ['invite_id' => $inviteId]);
        }

        return [
            'data' => $responseTerminal,
            'processingZones' => $processingZones,
        ];
    }

    public function checkTerminalStatus(Collection $terminals)
    {
        $status = [];
        foreach ($terminals as $terminal) {
            $status[$terminal->id] = $this->getTerminalInfoAction->handle($terminal);
        }
        return $status;
    }

}

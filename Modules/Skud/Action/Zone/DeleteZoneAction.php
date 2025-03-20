<?php

namespace Modules\Skud\Action\Zone;

use App\Http\ActionMessage;
use Modules\Skud\Entities\Zone;
use Modules\Skud\Repositories\ZoneRepository;

class DeleteZoneAction
{
    public function __construct(private ZoneRepository $repository)
    {
    }

    public function __invoke(Zone $zone)
    {
        try {
            $this->repository->delete($zone->id);
            return new ActionMessage([], 'Удалено', true);;
        } catch (\Throwable $exception) {
            return new ActionMessage([], $exception->getMessage(), false);
        }
    }
}

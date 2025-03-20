<?php

namespace Modules\Skud\Action\Zone;

use App\Http\ActionMessage;
use Modules\Skud\DTOs\ZoneDTO;
use Modules\Skud\Entities\Zone;
use Modules\Skud\Repositories\ZoneRepository;

class UpdateZoneAction
{
    public function __construct(private ZoneRepository $repository)
    {
    }

    public function __invoke(Zone $zone, ZoneDTO $dto)
    {
        try {
            $model = $this->repository->update($zone->id, $dto->toArray());
            $this->repository->attachTerminals($model->id, $dto->terminals);
            return new ActionMessage($model, 'Обновлено', true);
        } catch (\Throwable $exception) {
            return new ActionMessage([], $exception->getMessage(), false);
        }
    }
}

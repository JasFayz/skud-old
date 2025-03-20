<?php

namespace Modules\Skud\Action\Zone;

use App\Http\ActionMessage;
use App\Http\ResponseHelper;
use Illuminate\Support\Facades\DB;
use Modules\Skud\DTOs\TerminalDTO;
use Modules\Skud\DTOs\ZoneDTO;
use Modules\Skud\Repositories\ZoneRepository;

class CreateZoneAction
{
    public function __construct(private ZoneRepository $repository)
    {
    }

    public function __invoke(ZoneDTO $dto)
    {
        DB::beginTransaction();
        try {
            $zone = $this->repository->create($dto->toArray());
            $this->repository->attachTerminals($zone->id, $dto->terminals);
            DB::commit();
            return new ActionMessage($zone, 'Создано', true);
        } catch (\Throwable $exception) {
            DB::rollBack();
            return new ActionMessage([], $exception->getMessage(), false);
        }
    }
}

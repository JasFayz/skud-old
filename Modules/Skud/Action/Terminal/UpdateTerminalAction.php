<?php

namespace Modules\Skud\Action\Terminal;

use App\Http\ActionMessage;
use App\Http\ResponseHelper;
use Modules\Skud\DTOs\TerminalDTO;
use Modules\Skud\Entities\Terminal;
use Modules\Skud\Repositories\TerminalRepository;

class UpdateTerminalAction
{
    public function __construct(private TerminalRepository $repository)
    {
    }

    public function __invoke(Terminal $terminal, TerminalDTO $dto)
    {
        try {
            $model = $this->repository->update($terminal->id, $dto->toArray());
            return new ActionMessage($model, 'Обновлено', true);
        } catch (\Throwable $exception) {
            return new ActionMessage([], $exception->getMessage(), false);
        }
    }
}

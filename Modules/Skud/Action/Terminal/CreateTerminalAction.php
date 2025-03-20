<?php

namespace Modules\Skud\Action\Terminal;

use App\Http\ActionMessage;
use App\Http\ResponseHelper;
use Modules\Skud\DTOs\TerminalDTO;
use Modules\Skud\Repositories\TerminalRepository;

class CreateTerminalAction
{
    public function __construct(private TerminalRepository $repository)
    {
    }

    public function __invoke(TerminalDTO $dto)
    {
        try {
            $terminal = $this->repository->create($dto->toArray());
            return new ActionMessage($terminal, 'Создано', true);
        } catch (\Throwable $exception) {
            return new ActionMessage([], $exception->getMessage(), false);
        }
    }
}

<?php

namespace Modules\Skud\Action\Terminal;

use App\Http\ActionMessage;
use App\Http\ResponseHelper;
use Modules\Skud\Entities\Terminal;
use Modules\Skud\Repositories\TerminalRepository;

class DeleteTerminalAction
{
    public function __construct(private TerminalRepository $repository)
    {
    }

    public function __invoke(Terminal $terminal)
    {
        try {
            $this->repository->delete($terminal->id);
            return new ActionMessage([], 'Удалено', true);
        } catch (\Throwable $exception) {
            return new ActionMessage([], $exception->getMessage(), false);
        }
    }
}

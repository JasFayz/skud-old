<?php

namespace Modules\Skud\Repositories;

use Modules\Skud\Entities\Terminal;

class TerminalRepository
{
    public function __construct(private Terminal $model)
    {
    }

    public function findById($id)
    {
        return $this->model->findOrFail($id);
    }


    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $model = $this->findById($id);
        $model->updateOrFail($data);
        return $model->fresh();
    }

    public function delete($id)
    {
        return $this->findById($id)->deleteOrFail();
    }


}

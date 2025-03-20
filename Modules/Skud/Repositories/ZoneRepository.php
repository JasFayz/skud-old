<?php

namespace Modules\Skud\Repositories;

use Modules\Skud\Entities\Terminal;
use Modules\Skud\Entities\Zone;

class ZoneRepository
{
    public function __construct(private Zone $model)
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

    public function attachTerminals($id, $terminals)
    {
        $model = $this->findById($id);

        $model->terminals()->whereNotIn('id', $terminals)->update(['zone_id' => null]);

        Terminal::query()->whereIn('id', $terminals)->update(['zone_id' => $id]);

        return $model->fresh();
    }

    public function detachTerminals($id, $terminals)
    {
        return $this->findById($id)->detach($terminals);
    }
}

<?php

namespace Modules\User\FilterData;

use Illuminate\Contracts\Support\Arrayable;

class UserFilterData implements Arrayable
{
    public function __construct(
        public ?string $fio = null,
        public ?bool   $has_photo = null,
        public ?int    $role = null,
        public ?int    $company_id = null,
        public ?int    $department_id = null
    )
    {
    }

    public function toArray()
    {
        return [
            'fio' => $this->fio,
            'has_photo' => $this->has_photo,
            'role' => $this->role,
            'company_id' => $this->company_id,
            'department' => $this->department_id
        ];
    }

}

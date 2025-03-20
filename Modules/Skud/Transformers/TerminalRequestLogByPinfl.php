<?php

namespace Modules\Skud\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class TerminalRequestLogByPinfl extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            "date" => $this->date,
            "terminal_date" => $this->terminal_date,
            "terminal_id" => $this->terminal_id,
            "identifier_number" => $this->identifier_number,
//            "photo" => $this->photo,
            "terminal_mode" => $this->terminal_mode,
            "is_calc_attend" => $this->is_calc_attend,
            "deleted_at" => $this->deleted_at,
//            "identification" => [
//                'id' => $this->identification->id,
//                "identifier_number" => $this->identification->identifier_number,
//                "model_id" => $this->identification->model_id,
//                "model_type" => $this->identification->model_type,
//
//            ],
            'user' => [
                'id' => $this->identification->identifiable->id,
                "name" => $this->identification->identifiable->name,
                "email" => $this->identification->identifiable->email,
                "deleted_at" => $this->identification->identifiable->deleted_at,
                "pinfl" => $this->identification->identifiable->pinfl,
                "last_sess_id" => $this->identification->identifiable->last_sess_id,
            ]

        ];
    }
}

<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Modules\HR\Entities\Position;
use Modules\HR\Entities\PositionGrade;

class PositionImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {

        $positions = [];
        Position::truncate();

        foreach ($collection as $key => $row) {
            if ($row['dolznost']) {
                $positions[$key]['name'] = trim($row['dolznost']);
            }
        }
        foreach ($positions as $position) {
            Position::updateOrCreate(
                ['name' => $position['name']],
                ['name' => $position['name'], 'company_id' => 1]);
        }
    }
}

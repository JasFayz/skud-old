<?php

namespace Modules\User\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Modules\User\Entities\User;


class PINFLImport implements ToCollection, WithHeadingRow
{
    use Importable;

    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            if ($row->has('email')) {
                User::where('email', '=', $row['email'])->update(
                    ['pinfl' => trim($row['pinfl'])]
                );
            }
        }
    }

}

<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Modules\User\Entities\User;

class UsersEmailImport implements ToCollection, WithHeadingRow, WithCalculatedFormulas
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $resul = 0;
        foreach ($collection as $key => $row) {
            $user = User::where('name', '=', trim($row['fiol']))->first();

            if ($user && $row['pocta']) {
                dump($user->name);
                $status = $user->update([
                    'email' => $row['pocta'],
                ]);
                if ($status) {
                    $resul++;
                }
            }
        }
        dump($resul);
        return $resul;
    }
}

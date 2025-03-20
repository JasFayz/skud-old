<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Modules\User\Entities\User;

class UserPhotoImport implements ToCollection, WithHeadingRow
{


    public function __construct(private ?int $company_id)
    {
    }
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        //
        foreach ($collection as $row) {
            $user = User::where('email', $row['email'])
                ->with('profile')
                ->where('company_id', $this->company_id)
                ->first();
            if ($user) {
                $user->profile()->update([
                    'photo' => $row['photo']
                ]);
            }
        }
    }

    public function headingRow()
    {
        return 1;
    }

}

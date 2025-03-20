<?php

namespace Modules\User\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Modules\HR\Entities\Department;
use Modules\HR\Entities\Division;
use Modules\HR\Entities\Position;
use Modules\HR\Entities\PositionGrade;
use Modules\User\Entities\User;

class UsersImport implements ToCollection, WithHeadingRow
{

    use Importable;

    public function __construct(private ?int $company_id)
    {
    }

    public function collection(Collection $staffs)
    {

        foreach ($staffs as $staff) {
            if ($staff->has('email') && $staff['email']) {
                $first_name = trim(strtoupper($staff['firstname']));
                $last_name = trim(strtoupper($staff['lastname']));
                $patronymic = trim(strtoupper($staff['patronymic']));
                $email = $staff['email'];
                $fullname = $last_name . ' ' . $first_name . ' ' . $patronymic;
                $user = User::updateOrCreate([
                    'email' => $email,
                    'company_id' => $this->company_id
                ], [
                    'name' => $fullname,
                    'email' => $email,
                    'company_id' => $this->company_id,
                    'password' => \Hash::make('Qwerty123$')
                ]);
                $user->profile()->update([
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'patronymic' => $patronymic,
                    'full_name' => $fullname
                ]);
                $user->syncRoles('user');
            } else {
                \File::put(storage_path('user-import.txt',), json_encode($staff));
            }
        }
    }

    public function clean(string $string)
    {
        return str_replace('ʻ', '', $name);
    }

    public function headingRow(): int
    {
        return 1;
    }

}

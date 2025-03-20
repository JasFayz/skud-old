<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Modules\User\Entities\User;
use  Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ExportUser implements FromView, WithColumnFormatting
{
    private int $company_id;

    public function __construct($company_id)
    {
        $this->company_id = $company_id;
    }


    public function view(): View
    {
        $maxAncestorCount = 0;

        $users = User::with(['profile', 'roles', 'profile.department', 'profile.department.ancestors', 'profile.position'])
            ->where('company_id', '=', $this->company_id)
            ->get()->each(function ($user) use (&$maxAncestorCount) {
                if ($user->profile->department?->ancestors->count() > $maxAncestorCount) {
                    $maxAncestorCount = $user->profile->department->ancestors->count();
                }
            });


        return view('user::export-staff', [
            'users' => $users,
            'maxAncestorCount' => $maxAncestorCount
        ]);
    }


    public function columnFormats(): array
    {
        return  [
            'D' => NumberFormat::FORMAT_NUMBER
        ];
    }
}

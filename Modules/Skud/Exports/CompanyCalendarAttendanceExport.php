<?php

namespace Modules\Skud\Exports;

use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use function view;

class CompanyCalendarAttendanceExport implements FromView, WithStyles, WithColumnWidths
{

    public function __construct(private array $data)
    {
    }

    public function view(): View
    {
        return view('skud::export-attendance-calendar', [
            'attendances' => $this->data
        ]);
    }


    public function columnWidths(): array
    {
        return [
            'B' => 50,
        ];
    }

    public function styles(Worksheet $sheet)
    {

        foreach ($sheet->getCellCollection()->getCoordinates() as $cell) {
            $sheet->getStyle($cell)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ]
                ],
            ])->getAlignment()->setWrapText(true);
        }


        $sheet->getStyle('A1:B1')
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('C1:BL1')
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

    }
}

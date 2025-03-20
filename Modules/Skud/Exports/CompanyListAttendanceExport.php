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

class CompanyListAttendanceExport implements FromView, WithStyles, WithColumnWidths
{
    public function __construct(private array $data)
    {

    }

    public function view(): View
    {

        return view('skud::export-attendance-list', [
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
        $sheet->getStyle('A1')
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);



        $sheet->getStyle('A1')
            ->applyFromArray([
                'borders' => [
                    'outline' => [
                        'borderStyle' =>Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ]
            ])->getAlignment();

        $sheet->getStyle('A2:B2')
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('C1:B1')
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

    }
}

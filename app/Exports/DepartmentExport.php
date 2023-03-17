<?php

namespace App\Exports;

use App\Models\Department;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Style;

class DepartmentExport implements ShouldAutoSize, WithMapping, WithHeadings, WithColumnWidths, WithStyles, WithDefaultStyles, WithEvents, FromQuery
{
    use Exportable;

    public $search_query;

    public function __construct($search_query)
    {
        $this->search_query = $search_query;
    }

    public function query()
    {
        return Department::query()->where('name', "like", "%" . $this->search_query . "%")->withTrashed();
    }
    public function columnWidths(): array
    {
        return [
            'A' => 10,
        ];
    }
    public function headings(): array
    {
        return [
            ["Departments Data"],
            [
                'ID',
                'NAME',
                'CREATED AT',
                'UPDATED AT',
                'DELETED'
            ]
        ];
    }
    public function map($department): array
    {
        return [
            $department->id,
            $department->name,
            $department->created_at,
            $department->updated_at,
            is_null($department->deleted_at) ? "NO" : "YES"
        ];
    }
    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
            2    => ['font' => ['bold' => true]],
        ];
    }
    public function defaultStyles(Style $defaultStyle)
    {
        return $defaultStyle->getAlignment('A')->setHorizontal('center');
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->mergeCells("A1:D1");
                $event->sheet
                    ->getStyle("A1:D1")
                    ->getAlignment()
                    ->setVertical('center')
                    ->setHorizontal('center')
                    ->setWrapText(true);
            }
        ];
    }
}
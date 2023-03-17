<?php

namespace App\Exports;

use App\Models\User;
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
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UserExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize, WithColumnWidths, WithStyles, WithDefaultStyles, WithEvents
{
    use Exportable;

    public $search_query;
    public function __construct($search_query)
    {
        $this->search_query = $search_query;
    }
    public function query()
    {
        $query = User::query()->with(['permissions', 'department']);
        $query = $query->where("role_id", "!=", 1);
        $search_query = $this->search_query;
        if (@$search_query) {
            $query = $query->where(function ($query) use ($search_query) {
                $query->where('name', 'like', '%' . $search_query . '%')
                    ->orWhere('email', 'like', '%' . $search_query . '%')
                    ->orWhereHas('department', function ($query) use ($search_query) {
                        $query->where('name', 'like', '%' . $search_query . '%');
                    });
            });
        }
        return $query;
    }
    public function map($user): array
    {
        // 
        $user_permissions = '';
        foreach ($user->formatted_permissions as $module => $permissions) {
            $user_permissions .= strtoupper($module) . " (" . strtoupper(implode(', ', $permissions)) . "), ";
        }
        return [
            $user->id,
            $user->name,
            $user->email,
            $user->department->name,
            trim($user_permissions, ", ")
        ];
    }
    public function headings(): array
    {
        return [
            [
                "Employees Data"
            ],
            [
                'ID',
                'NAME',
                'EMAIL',
                'DEPARTMENT',
                'PERMISSIONS',
            ]

        ];
    }
    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'E' => 68
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
                $event->sheet->mergeCells("A1:E1");
                $event->sheet
                    ->getStyle("A1:E1")
                    ->getAlignment()
                    ->setVertical('center')
                    ->setHorizontal('center')
                    ->setWrapText(true);
                $event->sheet
                    ->getPageSetup()
                    ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            }
        ];
    }
}
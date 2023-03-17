<?php

namespace App\Exports;

use App\Models\Announcement;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
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

class AnnouncementExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize, WithColumnWidths, WithStyles, WithDefaultStyles, WithEvents
{
    use Exportable;

    public $search_query;
    public function __construct($search_query)
    {
        $this->search_query = $search_query;
    }
    public function query()
    {
        $query = Announcement::query()->with(['user', 'department']);
        $search_query = $this->search_query;
        if (@$search_query) {
            $query = $query->where(function ($query) use ($search_query) {
                $query->where('title', 'like', '%' . $search_query . '%')
                    ->orWhereHas('department', function ($query) use ($search_query) {
                        $query->where('name', 'like', '%' . $search_query . '%');
                    })
                    ->orWhereHas('user', function ($query) use ($search_query) {
                        $query->where('name', 'like', '%' . $search_query . '%');
                    });
            });
        }
        return $query;
    }
    public function map($announcement): array
    {
        return [
            $announcement->id,
            $announcement->title,
            $announcement->content,
            $announcement->user->name,
            implode(", ", $announcement->department->pluck('name')->toArray()),
            Carbon::parse($announcement->created_at)->format("F j, y h:ia")
        ];
    }
    public function headings(): array
    {
        return [
            [
                "Announcements Data"
            ],
            [
                'ID',
                'TITLE',
                'CONTENT',
                'AUTHOR',
                'DEPARTMENTS',
                'DATE POSTED',
            ]

        ];
    }
    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 20,
            'C' => 30,
            'E' => 30,
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
                $event->sheet->mergeCells("A1:F1");
                $event->sheet
                    ->getStyle("A1:F1")
                    ->getAlignment()
                    ->setVertical('center')
                    ->setHorizontal('center')
                    ->setWrapText(true);
                $event->sheet
                    ->getPageSetup()
                    ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
                //     ->getStyle("E")
                //     ->getAlignment()
                //     ->setWrapText(true);
            }
        ];
    }
}
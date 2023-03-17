<?php

namespace App\Exports;

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
use Spatie\Activitylog\Models\Activity;

class ActivityLogExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize, WithColumnWidths, WithStyles, WithDefaultStyles, WithEvents
{
    use Exportable;

    public $search_query;
    public function __construct($search_query)
    {
        $this->search_query = $search_query;
    }
    public function query()
    {
        $query = Activity::query();
        $search_query = $this->search_query;
        if (@$search_query) {
            $query = $query->where(function ($query) use ($search_query) {
                $query = $query->where('event', 'like', '%' . $search_query . '%')
                    ->orWhereHas('causer', function ($query) use ($search_query) {
                        $query->where('name', 'like', '%' . $search_query . '%');
                    });
            });
        }
        return $query->with(['causer']);
    }
    public function map($activity): array
    {
        $log_props = json_decode($activity->properties);
        return [
            $activity->id,
            strtoupper($activity->event),
            $activity->causer->name,
            json_encode(@$log_props->attributes) ?: "",
            json_encode(@$log_props->old) ?: "",
            Carbon::parse($activity->created_at)->format("F j, y h:ia")
        ];
    }
    public function headings(): array
    {
        return [
            [
                "Activity Logs Data"
            ],
            [
                'ID',
                'EVENT',
                'USER',
                'NEW DATA',
                'OLD DATA',
                'DATE AND TIME',
            ]

        ];
    }
    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 15,
            'C' => 30,
            'D' => 30,
            'E' => 15,
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
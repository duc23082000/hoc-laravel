<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;


use App\Models\Course;

class CoursesExport implements FromCollection, WithHeadings, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    private $search;
    private $collum;
    private $order;

    public function __construct($search, $collum, $order)
    {
        $this->search = $search;
        $this->collum = $collum;
        $this->order = $order;
    }

    public function collection()
    {
        $search = $this->search;
        $collum = $this->collum;
        // dd($collum);
        $order = $this->order;
        // dd($order);

        $joinResult = Course::join('categories', 'courses.category_id', '=', 'categories.id')
        ->join('users as create_users', 'courses.created_by_id', '=', 'create_users.id')
        ->join('users as update_users', 'courses.modified_by_id', '=', 'update_users.id')
        ->select('courses.id', 'courses.course_name', 'courses.price', 'categories.name', 'create_users.email', 'update_users.email as email2', 'courses.created_at', 'courses.updated_at')
        ->where(function ($query) use ($search) {
            $query->where('courses.id', $search)
                ->orWhere('courses.course_name', 'LIKE', '%' . $search . '%')
                ->orWhere('categories.name', 'LIKE', "%$search%");
        })
        ->orderBy($collum ?? 'courses.updated_at', $order ?? 'desc')
        ->get();
        return $joinResult;
    }

    public function headings(): array
    {
        return ['Id', 'Name', 'Price', 'Category', 'Created by', 'Modified by', 'Created at', 'Modified at'];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:H1')->applyFromArray([
                    'font' => [
                        'color' => ['rgb' => '000000'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'A9A9A9'],
                    ],
                ]);
                $event->sheet->getColumnDimension('B')->setWidth(20);
                $event->sheet->getColumnDimension('D')->setWidth(100);
                $event->sheet->getColumnDimension('E')->setWidth(40);
                $event->sheet->getColumnDimension('F')->setWidth(40);

                // Thiết lập in trang
                $event->sheet->getPageSetup()->setFitToWidth(1);
            },
        ];
    }
}

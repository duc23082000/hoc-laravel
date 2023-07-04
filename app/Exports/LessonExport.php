<?php

namespace App\Exports;

use App\Models\Lesson;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;


class LessonExport implements FromCollection, WithHeadings
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
        
        return $joinResult = Lesson::join('courses', 'lessons.course_id', '=', 'courses.id')
        ->join('users', 'lessons.created_by_id', '=', 'users.id')
        ->join('users as users2', 'lessons.modified_by_id', '=', 'users2.id')
        ->select('lessons.id', 'lessons.lesson_name', 'courses.course_name', 'lessons.status', 'users.email', 'users2.email as email2', 'lessons.created_at', 'lessons.updated_at')
        ->where('lessons.id', $this->search)
        ->orWhere('lessons.lesson_name', 'LIKE', "%$this->search%")
        ->orWhere('courses.course_name', 'LIKE', "%$this->search%")
        ->orWhere('users.email', 'LIKE', "%$this->search%")
        ->orWhere('users2.email', 'LIKE', "%$this->search%")
        ->orderBy($this->collum ?? 'lessons.updated_at', $this->order ?? 'desc')
        ->get();
    }

    public function headings(): array
    {
        return ['Id', 'Name', 'Course', 'Status', 'Created_by', 'Modified by', 'Created at', 'Modified at'];
    }
}

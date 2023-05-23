<?php

namespace App\Http\Controllers\Admin;
// use Rap2hpoutre\LaravelLogViewer\LogViewerController;
// use Rap2hpoutre\LaravelExcel\Facades\Excel;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;

use App\Http\Requests\CourseRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;

use App\Models\Category;
use App\Models\UserModel;
use App\Models\Course;

class ExportController extends Controller
{
    public function exportExcel()
    {
        User::all()->downloadExcel(
            $filePath,
            $writerType = null,
            $headings = false
        );
    }
}

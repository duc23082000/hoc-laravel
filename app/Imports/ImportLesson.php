<?php

namespace App\Imports;

use App\Http\Requests\LessonExcelRequest;
use App\Models\Course;
use App\Models\ImportNotice;
use App\Models\Lesson;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportLesson implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    private $user_id;
    private $file_name;

    public function __construct($user_id, $file_name)
    {
        $this->user_id = $user_id;
        $this->file_name = $file_name;

    }
    public function collection(Collection $rows)
    {
        $errors = '';
        $data = [];

        $create = new ImportNotice();
        $create->name = $this->file_name;
        $create->user_id = $this->user_id;
        //  return Log::info($rows->toArray());

        if(array_keys($rows->toArray()[0]) != ['lesson_name', 'course_id', 'content', 'status']){
            $create->status = 1;
            $create->notification = 'File excel phải có 4 cột và sắp xếp theo đúng thứ tự lesson_name, course_id, content, status';
            return $create->save();
        }

        $coursesId = Course::pluck('id')->toArray();

        foreach($rows as $key=>$row){
            $validate = new LessonExcelRequest($coursesId, $key, $row);
            $validator = Validator::make($row->toArray(), $validate->rules(), $validate->messages());
            if($validator->fails()){
                $errors = $validator->messages()->all();
                $string = join("<br>", $errors);
                // Log::info($string);
                $errors .= $string.'<br>';
            }
            $data[] = [
                'lesson_name' => $row['lesson_name'],
                'content' => $row['content'],
                'course_id' => $row['course_id'],
                'status' => $row['status'],
                'created_by_id' => $this->user_id,
                'modified_by_id' => $this->user_id,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        $create->notification = $errors;

        // Nếu validate không lỗi thì thông báo là thành công và insert data 
        if(empty($errors)){
            Lesson::insert($data);
            $create->status = 0;
            return $create->save();
        }

        $create->status = 1;
        return $create->save();

    }
    
}

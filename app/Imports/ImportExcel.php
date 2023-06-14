<?php

namespace App\Imports;

use App\Models\Category;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ExcelRequest;
use App\Models\ImportNotice;
use Illuminate\Support\Facades\Log;

class ImportExcel implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    private $id;
    private $fileName;
    public function __construct($id, $fileName)
    {
        $this->id = $id;
        $this->fileName = $fileName;
    }
    public function collection(Collection $rows)
    {
        $a = '';
        $data = [];

        $create = new ImportNotice;
        $create->name = $this->fileName;
        $create->user_id = $this->id;
        Log::info($this->id);
        
        if(array_keys($rows->toArray()[0]) != ['course_name', 'price', 'category_id', 'description', 'status']){
            $create->status = 1;
            $create->notification = 'File excel phải có 5 cột và sắp xếp theo đúng thứ tự course_name, price, category_id, description, status';
            return $create->save();
        }

        $categoryId = Category::pluck('id')->toArray();
        
        foreach ($rows as $key => $row) {
            

            $validate =  new ExcelRequest($categoryId, $key, $row);
            $validator = Validator::make($row->toArray(), $validate->rules(), $validate->messages());

            if($validator->fails()){
                $errors = $validator->messages()->all();
                $string = join("<br>", $errors);
                // Log::info($string);
                $a .= $string.'<br>';
                Log::info($a);
            }
            $data[] = [
                'course_name' => $row['course_name'],
                'price' => $row['price'],
                'description' => $row['description'],
                'category_id' => $row['category_id'],
                'status' => $row['status'],
                'created_by_id' => $this->id,
                'modified_by_id' => $this->id,
                'created_at' =>now(),
                'updated_at' =>now()
            ];
        }

        $create->notification = $a;

        if (empty($a)) {          
            Course::insert($data);
            $create->status = 0;
            return $create->save();
        }

        $create->status = 1;
        return $create->save();

    
    }
}

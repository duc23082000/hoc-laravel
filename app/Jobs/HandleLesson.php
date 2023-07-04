<?php

namespace App\Jobs;

use App\Models\Lesson;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class HandleLesson implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $id;
    private $name;
    private $course_id;
    private $content;
    private $user_id;
    private $fileName;
    private $url;
    private $status;

    public function __construct($id, $name, $course_id, $content, $user_id, $fileName, $url, $status)
    {
        $this->id = $id;
        $this->name = $name;
        $this->course_id = $course_id;
        $this->content = $content;
        $this->user_id = $user_id;
        $this->fileName = $fileName;
        $this->url = $url;
        $this->status = $status;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    { 
        if($this->url == 'http://127.0.0.1:8000/admin/lesson/add'){
            // Tạo ra 1 khóa học mới 
            $lesson = new Lesson();
            $lesson->lesson_name = $this->name;
            $lesson->course_id = $this->course_id;
            $lesson->status = $this->status;
            $lesson->content = $this->content;
            $lesson->created_by_id = $this->user_id;
            $lesson->modified_by_id = $this->user_id;

            // kiểm tra xem người dùng có gửi video hay ko nếu có thì thêm ảnh ngược lại ảnh sẽ là null 
            if (!$this->fileName) {
                Log::info($this->name);
                return $lesson->save();
            }

            $lesson->video = $this->fileName;
            Log::info($lesson->video);
            return $lesson->save();
        }

        $lesson = Lesson::find($this->id);
        $lesson->lesson_name = $this->name;
        $lesson->course_id = $this->course_id;
        $lesson->status = $this->status;
        $lesson->content = $this->content;
        $lesson->created_by_id = $this->user_id;
        $lesson->modified_by_id = $this->user_id;

        // kiểm tra xem người dùng có gửi video hay ko nếu có thì thêm ảnh ngược lại ảnh sẽ là null 
        if (!$this->fileName) {
            Log::info($this->name);
            return $lesson->save();
        }

        $lesson->video = $this->fileName;
        // Log::info($lesson->video);
        $lesson->save();
        return Log::info($lesson->save());
    }
}

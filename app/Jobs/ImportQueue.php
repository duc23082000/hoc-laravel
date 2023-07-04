<?php

namespace App\Jobs;

use App\Imports\ImportExcel;
use App\Imports\ImportLesson;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ImportQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $userId;
    private $url;
    public function __construct($userId, $url)
    {
        $this->userId = $userId;
        $this->url = $url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info($this->url);
        $files = File::files(glob(storage_path('app/public/excels/')));
        Log::debug($files[0]->getRelativePathname());
        foreach ($files as $file) {
            if ($this->url === 'http://127.0.0.1:8000/admin/courses/import') {
                Log::debug('True');
                Excel::import(new ImportExcel($this->userId, $file->getRelativePathname()), $file);
            }
            if ($this->url === 'http://127.0.0.1:8000/admin/lesson/import') {
                Log::debug('False');
                Excel::import(new ImportLesson($this->userId, $file->getRelativePathname()), $file);
            }

            File::delete(storage_path('app/public/excels/' . $file->getRelativePathname()));
        }
    }
}

<?php

namespace App\Jobs;

use App\Imports\ImportExcel;
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
    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Log::info('haha');
        $files = File::files(glob(storage_path('app/public/excels/')));
        Log::debug($files[0]->getRelativePathname());
        foreach($files as $file){
            Log::debug('True');
            Excel::import(new ImportExcel($this->userId, $file->getRelativePathname()), $file);
            
            File::delete(storage_path('app/public/excels/' . $file->getRelativePathname()));
        }
       
    }
}

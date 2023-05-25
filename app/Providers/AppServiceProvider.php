<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Maatwebsite\Excel\ExcelServiceProvider;
use Maatwebsite\Excel\Facades\Excel;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(ExcelServiceProvider::class);

        // Other code...

        Excel::extend('excel', function ($excel, $app) {
            $excel->getProperties()->setTitle('Title')->setDescription('Description');
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\CreateAccountController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LostPasswordController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\Course2Controller;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\LessonController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\RouteGroup;
use App\Http\Controllers\Auth\SettingController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('user')->group(function () {

    Route::middleware('check.login')->group(function () {
        Route::get('/login', [LoginController::class, 'login'])->name('login');

        Route::post('login', [LoginController::class, 'checkLogin'])->name('checkLogin');

        Route::get('/signup', [CreateAccountController::class, 'signup'])->name('signup');

        Route::post('signup', [CreateAccountController::class, 'createPost'])->name('createAccount');

        Route::get('/lost-password', [LostPasswordController::class, 'addEmail'])->name('lostPass');

        Route::post('/lost-password', [LostPasswordController::class, 'checkEmail'])->name('checkEmail');

        Route::get('login/google', [LoginController::class, 'redirectToGoogle'])->name('login.Google');

        Route::get('login/google/callback', [LoginController::class, 'handleGoogleCallback']);
    });

    Route::get('reset-password/email={email}&token={token}', [LostPasswordController::class, 'formReset'])->name('password.reset');

    Route::post('reset-password', [LostPasswordController::class, 'resetPass'])->name('reset');
       
});

Route::get('verification-email/{email}-{token}', [LoginController::class, 'verificationEmail'])->name('verificationEmail'); 

Route::prefix('admin')->middleware(['auth', 'localization'])->group(function () {
    Route::get('otp', [SettingController::class, 'formOtp'])->name('inputOtp');

    Route::post('otp', [SettingController::class, 'handleOtp'])->name('handleOtp');

    Route::get('logout', [LoginController::class, 'logout'])->name('logout');

    Route::middleware('check.twoKey')->group(function () {        
        Route::get('', [HomeController::class, 'home'])->name('home');

        Route::get('change-language/{language}', [HomeController::class, 'changeLanguage'])->name('change-language');

        Route::get('change-password', [LoginController::class, 'changePasswordForm'])->name('change.password');

        Route::post('change-password', [LoginController::class, 'changePassword'])->name('change');

        Route::get('send-verification-email', [LoginController::class, 'sendVerificationEmail'])->name('send.verificationEmail');

        Route::get('setting', [SettingController::class, 'index'])->name('setting');

        Route::get('2-key', [SettingController::class, 'onTwoKey'])->name('onTwoKey');

        Route::prefix('categories')->group(function () {
            Route::get('list', [CategoryController::class, 'list'])->name('categories.list');

            Route::get('delete/{id}', [CategoryController::class, 'delete'])->name('categories.delete');

            Route::get('add', [CategoryController::class, 'formAdd'])->name('categories.add');

            Route::post('add', [CategoryController::class, 'addData'])->name('addData');

            Route::get('edit/{id}', [CategoryController::class, 'formEdit'])->name('categories.edit');

            Route::put('edit/{id}', [CategoryController::class, 'updateData'])->name('updateData');
        });

        Route::prefix('courses')->group(function () {
            Route::get('list', [CourseController::class, 'list'])->name('courses.list');

            Route::get('show/{id}', [CourseController::class, 'show'])->name('courses.show');

            Route::get('delete{id}', [CourseController::class, 'delete'])->name('courses.delete');

            Route::get('add', [CourseController::class, 'formAdd'])->name('courses.add');

            Route::post('add', [CourseController::class, 'addData'])->name('courses.addData');

            Route::get('edit/{id}', [CourseController::class, 'formEdit'])->name('courses.edit');

            Route::put('edit/{id}', [CourseController::class, 'updateData'])->name('course.update');

            Route::post('export', [CourseController::class, 'export'])->name('export.excel');

            Route::get('import', [CourseController::class, 'importForm'])->name('import.form');

            Route::post('import', [CourseController::class, 'import'])->name('import.excel');

            Route::get('show-error/{id}', [CourseController::class, 'showError'])->name('show.error');

            Route::get('remote-error', [CourseController::class, 'deleteError'])->name('remote.error');

        });

        Route::prefix('lesson')->group(function(){
            Route::get('list', [LessonController::class, 'list'])->name('lesson.list');

            Route::get('show/{id}', [LessonController::class, 'show'])->name('lesson.show');

            Route::get('delete/{id}', [LessonController::class, 'delete'])->name('lesson.delete');

            Route::get('add', [LessonController::class, 'formAdd'])->name('lesson.add');

            Route::post('add', [LessonController::class, 'addData'])->name('lesson.addData');

            Route::get('edit/{id}', [LessonController::class, 'formEdit'])->name('lesson.edit');

            Route::put('edit/{id}', [LessonController::class, 'updateData'])->name('lesson.update');

            Route::post('export', [LessonController::class, 'export'])->name('export.lesson');

            Route::get('import', [LessonController::class, 'importForm'])->name('import.form.lesson');

            Route::post('import', [LessonController::class, 'import'])->name('import.lesson');
        });
    });
    

});








Route::get('test', [AuthController::class, 'test']);

Route::get('test2', function(){
    return view('mail.forgotPass');
});



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
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\RouteGroup;

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

Route::prefix('user')->middleware('check.login')->group(function () {
    Route::get('/login', [LoginController::class, 'login'])->name('login');

    Route::post('login', [LoginController::class, 'checkLogin'])->name('checkLogin');

    Route::get('/signup', [CreateAccountController::class, 'signup'])->name('signup');

    Route::post('signup', [CreateAccountController::class, 'createPost'])->name('createAccount');

    Route::get('/lost-password', [LostPasswordController::class, 'addEmail'])->name('lostPass');

    Route::post('/lost-password', [LostPasswordController::class, 'checkEmail'])->name('checkEmail');

    Route::get('reset-password/email={email}&token={token}', [LostPasswordController::class, 'formReset'])->name('password.reset');

    Route::post('reset-password', [LostPasswordController::class, 'resetPass'])->name('reset');

    Route::get('login/google', [LoginController::class, 'redirectToGoogle'])->name('login.Google');

    Route::get('login/google/callback', [LoginController::class, 'handleGoogleCallback']);
});

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('', [HomeController::class, 'home'])->name('home');

    Route::post('', [LoginController::class, 'logout'])->name('logout');

    Route::get('change-password', [LoginController::class, 'changePasswordForm'])->name('change.password');

    Route::post('change-password', [LoginController::class, 'changePassword'])->name('change');

    Route::prefix('categories')->group(function () {
        Route::get('list', [CategoryController::class, 'list'])->name('categories.list');

        Route::get('delete{id}', [CategoryController::class, 'delete'])->name('categories.delete');

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
    });

});








Route::get('test', [AuthController::class, 'test']);

Route::get('test2', function(){
    return view('mail.forgotPass');
});



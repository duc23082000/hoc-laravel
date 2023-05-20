<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LostPasswordController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CourseController;
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

    Route::get('/signup', [AuthController::class, 'signup'])->name('signup');

    Route::post('signup', [AuthController::class, 'createPost'])->name('createAccount');

    Route::get('/lost-password', [LostPasswordController::class, 'addEmail'])->name('lostPass');

    Route::post('/lost-password', [LostPasswordController::class, 'checkEmail'])->name('checkEmail');

    Route::get('reset-password/email={email}&token={token}', [LostPasswordController::class, 'formReset'])->name('password.reset');

    Route::post('reset-password', [LostPasswordController::class, 'resetPass'])->name('reset');
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

        Route::get('edit-id={id}', [CategoryController::class, 'formEdit'])->name('categories.edit');

        Route::put('edit', [CategoryController::class, 'updateData'])->name('updateData');
    });

    Route::prefix('courses')->group(function () {
        Route::get('list', [CourseController::class, 'list'])->name('courses.list');

        Route::get('show/id={id}', [CourseController::class, 'show'])->name('courses.show');

        Route::get('delete{id}', [CourseController::class, 'delete'])->name('courses.delete');

        Route::get('add', [CourseController::class, 'formAdd'])->name('courses.add');

        Route::post('add', [CourseController::class, 'addData'])->name('courses.addData');

        Route::get('edit-id={id}', [CourseController::class, 'formEdit'])->name('courses.edit');

        Route::put('edit', [CourseController::class, 'updateData'])->name('course.update');
    });

});








Route::get('test', [AuthController::class, 'test']);

Route::get('test2', function(){
    $mytime = Carbon\Carbon::now()->format('Y-m-d H:i:s');
    dd($mytime);
    // return $id;
});



<?php

use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsersController;

use App\Http\Controllers\StudentController;
use App\Http\Controllers\YearLevelController;
use App\Http\Controllers\SectionController;
use Illuminate\Support\Facades\Auth;

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

Route::redirect('/', 'home');


Auth::routes();

Route::group(['middleware' => 'auth'], function(){
    Route::post('student/import',[StudentController::class, 'import'])->name('student.import');
    Route::resource('student',StudentController::class);

    Route::post('User/import',[UsersController::class, 'import'])->name('User.import');
    Route::resource('User',UsersController::class);

    Route::get('/home',[HomeController::class,'index'])->name('home');
    Route::resource('department',DepartmentController::class);
    Route::resource('year-level',YearLevelController::class);
    Route::resource('section',SectionController::class);
});



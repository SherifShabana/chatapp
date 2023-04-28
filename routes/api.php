<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\MainController;
use App\Http\Controllers\Api\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//*Admin functions
Route::middleware('auth:sanctum')->group(function () {
    //*Create chats
    Route::post('create-single-chat', [ChatController::class, 'singleStudent']); //*Create chat with a single student
    Route::post('create-group', [ChatController::class, 'groupChat']); //*Create group chat
    Route::post('create-chat', [ChatController::class, 'createChat']); //*Create a new chat

    Route::get('admin-chats', [AdminController::class, 'adminChats']); //*Get all chats with this specific admin
});


//*Student functions
Route::middleware('auth:sanctum')->group(function () {
    Route::get('student-chats', [StudentController::class, 'chatList']); //*Get all chats with this specific student
    Route::get('channel-messages', [StudentController::class, 'channelMessages']); //*Get messages within the chat

    //*Star (Favorite Functionality)
    Route::post('star-message', [ChatController::class, 'starMessage']); //*Star a message
    Route::get('starred-messages', [ChatController::class, 'starredMessages']); //*Get all starred messages

    //*Delete message
    Route::post('delete-message', [ChatController::class, 'deleteMsg']); //*Delete a message
});


//*Send Message
Route::post('send-message', [MainController::class, 'sendMessage']); //*Currently a demo


//*Logins
Route::post('student-login', [AuthController::class, 'studentLogin']); //*Student login
Route::post('admin-login', [AuthController::class, 'adminLogin']); //*Admin login


//*Filter
Route::post('student-search', [ChatController::class, 'getStudents']); //*Search for a student


//*Get a list of the following
Route::get('departments', [MainController::class, 'departments']); //*Get all departments
Route::get('year-levels', [MainController::class, 'yearLevels']); //*Get all year levels
Route::get('sections', [MainController::class, 'sections']);//*Get all sections

<?php

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

//*Chat creation routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('create-single-chat', [ChatController::class, 'singleStudent']); //*Create chat with a single student
    Route::post('create-group', [ChatController::class, 'groupChat']); //*Create group chat
    Route::post('create-chat', [ChatController::class, 'createChat']); //*Create a new chat
});


//*Students (Chat Lists)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('chat-list', [StudentController::class, 'chatList']); //*Get all chats with this specific student
    Route::get('channel-messages', [StudentController::class, 'channelMessages']); //*Get messages within the chat
    // test merge by rana & abdullah
});

/* //*Admins (Chat Lists)
Route::middleware('auth:sanctum')->group(function () {

});
 */


//!Star (Favorite Functions)
Route::post('star-message', [ChatController::class, 'starMessage']); //*Star a message
Route::get('starred-messages', [ChatController::class, 'starredMessages']); //*Get all starred messages


//*Send Message
Route::post('send-message', [MainController::class, 'sendMessage']);


//*Logins
Route::post('student-login', [AuthController::class, 'studentLogin']);
Route::post('admin-login', [AuthController::class, 'adminLogin']);


//*Filter
Route::post('student-search', [ChatController::class, 'getStudents']);


Route::get('departments', [MainController::class, 'departments']);
Route::get('year-levels', [MainController::class, 'yearLevels']);
Route::get('sections', [MainController::class, 'sections']);

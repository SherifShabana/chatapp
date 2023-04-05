<?php

namespace App\Http\Controllers\Api;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Http\Resources\DepartmentResource;
use App\Http\Resources\MessageResource;
use App\Http\Resources\SectionResource;
use App\Http\Resources\YearLevelResource;
use App\Models\Department;
use App\Models\Section;
use App\Models\Student;
use App\Models\YearLevel;
use Illuminate\Http\Request;

class MainController extends Controller
{

    //*Get all departments
    public function departments(Request $request)
    {
        $departments = Department::all();
        return response()->json([
            'status' => 'success',
            'message' => 'Loaded',
            'data' => DepartmentResource::collection($departments)
        ]);
    }

    //*Get all years
    public function yearLevels(Request $request)
    {
        $request->validate([
            'department_id' => 'required',
        ]);
        $yearLevels = YearLevel::where('department_id', $request->department_id)->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Loaded',
            'data' => YearLevelResource::collection($yearLevels)
        ]);
    }

    //*Get all sections
    public function sections(Request $request)
    {
        $request->validate([
            'year_level_id' => 'required',
        ]);
        $sections = Section::where('year_level_id', $request->year_level_id)->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Loaded',
            'data' => SectionResource::collection($sections)
        ]);
    }

    //*Send a message
    public function sendMessage(Request $request)
    {
        $user = 1;
        $messages = [];
        // students [1,2,3]
        $students = Student::whereIn('id', $request->students)->get();
        foreach ($students as $student) {
            $channel = $student->channels()->whereHas('participants', function ($query) use ($user) {
                $query->where('user_id', $user);
            })->first();
            if (!$channel) {
                $channel = $student->channels()->create([
                    'name' => $student->name,
                ]);
                $channel->participants()->attach($user); //TODO Check up on attach()
            }

            // have channel
            $message = $channel->messages()->create([
                'user_id' => $user,
                'content' => $request->input('content'),
            ]);
            $messages[] = $message;

            MessageSent::dispatch($message->load('channel.participants', 'user'));
        }
        // return response with success message
        return response()->json([
            'status' => 'success',
            'message' => 'Message sent successfully',
            'data' => MessageResource::collection($messages)
        ]);
    }
}

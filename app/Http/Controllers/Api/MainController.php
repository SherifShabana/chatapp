<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Channel;
use App\Models\Section;
use App\Models\YearLevel;
use App\Models\Department;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ChannelResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\MessageResource;
use App\Http\Resources\SectionResource;
use App\Http\Resources\YearLevelResource;
use App\Http\Resources\DepartmentResource;

class MainController extends Controller
{

    //!Get all staff
    public function staff(Request $request)
    {
        $staff = User::all();

        return response()->json([
            'status' => 'success',
            'message' => 'loaded',
            'data' => UserResource::collection($staff)
        ]);
    }

    //!Get all departments
    public function departments(Request $request)
    {
        $departments = Department::all();
        return response()->json([
            'status' => 'success',
            'message' => 'Loaded',
            'data' => DepartmentResource::collection($departments)
        ]);
    }

    //!Get all years
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

    //!Get all sections
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

    //!Send a message
    public function sendMessage(Request $request)
    {
        $request->validate([
            'channel_id' => 'required',
            'message' => 'required',
        ]);

        $user = auth('sanctum')->user()->id;

        $channel = Channel::find($request->channel_id);
        if (!$channel) {
            return response()->json([
                'status' => 'error',
                'message' => 'Channel not found',
            ]);
        }

        $message = $channel->messages()->create([
            'user_id' => $user,
            'content' => $request->message,
        ]);

        MessageSent::dispatch($message->load('channel.participants'));

        return response()->json([
            'status' => 'success',
            'message' => 'Message sent successfully',
            'data' => [
                'message' => new MessageResource($message),
                'channel' => new ChannelResource($channel),
            ],
        ]);
    }
}

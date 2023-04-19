<?php

namespace App\Http\Controllers\Api;

use App\Models\Channel;
use App\Models\Student;
use App\Models\Group;
use App\Models\Department;
use App\Models\YearLevel;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\Controller;
use App\Http\Resources\ChannelResource;
use App\Http\Resources\ChatMessageResource;

class studentController extends Controller
{
    /* public function getUsers()
    {

        $student = Student::orderby('id', 'asc')->select('*')->get();

        // Fetch all records
        $response['data'] = $student;

        return response()->json($response);
    } */


    public function chatList(Request $request)
    {

        $student = $request->user();

        $channels = Channel::with(['lastmessage'])->withCount('unseenmessages')->whereHasMorph(
            'chattable',
            [Student::class, Group::class, Department::class, YearLevel::class, Section::class],
            function (Builder $query, string $type) use ($student) {
                switch ($type) {
                    case Student::class:
                        $query->where('students.id', $student->id);
                        break;
                    case Group::class:
                        $query->whereHas('students', function ($query) use ($student) {
                            $query->where('student_id', $student->id);
                        });
                        break;

                    case Section::class:
                        $query->whereHas('students', function ($query) use ($student) {
                            $query->where('students.id', $student->id);
                        });
                        break;

                    case YearLevel::class:
                        $query->whereHas('sections', function ($query) use ($student) {
                            $query->whereHas('students', function ($query) use ($student) {
                                $query->where('students.id', $student->id);
                            });
                        });
                        break;
                    case Department::class:
                        $query->whereHas('yearLevels', function ($query) use ($student) {
                            $query->whereHas('sections', function ($query) use ($student) {
                                $query->whereHas('students', function ($query) use ($student) {
                                    $query->where('students.id', $student->id);
                                });
                            });
                        });
                        break;
                }
            }
        )->get();

        return response()->json([
            'status' => 'success',
            'chats' => ChannelResource::collection($channels)
        ]);
    }


    //*Retrieves the messages for the given channel and updates their "seen" field to true.
    public function channelMessages(Request $request)
    {
        //*Find the channel with the given ID
        $channel = Channel::find($request->channel_id);

        //*If the channel does not exist, return an error response
        if (!$channel) {
            return response()->json([
                'status' => 'error',
                'message' => 'Channel not found'
            ]);
        }

        //*Update the "seen" field of the unseen messages in the channel to true
        $channel->unseenmessages()->update(['seen' => true]);

        //*Retrieve all the messages for the channel
        $messages = $channel->messages()->get();

        return response()->json([
            'status' => 'success',
            'messages' => ChatMessageResource::collection($messages)
        ]);
    }
}

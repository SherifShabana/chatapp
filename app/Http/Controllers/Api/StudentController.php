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
    //!Get all Student Chats
    public function chatList(Request $request)
    {
        $request->validate([
            'key' => 'nullable|string|min:2',
        ]);

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
        );

        //*Filter the chats
        if ($request->key) {
            $channels = $channels->where('name', 'like', '%'.$request->key.'%')
            
            ->orWhereHas('messages', function ($query) use ($request) {
                $query->whereHas('user', function ($query) use ($request) {
                    $query->where('name', 'like', '%'.$request->key.'%');
                });
            });
        }

        $channels = $channels->get();

        return response()->json([
            'status' => 'success',
            'chats' => ChannelResource::collection($channels)
        ]);
    }


    //!Retrieves the messages for the given channel and updates their "seen" field to true.
    public function channelMessages(Request $request)
    {
        $request->validate([
            'key' => 'nullable|string|min:2',
        ]);

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

        //*Prepare it for a query
        $messages = $channel->messages();

        //*Filter the messages
        if ($request->key) {
            $messages = $messages->where('content', 'like', '%' . $request->key . '%');
        }

        //*Retrieve all the messages for the channel
        $messages = $messages->get();

        return response()->json([
            'status' => 'success',
            'messages' => ChatMessageResource::collection($messages)
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Models\Group;
use App\Models\Section;
use App\Models\Student;
use App\Models\YearLevel;
use App\Models\Department;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\GroupResource;
use App\Http\Resources\MessageResource;
use App\Http\Resources\SectionResource;
use App\Http\Resources\YearLevelResource;
use App\Http\Resources\DepartmentResource;
use App\Models\Message;
use Illuminate\Mail\Events\MessageSent as EventsMessageSent;

class ChatController extends Controller
{
    //!Get all students
    public function getStudents(Request $request)
    {
        $students = Student::where(function ($query) use ($request) {
            if ($request->department_id) {
                $query->whereHas('section', function ($query) use ($request) {
                    $query->whereHas('yearLevel', function ($query) use ($request) {
                        $query->where('department_id', $request->department_id);
                    });
                });
            }

            if ($request->yl_id) {
                $query->whereHas('section', function ($query) use ($request) {
                    $query->where('year_level_id', $request->yl_id);
                });
            }

            if ($request->section_id) {
                $query->whereIn('section_id', (array)$request->section_id);
            }

            if ($request->keyword) {
                $query->where('name', 'like', "%$request->keyword%");
            }
        })->paginate();

        return response()->json($students);
    }




    //!Single Student Chat
    public function singleStudent(Request $request)
    {
        $request->validate([
            'student_id' => 'required',
            'message' => 'required'
        ]);

        // TODO check if it better to just use findorFail instead
        $student = Student::find($request->student_id); // TODO check what is the reason for using the first() method

        //* Check if a channel already exists for this student 
        $channel = $student->channels()->whereHas('participants', function ($query) {
            $query->where('user_id', auth('sanctum')->user()->id);
        })->first();

        //*If not, create a new channel
        if (!$channel) {
            $channel = $student->channels()->create([
                'name' => $student->name,
            ]);
            $channel->participants()->attach(auth('sanctum')->user()->id);
        }

        //*Create a new message in the channel
        $user = auth('sanctum')->user()->id;
        $message = $channel->messages()->create([
            'user_id' => $user,
            'content' => $request->message
        ]);

        //*Push the message to the channel
        MessageSent::dispatch($message->load('channel.participants'));

        return response()->json([
            'status' => 'success',
            'message' => 'Message sent successfully',
            'data' => new MessageResource($message)
        ]);
    }


    //!Group Chat
    public function groupChat(Request $request)
    {
        $request->validate([
            'group_name' => 'required',
            'students' => 'required|array|min:2',
            'message' => 'required',
        ]);

        //Create the group if it doesn't exist
        $group = Group::create([
            'name' => $request->group_name,
        ]);

        //* Check if a channel already exists for this group 
        $channel = $group->channels()->whereHas('participants', function ($query) {
            $query->where('user_id', auth('sanctum')->user()->id);
        })->first();

        //*If not, create a new channel
        if (!$channel) {
            $channel = $group->channels()->create([
                'name' => $group->name,
            ]);
            $channel->participants()->attach(auth('sanctum')->user()->id);
        }

        //*Add students to the group
        $group->students()->attach($request->students);

        //*Create a new message in the channel
        $user = auth('sanctum')->user()->id;
        $message = $channel->messages()->create([
            'user_id' => $user,
            'content' => $request->message
        ]);

        //*Push the message to the channel
        MessageSent::dispatch($message->load('channel.participants'));

        return response()->json([
            'status' => 'success',
            'message' => 'Group chat created successfully',
            'data' => [
                'group' => new GroupResource($group),
                'message' => new MessageResource($message),
            ],
        ]);
    }



    //!Create a chat for a new department or array of section or year levels
    public function createChat(Request $request)
    {
        $request->validate([
            'department_id' => 'required',
            'message' => 'required'
        ]);
        $messages = [];

        if ($request->section_id) {
            $sections = Section::find($request->section_id);

            //* If there is an array of sections
            foreach ($sections as $section) {
                //*Check if a channel already exists for this section 
                $channel = $section->channels()->whereHas('participants', function ($query) {
                    $query->where('user_id', auth('sanctum')->user()->id);
                })->first();

                if (!$channel) {
                    $channel = $section->channels()->create([
                        'name' => $section->name,
                    ]);
                    $channel->participants()->attach(auth('sanctum')->user()->id);
                }

                //*Create a new message in the channel
                $user = auth('sanctum')->user()->id;
                $message = $channel->messages()->create([
                    'user_id' => $user,
                    'content' => $request->message
                ]);

                $messages[] = $message;
                //*Push the message to the channel
                MessageSent::dispatch($message->load('channel.participants'));
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Message sent successfully',
                'data' => MessageResource::collection($messages),
                'Sections' => SectionResource::collection($sections)
            ]);
        } elseif ($request->level_id) {
            $yLevel = YearLevel::find($request->level_id);
            //*Check if a channel already exists for this yearlevel
            $channel = $yLevel->channels()->whereHas('participants', function ($query) {
                $query->where('user_id', auth('sanctum')->user()->id);
            })->first();

            if (!$channel) {
                $channel = $yLevel->channels()->create([
                    'name' => $yLevel->name,
                ]);
                $channel->participants()->attach(auth('sanctum')->user()->id);
            }

            //*Create a new message in the channel
            $user = auth('sanctum')->user()->id;
            $message = $channel->messages()->create([
                'user_id' => $user,
                'content' => $request->message
            ]);
            //*Push the message to the channel
            MessageSent::dispatch($message->load('channel.participants'));

            return response()->json([
                'status' => 'success',
                'message' => 'Message sent successfully',
                'data' => new MessageResource($message),
                'Year Level' => new YearLevelResource($yLevel)
            ]);
        } else {
            $dept = Department::find($request->department_id);

            //*Check if a channel already exists for this department 
            $channel = $dept->channels()->whereHas('participants', function ($query) {
                $query->where('user_id', auth('sanctum')->user()->id);
            })->first();

            if (!$channel) {
                $channel = $dept->channels()->create([
                    'name' => $dept->name
                ]);
                $channel->participants()->attach(auth('sanctum')->user()->id);
            }

            //*Create a new message in the channel
            $user = auth('sanctum')->user()->id;
            $message = $channel->messages()->create([
                'user_id' => $user,
                'content' => $request->message
            ]);
            //*Push the message to the channel
            MessageSent::dispatch($message->load('channel.participants'));

            return response()->json([
                'status' => 'success',
                'message' => 'Message sent successfully',
                'data' => new MessageResource($message),
                'Department' => new DepartmentResource($dept)
            ]);
        }
    }

    //Create a function to toggle messages for a specific student
    public function starMessage(Request $request)
    {

        $student = $request->user();
        $message = Message::find($request->message_id);

        if ($student && $message) {
            $student->messages()->toggle($message->id);
            $student->save(); //*Save the message in the database

            //*Check if the message is starred
            $isStarred = $student->messages()->where('id', $message->id)->exists();
            if ($isStarred) {
                $status = 'Message Starred';
            } else {
                $status = 'Message Unstarred';
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => $status,
            'data' => new MessageResource($message)
        ]);
    }

    //Create a function that shows starred messages
    public function starredMessages(Request $request)
    {
        $student = $request->user();
        $messages = $student->messages()->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Starred Messages',
            'data' => MessageResource::collection($messages)
        ]);
    }
}

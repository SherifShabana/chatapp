<?php

namespace App\Http\Controllers\Api;

use App\Models\Group;
use App\Models\Archive;
use App\Models\Message;
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
use App\Traits\FCMnotification;

class ChatController extends Controller
{
    use FCMnotification;
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
            'message' => 'nullable',
            'file' => 'nullable|file|max:40960'
        ]);

        $student = Student::find($request->student_id);

        

        /* $tokens = [$student->tokens];

        $data = [
            'sender' => $adminName,
            'receiver' => $student->name,
        ];

        $notification = $this->notifyByFirebase($title, $body, $token, $data); */


        //*Check if a channel already exists for this student
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

        //*If the request is empty return this response
        if (!$request->hasFile('file') && !$request->message) {
            return response()->json([
                'status' => 'error',
                'message' => 'Message is required',
            ]);
        }

        /* //*If the size is bigger than limit return this response
        if ($request->hasFile('file') && $request->file('file')->getSize() > 39 * 1024 * 1024) {
            return response()->json([
                'status' => 'error',
                'message' => 'File size is bigger than limit',
            ]);
        } */


        //*Store the uploaded file
        $fileUrl = null;
        $messageType = 1; //*Initialize the message type as 1
        /*  if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileUrl = $file->storeAs('uploads', $file->hashName(), 'public');

            //*Update the message type based on the uploaded file's MIME type
            $mimeType = $file->getMimeType();
            if (str_starts_with($mimeType, 'image/')) {
                $messageType = 2; //*Photo
            } elseif (str_starts_with($mimeType, 'application/')) {
                $messageType = 3; //*Document
            }
        }

        //*Check for a link
        if (Str::contains($request->message, 'https://') || Str::contains($request->message, 'http://')) {
            $messageType = 4; //*Link
        } */

        $user = auth('sanctum')->user()->id;
        $messageData = [
            'user_id' => $user,
            'content' => $request->message,
            'file' => $fileUrl //*Store the file URL in the message
        ];

        if (
            $messageType !== null
        ) {
            $messageData['type'] = $messageType; //*Update the message type only if it's not null
        }

        //*Create a new message in the channel
        $message = $channel->messages()->create($messageData);


        //!The data needed for the notification
        $adminName = auth('sanctum')->user()->name;

        $title = 'New Message from ' . $adminName;

        $body = $request->message;

        //*Push the message to the channel
        MessageSent::dispatch($message->load('channel.participants'),$title, $body);


        //*Return successful response
        return response()->json([
            'status' => 'success',
            'message' => 'Message sent',
            'data' => new MessageResource($message),
        ]);
    }


    //!Group Chat
    public function groupChat(Request $request)
    {
        $request->validate([
            'group_name' => 'required',
            'students' => 'required|array|min:2',
            'message' => 'nullable',
            'file' => 'nullable|file|max:40960'
        ]);


        //*Create the group if it doesn't exist
        $group = Group::where('name', $request->group_name)->first();
        if (!$group) {
            $group = Group::create([
                'name' => $request->group_name
            ]);
        }

        //*Check if a channel already exists for this group
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

        //*If the request is empty return this response
        if (!$request->hasFile('file') && !$request->message) {
            return response()->json([
                'status' => 'error',
                'message' => 'Message is required',
            ]);
        }

        //*If the size is bigger than limit return this response
        if ($request->hasFile('file') && $request->file('file')->getSize() > 39 * 1024 * 1024) {
            return response()->json([
                'status' => 'error',
                'message' => 'File size is bigger than limit',
            ]);
        }


        //* Store the uploaded file
        $fileUrl = null;
        $messageType = 1; //* Initialize the message type as 1
        /* if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileUrl = $file->storeAs('uploads', $file->hashName(), 'public');

            //* Update the message type based on the uploaded file's MIME type
            $mimeType = $file->getMimeType();
            if (str_starts_with($mimeType, 'image/')) {
                $messageType = 2; //* Photo
            } elseif (str_starts_with($mimeType, 'application/')) {
                $messageType = 3; //* Document
            }
        }

        //* Check for a link
        if (Str::contains($request->message, 'https://') || Str::contains($request->message, 'http://')) {
            $messageType = 4; //* Link
        } */

        $user = auth('sanctum')->user()->id;
        $messageData = [
            'user_id' => $user,
            'content' => $request->message,
            'file' => $fileUrl //*Store the file URL in the message
        ];

        if (
            $messageType !== null
        ) {
            $messageData['type'] = $messageType; //*Update the message type only if it's not null
        }

        //*Create a new message in the channel
        $message = $channel->messages()->create($messageData);


        //!The data needed for the notification
        $adminName = auth('sanctum')->user()->name;

        $title = 'New Message from ' . $adminName;

        $body = $request->message;

        /* $token = $group->students()->pluck('token')->toArray(); */

        //*Push the message to the channel
        MessageSent::dispatch($message->load('channel.participants'),$title, $body);

        //*Return successful response
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
            'message' => 'nullable',
            'file' => 'nullable|file|max:40960'
        ]);
        $messages = [];


        //!The data needed for the notification
        $adminName = auth('sanctum')->user()->name;

        $title = 'New Message from ' . $adminName;

        $body = $request->message;

        //*If the size is bigger than limit return this response
        /* if ($request->hasFile('file') && $request->file('file')->getSize() > 39 * 1024 * 1024) {
            return response()->json([
                'status' => 'error',
                'message' => 'File size is bigger than limit',
            ]);
        } */

        //* Store the uploaded file
        $fileUrl = null;
        $messageType = 1; //* Initialize the message type as 1
        /* if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileUrl = $file->storeAs('uploads', $file->hashName(), 'public');

            //* Update the message type based on the uploaded file's MIME type
            $mimeType = $file->getMimeType();
            if (str_starts_with($mimeType, 'image/')) {
                $messageType = 2; //* Photo
            } elseif (str_starts_with($mimeType, 'application/')) {
                $messageType = 3; //* Document
            }
        }

        //* Check for a link
        if (Str::contains($request->message, 'https://') || Str::contains($request->message, 'http://')) {
            $messageType = 4; //* Link
        } */


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

                //*If the request is empty return this response
                if (!$request->hasFile('file') && !$request->message) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Message is required',
                    ]);
                }

                $user = auth('sanctum')->user()->id;
                $messageData = [
                    'user_id' => $user,
                    'content' => $request->message,
                    'file' => $fileUrl //*Store the file URL in the message
                ];

                if (
                    $messageType !== null
                ) {
                    $messageData['type'] = $messageType; //*Update the message type only if it's not null
                }

                //*Create a new message in the channel
                $message = $channel->messages()->create($messageData);

                $messages[] = $message;


                //*Push the message to the channel
                MessageSent::dispatch($message->load('channel.participants'), $title, $body);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Message sent successfully',
                'data' => MessageResource::collection($messages),
                'sections' => SectionResource::collection($sections),
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

            //*If the request is empty return this response
            if (!$request->hasFile('file') && !$request->message) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Message is required',
                ]);
            }

            $user = auth('sanctum')->user()->id;
            $messageData = [
                'user_id' => $user,
                'content' => $request->message,
                'file' => $fileUrl //*Store the file URL in the message
            ];

            if (
                $messageType !== null
            ) {
                $messageData['type'] = $messageType; //*Update the message type only if it's not null
            }

            //*Create a new message in the channel
            $message = $channel->messages()->create($messageData);

            //*Push the message to the channel
            MessageSent::dispatch($message->load('channel.participants'), $title, $body);

            return response()->json([
                'status' => 'success',
                'message' => 'Message sent successfully',
                'data' => new MessageResource($message),
                'year_level' => new YearLevelResource($yLevel),

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

            //*If the request is empty return this response
            if (!$request->hasFile('file') && !$request->message) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Message is required',
                ]);
            }

            $user = auth('sanctum')->user()->id;
            $messageData = [
                'user_id' => $user,
                'content' => $request->message,
                'file' => $fileUrl //*Store the file URL in the message
            ];

            if (
                $messageType !== null
            ) {
                $messageData['type'] = $messageType; //*Update the message type only if it's not null
            }

            //*Create a new message in the channel
            $message = $channel->messages()->create($messageData);

            //*Push the message to the channel
            MessageSent::dispatch($message->load('channel.participants'), $title, $body);


            return response()->json([
                'status' => 'success',
                'message' => 'Message sent successfully',
                'data' => new MessageResource($message),
                'department' => new DepartmentResource($dept),
            ]);
        }
    }

    //!Create a function to toggle messages for a specific student
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

    //!Create a function that shows starred messages
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

    //!Create a function that deletes a message
    public function deleteMsg(Request $request)
    {
        //*Retrieve the authenticated user and the message specified by the request's message_id.
        $user = $request->user();
        $message = Message::find($request->message_id);

        //*Check if the user is authenticated and if the message exists.
        if (!$user || !$message) {

            //*If either the user or the message is not found, an error response is returned.
            return response()->json([
                'status' => 'error',
                'message' => 'message not found or user not authorized',
            ]);
        }

        //*If the user and the message exist, and if the user is the owner of the message.
        if ($user && $message && $user->id == $message->user_id) {

            //*Store the message in the Archive table then delete the message
            $archiveData = [
                'message_id' => $message->id,
                'content' => $message->content,
                'user_id' => $message->user_id,
                'type' => $message->type,
                'file' => $message->file,
                'deleted_at' => now()
            ];
            Archive::create($archiveData);

            //*The message is deleted and a success response is returned.
            $message->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'message deleted'
            ]);
        }

        //*If the user and the message exist, and if the user isn't the owner of the message.
        if ($user && $message && $user->id !== $message->user_id) {
            //*The message isn't deleted and a error response is returned.
            return response()->json([
                'status' => 'error',
                'message' => 'message not found or user not authorized'
            ]);
        }
    }
}

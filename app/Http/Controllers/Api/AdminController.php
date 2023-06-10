<?php

namespace App\Http\Controllers\Api;

use App\Models\Group;
use App\Models\Archive;
use App\Models\Channel;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\GroupResource;
use App\Http\Resources\ArchiveResource;
use App\Http\Resources\MessageResource;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Resources\AdminChatMessageResource;

class AdminController extends Controller
{
    //!Get all chats for a specific admin
    public function adminChats(Request $request)
    {
        $request->validate([
            'key' => ['nullable', 'string', 'min:2']
        ]);

        $user = $request->user();


        $channels = Channel::when($request->key, function ($query) use ($request) {
            $query->where('name', 'like', "%$request->key%");
        })->with(['lastmessage'])
            ->whereHas('participants', function (Builder $query) use ($user) {
                $query->where('users.id', $user->id);
            })->selectRaw("channels.*, (SELECT MAX(created_at) from messages WHERE messages.channel_id=channels.id) as latest_message_on")
            ->orderBy("latest_message_on", "DESC")
            ->get();



        return response()->json([
            'status' => 'success',
            'chats' => AdminChatMessageResource::collection($channels)
        ]);
    }

    public function archiveMessages(Request $request)
    {
        $archive = Archive::all();
        return response()->json([
            'status' => 'success',
            'messages' => ArchiveResource::collection($archive)
        ]);
    }

    public function staffChat(Request $request)
    {
        $request->validate([
            'group_name' => 'required',
            'staff' => 'required|array|min:2',
            'message' => 'nullable'
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

        //*Add staff to the group
        $group->staff()->attach($request->staff);

        //*If the request is empty return this response
        if (!$request->message) {
            return response()->json([
                'status' => 'error',
                'message' => 'Message is required',
            ]);
        }

       /*  //*If the size is bigger than limit return this response
        if ($request->hasFile('file') && $request->file('file')->getSize() > 39 * 1024 * 1024) {
            return response()->json([
                'status' => 'error',
                'message' => 'File size is bigger than limit',
            ]);
        } */


        //* Store the uploaded file
        /* $fileUrl = null; */
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
            /* 'file' => $fileUrl //*Store the file URL in the message */
        ];

        if (
            $messageType !== null
        ) {
            $messageData['type'] = $messageType; //*Update the message type only if it's not null
        }

        //*Create a new message in the channel
        $message = $channel->messages()->create($messageData);

        //*Push the message to the channel
        MessageSent::dispatch($message->load('channel.participants'));


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
}

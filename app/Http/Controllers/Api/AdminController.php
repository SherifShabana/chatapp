<?php

namespace App\Http\Controllers\Api;

use App\Models\Channel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\AdminChatMessageResource;
use App\Http\Resources\ArchiveResource;
use App\Models\Archive;
use Illuminate\Database\Eloquent\Builder;

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
}

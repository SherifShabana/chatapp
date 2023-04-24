<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Group;
use App\Models\Channel;
use App\Models\Section;
use App\Models\Student;
use App\Models\YearLevel;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\AdminChatMessageResource;
use App\Http\Resources\ChannelResource;
use Illuminate\Database\Eloquent\Builder;

class AdminController extends Controller
{
    //!Get all chats for a specific admin
    public function adminChats(Request $request)
    {
        $request->validate([
            'key' => ['filled', 'string', 'min:3']
        ]);

        $user = $request->user();

        $channels = Channel::when($request->key, function ($query) use ($request) {
            $query->where('name', 'like', "%$request->key%");
        })->with(['lastmessage'])->whereHas('participants', function (Builder $query) use ($user) {
            $query->where('users.id', $user->id);
        })->get();


        return response()->json([
            'status' => 'success',
            'chats' => AdminChatMessageResource::collection($channels)
        ]);
    }
}

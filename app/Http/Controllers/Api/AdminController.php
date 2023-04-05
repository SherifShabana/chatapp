<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Student;
use App\Models\Group;
use App\Models\Department;
use App\Models\YearLevel;
use App\Models\Section;
use App\Models\Channel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;


class AdminController extends Controller
{
    //Get all chats for a specific admin
    public function adminChats(Request $request)
    {
       $user = $request->user();

       $channels = Channel::whereHas('participants', function (Builder $query) use ($user) {
        $query->where('users.id', $user->id);
       })->get();

       return response()->json($channels);
    }
}

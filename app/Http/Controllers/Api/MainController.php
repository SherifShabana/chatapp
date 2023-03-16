<?php

namespace App\Http\Controllers\Api;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class MainController extends Controller
{
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
            'data' => $messages
        ]);
    }
}

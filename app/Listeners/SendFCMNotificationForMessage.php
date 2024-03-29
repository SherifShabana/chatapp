<?php

namespace App\Listeners;

use App\Traits\FCMnotification;


class SendFCMNotificationForMessage
{
    use FCMnotification;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $tokens = [];
        $channel = $event->message->channel;
        $students = $channel->getStudents();
        

        foreach ($students as $student){
            foreach ($student->tokens as $token){
                $tokens[] = $token->fcm_token;
            }
        }

        $this->notifyByFirebase($event->title,$event->body,$tokens,[
            'message_id' => $event->message->id,
            'sender' => $event->title
        ]);
    }
}

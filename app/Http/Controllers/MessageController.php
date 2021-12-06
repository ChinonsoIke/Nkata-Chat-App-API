<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        // return $user;
        try {
            $messagesReceived= $user->messages()->get();
            $messagesSent = $user->sentMessages()->get();
            $allMessages = $messagesReceived->merge($messagesSent);
            $success = true;
            $message= 'Messages returned successfully';
        } catch (Exception $e) {
            $success = false;
            $message = 'Error retrieving messages '. $e->getMessage();
        }

        $response= [
            'success'=>$success,
            'message'=>$message,
            'data'=>[
                'messages'=>$allMessages
            ]
        ];

        return response()->json($response);
        
    }
    
    public function send(Request $request)
    {
        $attributes= $request->validate(
            [
                'to'=>'required|exists:users,id',
                'body'=>'required'
            ]
        );
        
        try{
            $messageCreate = Message::create(['to'=>$attributes['to'], 'body'=>$attributes['body'], 'from'=>$request->user()->id]);
            $success = true;
            $message = 'Message sent successfully';
            Log::debug('message sent', ['message'=>$messageCreate]);
            broadcast(new MessageSent($messageCreate->load('to_user')));
        } catch (Exception $e) {
            $success = false;
            $message = 'Error sending message '. $e->getMessage();
        }

        $response= [
            'success'=>$success,
            'message'=>$message
        ];

        return response()->json($response);
    }


}

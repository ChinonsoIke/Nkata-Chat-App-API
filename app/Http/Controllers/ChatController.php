<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        try{
            $user= $request->user();
            $contacts=$user->contacts()->get(); //User::where('id', $user->contacts()->pluck('contact_id'))->get();
            // return $contacts;
            $allMessages= [];
            foreach ($contacts as $con) {
                $contact= User::where('id', $con->contact_id)->first();
                $contactMessagesReceived= $contact->messages()->where('to', $contact->id)->where('from',$user->id)->with('to_user')->get();
                $contactMessagesSent = $contact->sentMessages()->where('from',$contact->id)->where('to',$user->id)->with('from_user')->get();
                $recent = $contactMessagesReceived->merge($contactMessagesSent)->sortByDesc('created_at')->values()->first();
                $allMessages[]= $recent;
                $allMessages= collect($allMessages)->sortByDesc('created_at')->values()->all();
            }
            
            // $chats= $allMessages->where('from', $user->id)->orWhere('to', $user->id)->latest();
            // $chats= $allMessages->where(function($query) use ($user){
            //     $query->where('from', $user->id)->orWhere('to', $user->id);
            // })->latest();

            $success= true;
            $message= 'Chats returned successfully';
        } catch (Exception $e) {
            $success= false;
            $message= 'Error: '.$e->getMessage();
        }

        $response= [
            'success' => $success,
            'message' => $message,
            'data' => [
                'chats' => $allMessages
            ]
        ];

        return response()->json($response);
        
    }

    public function show(Request $request, $id)
    {
        try{
            $user= $request->user();
            $contact= User::where('id', $id)->first();
            $contactMessagesReceived= $contact->messages()->where('to', $contact->id)->where('from',$user->id)->get();
            $contactMessagesSent = $contact->sentMessages()->where('from',$contact->id)->where('to',$user->id)->get();
            $allMessages = $contactMessagesReceived->merge($contactMessagesSent)->sortBy('created_at')->values()->all();
            
            $success= true;
            $message= 'Single chat returned successfully';
        } catch (Exception $e) {
            $success= false;
            $message= 'Error: '.$e->getMessage();
        }

        $response= [
            'success' => $success,
            'message' => $message,
            'data' => [
                'chats' => $allMessages
            ]
        ];

        return response()->json($response);
    }
}

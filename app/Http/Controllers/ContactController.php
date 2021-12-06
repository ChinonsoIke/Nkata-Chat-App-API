<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $contacts= $request->user()->contacts()->with('userContact')->get();
        return response()->json($contacts);
    }

    public function add(Request $request)
    {
        $contact= User::where('username', $request->username)->first();
        if($contact){
            $request->user()->contacts()->create([
                'user_id' => $request->user()->id,
                'contact_id' => $contact->id,
            ]);

            $contact->contacts()->create([
                'user_id' => $contact->id,
                'contact_id' => $request->user()->id,
            ]);

            $response=[
                'success' => true,
                'message' => 'Contact added successfully',
            ];
        }else{
            $response= [
                'success' => false,
                'message' => 'Contact has not joined Nkata yet. Kindly invite them',
            ];
        }
        return response()->json($response);
    }
}

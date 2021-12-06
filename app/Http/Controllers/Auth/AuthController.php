<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session as Session;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try{
            $attributes= $request->validate(
                [
                    'firstname'=>'required|min:3|max:30|alpha',
                    'lastname'=>'required|min:3|max:30|alpha',
                    'email'=>'required|email|unique:users',
                    'username'=>'required|min:3|max:30|alpha_num',
                    'phone'=>'required|digits:11',
                    'password'=>'required|min:6|max:30',
                ]
            );
    
            $attributes['password']= bcrypt($attributes['password']);
    
            $user = User::create($attributes);
            $pat= $user->createToken($user->username)->plainTextToken;

            $message= "Account created successfully, welcome aboard.";
            $success= true;
        } catch(Exception $e){
            $success= false;
            $message= $e->getMessage();
        }
        

        $response= [
            'success'=>$success,
            'message'=>$message,
        ];

        return response()->json($response);
    }

    public function login(Request $request)
    {
        $attributes= $request->validate(
            [
                'email'=>'required|email',
                'password'=>'required',
            ]
        );

        if(!auth()->attempt($attributes)){
            return response()->json(
                ['message'=>'These credentials do not match our records'], 401
            );
        }

        $user= auth()->user();
        $pat= $user->createToken($user->id)->plainTextToken;

        $message= "User logged in successfully";
        $success= true;

        $response= [
            'success'=>$success,
            'message'=>$message,
            'data'=>[
                'user'=>$user,
                'token'=>$pat,
            ],
        ];     

        return response()->json($response, 200);
    }

    public function logout()
    {
        try{
            Session::flush();
            $success= true;
            $message= 'User logged out successfully';
        } catch(Exception $e){
            $success= false;
            $message= 'User could not be logged out: '. $e->getMessage();
        }

        $response= [
            'success'=>$success,
            'message'=>$message,
        ];

        return response()->json($response);
    }
}

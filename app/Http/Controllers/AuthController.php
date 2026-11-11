<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register (Request $request){
        $request->validate([
            'name'=>'required|string',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:8'
        ]);
        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>$request->password
        ]);
        $token = $user ->createToken('auth_token')->plainTextToken;
        return response()->json([
            'user'=> $user,
            'token'=>$token ,
        ],201);
    }
    public function login (Request $request){
        $request->validate([
            'email'=>'required|email',
            'password'=>'required|string'
        ]);
        if(!Auth::attempt([
            'email'=> $request->email,
            'password'=> $request->password
        ])){
            return response()->json([
                'message'=>'Invaild credentails'
            ],401);
        };
        $user = Auth::user();
        $token = $user -> createToken('auth_token')->plainTextToken;
        return response()->json([
            'user'=>$user,
            'token'=>$token
        ],200);
    }
    public function logout(Request $request){

        $user = $request->user();
        $token = $user->currentAccessToken();
        $token->delete();
        return response()->json([
            'message'=>'logout successful'
        ],200);
    }
}

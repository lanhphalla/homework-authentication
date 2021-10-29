<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\hash;

class UserController extends Controller
{
    public function login(Request $request)
    {
        //validation
        //create user
        $request->validate([
            'password'=>'required|confirmed',
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        //create token(key) for use to access api 
        $token = $user->createToken('mytoken')->plainTextToken;
        return response()->json([
          
            'user' => $user,
            'token' => $token,
        ]);

 
    }
    public function logout(Request $requet) {
        auth()->user()->tokens()->delete();
        return response()->json(['message' =>'Signing out']);
    }
    public function register(Request $request)
    //  "token": "4|P59NdyQOl0xlUx6lORv7A0pstHylrhEk1mWBSCaS"
    {
        $user = User::where('email', $request->email)->first();
        if(!$user || !hash::check($request->password, $user->password))  {
            return response()->json(['message' => 'Bad register'], 401);
        }
        //create token(key) for use to access api 
        $token = $user->createToken('mytoken')->plainTextToken;
        return response()->json([
          
            'user' => $user,
            'token' => $token,
        ]);
    }
}
// "token": "2|IqmGxvSIB4euop2CAKWFXTSrHsWzBqyPTmax6AKc"
// "token": "3|i04Yy3ssC2z8zZ2ETGa7QajA6aPgUttJSwDHABNr"
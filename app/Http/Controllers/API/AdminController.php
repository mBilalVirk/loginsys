<?php

namespace App\Http\Controllers\API;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Friend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
   public function login(Request $request)
{
    $credentials = $request->validate(
        [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ],
        [
            'email.required' => 'Email is required',
            'email.email'    => 'Please enter a valid email address',
            'password.required' => 'Password is required',
        ]
    );
    // return $credentials;
    $user = USER::where('email',$credentials['email'])->first();
    if($user == null){
                return response()->json([
                    'success'=> false,
                    'message' => 'Invalid Credentials!'
                ],401 );

            }
    if ($user->role !== 'admin' && $user->role !== 'super_admin') {
            return response()->json([
                    'success'=> false,
                    'message' => 'You cant login in admin panel!'
                ],400 );
    }else{
        if (auth()->attempt($credentials)) {
                $user->tokens()->delete();
                $token = $user->createToken('api-token')->plainTextToken;
                return response()->json([
                    'success'=> true,
                    'message' => 'Admin is login',
                    'data' => [
                        'user'=> $user,
                        'token' => $token

                    ]
                ],200 );
            }
    }
    if (!auth()->attempt($credentials)) {
        return back()->withErrors([
            'email' => 'Invalid email or Password',
            ]);
            }
            
    }
    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return response()->json([
            'success' => true,
            'message' => 'Logout successful',

        ], 200);

    }
}

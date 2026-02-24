<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|string|email',
        'password' => 'required|string',
    ],[
        'email.required' => 'Email is required',
        'email.email' => 'Please enter a valid email address',
        'password.required' => 'Password is required',
    ]);

    $user = User::where('email', $credentials['email'])->first();

   
    if ($user == null) {
        return response()->json([
            'success' => false,
            'message' => 'Please register first to login',
            'field' => 'email'
        ], 404);
    }

    
    if ($user->role === 'admin' || $user->role === 'super_admin') {
        return response()->json([
            'success' => false,
            'message' => 'Admin must login with the admin page'
        ], 403);
    }

    
    if (auth()->attempt($credentials)) {
       
        
        $user->tokens()->delete();
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'token' => $token
            ]
        ], 200);
    }

    // Password incorrect
    return response()->json([
        'success' => false,
        'message' => 'Password incorrect',
        'field' => 'email'
    ], 401);
}

    public function logout(Request $request){
       $request->user()->tokens()->delete();
        return response()->json([
            'success' => true,
            'message' => 'Logout successful',

        ], 200);
    }

    public function fetch()
    {
        // DB::enableQueryLog();

        $user = auth()->user();

        //Show user's and friends posts on dashboard page
        $posts = Post::whereHas('user', function ($q){
            $q->whereNull('deleted_at');
        })
        ->whereIn('user_id', function ($query) use ($user) {
            $query->select('friend_id')
                  ->from('friends')
                  ->where('user_id', $user->id)
                  ->whereNull('deleted_at')
                  ->where('status', 'accepted');
        })->orWhereIn('user_id', function ($query) use ($user) {
            $query->select('user_id')
                  ->from('friends')
                  ->where('friend_id', $user->id)
                  ->whereNull('deleted_at')
                  ->where('status', 'accepted');
        })->orWhere('user_id', $user->id)
          ->orderBy('created_at', 'desc')
          ->with(['comments'=> function($q){
                $q->whereNull('deleted_at')
                ->whereNull('parent_id');               
                }])
          ->get();

          return response()->json([
            'success'=> true,
            'message'=> "Data Fetched",
            'data' => [
                'user' => $user,
                'post' =>  $posts
            ]
          ], 200);
       
    }


    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
           'password' => [
            'required',
            'string',
            'min:8', 
            'confirmed',
            'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).+$/'
    ],
            'photo'=> 'required|image|mimes:jpeg,png,jpg|max:2048'
            
        ],[
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'This email is already registered',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
            'password.confirmed' => 'Password confirmation does not match',
            'password.regex' => 'Password must include at least 1 uppercase letter, 1 lowercase letter, 1 number, and 1 special character',
            'photo.required'=>'Photo is Required',
        ]);
        if ($request->hasFile('photo')) {
            // if ($user->photo && file_exists(public_path($user->photo))) {
            // unlink(public_path($user->photo));}

            $imageName = time().'_'.$request->photo->getClientOriginalName();
            $request->photo->move(public_path('images'), $imageName);
    
            
            $validatedData['photo'] = 'images/'.$imageName;
        }
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
            'photo' => $validatedData['photo'],
            'role' => 'user',
        ]);
        if($user){
            return response()->json([
                'success'=> true,
                'message'=> 'User has been registered',
                'data' => $user
            ], 200);
        }else{
            return response()->json([
                'success'=> false,
                'message'=> 'User has not been registered',
                
            ], 400);
        }
    } 
}

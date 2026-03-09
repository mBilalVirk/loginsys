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

    public function fetch()
    {
        $user = auth()->user();
        if($user->role == 'super_admin'){
            $users = user::where('id', '!=', $user->id)->whereNull('deleted_at')->get();
        }else{
             $users = user::where('role', '!=', 'admin')
                            ->where('role', '!=', 'super_admin')
                            ->whereNull('deleted_at')->get();
        }
        return response()->json([
            'success'=>true,
            'user' => $users,
        ],200);
       
    }
     public function countUsersPosts()
    {
        $userCount = User::where('role', 'user')->count();
        $postCount = Post::count();
        $adminCount = User::where('role', 'admin')->count();
        $comments = Comment::count();
        
        return response()->json([
            'success' => true,
            'data' => [
                'user_count' => $userCount,
                'post_count' => $postCount,
                'admin_count' => $adminCount,
                'comment_count' => $comments,
            ],
        ]);
        
    }
     public function delete($id){
        $user = user::FindOrFail($id);
        $user -> delete();
        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully',
        ], 200);
    }
      public function userPosts(Request $request){
        $posts = USER::with('posts.comments')->get();
        return response()->json([
            'success' => true,
            'data' => $posts,
        ], 200);
    }
    
}

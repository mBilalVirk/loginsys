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
                'email.email' => 'Please enter a valid email address',
                'password.required' => 'Password is required',
            ],
        );
        // return $credentials;
        $user = USER::where('email', $credentials['email'])->first();
        if ($user == null) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Invalid Credentials!',
                ],
                401,
            );
        }
        if ($user->role !== 'admin' && $user->role !== 'super_admin') {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'You cant login in admin panel!',
                ],
                400,
            );
        } else {
            if (auth()->attempt($credentials)) {
                $user->tokens()->delete();
                $token = $user->createToken('api-token')->plainTextToken;
                return response()->json(
                    [
                        'success' => true,
                        'message' => 'Admin is login',
                        'data' => [
                            'user' => $user,
                            'token' => $token,
                        ],
                    ],
                    200,
                );
            }
        }
        if (!auth()->attempt($credentials)) {
            return back()->withErrors([
                'email' => 'Invalid email or Password',
            ]);
        }
    }
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(
            [
                'success' => true,
                'message' => 'Logout successful',
            ],
            200,
        );
    }

    public function fetch()
    {
        $user = auth()->user();
        if ($user->role == 'super_admin') {
            $users = user::where('id', '!=', $user->id)->whereNull('deleted_at')->with('posts')->get();
        } else {
            $users = user::where('role', '!=', 'admin')->where('role', '!=', 'super_admin')->whereNull('deleted_at')->get();
        }
        return response()->json(
            [
                'success' => true,
                'user' => $users,
            ],
            200,
        );
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
    public function delete($id)
    {
        $user = user::FindOrFail($id);
        $user->delete();
        return response()->json(
            [
                'success' => true,
                'message' => 'User deleted successfully',
            ],
            200,
        );
    }
    public function userPosts(Request $request)
    {
        $posts = USER::with('posts.comments')->get();
        return response()->json(
            [
                'success' => true,
                'data' => $posts,
            ],
            200,
        );
    }
    public function fetchAdmin()
    {
        $user = auth()->user();
        if ($user->role == 'super_admin') {
            $admins = user::where('role', 'admin')->where('id', '!=', $user->id)->whereNull('deleted_at')->get();
            return response()->json(
                [
                    'success' => true,
                    'data' => $admins,
                ],
                200,
            );
        } else {
            $admins = USER::where('id', $user->id)->whereNull('deleted_at')->whereNull('deleted_at')->get();
            return response()->json(
                [
                    'success' => true,
                    'data' => $admins,
                ],
                200,
            );
        }
    }
    public function createNewAdmin(Request $request)
    {
        $validatedData = $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).+$/'],
                'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ],
            [
                'name.required' => 'Name is required',
                'email.required' => 'Email is required',
                'email.email' => 'Please enter a valid email address',
                'email.unique' => 'This email is already registered',
                'password.required' => 'Password is required',
                'password.min' => 'Password must be at least 8 characters',
                'password.confirmed' => 'Password confirmation does not match',
                'password.regex' => 'Password must include at least 1 uppercase letter, 1 lowercase letter, 1 number, and 1 special character',
            ],
        );
        // return $validatedData;
        if ($request->hasFile('photo')) {
            // if ($user->photo && file_exists(public_path($user->photo))) {
            // unlink(public_path($user->photo));}

            $imageName = time() . '_' . $request->photo->getClientOriginalName();
            $request->photo->move(public_path('images'), $imageName);

            $validatedData['photo'] = 'images/' . $imageName;
        }
        $validatedData['role'] = 'admin';
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
            'photo' => $validatedData['photo'] ?? null,
            'role' => $validatedData['role'],
        ]);
        return response()->json(
            [
                'success' => true,
                'message' => 'Admin created successfully',
                'data' => $user,
            ],
            201,
        );
    }
    public function search(Request $request)
    {
        $user = auth()->user();
        $category = $request->get('category');
        $query = $request->get('query');

        $results = [];
        if ($category === 'users') {
            $results = User::where('role', 'user')
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%$query%")->orWhere('email', 'like', "%$query%");
                })
                ->get();
        } elseif ($category === 'posts') {
            $results = Post::where('content', 'like', "%$query%")
                ->orWhereHas('user', function ($q) use ($query) {
                    $q->where('name', 'like', "%$query%");
                })
                ->with('user')
                ->with('comments')
                ->get();
        } elseif ($category === 'admins') {
            $results = User::where('role', 'admin')
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%$query%")->orWhere('email', 'like', "%$query%");
                })
                ->get();
        }
        if ($results) {
            return response()->json(
                [
                    'success' => true,
                    'data' => $results,
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'success' => false,
                    'data' => $results,
                ],
                400,
            );
        }
    }
}

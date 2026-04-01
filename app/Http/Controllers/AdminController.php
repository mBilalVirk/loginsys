<?php

namespace App\Http\Controllers;
use App\Charts\PostCommentChart;
use App\Charts\UsersChart;
use App\Models\Comment;
use App\Models\Friend;
use App\Models\Post;
use App\Models\User;
use App\Models\ChatbotMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.login');
    }
    public function countUsersPosts()
    {
        $userCount = User::where('role', 'user')->count();
        $postCount = Post::count();
        $adminCount = User::where('role', 'admin')->count();
        $comments = Comment::count();
        $chart = new UsersChart();
        $postChart = new PostCommentChart();
        return view('admin.dashboard', compact('userCount', 'postCount', 'adminCount', 'comments', 'chart', 'postChart'));
    }
    public function fetch(Request $request)
    {
        $authUser = auth()->user();

        $query = User::query();

        // 🔐 Role-based filtering
        if ($authUser->role == 'super_admin') {
            $query->where('id', '!=', $authUser->id);
        } else {
            $query->whereNotIn('role', ['admin', 'super_admin']);
        }

        // ❌ Exclude soft deleted
        $query->whereNull('deleted_at');

        // 🔍 SEARCH (name + email)
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->search}%")->orWhere('email', 'LIKE', "%{$request->search}%");
            });
        }

        // 📅 DATE FILTER
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // 🔽 SORTING
        switch ($request->sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;

            case 'az':
                $query->orderBy('name', 'asc');
                break;

            case 'za':
                $query->orderBy('name', 'desc');
                break;

            default:
                $query->orderBy('created_at', 'desc'); // newest
        }
        
        // 📄 PAGINATION
        $users = $query->paginate(5);

        return response()->json($users);
    }
    public function fetchAdmin(Request $request)
    {
        $user = auth()->user();
        $query = User::query();
        if ($user->role == 'super_admin') {
            $query->where('role', 'admin')->where('id', '!=', $user->id)->whereNull('deleted_at');
          
        } else {
            $query->where('id', $user->id)->whereNull('deleted_at')->whereNull('deleted_at');
           
           
        }
        
        // ❌ Exclude soft deleted
        $query->whereNull('deleted_at');

        // 🔍 SEARCH (name + email)
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->search}%")->orWhere('email', 'LIKE', "%{$request->search}%");
            });
        }

        // 📅 DATE FILTER
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // 🔽 SORTING
        switch ($request->sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;

            case 'az':
                $query->orderBy('name', 'asc');
                break;

            case 'za':
                $query->orderBy('name', 'desc');
                break;

            default:
                $query->orderBy('created_at', 'desc'); // newest
        }
        $admins = $query->get();
        return response()->json($admins);

    }
    public function adminlogin(Request $request)
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
        $user = User::where('email', $credentials['email'])->first();
        if ($user == null) {
            return back()
                ->withErrors([
                    'email' => 'Invalid email or Password',
                ])
                ->onlyInput('email');
        }
        if ($user->role !== 'admin' && $user->role !== 'super_admin') {
            return redirect()->back()->with('status', 'You are not allowed to login as admin');
        } else {
            if (auth()->attempt($credentials)) {
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard');
            }
        }
        if (!auth()->attempt($credentials)) {
            return back()->withErrors([
                'email' => 'Invalid email or Password',
            ]);
        }
    }

    public function edit(Request $request, $id)
    {
        $user = user::FindOrFail($id);
        return view('admin.edit', compact('user'));
    }
    public function userUpdate(Request $request, $id)
    {
        $user = user::FindOrFail($id);
        $validatedData = $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ],
            [
                'name.required' => 'Name is Required',
                'email.required' => 'Email is Required',
                'name.email' => 'Please enter a valid email',
                'name.unique' => 'Email already Exist',
            ],
        );
        //   return $validatedData;
        if ($request->hasFile('photo')) {
            if ($user->photo && file_exists(public_path($user->photo))) {
                unlink(public_path($user->photo));
            }

            $imageName = time() . '_' . $request->photo->getClientOriginalName();
            $request->photo->move(public_path('images'), $imageName);

            $validatedData['photo'] = 'images/' . $imageName;
        }

        $user->update($validatedData);
        return response()->json(['res' => 'User updated successfully!']);
        //   return redirect()->back()->with('status','User updated successfully.');
        // return redirect()->route('admin.index')->with('status','User updated successfully.');
    }
    public function logout(Request $request)
    {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    public function delete($id)
    {
        $user = user::FindOrFail($id);
        $user->delete();
        return response()->json(['res' => 'Delete successfully']);
        // return redirect()->back()->with('status','User deleted successfully.');
        // return redirect()->route('admin.index')->with('status', 'User deleted successfully');
    }
    public function deleteComment($id)
    {
        $comment = Comment::FindOrFail($id);
        $comment->delete();

        return redirect()->back()->with('status', 'Comment deleted successfully');
        // return "<h1>Comment deleted successfully</h1>";
        //         return redirect()->back()->with('toast', [
        //     'type' => 'success', // success | error | warning | help
        //     'title' => 'Success!',
        //     'message' => 'Your comment was deleted successfully.'
        // ]);
    }
    public function userPosts(Request $request)
    {
        $posts = User::with('posts.comments')->paginate(3);
        //    return $posts;
        if ($request->expectsJson()) {
            return response->json($posts);
        }
        return view('admin.posts', compact('posts'));
    }
    public function setting()
    {
        return view('admin.setting');
    }
    public function assistant()
    {
        return view('admin.aiassistant');
    }
    public function ai_history()
    {
        $messages = ChatbotMessage::with('user')->paginate(5);

        return response()->json(
            [
                'success' => true,
                'messages' => $messages->items(),
                'pagination' => [
                    'total' => $messages->total(),
                    'per_page' => $messages->perPage(),
                    'current_page' => $messages->currentPage(),
                    'last_page' => $messages->lastPage(),
                ],
            ],
            200,
        );
    }
    public function clearAIHistory()
    {
        ChatbotMessage::truncate();
        return response()->json([
            'success' => true,
            'message' => 'AI chat history cleared successfully.',
        ]);
    }
    public function search()
    {
        return view('admin.search');
    }
    public function searchData(Request $request)
    {
        $request->validate([
            'category' => ['nullable', 'in:users,posts,admins'],
            'search' => ['nullable', 'string', 'max:255'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'sort' => ['nullable', 'in:newest,oldest,az,za'],
        ]);

        $category = $request->input('category');
        $query = trim($request->input('search', ''));
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $sort = $request->input('sort', 'newest');

        if (!$category) {
            return response()->json(['success' => false, 'message' => 'Category is required.'], 422);
        }

        $role = match ($category) {
            'admins' => 'admin',
            'users' => 'user',
            default => null,
        };

        $results = match ($category) {
            'users', 'admins' => User::select('id', 'name', 'email', 'role', 'photo', 'created_at')
                ->where('role', $role)
                ->when($query, fn($q) => $q->where(fn($q) => $q->where('name', 'like', "%$query%")->orWhere('email', 'like', "%$query%")))
                ->when($sort === 'newest', fn($q) => $q->orderBy('id', 'desc'))
                ->when($sort === 'oldest', fn($q) => $q->orderBy('id', 'asc'))
                ->when($sort === 'az', fn($q) => $q->orderBy('name', 'asc'))
                ->when($sort === 'za', fn($q) => $q->orderBy('name', 'desc'))
                ->get(),

            'posts' => Post::with('user')
                ->when($query, fn($q) => $q->where(fn($q) => $q->where('content', 'like', "%$query%")->orWhereHas('user', fn($q) => $q->where('name', 'like', "%$query%"))))
                ->when($dateFrom, fn($q) => $q->whereDate('created_at', '>=', $dateFrom))
                ->when($dateTo, fn($q) => $q->whereDate('created_at', '<=', $dateTo))
                ->when($sort === 'newest', fn($q) => $q->latest())
                ->when($sort === 'oldest', fn($q) => $q->oldest())
                ->when($sort === 'az', fn($q) => $q->orderBy('content', 'asc'))
                ->when($sort === 'za', fn($q) => $q->orderBy('content', 'desc'))
                ->get(),
        };

        return response()->json([
            'success' => true,
            'data' => $results,
        ]);
    }
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $validatedData = $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'password' => 'nullable|string|min:8|confirmed',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ],
            [
                'name.required' => 'Name is required',
                'email.required' => 'Email is required',
                'email.email' => 'Please enter a valid email address',
                'email.unique' => 'This email is already registered',
                'password.min' => 'Password must be at least 8 characters',
                'password.confirmed' => 'Password confirmation does not match',
            ],
        );

        if (!$request->filled('password')) {
            unset($validatedData['password']);
        } else {
            $validatedData['password'] = Hash::make($validatedData['password']);
        }
        if ($request->hasFile('photo')) {
            if ($user->photo && file_exists(public_path($user->photo))) {
                unlink(public_path($user->photo));
            }
            $imageName = time() . '_' . $request->photo->getClientOriginalName();
            $request->photo->move(public_path('images'), $imageName);
            $validatedData['photo'] = 'images/' . $imageName;
        }

        // if password in not filled, it will not be updated

        $user->update($validatedData);
        return response()->json(['res' => 'Profile Updated Successfully']);
        // return redirect()->back()->with('success', 'Profile updated successfully.');
    }
    /**
     * Show the form for creating a new resource.
     */
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
        return response()->json(['res' => 'New admin created successfully']);
        // return redirect()->back()->with('success','New admin created successfully');
    }

    public function fetchTrashedData()
    {
        $users = User::onlyTrashed()->where('role', 'user')->get();
        $posts = Post::onlyTrashed()->get();
        $admins = User::onlyTrashed()->where('role', 'admin')->get();
        $comments = Comment::onlyTrashed()->get();
        $friends = Friend::onlyTrashed()->get();
        return view('admin.Deleted', compact('users', 'posts', 'admins', 'comments', 'friends'));
    }
    public function restoreUser($id)
    {
        $user = User::withTrashed()->find($id);
        $user->restore();
        return redirect()->back()->with('status', 'User Restore Successfully!');
    }
    public function permanentDeleteUser($id)
    {
        $user = User::withTrashed()->find($id);
        $user->forceDelete();
        return redirect()->back()->with('status', 'User Permanently Deleted!');
    }
    public function restorePost($id)
    {
        $post = Post::withTrashed()->find($id);
        $post->restore();
        return redirect()->back()->with('status', 'Post Restore Successfully!');
    }
    public function permanentDeletePost($id)
    {
        $post = Post::withTrashed()->find($id);
        $post->forceDelete();
        return redirect()->back()->with('status', 'Post Permanently Deleted!');
    }
    public function restoreComment($id)
    {
        $comment = Comment::withTrashed()->find($id);
        $comment->restore();
        return redirect()->back()->with('status', 'Comment Restore Successfully!');
    }
    public function permanentDeleteComment($id)
    {
        $comment = Comment::withTrashed()->find($id);
        $comment->forceDelete();
        return redirect()->back()->with('status', 'Comment Permanently Deleted!');
    }
    public function restoreAdmin($id)
    {
        $admin = User::withTrashed()->find($id);
        $admin->restore();
        return redirect()->back()->with('status', 'Admin Restore Successfully!');
    }
    public function permanentDeleteAdmin($id)
    {
        $admin = User::withTrashed()->find($id);
        $admin->forceDelete();
        return redirect()->back()->with('status', 'Admin Permanently Deleted!');
    }
    public function fetchFriends(Request $request)
    {
        $query = Friend::query()->with(['sender', 'receiver']);

        if ($request->search) {
            $query->whereHas('sender', fn($q) => $q->where('name', 'like', "%{$request->search}%"))->orWhereHas('receiver', fn($q) => $q->where('name', 'like', "%{$request->search}%"));
        }

        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->sort) {
            switch ($request->sort) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'az':
                    $query->orderBy('sender_id'); // or a related column
                    break;
                case 'za':
                    $query->orderBy('sender_id', 'desc'); // or a related column
                    break;
            }
        }

        $friends = $query->get(); // you can use paginate() if needed
        return response()->json($friends);
    }
    public function deleteFriend($id)
    {
        $friend = Friend::FindOrFail($id);
        $friend->delete();
        return redirect()->back()->with('status', 'Friend deleted successfully.');
        // return redirect()->route('admin.index')->with('status', 'User deleted successfully');
    }
    public function restoreFriend($id)
    {
        $friend = Friend::withTrashed()->find($id);
        $friend->restore();
        return redirect()->back()->with('status', 'Friendship Restore Successfully!');
    }
    public function permanentDeleteFriend($id)
    {
        $friend = Friend::withTrashed()->find($id);
        $friend->forceDelete();
        return redirect()->back()->with('status', 'Friendship Permanently Deleted!');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */

    /**
     * Update the specified resource in storage.
     */

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

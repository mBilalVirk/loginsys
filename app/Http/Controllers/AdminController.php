<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

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
        return view('admin.dashboard', compact('userCount', 'postCount', 'adminCount'));
        
    }
    public function fetch()
    {
        $user = auth()->user();
        if($user->role == 'super_admin'){
            $users = user::where('id', '!=', $user->id)->get();
        }else{
             $users = user::where('role', '!=', 'admin')->get();
        }
        return view('admin.users', compact('users','user')); 
       
    }
      public function fetchAdmin()
    {
        $user = auth()->user();
        if($user->role =='super_admin'){
            $admins = user::where('role', 'admin')
                            ->where('id', '!=', $user->id)
                            ->get();
                            return view('admin.admins', compact('admins')); 

        }else{
            $admins = USER::where('id',$user->id)->get();
                            return view('admin.admins', compact('admins'));

        }
                        
       
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
            'email.email'    => 'Please enter a valid email address',
            'password.required' => 'Password is required',
        ]
    );
    // return $credentials;
    if (!auth()->attempt($credentials)) {
        return back()->withErrors([
            'email' => 'Invalid email or password',
        ]);
    }

    
    if (auth()->user()->role !== 'admin' && auth()->user()->role !== 'super_admin') {
        auth()->logout(); 
        session()->invalidate();
        session()->regenerateToken();
        return redirect()
            ->back()
            ->with('status', 'You are not allowed to login as admin');
    }

    $request->session()->regenerate();

    return redirect()->route('admin.dashboard');
}



    public function edit(Request $request, $id){
        $user = user::FindOrFail($id);
        return view('admin.edit', compact('user'));
    }
     public function userUpdate(Request $request, $id){
        $user = user::FindOrFail($id);
        $validatedData = $request->validate(
            [
                'name'=>'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'photo'=> 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ],[
                'name.required'=>'Name is Required',
                'email.required'=>'Email is Required',
                'name.email'=>'Please enter a valid email',
                'name.unique'=>'Email already Exist'
            ]
      );
    //   return $validatedData;
     if ($request->hasFile('photo')) {
    if ($user->photo && file_exists(public_path($user->photo))) {
    unlink(public_path($user->photo));
}

    $imageName = time().'_'.$request->photo->getClientOriginalName();
    $request->photo->move(public_path('images'), $imageName);

    
    $validatedData['photo'] = 'images/'.$imageName;
}

      $user -> update($validatedData);
      return redirect()->back()->with('status','User updated successfully.');
        // return redirect()->route('admin.index')->with('status','User updated successfully.');
    }
     public function logout(Request $request)
    {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/adminlogin');
    }

    public function delete($id){
        $user = user::FindOrFail($id);
        $user -> delete();
        return redirect()->back()->with('status','User deleted successfully.');
        // return redirect()->route('admin.index')->with('status', 'User deleted successfully');
    }
    public function deleteComment($id){
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
    public function userPosts(){
        $posts = USER::with('posts.comments')->get();
      //  return $posts;
        return view('admin.posts', compact('posts'));
    }
        public function setting(){

            return view('admin.setting');
        }
        public function updateProfile(Request $request){
            $user = auth()->user();
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'password' => 'nullable|string|min:8|confirmed',
                'photo'=> 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ],[
                'name.required' => 'Name is required',
                'email.required' => 'Email is required',
                'email.email' => 'Please enter a valid email address',
                'email.unique' => 'This email is already registered',
                'password.min' => 'Password must be at least 8 characters',
                'password.confirmed' => 'Password confirmation does not match',
            ]);

             if (!$request->filled('password')) {
        unset($validatedData['password']);
    } else {
        $validatedData['password'] = Hash::make($validatedData['password']);
    }       if ($request->hasFile('photo')) {
            if ($user->photo && file_exists(public_path($user->photo))) {
            unlink(public_path($user->photo));}
            $imageName = time().'_'.$request->photo->getClientOriginalName();
            $request->photo->move(public_path('images'), $imageName);
            $validatedData['photo'] = 'images/'.$imageName;
        }

            // if password in not filled, it will not be updated

            $user->update($validatedData);
            return redirect()->back()->with('success', 'Profile updated successfully.');
        }
    /**
     * Show the form for creating a new resource.
     */
    public function createNewAdmin(Request $request)
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
            'photo'=> 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            
        ],[
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'This email is already registered',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
            'password.confirmed' => 'Password confirmation does not match',
            'password.regex' => 'Password must include at least 1 uppercase letter, 1 lowercase letter, 1 number, and 1 special character',
            
        ]);
        // return $validatedData;
        if ($request->hasFile('photo')) {
            // if ($user->photo && file_exists(public_path($user->photo))) {
            // unlink(public_path($user->photo));}

            $imageName = time().'_'.$request->photo->getClientOriginalName();
            $request->photo->move(public_path('images'), $imageName);
    
            
            $validatedData['photo'] = 'images/'.$imageName;
        }
        $validatedData['role'] = 'admin';
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
            'photo' => $validatedData['photo'] ?? null,
            'role' => $validatedData['role'],
        ]);

        return redirect()->back()->with('success','New admin created successfully');
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

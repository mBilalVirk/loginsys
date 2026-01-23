<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Post;
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
        $userCount = User::count();
        $postCount = Post::count();
        return view('admin.dashboard', compact('userCount', 'postCount'));
        
    }
    public function fetch()
    {
        $user = auth()->user();
         $users = user::where('id', '!=', $user->id)->get();
        return view('admin.users', compact('users','user')); 
       
    }
    public function adminlogin(Request $request)
{
    $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ],[
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'password.required' => 'Password is required',
        ]);

    if (auth()->attempt($credentials)) {
        $request->session()->regenerate();
        if (auth()->user()->role === 'user') {
        return redirect()->route('userLogin')->with('status','User must login with this page');
    }else{

        return redirect()->route('admin.dashboard');
        
    }
       

       
    }

    return back()->withErrors([
        'email' => 'Invalid email or password.',
    ])->onlyInput('email');
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

    public function userPosts(){
        $posts = USER::with('posts')->get();
        // return $posts;
        return view('admin.posts', compact('posts'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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

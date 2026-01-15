<?php

namespace App\Http\Controllers;
use App\Models\User;
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

    public function fetch()
    {
        $user = auth()->user();
         $users = user::all();
        return view('admin.index', compact('users','user')); 
       
    }
    public function adminlogin(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        
            return redirect()->route('admin.index');
       

       
    }

    return back()->withErrors([
        'email' => 'Invalid email or password.',
    ])->onlyInput('email');
}
    public function edit(Request $request, $id){
        $user = user::FindOrFail($id);
        return view('admin.edit', compact('user'));
    }
     public function update(Request $request, $id){
        $user = user::FindOrFail($id);
        $validatedData = $request->validate(
            [
                'name'=>'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'photo'=> 'required|image|mimes:jpeg,png,jpg|max:2048'
            ],[
                'name.required'=>'Name is Required',
                'email.required'=>'Email is Required',
                'photo.required'=>'Photo is Required',
                'name.email'=>'Please enter a valid email',
                'name.unique'=>'Email already Exist'
            ]
      );
     if ($request->hasFile('photo')) {
    if ($user->photo && file_exists(public_path($user->photo))) {
    unlink(public_path($user->photo));
}

    $imageName = time().'_'.$request->photo->getClientOriginalName();
    $request->photo->move(public_path('images'), $imageName);

    
    $validatedData['photo'] = 'images/'.$imageName;
}

      $user -> update($validatedData);
        return redirect()->route('admin.index')->with('status','User updated successfully.');
    }
     public function logout(Request $request)
    {
        auth()->logout();
        return redirect('/adminlogin');
    }

    public function delete($id){
        $user = user::FindOrFail($id);
        $user -> delete();
        return redirect()->route('admin.index')->with('status', 'User deleted successfully');
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

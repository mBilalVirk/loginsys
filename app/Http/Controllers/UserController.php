<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;

class UserController extends Controller
{

//  public function fetch()
//     {
//         $user = auth()->user();
//          $users = user::all();
//         return view('admin.index', compact('users','user')); 
       
//     }
    public function dashboard()
    {
        $user = auth()->user();

        //Show user's and friends posts on dashboard page
        $posts = Post::whereIn('user_id', function ($query) use ($user) {
            $query->select('friend_id')
                  ->from('friends')
                  ->where('user_id', $user->id)
                  ->where('status', 'accepted');
        })->orWhereIn('user_id', function ($query) use ($user) {
            $query->select('user_id')
                  ->from('friends')
                  ->where('friend_id', $user->id)
                  ->where('status', 'accepted');
        })->orWhere('user_id', $user->id)
          ->orderBy('created_at', 'desc')
          ->get();
        // $posts = Post::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        return view('dashboard', compact('user', 'posts'));
    }
    public function logout(Request $request)
    {
        auth()->logout();
        return redirect('/');
    }
    public function registerUser(Request $request)
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

        auth()->login($user);

        return redirect('/dashboard');
    }   
    public function loginUser(Request $request)
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
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.login')->with('status','Admin must login with this page');
    }else{

        return redirect('/dashboard');
    }
}


        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }   
    public function updateProfile(Request $request)
    {
        

        $user = auth()->user();

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
             'photo'=> 'image|mimes:jpeg,png,jpg|max:2048' 
        ],[
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'This email is already registered',
        ]);
        if ($request->hasFile('photo')) {

    $imageName = time().'_'.$request->photo->getClientOriginalName();
    $request->photo->move(public_path('images'), $imageName);

    
    $validatedData['photo'] = 'images/'.$imageName;
} 
        $user->update($validatedData);

        return redirect('/dashboard')->with('status', 'Profile updated successfully.');
    }
    public function updatePassword(Request $request)
{
    $user = auth()->user();

    $request->validate([
        'current_password' => 'required|string',
        'new_password' => 'required|string',
        
        
    ], [
        'current_password.required' => 'Old Password is required',
        'new_password.required' => 'New Password is required',
    ]);

    
    if (!Hash::check($request->current_password, $user->password)) {
        return back()->withErrors([
            'current_password' => 'Old password does not match'
        ]);
    }

    // Update password
    $user->password = Hash::make($request->new_password);
    $user->save();

    return redirect('/dashboard')->with('status', 'Password successfully updated');
}
    public function userProfile($id)
    {
        $user = User::findOrFail($id);
        $posts = Post::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();   
        return view('user.profile', compact('user', 'posts'));
    }   
   public function updatePhoto(Request $request)
   {
       $user = auth()->user();

       $request->validate([
           'photo'=> 'required|image|mimes:jpeg,png,jpg|max:2048'
       ],[
           'photo.required'=>'Photo is Required',
       ]);

       if ($request->hasFile('photo')) {
           if ($user->photo && file_exists(public_path($user->photo))) {
               unlink(public_path($user->photo));
           }

           $imageName = time().'_'.$request->photo->getClientOriginalName();
           $request->photo->move(public_path('images'), $imageName);

           $user->photo = 'images/'.$imageName;
           $user->save();
       }

       return redirect()->back()->with('status', 'Profile photo updated successfully.');
   }
   public function updateName(Request $request)
   {
       $user = auth()->user();

       $request->validate([
           'name'=> 'required|string|max:255'
       ],[
           'name.required'=>'Name is Required',
       ]);

       
           $user->name = $request->name;
           $user->save();
       

       return redirect()->back()->with('status', 'Name updated successfully.');
   }
   public function updateEmail(Request $request)
   {
       $user = auth()->user();  
         $request->validate([
              'email'=> 'required|string|email|max:255|unique:users,email,' . $user->id,
         ],[
              'email.required'=>'Email is Required',
              'email.email' => 'Please enter a valid email address',
              'email.unique' => 'This email is already registered',
         ]);
    
         
              $user->email = $request->email;
              $user->save();
              return redirect()->back()->with('status', 'Email updated successfully.');

            }
            
    
}

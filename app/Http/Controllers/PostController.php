<?php

namespace App\Http\Controllers;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function store(Request $request){
        $validatedData = $request->validate([
           
           
            'content'=>'required|string',
            'photo'=> 'mimes:jpeg,png,jpg|max:2024'
        ],[
            
            'content.required'=>'You must need add a content',

        ]);
       
        if ($request->hasFile('photo')) {
    
        $imageName = time().'_'.$request->photo->getClientOriginalName();
        $request->photo->move(public_path('images/posts'), $imageName);
        $validatedData['photo'] = 'images/posts/' . $imageName;
}
    // dd($validatedData['photo']);
// Create the post
$post = Post::create([
    'user_id' => auth()->id(),
    'content' => $validatedData['content'],
    'photo' => $validatedData['photo'],
]);
    return redirect('/dashboard')->with('status','Post created successfully');
    }   

    public function delete($id){
        $post=Post::findOrFail($id);
        $post->delete();
        unlink(public_path($post->photo));
        return redirect('/dashboard')->with('status','Post has been deleted successfully');
    }
   
}

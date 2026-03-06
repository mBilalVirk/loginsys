<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request){
        $validatedData = $request->validate([
           
           
            'content'=>'required|string',
           'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'

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
    'photo' => $validatedData['photo'] ?? null,
]);
    return response()->json([
        'success' => true,
        'message' => 'Post has been created successfully',
        'data' => $post
    ], 201);
    } 
public function createComment(Request $comment){
       $validatedData = $comment->validate([
            'post_id'=> 'required | string ',
            'comment' => 'required | string | max:200',
            'parent_id'=> 'nullable | string '
       ],[
            'post_id.required'=> 'Required Post id does not exist',
            'comment.required'=> 'You must need type a comment'
       ]);
        
       $comment = Comment::create([
        'user_id'=> auth()->id(),
        'comment'=> $validatedData['comment'],
        'post_id'=> $validatedData['post_id'],
        'parent_id'=> $validatedData['parent_id'] ?? null,
       ]);
       return response()->json([
        'success' => true,
        'message' => 'Comment',
        'data' => $comment
       ],201);
    }
    public function deleteComment($id){
                $comment = Comment::FindOrFail($id);
                 $user_id = $comment->user_id;
                if(auth()->id() == $user_id){
                $comment->delete();
                return response()->json([
                    'success' => true,
                    'message' => 'Comment has been Deleted',
                    
                ],201);
                }else{
                    return response()->json([
                    'success' => false,
                    'message' => 'You cant Delete This comment',
                    
                ],400);

                }

            }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

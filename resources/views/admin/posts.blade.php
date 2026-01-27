@extends('layouts.admin')

@section('title', 'Admin Dashboard')
<script>
    function openImage(imgElement) {
        var modal = document.getElementById('imageModal');
        var modalImg = document.getElementById('modalImg');

        modal.style.display = "flex";
        modalImg.src = imgElement.src;
}
    function closeImage() {
        document.getElementById('imageModal').style.display = "none";
}
</script>
<style>
    #imageModal {
    display: none;
    /* hidden by default */
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.9);
    justify-content: center;
    align-items: center;
}

/* Modal Image */
#modalImg {
    max-width: 90%;
    max-height: 90%;
    margin: auto;
    display: block;
    border-radius: 10px;
}

/* Close Button */
#closeBtn {
    position: absolute;
    top: 20px;
    right: 35px;
    color: white;
    font-size: 40px;
    font-weight: bold;
    cursor: pointer;
}


</style>
@section('content')
 <!-- Top Bar -->
@if(session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Dashboard : Posts</h1>
    </div>
<div class="container mt-4">

   @if($posts->isEmpty())
    <p>No posts available.</p>
@else
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>User name</th>
                <th>User Email</th>
                <th>Post ID</th>
                <th>Image</th>
                <th>Content</th>
                <th>Comments</th>
                <th>Created</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            @foreach($posts as $user)
                @foreach($user->posts as $post)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $post->id }}</td>
                        <td>
                            @if($post->photo)
                                <img src="{{ asset($post->photo) }}" class="post-image" width="300" style="width:30px; margin-top:10px; border-radius:8px;" onclick="openImage(this)">
                            @else
                                N/A
                            @endif
                        </td>
                        <td>{{ \Illuminate\Support\Str::limit($post->content, 50) }}</td>
                        <td>
                           
                                
                                <button
                                    type="button"
                                    class="btn btn-primary"
                                    data-toggle="modal"
                                    data-target="#viewComments{{$post->id}}"
                                    >
                                    View : {{$post->comments->count()}}
                                </button>

                                <!-- The Modal -->
                            <div class="modal" id="viewComments{{$post->id}}">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h4 class="modal-title">Comments</h4>
                                            <button
                                                type="button"
                                                class="close"
                                                data-dismiss="modal"
                                            >
                                                &times;
                                            </button>
                                        </div>

                                        <!-- Modal body -->
                                        <div class="modal-body">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Comments</th>
                                                        <th>Posts</th>
                                                        <th>Comments By</th>
                                                        <th>Edit</th>
                                                        <th>Delete</th>
                                                    </tr>
                                                </thead>
                                                    <tbody>
                                                       @foreach($post->comments as $comment)
                                                      
                                                            <tr>
                                                                <td>{{ $comment->comments }}</td>
                                                                <td>{{ $comment->post_id }}</td>
                                                                <td>{{$comment->user->name }}</td>
                                                                <td></td>
                                                                <td> 
                                                                    <form
                                                                        action=""
                                                                        action=""
                                                                        method="POST"
                                                                        onsubmit="return confirm('Are you sure you want to delete this post?');"
                                                                    >
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                                    </form>
                                                                 </td>
                                                            </tr>
                                                        @endforeach

                                                    </tbody>

                                            </table>
                                            
                                        </div>

                                        <!-- Modal footer -->
                                        <div class="modal-footer">
                                            <button
                                                type="button"
                                                class="btn btn-danger"
                                                data-dismiss="modal"
                                            >
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </td>
                        <td>{{ $post->created_at->diffForHumans() }}</td>
                        <td>
                            <button
                                    type="button"
                                    class="btn btn-primary"
                                    data-toggle="modal"
                                    data-target="#editPost{{ $post->id }}"
                                    >
                                    Edit
                            </button>
                            <!-- The Modal -->
                            <div class="modal" id="editPost{{ $post->id }}">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h4 class="modal-title">Edit Post</h4>
                                            <button
                                                type="button"
                                                class="close"
                                                data-dismiss="modal"
                                            >
                                                &times;
                                            </button>
                                        </div>

                                        <!-- Modal body -->
                                        <div class="modal-body">

                                            <form
                                                action="{{route('editpost', $post->id)}}"
                                                method="POST"
                                                enctype="multipart/form-data"
                                            >
                                                @csrf
                                                @method('POST')
                                                <div class="form-group">
                                                    <label for="content">Content:</label>
                                                    <textarea
                                                        name="content"
                                                        id="content"
                                                        required
                                                        class="form-control"
                                                        style="height: 200px;"
                                                    >{{ $post->content }}</textarea>
                                                    <label for="photo">Image:</label>
                                                    <input
                                                        type="file"
                                                        name="photo"
                                                        id="photo"
                                                        accept="image/*"
                                                        class="form-control"
                                                    >
                                                    <input
                                                        type="hidden"
                                                        name="existing_photo"
                                                        value="{{ $post->photo }}"
                                                    >
                                                    <button type="submit" class="btn btn-primary mt-2">Update</button>
                                                    </form>
                                        </div>

                                        <!-- Modal footer -->
                                        <div class="modal-footer">
                                            <button
                                                type="button"
                                                class="btn btn-danger"
                                                data-dismiss="modal"
                                            >
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <form
                                action="{{ route('delpost', $post->id) }}"
                                method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this post?');"
                            >
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
@endif


<div id="imageModal" onclick="closeImage()">
    <span id="closeBtn">&times;</span>
    <img id="modalImg" src="">
</div>

    </div>
@endsection

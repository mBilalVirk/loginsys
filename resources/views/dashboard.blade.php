@extends('layouts.user')

@section('title', 'Home')
@include('layouts.navbar')
@section('head')
        <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        />
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
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


        document.querySelector('.edit-icon').addEventListener('click', function() {
            document.getElementById('photoUpload').click();
        });

        function postUpdate() {
            // Implement post update functionality here
            getElementById('content').style.display = "none";

            alert('Post update functionality to be implemented.');
        }
        setTimeout(() => {
            document.querySelector('.alert')?.remove();
        }, 4000);
    </script>
@endsection
@section('content')
<head>
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
</head>
<div class="profile-page">

    <!-- PROFILE CARD -->
    <div class="profile-card">
        <img src="{{ auth()->user()->photo 
                ? asset('/' . auth()->user()->photo) 
                : asset('images/default-user.png') }}" 
             class="profile-img">

        <h2>{{ auth()->user()->name }}</h2>
        <p>{{ auth()->user()->email }}</p>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="btn logout-btn">Logout</button>
        </form>
         <a href="{{ route('dashboard') }}" class="link">Home</a>
         <a href="{{ route('userMessages') }}" class="link">Messages</a>
            <a href="{{ route('friends') }}" class="link">Friends</a>
         <a href="{{ route('user.profile', auth()->user()->id) }}" class="link">Profile</a>
        <a href="{{ route('passwordupdate') }}" class="link">Change Password</a>
    </div>

    <!-- UPDATE PROFILE FORM -->
    <div class="update-profile card">
        <h3>Create a Post</h3>

        @if($errors->any())
            <div class="alert error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        @if(session('status'))
            <div class="alert success">
                {{ session('status') }}
            </div>
        @endif
        <form action="{{ route('post') }}" method="POST" enctype="multipart/form-data">
            @csrf
           @method('POST')
            <div class="form-group">
                <label for="content">Content</label>
                <textarea name="content" id="content" required>{{ old('content') }}</textarea>
            </div>
            <div class="form-group">
                <label for="photo">Image (optional)</label>
                <input type="file" name="photo" id="image" accept="image/*">
            <button type="submit" class="btn">Create Post</button>
        </form>
    </div>
    <div>

    </div>

    <div>
        <h2>All Post</h2>
    </div>
    <div class="update-profile card">
        
        @foreach($posts as $post)
        <div style="display:flex; align-items:center; gap:10px; margin-bottom:10px;">
            <img src="{{ auth()->user()->photo 
                ? asset('/' . auth()->user()->photo) 
                : asset('images/default-user.png') }}" 
             class="mini-img">
            <p>{{ $post->user->name }}</p>
            <p>Posted at {{ $post->created_at->format('M d, Y') }}</p>
        </div>
    <div class="post-card ">
        <p style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI',
             Roboto, Helvetica, Arial, sans-serif;">{{ $post->content }}</p>
        
        @if($post->photo)
            <img src="{{ asset($post->photo) }}" width="300" style="margin-top:10px; border-radius:8px; ">
        @endif
        <div>
        </div>
        <hr>
        <div class="comment-section">
            @foreach($post->comments as $comment)
                     <div class="comment" style="display:flex; align-items:center; gap:10px;">
                            <p style="margin-top:-10px;margin-bottom:-5px;">{{$comment->user->name}} : {{$comment->comment}}</p>
                            <i class="fa-regular fa-pen-to-square" style="margin-bottom: 5px; cursor: pointer;"
                            data-toggle="modal"
                            data-target="#editComment{{$comment->id}}"
                            ></i>
                            <!-- The Modal -->
                            <div class="modal" id="editComment{{$comment->id}}">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h4 class="modal-title">Edit Comment</h4>
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
                                                action="{{route('commentUpdate',$comment->id)}}"
                                                method="POST" enctype="multipart/form-data"
                                            >
                                                @csrf
                                                @method('POST')
                                                <div class="form-group">
                                                    <label for="comment">Comment</label>
                                                    <textarea
                                                        name="comment"
                                                        id="comment"
                                                        class="form-control"
                                                        rows="3"
                                                        required
                                                    >{{ $comment->comment }}</textarea> 
                                                    <input type="submit" value="Update Comment" class="btn btn-success mt-2"/>
                                            </form>
                                        </div>
                                        <!-- Modal footer -->
                                        <div class="modal-footer">
                                            <button
                                                type="button"
                                                class="btn btn-danger"
                                                data-dismiss="modal"
                                                style="margin-right: 10px; background-color: blue;"
                                            >
                                                Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                            <form action="{{route('user.deleteComment',$comment->id)}}" method="post" onsubmit="return confirm('Are you sure you want to delete this comment?');" style="all:unset; display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="all:unset; background:none; border:none;cursor:pointer; color:black; font-size:16px; margin-left:5px;">
                                    
                                    <i class="fa-solid fa-delete-left" style="margin-bottom: 5px; cursor: pointer;"  ></i>
                                </button>
                                </form>
                            
                            </div>
                            
                @endforeach
            <hr>
            <form action="{{route('giveComment')}}" method="POST">
                @csrf
                <label for="comment">Give comments to this post:</label>
                <div class="form-group" style="display:flex;">
                    <input type="text" id='comment' name="comment" placeholder="Write your comment here..." style="width:100%;" required>
                    <input type="text" name="post_id" id="post_id" value="{{$post->id}}" hidden>
                   <input type="submit" value="➡️"style="width:50px;">

                </div>
            </form>
        </div>
        @if($post->user->name == auth()->user()->name)
            <form action="{{url('post/delete/'.$post->id)}}" method="post">
                @csrf
                @method('DELETE')
                <button class="delete-btn btn btn-danger mt-2"
                            onclick="return confirm('Are you sure you want to delete this Post?')">
                            Delete
                        </button>
            </form>
            @else
            <p></p>
            @endif
    </div>
@endforeach
    </div>
@endsection

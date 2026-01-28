@extends('layouts.user')

@section('title', 'Profile')
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
    @if(session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert"
            style="position: fixed; top: 1rem; right: 1rem; z-index: 1000; min-width: 300px;">
            {{ session('status') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
@endif

   
    <div class="container">
    <!-- Profile Section -->
        <div class="profile-card">
            <div class="profile-photo-wrapper">
                <img src="{{ auth()->user()->photo 
                        ? asset(auth()->user()->photo) 
                        : asset('images/default-user.png') }}" 
                    alt="Profile" class="profile-img">

                <span class="edit-icon material-symbols-outlined" onclick="document.getElementById('photoUpload').click()">edit</span>

                <!-- Optional: hidden file input -->
                <form action="{{ route('user.updatePhoto') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <input type="file" id="photoUpload" name="photo" style="display:none;" onchange="this.form.submit()">
                </form>
            </div>

            <div class="profile-info-field">
                    <h2>{{ auth()->user()->name }}</h2>
                
                    <span class="text-edit-icon material-symbols-outlined" onclick="this.style.display=''; document.getElementById('nameupdate').style.display='inline-block'; document.getElementById('nameupdate').focus();">edit</span>
                    <form action="{{ route('user.updateName') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <input type="text" id="nameupdate" name="name" style="display:none;" onchange="this.form.submit()">
                    </form>
            </div>


            <div class="profile-info-field">
                <h2>{{ auth()->user()->email }}</h2>
                <span class="text-edit-icon material-symbols-outlined" onclick="this.style.display=''; document.getElementById('emailupdate').style.display='inline-block'; document.getElementById('emailupdate').focus();">edit</span>
                <form action="{{ route('user.updateEmail') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <input type="email" id="emailupdate" name="email" style="display:none;" onchange="this.form.submit()">
                </form>

                <a href="{{ route('dashboard') }}">Home</a>
                <a href="{{ route('friends') }}" class="link">Friends</a>
                <a href="#">Messages</a>
                <a href="{{ route('passwordupdate') }}" class="link">Change Password</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn logout-btn btn-primary">Logout</button>
                </form>
           
            
             </div>
       
    </div>

    <!-- Posts Section -->
     
    <div class="posts-section">
         <div class="form">
                    <form action="{{ route('post') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <div class="form-group">
                            <label for="content">Content</label>
                            <textarea name="content" id="content" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="photo">Image (optional)</label>
                            <input type="file" name="photo" id="image" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-primary">Create Post</button>
                    </form>
         </div>
      <hr>
        <div class="posts">
            @forelse(auth()->user()->posts as $post)
            <div class="post-card" style="position:relative;">
                <span class="editPost-icon material-symbols-outlined"
                
                    data-toggle="modal"
                    data-target="#editPost{{$post->id}}"
                    style="margin-right: 10px; cursor: pointer;position: absolute;top:1rem; right:1rem;"

                >
                    edit
                </span>
                
                    
                {{-- Post Image --}}
                @if($post->photo)
                    <img src="{{ asset($post->photo) }}" class="post-image" width="300" style="margin-top:10px; border-radius:8px;" onclick="openImage(this)">
                @endif

                {{-- Post Content --}}
                <!-- <textarea rows="3" disabled name="content">{{ $post->content }}</textarea> -->
                <p>{{ $post->content }}</p>

                <span>{{ $post->created_at->diffForHumans() }}</span>
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
                    <input type="submit" value="⤴️"style="width:50px;">

                    </div>
                </form>
        </div>
            <!-- <div class="container">    -->
                <div class="post-actions" >      
                    <!-- The Modal -->
                    <div class="modal" id="editPost{{$post->id}}">
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
                                        action="{{ route('editpost', $post->id) }}"
                                        method="POST" enctype="multipart/form-data"
                                    >
                                        @csrf
                                        @method('POST')
                                        <div class="form-group">
                                            <label for="content">Content</label>
                                            <textarea
                                                name="content"
                                                id="content"
                                                class="form-control"
                                                rows="3"
                                                required
                                            >{{ $post->content }}</textarea>
                                            <label for="photo">Image</label>
                                            <input
                                                type="file"
                                                name="photo"
                                                id="photo"
                                                class="form-control"
                                                accept="image/*"
                                            />
                                            <input type="submit" value="Update Post" class="btn btn-success mt-2"/>
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
            <!-- End of model -->
            <div class="post-actions">
                <form action="{{url('post/delete/'.$post->id)}}" method="post">
                    @csrf
                    @method('DELETE')
                    <button class="delete-btn" style="margin-right: 10px; background-color: red;"
                                onclick="return confirm('Are you sure you want to delete this Post?')">
                                Delete
                    </button>
                </form>  
            </div>
        </div>
    </div>
    @empty
        <p>No posts yet.</p>
    @endforelse
    

</div>
<!-- Fullscreen Image Modal -->
<div id="imageModal" onclick="closeImage()">
    <span id="closeBtn">&times;</span>
    <img id="modalImg" src="">
</div>


@endsection

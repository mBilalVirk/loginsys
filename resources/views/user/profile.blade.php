@extends('layouts.user')

@section('title', 'Profile')
@section('head')
     <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
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
</script>
@endsection
@section('content')



    
    @if(session('status'))
    <div class="container">
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-top: 20px; z-index: 2;">
        {{ session('status') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
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
        <!-- <div class="profile-info">
            <label>Name</label>
            <input type="text" value="{{ auth()->user()->name }}">

            <label>Email</label>
            <input type="email" value="{{ auth()->user()->email }}">

            <label>Bio</label>
            <textarea rows="3"></textarea>

            <button>Update Profile</button>
        </div> -->
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
      
        <div class="posts">
            @forelse(auth()->user()->posts as $post)
            <div class="post-card">
                <span class="editPost-icon material-symbols-outlined"
                
                    data-toggle="modal"
                    data-target="#editPost{{$post->id}}"
                    style="margin-right: 10px; cursor: pointer;position: relative;top: -180px;right: -810px;"

                >
                    edit
                </span>
                <span class="editPost-icon material-symbols-outlined"
                
                    data-toggle="modal"
                    data-target="#editPost{{$post->id}}"
                    style="margin-right: 10px; cursor: pointer;position: relative;top: -180px;right: -810px;"

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

                <div class="container">
                <div class="post-actions" >
                        <!-- <button style="color: blue;" onclick="postUpdate()">Edit</button>    -->
                    <!-- start of model -->
            
                <!-- Button to Open the Modal -->
                <!-- <span class="edit-icon material-symbols-outlined" data-target="#editPost{{$post->id}}">edit</span> -->
                <!-- <span class="editPost-icon material-symbols-outlined"
                
                    data-toggle="modal"
                    data-target="#editPost{{$post->id}}"
                    style="margin-right: 10px; background-color: blue;"

                >
                    edit
                </span> -->

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
    </div>

</div>
<!-- Fullscreen Image Modal -->
<div id="imageModal" onclick="closeImage()">
    <span id="closeBtn">&times;</span>
    <img id="modalImg" src="">
</div>


@endsection

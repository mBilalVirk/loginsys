@extends('layouts.user')

@section('title', 'Profile')
@include('layouts.navbar')
@section('content')

<!DOCTYPE html>
<html lang="en">
<head>

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

</head>
<body>

    
<div class="container">
    @if(session('status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-top: 20px; z-index: 100;">
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
     <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="btn logout-btn">Logout</button>
        </form>

        <a href="{{ route('friends') }}" class="link">Friends</a>
        <a href="{{ route('passwordupdate') }}" class="link">Change Password</a>
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
     
    <div class="posts">
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
       
        <div class="posts">
            @forelse(auth()->user()->posts as $post)
        <div class="post-card">

            {{-- Post Image --}}
            @if($post->photo)
                <img src="{{ asset($post->photo) }}" class="post-image" width="300" style="margin-top:10px; border-radius:8px;" onclick="openImage(this)">
            @endif

            {{-- Post Content --}}
            <!-- <textarea rows="3" disabled name="content">{{ $post->content }}</textarea> -->
             <p>{{ $post->content }}</p>

             <span>{{ $post->created_at->diffForHumans() }}</span>
            <div class="post-actions" >
                <form action="" method="POST" style="display: inline;">
                    @csrf
                    @method('POST')
                    <button style="color: blue;" onclick="postUpdate()">Edit</button>   
                    
                </form>
</div>
<div class="post-actions">
                <form action="{{url('post/delete/'.$post->id)}}" method="post">
                @csrf
                @method('DELETE')
                <button class="delete-btn"
                            onclick="return confirm('Are you sure you want to delete this Post?')">
                            Delete
                        </button>
            </form>  
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
</body>
</html>

@endsection

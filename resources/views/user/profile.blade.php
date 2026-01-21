@extends('layouts.user')

@section('title', 'Profile')
@include('layouts.navbar')
@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=edit" />
    
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


</script>

</head>
<body>

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
        @foreach($posts as $post)
        <div class="posts">

    @forelse(auth()->user()->posts as $post)
        <div class="post-card">

            {{-- Post Image --}}
            @if($post->photo)
                <img src="{{ asset($post->photo) }}" class="post-image" width="300" style="margin-top:10px; border-radius:8px;" onclick="openImage(this)">
            @endif

            {{-- Post Content --}}
            <textarea rows="3" disabled>{{ $post->content }}</textarea>

            <div class="post-actions">
                <span>{{ $post->created_at->diffForHumans() }}</span>
                <button>Edit</button>
            </div>

        </div>
    @empty
        <p>No posts yet.</p>
    @endforelse

</div>

        @endforeach
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

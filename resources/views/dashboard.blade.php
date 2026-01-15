@extends('layouts.user')

@section('title', 'Profile')

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
    <div class="update-profile card">
        <h3>Your Posts</h3>
        @foreach($posts as $post)
        <div style="display:flex; align-items:center; gap:10px; margin-bottom:10px;">
            <img src="{{ auth()->user()->photo 
                ? asset('/' . auth()->user()->photo) 
                : asset('images/default-user.png') }}" 
             class="mini-img">
            <p>{{ auth()->user()->name }}</p>
            <p>Posted at {{ $post->created_at->format('M d, Y') }}</p>
        </div>
    <div class="post-card ">
        <p style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI',
             Roboto, Helvetica, Arial, sans-serif;">{{ $post->content }}</p>
        
        @if($post->photo)
            <img src="{{ asset($post->photo) }}" width="300" style="margin-top:10px; border-radius:8px; ">
        @endif
        <div>
            <form action="{{url('post/delete/'.$post->id)}}" method="post">
                @csrf
                @method('DELETE')
                <button class="delete-btn"
                            onclick="return confirm('Are you sure you want to delete this Post?')">
                            Delete
                        </button>
            </form>
        </div>
        <hr>
    </div>
@endforeach
    </div>
@endsection

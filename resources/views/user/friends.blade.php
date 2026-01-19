@extends('layouts.user')

@section('title', 'Friends')
<head>
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
</head>

<h1>All users</h1>

@section('content')
@if(session('status'))
    <div class="alert success">
        {{ session('status') }}
    </div>
@endif
<div class="friends-list">
@foreach($friends as $friend)

   
    <div class="friend-card">
        <img src="{{ $friend->photo 
                ? asset('/' . $friend->photo) 
                : asset('images/default-user.png') }}" 
             class="friend-img" 
             class="profile-img">

        <h2>{{ $friend->name }}</h2>
        <p>{{ $friend->email }}</p>
        
       @foreach($friendRequests as $request)
    {{ $request->status }}
@endforeach
            <form action="{{route('send.request',$friend->id)}}" method="POST">
                @csrf
            <button class="btn logout-btn">Send Friend Request</button>
            </form>
 

        

    </div>
    @endforeach
</div>
@endsection
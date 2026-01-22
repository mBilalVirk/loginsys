@extends('layouts.user')
@section('title', 'Profile')
@include('layouts.navbar')
@section('content')
<!-- my friends -->
<div style="display:flex;justify-content:center;align-items:center;flex-direction:column;gap:20px; margin-top:20px;">
    @if(session('status'))
    <div class="alert success">
        {{ session('status') }}
    </div>
    @endif

    <div class="search-bar" style="width:500px">
        <form action="{{ route('search.user') }}" method="POST" style="display: flex; align-items: flex-start; gap: 8px;width: 100%;">
            @csrf
            @method('POST')

            <input type="search" name="searchUser" placeholder="Search friends..." style=" padding: 8px;" />

            <button type="submit" style="cursor: pointer;">
                Search
            </button>
        </form>
       
    </div>

    <h1>Your Friends</h1>
    <div class="friends-list">
        @foreach($acceptedFriends as $friendRequest)
        @php
        // Determine who the friend is
        if ($friendRequest->user_id == auth()->id()) {
        $friendUser = $friendRequest->receiver; // You sent the request
        } else {
        $friendUser = $friendRequest->sender; // They sent the request
        }

        @endphp

        <div class="friend-card">
            <img src="{{ $friendUser->photo ? asset($friendUser->photo) : asset('images/default-user.png') }}"
                class="friend-img">

            <h2>{{ $friendUser->name }}</h2>
            <p>{{ $friendUser->email }}</p>

            <form action="{{ route('unfriend.request', $friendRequest->id) }}" method="POST">
                @csrf
                <button class="btn logout-btn">Unfriend</button>
            </form>
        </div>
        @endforeach

    </div>
    <!-- Sent Friend Requests -->
    <h1>Sent Friend Requests</h1>
    <div class="friends-list">
        @foreach($sentFriendRequests as $user)
        @foreach($user->sentFriendRequests as $request)

        <div class="friend-card">
            <img src="{{ $request->receiver && $request->receiver->photo
                    ? asset('/' . $request->receiver->photo)
                    : asset('images/default-user.png') }}" class="friend-img">

            <h2>{{ $request->receiver->name }}</h2>
            <p>{{ $request->receiver->email }}</p>
            <form action="{{route('delete.request',$request->id)}}" method="POST">
                @csrf
                @method('DELETE')
                <button class="btn logout-btn">Cancel Request</button>
            </form>
        </div>
        @endforeach
        @endforeach
    </div>
    <!-- receivedFriendRequests -->
    <h1>Received Friend Requests</h1>
    <div class="friends-list">
        @foreach($receivedFriendRequests as $user)
        @foreach($user->receivedFriendRequests as $request)

        <div class="friend-card">
            <img src="{{ $request->sender && $request->sender->photo
                    ? asset('/' . $request->sender->photo)
                    : asset('images/default-user.png') }}" class="friend-img">

            <h2>{{ $request->sender->name }}</h2>
            <p>{{ $request->sender->email }}</p>
            <form action="{{route('delete.request',$request->id)}}" method="POST">
                @csrf
                @method('DELETE')
                <button class="btn logout-btn">Cancel Request</button>
            </form>
            <form action="{{route('accept.request',$request->id)}}" method="POST">
                @csrf
                @method('POST')
                <input type="submit" class="btn logout-btn" value="Accept Friend Request">

            </form>
        </div>
        @endforeach
        @endforeach
    </div>

    <h1>All others</h1>

    <div class="friends-list">
        @foreach($friends as $friend)


        <div class="friend-card">
            <img src="{{ $friend->photo 
                ? asset('/' . $friend->photo) 
                : asset('images/default-user.png') }}" class="friend-img" class="profile-img">

            <h2>{{ $friend->name }}</h2>
            <p>{{ $friend->email }}</p>


            <form action="{{route('send.request',$friend->id)}}" method="POST">
                @csrf
                
                <button class="btn logout-btn">Send Friend Request</button>
            </form>




        </div>
        @endforeach
    </div>

</div>
@endsection
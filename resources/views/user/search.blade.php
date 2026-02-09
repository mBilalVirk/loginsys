@extends('layouts.user')
@section('title', 'Search Users')
@include('layouts.navbar')
@section('content')
<!-- search users -->
<div class="search-bar" style="width:500px">
<div style="display:flex;justify-content:center;align-items:center;flex-direction:column;gap:20px; margin-top:20px;position:relative;">
    @if(session('status'))
    <div class="alert success" style="margin-top:20px; z-index:100;">
        {{ session('status') }}
    </div>
    @endif
        <form action="{{ route('search.user') }}" method="GET" style="display: flex; align-items: flex-start; gap: 8px;width: 100%;">
            <input type="search" value="{{ request('searchUser') }}" name="searchUser" placeholder="Search friends..." style=" padding: 8px;" />
            <input type="submit" value="Search">
        </form>
        @if($users->isEmpty())
        <p>No users found.</p>
        @else
    <h2>Search Results:</h2>    
    <div class="friends-list">
        @foreach($users as $user)
        <div class="friend-card">
            <img src="{{ $user->photo 
                ? asset('/' . $user->photo) 
                : asset('images/default-user.png') }}" class="friend-img" class="profile-img"/>
            <h2>{{ $user->name }}</h2>
            <p>{{ $user->email }}</p>
           @if($friend_id->contains($user->id))
            <h6>Already Friend or Initiate</h6>
            
           @else
                <form action="{{route('sendRequestSearch',$user->id)}}" method="POST">
                @csrf
                <input type="submit" value="Send Friend Request">
            </form>
           @endif
            
        </div>
        @endforeach
    </div>
    @endif
</div>
</div>
@endsection
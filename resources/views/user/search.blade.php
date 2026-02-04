@extends('layouts.user')
@section('title', 'Search Users')
@include('layouts.navbar')
@section('content')
<!-- search users -->
<div class="search-bar" style="width:500px">
    @if(session('status'))
    <div class="alert success" style="margin-top:20px; z-index:100;">
        {{ session('status') }}
    </div>
    @endif
        <form action="{{ route('search.user') }}" method="POST" style="display: flex; align-items: flex-start; gap: 8px;width: 100%;">
           @csrf
            <input type="search" name="searchUser" placeholder="Search friends..." style=" padding: 8px;" />
            <button  style="cursor: pointer;">
                Search
            </button>
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
            <form action="{{route('sendRequestSearch', $user->id)}}" method="GET">
                
                <button class="btn primary-btn">Send Friend Request</button>
            </form>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
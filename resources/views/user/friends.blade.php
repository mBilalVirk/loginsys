@extends('layouts.user')
@section('title', 'Profile')


@section('content')

    {{-- side bar --}}
    <div class="profile-page">
        <div id="side-nav" class="side-nav">
            <div class="side-nav-title">Friends</div>
            <div class="side-nav-links">

                <a href="{{ route('friends') }}" class="side-nav-link active">
                    <span class="side-nav-icon"><i class="fas fa-users"></i></span>
                    <span class="side-nav-text">Friends</span>
                </a>
                <a href="#sent-requests" class="side-nav-link">
                    <span class="side-nav-icon"><i class="fas fa-paper-plane"></i></span>
                    <span class="side-nav-text">Sent Requests</span>
                </a>
                <a href="#received-requests" class="side-nav-link">
                    <span class="side-nav-icon"><i class="fas fa-inbox"></i></span>
                    <span class="side-nav-text">Received Requests</span>
                </a>
                <a href="#suggestions" class="side-nav-link">
                    <span class="side-nav-icon"><i class="fas fa-lightbulb"></i></span>
                    <span class="side-nav-text">Suggestions</span>
                </a>
                <a href="#all-friends" class="side-nav-link">
                    <span class="side-nav-icon"><i class="fas fa-user-friends"></i></span>
                    <span class="side-nav-text">All Friends</span>
                </a>

            </div>
        </div>
        {{-- end of side bar --}}
        <!-- my friends -->
        <div class="update-profile post-card">
            @if (session('status'))
                <div class="alert success">
                    {{ session('status') }}
                </div>
            @endif

            <div class="search-bar" style="width:500px ">
                <form action="{{ route('search.user') }}" method="POST"
                    style="display: flex; align-items: flex-start; gap: 8px;width: 100%;">
                    @csrf
                    @method('POST')

                    <input type="search" name="searchUser" placeholder="Search friends..." style=" padding: 8px;" />

                    <button type="submit" style="cursor: pointer;">
                        Search
                    </button>
                </form>

            </div>
            @if ($acceptedFriends->isNotEmpty())

                <h3 id="your-friends">Your Friends</h3>
                <div class="friends-list">
                    @foreach ($acceptedFriends as $friendRequest)
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
                <hr />
            @else
                {{ ' ' }}
            @endif
            @if ($sentFriendRequests->isNotEmpty())
                <!-- Sent Friend Requests -->
                <h3 id="sent-requests">Sent Friend Requests</h3>
                <div class="friends-list">
                    @foreach ($sentFriendRequests as $user)
                        @foreach ($user->sentFriendRequests as $request)
                            <div class="friend-card">
                                <img src="{{ $request->receiver && $request->receiver->photo
                                    ? asset('/' . $request->receiver->photo)
                                    : asset('images/default-user.png') }}"
                                    class="friend-img">

                                <h2>{{ $request->receiver->name }}</h2>
                                <p>{{ $request->receiver->email }}</p>
                                <form action="{{ route('delete.request', $request->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn logout-btn">Cancel Request</button>
                                </form>
                            </div>
                        @endforeach
                    @endforeach
                </div>
                <hr />
            @else
                {{ ' ' }}
            @endif
            @if ($receivedFriendRequests->isNotEmpty())
                <!-- receivedFriendRequests -->
                <h3 id="received-requests">Received Friend Requests</h3>
                <div class="friends-list">
                    @foreach ($receivedFriendRequests as $user)
                        @foreach ($user->receivedFriendRequests as $request)
                            <div class="friend-card">
                                <img src="{{ $request->sender && $request->sender->photo
                                    ? asset('/' . $request->sender->photo)
                                    : asset('images/default-user.png') }}"
                                    class="friend-img">

                                <h2>{{ $request->sender->name }}</h2>
                                <p>{{ $request->sender->email }}</p>
                                <form action="{{ route('delete.request', $request->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn logout-btn">Cancel Request</button>
                                </form>
                                <form action="{{ route('accept.request', $request->id) }}" method="POST">
                                    @csrf
                                    @method('POST')
                                    <input type="submit" class="btn logout-btn" value="Accept Friend Request">

                                </form>
                            </div>
                        @endforeach
                    @endforeach
                </div>
                <hr>
            @else
                {{ '' }}
            @endif


            <h3 id="all-friends">All others</h3>
            <div class="friends-list">
                @foreach ($friends as $friend)
                    <div class="friend-card">
                        <img src="{{ $friend->photo ? asset('/' . $friend->photo) : asset('images/default-user.png') }}"
                            class="friend-img" class="profile-img">

                        <h2>{{ $friend->name }}</h2>
                        <p>{{ $friend->email }}</p>


                        <form action="{{ route('send.request', $friend->id) }}" method="POST">
                            @csrf

                            <button class="btn logout-btn">Send Friend Request</button>
                        </form>




                    </div>
                @endforeach
            </div>


        </div>

    </div>

@endsection

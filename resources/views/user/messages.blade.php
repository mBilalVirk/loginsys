@extends('layouts.user')

@section('title', 'Home')

@section('content')
    <div class="container">
        <!-- Friends / Users List -->
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header fw-bold" style="display:flex;justify-content:center;align-items:center;gap:10px;">
                    <h3>Friends</h3>
                </div>

                @foreach ($friends as $friends)
                    @php

                        if ($friends->user_id == auth()->id()) {
                            $friendUser = $friends->receiver;
                        } else {
                            $friendUser = $friends->sender;
                        }

                    @endphp

                    <a href="{{ route('chat', $friendUser->id) }}" style="text-decoration:none; display:block;">
                        <div
                            style="width: 300px; background-color: white; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); padding: 10px; display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">

                            <!-- Left side: photo + name -->
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <img src="{{ asset('/' . $friendUser->photo) }}" alt="Profile Picture"
                                    style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover; border: 2px solid #ddd;">
                                <span style="font-weight: 500; font-size: 16px; color: #333;">{{ $friendUser->name }}</span>
                            </div>

                            <!-- Right side: message icon -->
                            <div style="position: relative; cursor: pointer;">
                                <i class="fa-regular fa-message" style="font-size: 20px; color: #555;"></i>
                                <!-- Notification badge -->
                                <span
                                    style="position: absolute; top: -5px; right: -5px; background-color: red; color: white; font-size: 12px; font-weight: bold; width: 18px; height: 18px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    3
                                </span>
                            </div>

                        </div>
                    </a>
                @endforeach


            </div>
        </div>
    </div>

@endsection

<!-- <h1>Update password</h1>
@if( $errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li style="color:red;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form action="{{route('updatePassword')}}" method="post">
    @csrf
    <label for="current_password">Enter Current Password</label>
    <input type="password" name="current_password" id="">
    <label for="new_password">Enter New Password</label>
    <input type="password" name="new_password" id="">
    
    <input type="submit" value="Update Password">
</form> -->
<head>
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
</head>

@section('title', 'Login')

@section('content')
@extends('layouts.guest')
<div class="container" style="width:30%; ">
<h1>Update Password</h1>
@if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li style="color:red;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form method="POST" action="{{route('updatePassword')}}">
    @csrf
    <label for="current_password">Enter Current Password</label>
    <input type="password" name="current_password" id="">
    <label for="new_password">Enter New Password</label>
    <input type="password" name="new_password" id="">
    
    <input type="submit" value="Update Password">
</form>

</div>
@endsection
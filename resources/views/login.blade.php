

<!-- <form action="{{ route('loginUser') }}" method="post">
    @csrf
   
    <div>
        Email: <input type="email" name="email">
    </div>
    <div>
        Password: <input type="password" name="password">
    </div>
    <div>
        <button type="submit">Login</button>
    </div>
    
    <div>
        Register: <a href="{{ route('register') }}">Register New User</a> 
    </div>
    <div>
        Admin Login: <a href="{{route('admin.login')}}">Admin Login</a>
    </div>
</form> -->
<head>
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
</head>

@section('title', 'Login')

@section('content')
@extends('layouts.guest')
<div class="container" style="width:30%; ">
<h1>Login</h1>
@if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li style="color:red;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form method="POST" action="{{ route('loginUser') }}">
    @csrf
    <input type="email" name="email" placeholder="Email">
    <input type="password" name="password" placeholder="Password">
    <button>Login</button>
</form>

    <div>
        Register: <a href="{{ route('register') }}">Register New User</a> 
    </div>
    <div>
        Admin Login: <a href="{{route('admin.login')}}">Admin Login</a>
    </div>

</div>
@endsection

@extends('layouts.admin')

@section('title', 'Settings')

@section('content')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Dashboard : Settings</h1>
        
    </div>
<div class="container mt-4">
   <!-- Admin can change their name and email from here and password -->
    <form method="POST" action="{{ route('admin.updateProfile') }}" enctype="multipart/form-data">
     @csrf
     @method('POST')
    
     <div class="mb-3">
          <label for="name" class="form-label">Name</label>
          <input type="text" class="form-control" id="name" name="name" value="{{ Auth::user()->name }}" required>
        </div>
        
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}" required>
        </div>
        
        <div class="mb-3">
            <label for="password" class="form-label">New Password</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm New Password</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
        </div>
        <div class="mb-3">
            <label for="photo" class="form-label">Profile Photo</label>
            <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
   
</div>


@endsection

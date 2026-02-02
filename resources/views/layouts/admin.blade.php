<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
     <title>@yield('title', 'Auth')</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            min-height: 100vh;
            background: #212529;
        }
        .sidebar a {
            color: #fff;
            text-decoration: none;
            display: block;
            padding: 10px 15px;
        }
        .sidebar a:hover {
            background: #343a40;
        }
        @media screen {
            
        }
    </style>
</head>
<body>

<!-- Top Navbar -->
<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1">Admin Panel</span>
        <span class="text-white">Welcome, {{ Auth::user()->role }} : {{ Auth::user()->name }}</span>
    
        
            <img src="{{ asset(Auth::user()->photo) }}"
                 width="40"
                 height="40"
                 class="rounded-circle object-fit-cover"
                 alt="Profile Picture"
                 />

        
         <a href="{{ route('admin.logout') }}"
           class="btn btn-danger"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Logout
        </a>
            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
            @csrf
            @method('POST')
        </form>
    </div>
   
    
</nav>

<div class="container-fluid">
    <div class="row">

        <!-- Sidebar -->
        <div class="col-md-2 sidebar p-0">
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a href="{{ route('admin.users') }}">Users</a>
            <a href="{{ route('admin.posts') }}">Posts</a>
            <a href="{{ route('admin.friends') }}">Friends</a>
            <a href="{{ route('admin.admins') }}">Add New Admin</a>
            <a href="{{ route('admin.DeletedData') }}">Deleted Record</a>
            <a href="{{ route('admin.setting') }}">Settings</a>
           
        </div>

        <!-- Main Content -->
        <div class="col-md-10 p-4">
           @yield('content')

        </div>

    </div>
</div>

</body>
</html>

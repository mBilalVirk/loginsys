<!DOCTYPE html>
<html lang="en">
@include('admin.chatbot')

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Auth')</title>
    {{-- favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="apple-mobile-web-app-capable" content="yes">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    {{-- favicon --}}
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        html {
            scroll-behavior: smooth;
        }

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
            color: #fff !important;
            border-left: 4px solid #0d6efd;
            border-right: 4px solid #0d6efd;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .sidebar a i {
            width: 20px;
            margin-right: 8px;
        }

        .active {
            background: #343a40;
            color: #fff !important;
            border-left: 4px solid #0d6efd;
            border-right: 4px solid #0d6efd;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
    </style>
</head>

<body>

    <!-- Top Navbar -->
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">Admin Panel</span>
            <span class="text-white">Welcome, {{ Auth::user()->role }} : {{ Auth::user()->name }}</span>

            <img src="{{ asset(Auth::user()->photo) }}" width="40" height="40"
                class="rounded-circle object-fit-cover" alt="Profile Picture" />

            <a href="{{ route('admin.logout') }}" class="btn btn-danger"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                @csrf
                @method('POST')
            </form>
        </div>
    </nav>

    <!-- Sidebar + Main Content -->
    <div class="container-fluid">
        <div class="row">

            <!-- Sidebar -->
            <nav class="col-12 col-sm-4 col-md-3 col-lg-2 sidebar p-0">
                <a href="{{ route('admin.dashboard') }}"
                    class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-gauge-high"></i> Dashboard
                </a>

                <a href="{{ route('userView') }}" class="{{ request()->routeIs('userView') ? 'active' : '' }}">
                    <i class="fa-solid fa-users"></i> Users
                </a>

                <a href="{{ route('admin.posts') }}" class="{{ request()->routeIs('admin.posts') ? 'active' : '' }}">
                    <i class="fa-solid fa-newspaper"></i> Posts
                </a>

                <a href="{{ route('friendsView') }}" class="{{ request()->routeIs('friendsView') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-group"></i> Friends
                </a>

                @if (Auth::user()->role == 'super_admin')
                    <a href="{{ route('adminsview') }}"
                        class="{{ request()->routeIs('adminsview') ? 'active' : '' }}">
                        <i class="fa-solid fa-user-shield"></i> Add New Admin
                    </a>
                @endif

                <a href="{{ route('admin.DeletedData') }}"
                    class="{{ request()->routeIs('admin.DeletedData') ? 'active' : '' }}">
                    <i class="fa-solid fa-trash-can"></i> Recycle Bin
                </a>

                <a href="{{ route('admin.search') }}"
                    class="{{ request()->routeIs('admin.search') ? 'active' : '' }}">
                    <i class="fa-solid fa-magnifying-glass"></i> Search
                </a>


                <a href="{{ route('admin.assistant') }}"
                    class="{{ request()->routeIs('admin.assistant') ? 'active' : '' }}">
                    <i class="fa-solid fa-gear"></i> AI Assistant
                </a>
            </nav>

            <!-- Main Content -->
            <main class="col-12 col-sm-8 col-md-9 col-lg-10 p-4">
                @yield('content')
            </main>

        </div>
    </div>

</body>

</html>

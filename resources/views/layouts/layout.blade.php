<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
     <title>@yield('title', 'Auth')</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

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
    </style>
</head>
<body>

<!-- Top Navbar -->
<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1">Admin Panel</span>
        <span class="text-white">Welcome, Admin</span>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">

        <!-- Sidebar -->
        <div class="col-md-2 sidebar p-0">
            <a href="#">Dashboard</a>
            <a href="#">Users</a>
            <a href="#">Posts</a>
            <a href="#">Settings</a>
            <a href="#">Logout</a>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 p-4">
            <h3>Dashboard</h3>
            <hr>

            <div class="row">
                <div class="col-md-4">
                    <div class="card text-bg-primary mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Users</h5>
                            <p class="card-text fs-4">120</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card text-bg-success mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Posts</h5>
                            <p class="card-text fs-4">85</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card text-bg-warning mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Comments</h5>
                            <p class="card-text fs-4">230</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

</body>
</html>

@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Dashboard:</h3>
        <div>
            <a class="btn btn-warning flex text-white" href="adminpdf">
                <i class="fa-solid fa-file-pdf"></i> Admin PDF
            </a>

            <a class="btn btn-success flex text-white" href="useradminexport">
                <i class="fa-solid fa-file-excel"></i> Export User/Admin
            </a>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-4">
            <div class="card text-bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Users</h5>
                    <p class="card-text fs-4">{{ $userCount }}</p>


                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Posts</h5>
                    <p class="card-text fs-4">{{ $postCount }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Admins</h5>
                    <p class="card-text fs-4">{{ $adminCount }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Comments</h5>
                    <p class="card-text fs-4">{{ $comments }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div style="width: 500px; margin: auto;">

            {!! $chart->container() !!}

        </div>
        <div style="width: 500px; margin: auto;">
            {!! $postChart->container() !!}

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {!! $chart->script() !!}
    {!! $postChart->script() !!}


@endsection

@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<h3>Dashboard</h3>
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
            </div>



@endsection

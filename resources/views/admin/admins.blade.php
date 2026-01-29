@extends('layouts.admin')

@section('title', 'Users')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Dashboard : Admins</h1>
        
         @if(auth()->user()->role == 'super_admin')
            <button
                type="button"
                class="btn btn-primary"
                data-toggle="modal"
                data-target="#addAdmin"
            >
                Add New Admin
        </button>
        @else 
            <p></p>
        @endif
            @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
        <div class="modal" id="addAdmin">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Add New Admin</h4>
                            <button
                                type="button"
                                class="close"
                                data-dismiss="modal"
                            >
                                &times;
                            </button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            <form action="{{ route('admin.createNewAdmin') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('POST')
                                <div class="form-group">
                                    <label for="name">Name:</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="name"
                                        name="name"
                                        required
                                    />  
                                </div>
                                <div class="form-group
">
                                    <label for="email">Email</label>
                                    <input
                                        type="email"
                                        class="form-control"
                                        id="email"
                                        name="email"
                                        required
                                    />
                                </div>
                                <div class="form-group
">
                                    <label for="password">Password</label>
                                    <input
                                        type="password"
                                        class="form-control"
                                        id="password"
                                        name="password"
                                        required
                                    />
                                </div> 
                                <div class="form-group
">
                                    <label for="confirm-password">Confirm Password</label>
                                    <input
                                        type="password"
                                        class="form-control"
                                        id="confirm-password"
                                        name="password_confirmation"
                                        required
                                    />
                                </div>
                                
                                <div class="form-group
">
                                    <label for="photo">Photo</label>
                                    <input
                                        type="file"
                                        class="form-control"
                                        name="photo"
                                        id="photo"
                                       
                                    />
                                </div>
                                <button type="submit" class="btn btn-primary mt-3">
                                    Add Admin
                                </button>
                            </form>
                        </div>
                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button
                                type="button"
                                class="btn btn-danger"
                                data-dismiss="modal"
                            >
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
    </div>
<div class="container mt-4">

    <!-- Top Bar -->
    

    <!-- Status Message -->
    @if(session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

   
    

    <!-- Users Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Photo</th>
                    <th colspan="2">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($admins as $admin)
                <tr>
                    <td>{{ $admin->id }}</td>
                    <td>{{ $admin->name }}</td>
                    <td>{{ $admin->email }}</td>
                    <td>
                        <img src="{{ asset($admin->photo) }}"
                             width="60"
                             height="60"
                             class="rounded-circle object-fit-cover">
                    </td>
                    <td>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editUser{{ $admin->id }}">
                           Edit
                        </button>

                        <!-- The Modal -->
                            <div class="modal" id="editUser{{ $admin->id }}">
                            <div class="modal-dialog">
                                <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">Edit User</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>

                                <!-- Modal body -->
                                <div class="modal-body">
                                    <form action="{{ route('admin.userUpdate', $admin->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('POST')
                                        <div class="form-group">
                                            <label for="name">Name:</label>
                                            <input type="text" class="form-control" id="name" name="name" value="{{ $admin->name }}" required>   
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" value="{{ $admin->email }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="photo">Photo</label>
                                            <input type="file" class="form-control" name="photo" id="" >
                                        </div>
                                        <button type="submit" class="btn btn-primary mt-3">Update User</button>
                                    </form>
                                </div>

                                <!-- Modal footer -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                </div>

                                </div>
                            </div>
                            </div>
                       
                    </td>
                    <td>
                        

                            <form action="{{ url('admin/delete/'.$admin->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this user?')">
                                    Delete
                                </button>
                            </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>


@endsection

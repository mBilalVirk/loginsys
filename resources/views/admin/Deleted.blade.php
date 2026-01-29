@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<h3>Dashboard : Recycle Bin</h3>
            <hr>
            <div class="row">
                <div class="col-md-4" data-toggle="modal" data-target="#viewUsers{{$users->count()}}" style="cursor:pointer;">
                    <div class="card text-bg-primary mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Deleted Users</h5>
                            <p class="card-text fs-4">{{$users->count()}}</p>
                        </div>
                        </div>
                        </div>
                <!-- Model start -->
                <div class="modal" id="viewUsers{{$users->count()}}">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h4 class="modal-title">Deleted Users</h4>
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
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                         <th>ID</th>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                        <th>Photo</th>
                                                        <th colspan="2">Action</th>
                                                    </tr>
                                                </thead>
                                                    <tbody>
                                                       @foreach($users as $user)
                                                      
                                                            <tr>
                                                                <td>{{ $user->id }}</td>
                                                                <td>{{ $user->name }}</td>
                                                                <td>{{ $user->email }}</td>
                                                                <td>
                                                                    <img src="{{ asset($user->photo) }}"
                                                                         width="60"
                                                                         height="60"
                                                                         class="rounded-circle object-fit-cover">
                                                                </td>
                                                               
                                                                <td>
                                                                    <form
                                                                        action="{{route('admin.restoreUser', $user->id)}}"
                                                                        method="POST"
                                                                        onsubmit="return confirm('Are you sure you want to restore this user?');"
                                                                    >
                                                                        @csrf
                                                                        @method('POST')
                                                                        <button type="submit" class="btn btn-success">Restore</button>
                                                                    </form>                                                       
                                                                <td> 
                                                                    <form
                                                                        action="{{route('admin.permanentDeleteUser', $user->id)}}"
                                                                        method="POST"
                                                                        onsubmit="return confirm('Are you sure you want to delete this post?');"
                                                                    >   
                                                                    <input type="hidden" name="user_id" value="{{ $user->id }}">                                                                  
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                                    </form>
                                                                 </td>
                                                            </tr>
                                                        @endforeach

                                                    </tbody>

                                            </table>
                                            
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
                <!-- end -->
                

                <div class="col-md-4" data-toggle="modal" data-target="#viewPost" style="cursor:pointer;">
                    <div class="card text-bg-success mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Deleted Posts</h5>
                            <p class="card-text fs-4">{{$posts->count()}}</p>
                        </div>
                    </div>
                     </div>
                    <!-- Model start -->
                     <div class="container" style="all:unset">
                <div class="modal" id="viewPost">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h4 class="modal-title">Deleted Posts</h4>
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
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Content</th>
                                                        <th>Posted By</th>
                                                        <th>Deleted At</th>
                                                        <th colspan="2">Action</th>
                                                    </tr>
                                                </thead>
                                                    <tbody>
                                                       @foreach($posts as $post)
                                                      
                                                            <tr>
                                                                <td>{{ $post->id }}</td>
                                                                <td>{{\Illuminate\Support\Str::limit($post->content, 50)}}</td>
                                                                <td>{{ $post->user->name ?? null }}</td>
                                                                <td>{{ $post->deleted_at }}</td>
                                                                <td>
                                                                    <form
                                                                        action="{{route('admin.restorePost', $post->id)}}"
                                                                        method="POST"
                                                                        onsubmit="return confirm('Are you sure you want to restore this post?');"
                                                                    >
                                                                        @csrf
                                                                        @method('POST')
                                                                        <button type="submit" class="btn btn-success">Restore</button>
                                                                    </form>
                                                                </td>
                                                                <td> 
                                                                    <form
                                                                        action="{{route('admin.permanentDeletePost', $post->id)}}"
                                                                        method="POST"
                                                                        onsubmit="return confirm('Are you sure you want to delete this post?');"
                                                                    >
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                                    </form>
                                                                 </td>
                                                            </tr>
                                                        @endforeach

                                                    </tbody>

                                            </table>
                                            
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
                <!-- end -->
               
                
                <div class="col-md-4" data-toggle="modal" data-target="#viewComments{{$comments->count()}}" style="cursor:pointer;">
                    <div class="card text-bg-danger mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Deleted Comments</h5>
                            <p class="card-text fs-4">{{$comments->count()}}</p>
                        </div>
                    </div>
                     </div>
                     <!-- Model start -->
                     <div class="container" >
                <div class="modal" id="viewComments{{$comments->count()}}">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h4 class="modal-title">Deleted Comments</h4>
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
                                                <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Comment</th>
                                                        <th>Comment By</th>
                                                        <th>Comment Post</th>
                                                        <th>Deleted At</th>
                                                        <th colspan="2">Action</th>
                                                    </tr>
                                                </thead>
                                                    <tbody>
                                                       @foreach($comments as $comment)
                                                      
                                                            <tr>
                                                                <td>{{ $comment->id }}</td>
                                                                <td>{{\Illuminate\Support\Str::limit($comment->comment, 50)}}</td>
                                                                <td>{{ $comment->user->name }}</td>
                                                                <td>{{ $comment->post_id }}</td>
                                                                <td>{{ $comment->deleted_at }}</td>
                                                                <td>
                                                                    <form
                                                                        action="{{route('admin.restoreComment', $comment->id)}}"
                                                                        method="POST"
                                                                        onsubmit="return confirm('Are you sure you want to restore this Comment?');"
                                                                    >
                                                                        @csrf
                                                                        @method('POST')
                                                                        <button type="submit" class="btn btn-success">Restore</button>
                                                                    </form>
                                                                </td>
                                                                <td> 
                                                                    <form
                                                                        action="{{route('admin.permanentDeleteComment', $comment->id)}}"
                                                                        method="POST"
                                                                        onsubmit="return confirm('Are you sure you want to delete this comment?');"
                                                                    >
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                                    </form>
                                                                 </td>
                                                            </tr>
                                                        @endforeach

                                                    </tbody>

                                            </table>
                                            
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
                    <!-- End -->
               


                <div class="col-md-4" data-toggle="modal" data-target="#viewAdmin{{$admins->count()}}" style="cursor:pointer;">
                    <div class="card text-bg-warning mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Deleted Admins</h5>
                            <p class="card-text fs-4">{{$admins->count()}}</p>
                        </div>
                    </div>
                </div>
</div>
                <!-- Model start -->
                <div class="modal" id="viewAdmin{{$admins->count()}}">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h4 class="modal-title">Deleted Admins</h4>
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
                                            <table class="table table-bordered">
                                                <thead>
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
                                                                    <form
                                                                        action="{{route('admin.restoreAdmin', $admin->id)}}"
                                                                        method="POST"
                                                                        onsubmit="return confirm('Are you sure you want to restore this user?');"
                                                                    >
                                                                        @csrf
                                                                        @method('POST')
                                                                        <button type="submit" class="btn btn-success">Restore</button>
                                                                    </form>                                                       
                                                                <td> 
                                                                    <form
                                                                        action="{{route('admin.permanentDeleteAdmin', $admin->id)}}"
                                                                        method="POST"
                                                                        onsubmit="return confirm('Are you sure you want to delete this post?');"
                                                                    >   
                                                                    <input type="hidden" name="user_id" value="{{ $admin->id }}">                                                                  
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                                    </form>
                                                                 </td>
                                                            </tr>
                                                        @endforeach

                                                    </tbody>

                                            </table>
                                            
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
                <!-- end -->
            



@endsection

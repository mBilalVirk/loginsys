@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<h3>Dashboard : Friends</h3>
            <hr>
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Sender</th>
                            <th>Receiver</th>
                            <th>Status</th>
                            <th >Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($friends as $friend)
                        <tr>
                            <td>{{ $friend->id }}</td>
                            <td>{{ $friend->sender->name }}</td>
                            <td>{{ $friend->receiver->name }}</td>
                            <td>
                                @if($friend->status == 'accepted')
                                    <span style="background: green; color: white; padding: 2px 5px; border-radius: 3px;">Friends
                                @else
                                    <span style="background: yellow; color: white; padding: 2px 5px; border-radius: 3px;">{{ $friend->status }}</span>
                                @endif
                            </td>

                            <td>
                                <form action="{{route('admin.deleteFriend',$friend->id)}}" method="post"
                                onsubmit="return confirm('Are you sure you want to delete this friends record?')">
                                @csrf
                                @method("DELETE")
                                <button type="submit" class="btn btn-danger">
                                    Delete
                                </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>


                    
            

@endsection

@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<h3>Dashboard : Friends</h3>
<div id="successAlert" class="alert alert-success d-none"></div>
<div id="errorAlert" class="alert alert-danger d-none"></div>

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
                    <tbody id="friendsTable">
                        {{-- @foreach ($friends as $friend)
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
                        @endforeach --}}
                    </tbody>
                </table>
            </div>


                    
<script>
    $(document).ready(function(){
        loadFriendShip();
        function loadFriendShip(){
            let rows = '';
        $.ajax({
            url:`/admin/friends`,
            type:"GET",
            success:function(data){
                
                data.forEach(function(friend){

                rows += `
                    <tr>
                            <td>${friend.id}</td>
                            <td>${friend.sender.name}</td>
                            <td>${friend.receiver.name}</td>
                            <td>
                                <span style="
                                    background: ${friend.status === 'accepted' ? 'green' : 'yellow'};
                                    color: white;
                                    padding: 2px 5px;
                                    border-radius: 3px;">
                                    ${friend.status === 'accepted' ? 'Friends' : friend.status}
                                </span>
                            </td>

                            <td>
                               
                                <button type="submit" class="btn btn-danger" onclick="deleteFriendShip(${friend.id})">
                                    Delete
                                </button>
                               
                            </td>
                        </tr>
                `;
                });
                $("#friendsTable").html(rows);
            },
            error:function(err){
                console.log(err.responseText);
            }
        });
        }

        function deleteFriendShip(id){
               
                    if(confirm("Are you sure you want to delete this Friendship?")){
                        $.ajax({
                            url:`/admin/friend/delete/${id}`,
                            type:"DELETE",
                            data:{
                                _token:'{{csrf_token()}}'
                            },
                            success: function(response){
                              $(".alert-success").removeClass("d-none").html(`<span>${response.res}</span>`).fadeIn();
                                
                               loadFriendShip();
                               setTimeout(() => {
                                $(".alert-success").addClass("d-none").fadeOut();
                               }, 4000);
                            },
                            error:function(err){
                                console.log(err.responseText);
                            }
      
                        });

                    }

                }
    });
</script>

@endsection

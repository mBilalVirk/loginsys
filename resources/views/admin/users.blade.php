@extends('layouts.admin')

@section('title', 'Users')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Dashboard : Users</h1>
        
    </div>
<div class="container mt-4">

    <!-- Top Bar -->
    

    <!-- Status Message -->
    @if(session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
<div class="alert alert-success d-none"></div>
<div class="alert alert-danger d-none"></div>
    <!-- Logout Form -->
    

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
            <tbody id="usersTable"></tbody>
        </table>
        <nav>
            <ul class="pagination justify-content-center" id="paginationLinks"></ul>
        </nav>


        <!-- model start -->
         <div class="modal fade" id="editUser" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">Edit User</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>

                                <!-- Modal body -->
                                <div class="modal-body">
                                    <form enctype="multipart/form-data" id="editForm">
                                        @csrf
                                       
                                        <div class="form-group">
                                            <label for="name">Name:</label>
                                            <input type="text" class="form-control" id="editName" name="name" value="" required>   
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" id="editEmail" name="email" value="">
                                        </div>
                                        <div class="form-group">
                                            <label for="photo">Photo</label>
                                            <input type="file" class="form-control" name="photo" id="photo" >
                                        </div>
                                        <button type="submit" class="btn btn-primary mt-3" >Update User</button>
                                    </form>
                                </div>

                                <!-- Modal footer -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal" id="editClose">Close</button>
                                </div>

                                </div>
                            </div>
                            </div>
                       
         <!-- model end -->
    </div>

</div>
<script>
    $(document).ready(function(){
        loadUsers(); 
    });
    function loadUsers(page = 1){
        let row = '';
         $.ajax({
            url:`{{route('admin.users')}}?page=` + page,
            type:"GET",
            success:function(response){
                response.data.forEach(function(user){
                    row += `<tr>
                                <td>${user.id}</td>
                                <td>${user.name}</td>
                                <td>${user.email}</td>
                                <td><img src="/${user.photo}" width='50' height='50' class="rounded-circle object-fit-cover"/></td>
                                <td>
                                     <button class="btn btn-primary"
                                     data-toggle="modal"
                                     onclick="editUser(${user.id},'${user.name}','${user.email}')"
                                     >Edit</button>
                                </td>
                                <td>
                                   <button class="btn btn-danger" onclick="deleteUser(${user.id})">Delete</button>
                                </td>
                                
                            </tr>`;
                });
                $("#usersTable").html(row);
                 buildPagination(response);
            },
            error:function(err){
                console.log(err.response.Text);
            }
       });
       }
    function deleteUser(id){
            $.ajax({
                    url:`/admin/delete/${id}`,
                    type:"DELETE",
                    data:{
                                _token:'{{csrf_token()}}'
                            },
                    success:function(data){
                        $(".alert-success").removeClass("d-none");
                        $(".alert-success").html(`<span>${data.res}</span>`)
                        setTimeout(() => {
                            $(".alert-success").addClass("d-none")
                        }, 4000);
                        loadUsers();
                    },
                    error:function(err){
                        console.log(err.responseText);
                         $(".alert-danger").removeClass("d-none");
                        $(".alert-danger").html(`<span>${err.responseText}</span>`)
                        setTimeout(() => {
                            $(".alert-danger").addClass("d-none")
                        }, 4000);
                    }
            });
        };
        function editUser(id,name,email){
        
        $("#editName").val(name);
        $("#editEmail").val(email);
        
        let modalEl = document.getElementById('editUser');
        let modal = new bootstrap.Modal(modalEl);
       
        modal.show(); 
        $("#editForm").submit(function(e){
            e.preventDefault();
            const form = $("#editForm")[0];
            const data = new FormData(form);
            $.ajax({
                url:`/admin/update/${id}`,
                type:"POST",
                data:data,
                processData:false,
                contentType:false,
                success:function(data){
                        $(".alert-success").removeClass("d-none");
                        console.log("success");
                        $(".alert-success").html(`<span>${data.res}</span>`)
                        $("#editClose").click();
                        loadUsers();
                        document.activeElement.blur(); // remove focus
                        modal.hide();
                        setTimeout(() => {
                            $(".alert-success").addClass("d-none")
                        }, 4000);
                },
                error:function(err){
                    console.log(err.responseText);
                    $(".alert-danger").html(`<span>${err.responseText}</span>`)
                    setTimeout(() => {
                            $(".alert-danger").addClass("d-none")
                        }, 4000);
                    
                }

            });
        });       
    }
    function buildPagination(response) {

    let pagination = '';

    // Previous Button
    pagination += `
        <li class="page-item ${response.current_page == 1 ? 'disabled' : ''}">
            <a class="page-link" href="javascript:void(0)"
               onclick="loadUsers(${response.current_page - 1})">
               Previous
            </a>
        </li>
    `;

    // Page Numbers
    for (let i = 1; i <= response.last_page; i++) {
        pagination += `
            <li class="page-item ${i == response.current_page ? 'active' : ''}">
                <a class="page-link" href="javascript:void(0)"
                   onclick="loadUsers(${i})">
                   ${i}
                </a>
            </li>
        `;
    }

    // Next Button
    pagination += `
        <li class="page-item ${response.current_page == response.last_page ? 'disabled' : ''}">
            <a class="page-link" href="javascript:void(0)"
               onclick="loadUsers(${response.current_page + 1})">
               Next
            </a>
        </li>
    `;

    $("#paginationLinks").html(pagination);
}


</script>

@endsection

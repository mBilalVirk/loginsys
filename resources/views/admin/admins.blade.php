<div id="admin-layout">
    @extends('layouts.admin')
</div>

@section('title', 'Admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Dashboard : Admins</h1>
        <div class="alert alert-success d-none "></div>
        <div class="alert alert-danger d-none"></div>
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
 
        <div class="modal fade" id="addAdmin">
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
                            <!-- <form action="{{ route('admin.createNewAdmin') }}" method="POST" enctype="multipart/form-data" id="admin-form"> -->
                           <div id="successAlert" class="alert alert-success d-none"></div>
                            <div id="errorAlert" class="alert alert-danger d-none"></div>

                            <form  enctype="multipart/form-data" id="admin-form">
                                @csrf
                               
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
                                <button type="submit" class="btn btn-primary mt-3" id="form-submit">
                                    Add Admin
                                </button>
                            </form>
                        </div>
                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button
                                type="button"
                                class="btn btn-danger"
                                data-dismiss="modal" id="model-btn"
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
            <tbody id="adminTableBody" >
              
            </tbody>
        </table>
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
                                    <form enctype="multipart/form-data" id="editAdmin">
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
       loadAdmins();
        $("#admin-form").submit(function(e){
            e.preventDefault();
            const form = $('#admin-form')[0];
            const data = new FormData(form);
           
            $("#form-submit").prop("disabled", true);
            $.ajax({
                url:"{{ route('admin.createNewAdmin') }}",
                type:"POST",
                data:data,
                processData:false,
                contentType:false,
                success:function(data){
                    $("#successAlert").removeClass('d-none').text(data.res);
                    $("#form-submit").prop("disabled", false);
                    $('#admin-form').get(0).reset();
                    $("#model-btn").click();
                    loadAdmins();
                },
                error:function(e){
                    const err = e.responseJSON.errors;
                    $.each(err,function(key,value){
                        $("#errorAlert").removeClass('d-none').text(value);
                        $('#admin-form').get(0).reset();
                        setTimeout(() => {
                             $("#errorAlert").addClass('d-none')
                        }, 4000);
                        $("#form-submit").prop("disabled", true);
                    });
                }
            });

        });

        
   });
function loadAdmins(){
         $.ajax({
                url: "{{ route('admin.admins') }}",
                type: "GET",
                success: function(data){
                    // console.log(data);
                   let rows = '';
                   data.forEach(function(admin){

                        rows +=`<tr>
                                    <td>${admin.id}</td>
                                    <td>${admin.name}</td>
                                    <td>${admin.email}</td>
                                    <td><img src='/${admin.photo}' width='60' height='60' class="rounded-circle object-fit-cover"/>
    </td>
                                    <td>
                                        <button class="btn btn-primary" 
                                            
                                            data-toggle="modal"
                                            onclick="editAdmin(${admin.id},'${admin.name}','${admin.email}')"
                                        >Edit</button>
                                    </td>
                                    <td><button class="btn btn-danger" onclick="deleteAdmin(${admin.id})">Delete</button></td>
                                </tr>
                                `;
                                
                   });
                   
                   $("#adminTableBody").html(rows);
                },
                error: function(err){
                    console.log("fetch data not success!");
                    console.log(err.responseText);
                   
                }
                });
                
               

    }
    function editAdmin(id,name,email){
        
        $("#editName").val(name);
        $("#editEmail").val(email);
        
        let modalEl = document.getElementById('editUser');
        let modal = new bootstrap.Modal(modalEl);
       
        modal.show(); 
        $("#editAdmin").submit(function(e){
            e.preventDefault();
            const form = $("#editAdmin")[0];
            const data = new FormData(form);
            $.ajax({
                url:`/admin/update/${id}`,
                type:"POST",
                data:data,
                processData:false,
                contentType:false,
                success:function(data){
                        $(".alert-success").removeClass("d-none");
                      
                        $(".alert-success").html(`<span>${data.res}</span>`)
                        $("#editClose").click();
                        loadAdmins();
                        document.activeElement.blur(); // remove focus
                        modal.hide();
                        setTimeout(() => {
                            $(".alert-success").addClass("d-none")
                        }, 4000);
                },
                error:function(err){
                    console.log(err.responseText);
                }

            });
        });       
    }
    
     function deleteAdmin(id){
               
                    if(confirm("Are you sure you want to delete this Admin?")){
                        $.ajax({
                            url:`/admin/delete/${id}`,
                            type:"DELETE",
                            data:{
                                _token:'{{csrf_token()}}'
                            },
                            success: function(response){
                              $(".alert-success").removeClass("d-none").html(`<span>${response.res}</span>`).fadeIn();
                                
                               loadAdmins();
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
</script>

@endsection

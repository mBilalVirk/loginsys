<div id="admin-layout">
    @extends('layouts.admin')
</div>

@section('title', 'Settings')

@section('content')

@if(session('success'))
    <div class="alert alert-success">

        {{ session('success') }}
    </div>
@endif
<div class="alert alert-success d-none"></div>
<div class="alert alert-danger d-none"></div>
<div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Dashboard : Settings</h1>
        
    </div>
<div class="container mt-4">
   <!-- Admin can change their name and email from here and password -->
    <form enctype="multipart/form-data" id="update-form">
     @csrf
     @method('POST')
    
     <div class="mb-3">
          <label for="name" class="form-label">Name</label>
          <input type="text" class="form-control" id="name" name="name" value="{{ Auth::user()->name }}" required>
        </div>
        
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}" required>
        </div>
        
        <div class="mb-3">
            <label for="password" class="form-label">New Password</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm New Password</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
        </div>
        <div class="mb-3">
            <label for="photo" class="form-label">Profile Photo</label>
            <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
   
</div>
<script>
            
           $(document).ready(function(){
                $("#update-form").submit(function(e){
                    const form = $("#update-form")[0];
                    const data = new FormData(form);
                    e.preventDefault();
                    $.ajax({
                        url:"{{ route('admin.updateProfile') }}",
                        type:"POST",
                        data:data,
                        processData:false,
                        contentType:false,
                        success:function(data){
                            console.log(data.res);
                            $(".alert-success").removeClass("d-none");
                            $(".alert-success").html(
                                `<span>${data.res}</span>`
                            );
                             setTimeout(() => {
                             $(".alert-success").addClass("d-none");
                             }, 4000);
                          

                        },
                        error:function(err){
                            const errors = err.responseJSON.errors;
                            $.each(errors, function(key, value){
                                
                                $(".alert-danger").removeClass("d-none");
                                $(".alert-danger").html(
                                `<span>${value}</span>`
                            );
                            });
                           
                           
                             setTimeout(() => {
                             $(".alert-danger").addClass("d-none");
                             }, 4000);
                           
                        }
                    });
                });
           });
            function loadData(){
                $.ajax({
                    url:"",
                    type:"GET",
                    success:function(Data){
                        console.log("Data Fetch successfully!");
                    },
                    error:function(err){
                        console.log(err);
                    }
                });
               
            }
    </script>

@endsection

<h1>Update password</h1>
@if( $errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li style="color:red;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form action="{{route('updatePassword')}}" method="post">
    @csrf
    <label for="current_password">Enter Current Password</label>
    <input type="password" name="current_password" id="">
    <label for="new_password">Enter New Password</label>
    <input type="password" name="new_password" id="">
    
    <input type="submit" value="Update Password">
</form>
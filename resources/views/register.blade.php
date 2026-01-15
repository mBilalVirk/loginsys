<h1>Register</h1>
@if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li style="color:red;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form action="{{ route('registerUser') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div>
        Name: <input type="text" name="name">
    </div>
    <div>
        Email: <input type="email" name="email">
    </div>
    <div>
        Password: <input type="password" name="password">
    </div>
    <div>
       Confirm Password: <input type="password" name="password_confirmation">
    </div>
    <div>
        Photo: <input type="file" name='photo'>
    </div>
    <div>
        <button type="submit">Register</button>
    </div>
</form>
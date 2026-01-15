<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h1>Admim Login</h1>
    @if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li style="color:red;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
 @if( session('status'))
            <div style="color:green">{{session('status')}}</div>
            @endif
    <form action="{{ route('adminlogin' )}}" method="post">
        @csrf
        <label for="email">Enter Email</label>
        <input type="email" name="email" id="email">
        <label for="password">Enter Password</label>
        <input type="password" name="password" id="password">
        <button type="submit">Login</button>
    </form>
</body>
</html>
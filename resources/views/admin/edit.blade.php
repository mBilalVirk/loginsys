<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .container {
            background: #fff;
            padding: 30px 40px;
            border-radius: 10px;
            width: 350px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        a {
            display: block;
            text-align: center;
            margin-bottom: 20px;
            text-decoration: none;
            color: #667eea;
            font-size: 14px;
        }

        a:hover {
            text-decoration: underline;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"],
        input[type="email"] {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            outline: none;
            transition: border 0.3s;
        }

        input[type="text"]:focus,
        input[type="email"]:focus {
            border-color: #667eea;
        }

        input[type="submit"] {
            background: #667eea;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s;
        }

        input[type="submit"]:hover {
            background: #5563c1;
        }
    </style>
</head>
<body>

    <div class="container">
        @if ($errors->any())
            <div>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li style="color:red;">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <h1>Edit User</h1>

        <a href="{{ route('admin.index') }}">← Back to Dashboard</a>

        <form action="{{ url('admin/update/'.$user->id) }}" method="post" enctype="multipart/form-data">
             @csrf
    @method('POST')
            <img src="{{ asset($user->photo) }}" 
             width="60" 
             height="60"
             style="border-radius:50%; object-fit:cover;">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="{{ $user->name }}">

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ $user->email }}">
            <label for="photo">Photo</label>
            <input type="file" name="photo" id="" >
            <br>

            <input type="submit" value="Update User">
        </form>
    </div>

</body>
</html>

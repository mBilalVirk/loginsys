<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background: #fff;
            padding: 30px;
            width: 80%;
            max-width: 900px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        h1 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #667eea;
            color: white;
            font-weight: bold;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        a.edit-btn {
            text-decoration: none;
            padding: 6px 12px;
            background: #4CAF50;
            color: #fff;
            border-radius: 4px;
            font-size: 14px;
        }

        a.edit-btn:hover {
            background: #43a047;
        }

        form {
            display: inline;
        }

        .delete-btn {
            padding: 6px 12px;
            background: #e53935;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .delete-btn:hover {
            background: #c62828;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .logout {
            text-decoration: none;
            background: #ff9800;
            color: white;
            padding: 8px 15px;
            border-radius: 4px;
            font-size: 14px;
        }

        .logout:hover {
            background: #fb8c00;
        }
    </style>
</head>
<body>

<div class="container">

    <div class="top-bar">
        <h1>Admin Dashboard</h1>
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           class="logout">Logout</a>
           
    </div>
    <div>
        @if( session('status'))
            <div style="color:green">{{session('status')}}</div>
            @endif
    </div>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
        @csrf
    </form>

    
    <table>
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
             style="border-radius:50%; object-fit:cover;">
  
                </td>
                <td>
                    <a href="{{ url('admin/edit/'.$user->id) }}" class="edit-btn">Edit</a>
                </td>
                <td>

                    @if($user->role=='admin')
                        Admin
                    @else
                     <form action="{{ url('admin/delete/'.$user->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="delete-btn"
                            onclick="return confirm('Are you sure you want to delete this user?')">
                            Delete
                        </button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>

</body>
</html>

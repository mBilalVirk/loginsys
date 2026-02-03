<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container py-4">
        <div class="nav">
             <a href="{{ route('dashboard') }}" class="mb-2 m-3 ">Home</a>
            <a href="{{  route('userMessages')  }}" class="mb-2  m-3">Messages</a>
        </div>
        <div class="row">
            
            <!-- Friends / Users List -->
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header fw-bold">
                        Friends
                    </div>
                    <ul class="list-group list-group-flush">
                        @foreach($friends as $friends)
                        @php
                            
                            if ($friends->user_id == auth()->id()) {
                            $friendUser = $friends->receiver; 
                            } else {
                            $friendUser = $friends->sender; 
                            }

                        @endphp
                         <a href="{{route('chat', $friendUser->id)}}"style="text-decoration:none; display:block;">
                        <li class="list-group-item d-flex justify-content-between align-items-center" style="cursor:pointer;">
                          {{$friendUser->name}}
                            <!-- <span class="badge bg-primary rounded-pill">0</span> -->
                        </li>
                        </a> 
                        @endforeach
                        
                    </ul>
                </div>
            </div>

            </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

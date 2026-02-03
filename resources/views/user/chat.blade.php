<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- model -->
     <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        />
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">

    <div class="container py-4">
        <div class="nav">
            <a href="{{ route('dashboard') }}" class="mb-2 m-3 ">Home</a>
            <a href="{{  route('userMessages')  }}" class="mb-2  m-3">Messages</a>
        </div>
        <div class="row">
            
            <!-- Chat Section -->
            <div class="col-md-12" >
                <div class="card shadow-sm">
                    <div class="card-header fw-bold">
                        Chat
                    </div>

                    <!-- Messages -->
                    <div class="card-body" style="height: 350px; overflow-y: auto;" >
                        @foreach($messages as $message)
                            @if($message->sender_id == auth()->id())
                        <div class="mb-2 text-end">
                            <span class="badge bg-primary p-2"> You : {{$message->message}}</span>

                            <i class="fa-regular fa-pen-to-square" style="margin-bottom: 5px; cursor: pointer;"
                            data-bs-toggle="modal"
                            data-bs-target="#editMessage{{$message->id}}"
                            ></i>
                            
                            <div class="modal" id="editMessage{{$message->id}}" style="width:50%;">
                                 <div class="model-dialog" >
                                    <div  class="modal-content" >
                                        <!-- Modal Header -->
                                      
                                        <div class="modal-body">
                                        <form action="" class="model-form">
                                            <input type="text" name="message" id="" value="{{$message->message}}"
                                            style="width:100%; border:none;" >
                                        </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        

                            <form action="{{route('deleteMessage',$message->id)}}" method="post" onsubmit="return confirm('Are you sure you want to delete this Message?');" style="all:unset; display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="all:unset; background:none; border:none;cursor:pointer; color:black; font-size:16px; margin-left:5px;">
                                    
                                    <i class="fa-solid fa-delete-left" style="margin-bottom: 5px; cursor: pointer;"  ></i>
                                </button>
                                </form>
                        </div>
                        @else
                        <div class="mb-2 text-start">
                            <span class="badge bg-secondary p-2">{{$message->sender->name}} : {{$message->message}}</span>
                             <i class="fa-regular fa-pen-to-square" style="margin-bottom: 5px; cursor: pointer;"
                            data-toggle="modal"
                            data-target="#editMessage"
                            ></i>
                            <form action="{{route('deleteMessage',$message->id)}}" method="post" onsubmit="return confirm('Are you sure you want to delete this Message?');" style="all:unset; display:inline;">    
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="all:unset; background:none; border:none;cursor:pointer; color:black; font-size:16px; margin-left:5px;">
                                    
                                    <i class="fa-solid fa-delete-left" style="margin-bottom: 5px; cursor: pointer;"  ></i>
                                </button>
                                </form>
                        </div>
                        @endif
                        @endforeach
                        
                    </div>
                    <!-- Message Input -->
                    <div class="card-footer">
                        <form class="d-flex gap-2" action="{{route('sendMessage')}}" method="POST">
                            @csrf 
                            @method("POST")
                            <input type="text" name="message" class="form-control" placeholder="Type a message...">
                            <input type="text" name="receiver_id" id="" value="{{ request()->route('id') }}" hidden>
                            <button class="btn btn-primary"><i class="fa-solid fa-paper-plane"></i>
</button>
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

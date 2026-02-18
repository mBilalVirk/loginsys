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
        <style>
            .chat-bubble {
                max-width: 60%;
                word-wrap: break-word;
                overflow-wrap: break-word;
                white-space: normal;
                color: #F4FCF8;
            }

            .chat-me {
                background: #32CAED;
            }

            .chat-friend {
                background: #6c757d;
            }

        </style>
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
                    <div class="card-body" style="height: 350px; overflow-y: auto; position:relative;" id="chatBlock" style="height: 400px;overflow-y: auto;">
                        {{-- @foreach($messages as $message)
                            @if($message->sender_id == auth()->id())
                        <div class="mb-2 text-end">
                            <span class="badge bg-primary p-2"> You : {{$message->message}}</span>
                        @else
                        <div class="mb-2 text-start">
                            <span class="badge bg-secondary p-2">{{$message->sender->name}} : {{$message->message}}</span>
                        @endif
                        @endforeach --}}
                    </div>
                    <!-- Message Input -->
                    <div class="card-footer">
                        <form class="d-flex gap-2"  id="messageForm">
                            @csrf 
                            <input type="text" name="message" class="form-control" placeholder="Type a message...">
                            <input type="text" name="receiver_id" id="" value="{{ request()->route('id') }}" hidden>
                            <button class="btn btn-primary" type="submit"><i class="fa-solid fa-paper-plane"></i></button>
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
$(document).ready(function(){

    // Make sure to wrap Blade variable in quotes
    let id = "{{ request()->route('id') }}";
    let authId = "{{auth()->id()}}"
    
    loadMessages();
    setInterval(function() {
    loadMessages();
}, 2000);

    
    let lastMessageId = 0;

    function loadMessages() {

        let chatMessage = "";
        $.ajax({
            url: `/user/chat/${id}` ,
            type: "GET",
            data: { last_id: lastMessageId },
            dataType: "json",
            success: function(data){
                data.forEach(function(message){
                        if(data.length > 0){
                        chatMessage += `

                            <div class="mb-2 ${message.sender_id == authId ? 'text-end' : 'text-start'}">
                                <span class="badge ${message.sender_id == authId ? 'chat-me' : 'chat-friend'} p-2 chat-bubble">
                                    ${message.sender_id == authId ? 'You' : 'Friend'}
                                    : ${message.message}
                                </span>
                            </div>
                            
                        `;
                    
                        }
                });
               $("#chatBlock").html(chatMessage);
               lastMessageId = message.id;
               $("#chatBox").scrollTop($("#chatBox")[0].scrollHeight);
            },
            error: function(err){
                console.log("AJAX Error:", err);
            }
        });
    }

    
        $("#messageForm").submit(function(e){
            e.preventDefault();
            const form = $("#messageForm")[0];
            const data = new FormData(form);
            $.ajax({
                url:`{{route('sendMessage')}}`,
                type:"POST",
                data:data,
                processData:false,
                contentType:false,
                success:function(data){
                        
                        loadMessages();
                        $("#messageForm")[0].reset();

                        
                },
                error:function(err){
                    console.log("Message not send");
                }
            });

        });

    

    
});
</script>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
 <meta name="csrf-token" content="{{ csrf_token() }}">
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
        

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        
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
            <a href="{{  route('userMessages')  }}" class="mb-2  m-3">Friends</a>
        </div>
        <div class="row">
            
            <!-- Chat Section -->
            <div class="col-md-12" >
                <div class="card shadow-sm">
                    <div class="card-header fw-bold">
                        Chat
                    </div>
                    <div class="alert alert-success d-none" role="alert"></div>
                   

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
                    {{-- model start --}}
                    <div class="modal fade" id="editMessageModel" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">Edit Message</h4>
                                    
                                </div>

                                <!-- Modal body -->
                                <div class="modal-body">
                                    <form enctype="multipart/form-data" id="editMsgForm">
                                        @csrf
                                       
                                        <div class="form-group">
                                            <label for="message">Message:</label>
                                            <input type="text" class="form-control" id="editMsg" name="message" value="" required>   
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary mt-3" >Update</button>
                                    </form>
                                </div>

                                

                                </div>
                            </div>
                            </div>
                    {{-- model end --}}
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
    loadMessages();
    //setInterval(loadMessages, 3000);
});
   let id = "{{ request()->route('id') }}";
    let authId = "{{auth()->id()}}"

let lastMessageId = 0;

    function loadMessages(lastMessageId) {

        let chatMessage = "";
        $.ajax({
            url: `/user/chat/${id}?last_id=${lastMessageId}` ,
            type: "GET",
            dataType: "json",
            success: function(data){
                

                if (data.length > 0 ){
                data.forEach(function(message){
                        
                        
                        chatMessage += `

                            <div class="mb-2 ${message.sender_id == authId ? 'text-end' : 'text-start'}">
                                <span class="badge ${message.sender_id == authId ? 'chat-me' : 'chat-friend'} p-2 chat-bubble">
                                    ${message.sender_id == authId ? 'You' : 'Friend' }
                                    : ${escapeHtml(message.message)}
                                </span>
                                ${message.sender_id == authId ? `<i class="fa-regular fa-pen-to-square" style="margin-bottom: 5px; cursor: pointer;"                                
                                    data-toggle="modal"
                                    onclick='editMessage(${message.id}, ${JSON.stringify(message.message)})'
                            ></i>  <i class="fa-solid fa-delete-left" style="margin-bottom: 5px; cursor: pointer;"  onclick="deleteMessage(${message.id})"></i>` : ''}
                            </div>
                                                      
                        `;
                    
                        lastMessageId = message.id;

                        
                        
                       
                });
               $("#chatBlock").html('');
                
               $("#chatBlock").append(chatMessage);
               $("#chatBlock").scrollTop($("#chatBlock")[0].scrollHeight);
                        }
            },
            error: function(err){
                console.log("AJAX Error:", err);
            }
        });
    }
    function escapeHtml(unsafe) {
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
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
                        console.log("Msg send!");
                        loadMessages();
                        $("#messageForm")[0].reset();

                        
                },
                error:function(err){
                    console.log("Message not send");
                }
            });

        });

function editMessage(id,message){
        
        $("#editMsg").val(message);
        
        
        let modalEl = document.getElementById('editMessageModel');
        let modal = new bootstrap.Modal(modalEl);
       
        modal.show(); 
        $("#editMsgForm").submit(function(e){
            e.preventDefault();
            
            const form = $("#editMsgForm")[0];
            const data = new FormData(form);
            $.ajax({
                url:`/user/massage/chat/update/${id}`,
                type:"POST",
                data:data,
                processData:false,
                contentType:false,
                success:function(data){
                        
                        $("#editClose").click();
                        loadMessages();
                        document.activeElement.blur(); 
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

    function deleteMessage(id){
               
                    if(confirm("Are you sure you want to delete this Message?")){
                        $.ajax({
                            url:`/user/message/chat/delete/${id}`,
                            type:"DELETE",
                            data:{
                                _token:'{{csrf_token()}}'
                            },
                            success: function(response){
                              $(".alert-success").removeClass("d-none").html(`<span>${response.res}</span>`).fadeIn();
                                
                               loadMessages();
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

                window.Echo.private('chat.' + id) // use receiver's id or chat id
                .listen('.message.sent', (e) => {
                    appendMessage(e.message, e.message.sender_id == authId);
                });
                
</script>

</body>
</html>

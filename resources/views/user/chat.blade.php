<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />

    <style>
        .chat-bubble {
            max-width: 60%;
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: normal;
            color: #F4FCF8;
        }
        .chat-me { background: #32CAED; }
        .chat-friend { background: #6c757d; }
    </style>
</head>

<body class="bg-light">

<div class="container py-4">
    <div class="nav mb-3">
        <a href="{{ route('dashboard') }}" class="me-3">Home</a>
        <a href="{{ route('userMessages') }}">Friends</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-header fw-bold">Chat</div>
        <div class="alert alert-success d-none" role="alert"></div>

        <!-- Messages -->
        <div class="card-body" id="chatBlock" style="height: 350px; overflow-y: auto;"></div>

        <!-- Message Input -->
        <div class="card-footer">
            <form class="d-flex gap-2" id="messageForm">
                @csrf
                 
                <input type="text" name="message" class="form-control" placeholder="Type a message..." required id="messageInput">
                <input type="hidden" name="receiver_id" value="{{ request()->route('id') }}">
                <button class="btn btn-primary" type="submit"><i class="fa-solid fa-paper-plane"></i></button>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editMessageModel" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Message</h4>
            </div>
            <div class="modal-body">
                <form id="editMsgForm">
                    @csrf
                    <div class="form-group">
                        <label for="editMsg">Message:</label>
                        <input type="text" class="form-control" id="editMsg" name="message" required>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JS: jQuery + Bootstrap -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Pusher JS -->
<script src="https://js.pusher.com/8.2/pusher.min.js" ></script>

<!-- Laravel Echo (IIFE version for browser global) -->
{{-- <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.11.2/dist/echo.iife.js" ></script> --}}
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.0/dist/echo.iife.js" ></script>
<script>
$(document).ready(function() {
    
    loadMessages(); // Load existing messages on page load
});

window.Pusher = Pusher;

    // Initialize Echo
    window.Echo = new Echo({
        broadcaster: 'pusher',  // even if using Reverb
        key: '1234567890abcdef',        // your key
        cluster: 'mt1',
        wsHost: window.location.hostname,
        wsPort: 8080,
        forceTLS: false,
        encrypted: false,
        disableStats: true,
    });
 

let id = "{{ request()->route('id') }}";       // Friend ID
let authId = "{{ auth()->id() }}";            // Authenticated user ID
let lastMessageId = 0;                        // Track last message

// Escape HTML to prevent XSS
function escapeHtml(unsafe) {
    return unsafe.replace(/&/g, "&amp;")
                 .replace(/</g, "&lt;")
                 .replace(/>/g, "&gt;")
                 .replace(/"/g, "&quot;")
                 .replace(/'/g, "&#039;");
}


function appendMessage(message, isMe, messageId = null) {
    if (!messageId) messageId = message.id;

    if ($(`#msg-${messageId}`).length) return;

    let html = `
        <div class="mb-2 ${isMe ? 'text-end' : 'text-start'}" id="msg-${messageId}">
            <span class="badge ${isMe ? 'chat-me' : 'chat-friend'} p-2 chat-bubble">
                ${isMe ? 'You' : 'Friend'} : ${escapeHtml(message.message)}
            </span>
            ${isMe ? `
                <i class="fa-regular fa-pen-to-square ms-2" style="cursor:pointer;"
                    onclick='editMessage(${messageId}, ${JSON.stringify(message.message)})'></i>
                <i class="fa-solid fa-delete-left ms-1" style="cursor:pointer;"
                    onclick="deleteMessage(${messageId})"></i>
            ` : ''}
        </div>
    `;
    $("#chatBlock").append(html);
    $("#chatBlock").scrollTop($("#chatBlock")[0].scrollHeight);

    lastMessageId = messageId; // update lastMessageId
}

// Load messages via AJAX
function loadMessages() {
    $.ajax({
        url: `/user/chat/${id}?last_id=${lastMessageId}`,
        type: "GET",
        dataType: "json",
        success: function(data) {
            if (data.length > 0) {
                data.forEach(msg => {
                    appendMessage(msg, msg.sender_id == authId, msg.id);
                });
            }
            
        },
        error: function(err) {
            console.log("AJAX Error:", err);
        }
    });
}

// Send a message
$("#messageForm").submit(function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    $.ajax({
        url: "{{ route('sendMessage') }}",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(res) {
            $("#messageForm")[0].reset();
            
        },
        error: function(err) { 
            console.log("Message not sent", err); 
        }
    });
});

// Edit a message
function editMessage(msgId, message) {
    $("#editMsg").val(message);
    let modal = new bootstrap.Modal(document.getElementById('editMessageModel'));
    modal.show();

    $("#editMsgForm").off('submit').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        $.ajax({
            url: `/user/massage/chat/update/${msgId}`,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                document.activeElement.blur(); 
                modal.hide();
               
                //$(`#msg-${msgId} span`).text(`You : ${$("#editMsg").val()}`);
            },
            error: function(err) { console.log(err.responseText); }
        });
    });
}

// Delete a message
function deleteMessage(msgId) {
    if(confirm("Are you sure you want to delete this message?")) {
        $.ajax({
            url: `/user/message/chat/delete/${msgId}`,
            type: "DELETE",
            data: { _token: '{{ csrf_token() }}' },
            success: function(res) {
                
            },
            error: function(err) { console.log(err.responseText); }
        });
    }
}

window.Echo.connector.pusher.connection.bind('connected', () => {
    console.log('WebSocket connected ✅');
});
// Real-time message sent updates via Echo
window.Echo.private('chat.' + authId)
    .listen('.message.sent', (e) => {
        appendMessage(e.message, e.message.sender_id == authId, e.message.id);
    });

window.Echo.private('chat.' + id)
    .listen('.message.sent', (e) => {
        appendMessage(e.message, e.message.sender_id == authId, e.message.id);
    });
// Real-time msg updated via Echo
window.Echo.private('chat.' + id)
    .listen('.message.updated', (e)=>{
         const msgId = e.message.id;
        const isMe = e.message.sender_id == authId;
        if ($(`#msg-${msgId}`).length) {
            $(`#msg-${msgId} span`).html(`${isMe ? 'You' : 'Friend'} : ${escapeHtml(e.message.message)}`);
        }
    });

window.Echo.private('chat.' + authId)
    .listen('.message.updated', (e)=>{
         const msgId = e.message.id;
        const isMe = e.message.sender_id == authId;
        if ($(`#msg-${msgId}`).length) {
            $(`#msg-${msgId} span`).html(`${isMe ? 'You' : 'Friend'} : ${escapeHtml(e.message.message)}`);
        }
    });
    // message Deleted listener
    window.Echo.private('chat.' + authId)
        .listen('.message.deleted', (e)=>{
            $(`#msg-${e.messageId}`).remove();
        });

window.Echo.private('chat.' + id)
    .listen('.message.deleted', (e)=>{
        $(`#msg-${e.messageId}`).remove();
    });
</script>

</body>
</html>
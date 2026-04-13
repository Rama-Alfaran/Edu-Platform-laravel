<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat - Edu Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e0e7e0, #f5f5f0);
            height: 100vh;
            overflow: hidden;
        }
        .chat-wrapper {
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .chat-header {
            background: #2e7d32;
            color: #fff;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .chat-header-info {
            display: flex;
            align-items: center;
        }
        .chat-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #1b5e20;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-weight: bold;
        }
        .chat-name {
            font-size: 18px;
            font-weight: 600;
        }
        .btn-back {
            background: #1b5e20;
            color: #fff;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
        }
        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            background: #fff;
        }
        .message {
            display: flex;
            margin-bottom: 15px;
            animation: fadeIn 0.3s;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .message.sent {
            justify-content: flex-end;
        }
        .message.received {
            justify-content: flex-start;
        }
        .message-bubble {
            max-width: 60%;
            padding: 10px 15px;
            border-radius: 18px;
            word-wrap: break-word;
        }
        .message.sent .message-bubble {
            background: #2e7d32;
            color: #fff;
            border-bottom-right-radius: 4px;
        }
        .message.received .message-bubble {
            background: #f1f1f1;
            color: #333;
            border-bottom-left-radius: 4px;
        }
        .message-time {
            font-size: 11px;
            margin-top: 5px;
            opacity: 0.7;
        }
        .chat-input-area {
            background: #fff;
            padding: 15px 20px;
            border-top: 1px solid #ddd;
            display: flex;
            gap: 10px;
        }
        .chat-input {
            flex: 1;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 25px;
            outline: none;
        }
        .btn-send {
            background: #2e7d32;
            color: #fff;
            border: none;
            padding: 10px 25px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
        }
        .btn-send:hover {
            background: #1b5e20;
        }
        .no-messages {
            text-align: center;
            color: #999;
            padding: 40px;
        }
    </style>
</head>
<body>
    <div class="chat-wrapper">
        <div class="chat-header">
            <div class="chat-header-info">
                <div class="chat-avatar">
                    @if($userType == 'teacher')
                        {{ strtoupper(substr($conversation->student->first_name, 0, 1)) }}
                    @else
                        {{ strtoupper(substr($conversation->teacher->first_name, 0, 1)) }}
                    @endif
                </div>
                <div class="chat-name">
                    @if($userType == 'teacher')
                        {{ $conversation->student->first_name }} {{ $conversation->student->last_name }}
                    @else
                        {{ $conversation->teacher->first_name }} {{ $conversation->teacher->last_name }}
                    @endif
                </div>
            </div>
            <a href="/chat" class="btn-back">← Back</a>
        </div>

        <div class="chat-messages" id="chatMessages">
            @if($conversation->messages->count() > 0)
                @foreach($conversation->messages as $message)
                    <div class="message {{ $message->sender_type == $userType ? 'sent' : 'received' }}" data-message-id="{{ $message->id }}">
                        <div class="message-bubble">
                            <div>{{ $message->message }}</div>
                            <div class="message-time">
                                {{ $message->created_at->format('g:i A') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="no-messages">No messages yet. Start the conversation!</div>
            @endif
        </div>

        <form action="/chat/{{ $conversation->id }}/send" method="POST" class="chat-input-area" id="messageForm">
            @csrf
            <input type="text" name="message" class="chat-input" placeholder="Type a message..." required autofocus id="messageInput">
            <button type="submit" class="btn-send">Send</button>
        </form>
    </div>

    <script>
        // Auto-scroll to bottom
        const chatMessages = document.getElementById('chatMessages');
        chatMessages.scrollTop = chatMessages.scrollHeight;

        // Auto-refresh for new messages every 3 seconds
        let lastMessageId = {{ $conversation->messages->last()->id ?? 0 }};
        
        setInterval(function() {
            fetch(`/chat/{{ $conversation->id }}/messages/${lastMessageId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.count > 0) {
                        data.messages.forEach(message => {
                            const messageDiv = document.createElement('div');
                            messageDiv.className = `message ${message.sender_type === '{{ $userType }}' ? 'sent' : 'received'}`;
                            messageDiv.setAttribute('data-message-id', message.id);
                            
                            const time = new Date(message.created_at).toLocaleTimeString('en-US', {
                                hour: 'numeric',
                                minute: '2-digit'
                            });
                            
                            messageDiv.innerHTML = `
                                <div class="message-bubble">
                                    <div>${message.message}</div>
                                    <div class="message-time">${time}</div>
                                </div>
                            `;
                            
                            chatMessages.appendChild(messageDiv);
                            lastMessageId = message.id;
                        });
                        
                        // Scroll to bottom
                        chatMessages.scrollTop = chatMessages.scrollHeight;
                        
                        // Remove "no messages" text if exists
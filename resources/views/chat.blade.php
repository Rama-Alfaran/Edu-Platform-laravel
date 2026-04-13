<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat - Edu Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e0e7e0, #f5f5f0);
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100%;
            background: #2e7d32;
            color: #fff;
            padding-top: 20px;
            z-index: 1000;
        }
        .sidebar h3 {
            text-align: center;
            padding: 20px;
            margin: 0;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar .nav-link {
            color: #fff;
            padding: 10px 20px;
            margin: 5px 0;
            border-radius: 0 8px 8px 0;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: #1b5e20;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
        }
        .chat-container {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 20px;
        }
        .new-chat-section {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .user-select {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .user-select select {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .btn-start-chat {
            background: #2e7d32;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-start-chat:hover {
            background: #1b5e20;
        }
        .conversation-list {
            list-style: none;
            padding: 0;
        }
        .conversation-item {
            display: flex;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            transition: background 0.3s;
            text-decoration: none;
            color: inherit;
        }
        .conversation-item:hover {
            background: #f5f5f5;
        }
        .conversation-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #2e7d32;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 15px;
            font-size: 20px;
        }
        .conversation-info {
            flex: 1;
        }
        .conversation-name {
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 5px;
        }
        .conversation-preview {
            color: #666;
            font-size: 14px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .conversation-badge {
            background: #d32f2f;
            color: #fff;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        }
        .no-conversations {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        .btn-logout {
            background: #d32f2f;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 5px;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h3>Edu Platform</h3>
        <nav class="nav flex-column">
            <a class="nav-link" href="/dashboard">Dashboard</a>
            @if($role == 'Teacher')
                <a class="nav-link" href="/courses">Manage Courses</a>
                <a class="nav-link" href="/students">Manage Students</a>
            @else
                <a class="nav-link" href="/my_courses">My Courses</a>
            @endif
            <a class="nav-link active" href="/chat">Chat</a>
        </nav>
        <form method="POST" action="{{ route('logout') }}" style="padding: 20px; position: absolute; bottom: 0; width: 100%;">
            @csrf
            <button type="submit" class="btn-logout">Logout</button>
        </form>
    </div>

    <div class="content">
        <div class="chat-container">
            <h2>Messages</h2>
            
            <div class="new-chat-section">
                <h5>Start New Conversation</h5>
                <form action="/chat/start" method="POST" class="user-select">
                    @csrf
                    <select name="user_id" required>
                        <option value="">Select {{ $role == 'Teacher' ? 'Student' : 'Teacher' }}</option>
                        @foreach($availableUsers as $availableUser)
                            <option value="{{ $availableUser->id }}">
                                {{ $availableUser->first_name }} {{ $availableUser->last_name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn-start-chat">Start Chat</button>
                </form>
            </div>

            <h5>Your Conversations</h5>
            
            @if($conversations->count() > 0)
                <ul class="conversation-list">
                    @foreach($conversations as $conversation)
                        <a href="/chat/{{ $conversation->id }}" class="conversation-item">
                            <div class="conversation-avatar">
                                @if($role == 'Teacher')
                                    {{ strtoupper(substr($conversation->student->first_name, 0, 1)) }}
                                @else
                                    {{ strtoupper(substr($conversation->teacher->first_name, 0, 1)) }}
                                @endif
                            </div>
                            <div class="conversation-info">
                                <div class="conversation-name">
                                    @if($role == 'Teacher')
                                        {{ $conversation->student->first_name }} {{ $conversation->student->last_name }}
                                    @else
                                        {{ $conversation->teacher->first_name }} {{ $conversation->teacher->last_name }}
                                    @endif
                                </div>
                                <div class="conversation-preview">
                                    @if($conversation->latestMessage)
                                        {{ Str::limit($conversation->latestMessage->message, 50) }}
                                    @else
                                        No messages yet
                                    @endif
                                </div>
                            </div>
                            @if($conversation->unread_count > 0)
                                <div class="conversation-badge">{{ $conversation->unread_count }}</div>
                            @endif
                        </a>
                    @endforeach
                </ul>
            @else
                <div class="no-conversations">
                    <p>No conversations yet. Start a new chat above!</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        // Auto-refresh to check for new messages every 10 seconds
        setTimeout(function() {
            location.reload();
        }, 10000);
    </script>
</body>
</html>
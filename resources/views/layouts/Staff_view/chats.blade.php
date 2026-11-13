<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Staff Portal - Chat Support Workspace</title>
    <style>
        body {
            margin: 0;
            font-family: system-ui, -apple-system, sans-serif;
            background: #1e1e1e;
            color: #fff;
            display: flex;
            height: 100vh;
        }

        .chat-sessions-sidebar {
            width: 300px;
            background: #252525;
            border-right: 1px solid #333;
            display: flex;
            flex-direction: column;
        }
        .sidebar-header {
            padding: 20px;
            background: #2d2d2d;
            border-bottom: 1px solid #333;
            font-size: 16px;
            font-weight: bold;
        }
        .sessions-list {
            flex: 1;
            overflow-y: auto;
            padding: 10px;
        }
        .session-card {
            width: 100%;
            background: #333;
            border: none;
            color: white;
            padding: 15px;
            text-align: left;
            border-radius: 6px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .session-card:hover, .session-card.active {
            background: #007bff;
        }

        .workspace-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #1c1c1c;
        }
        .workspace-header {
            padding: 18px 20px;
            background: #252525;
            border-bottom: 1px solid #333;
            font-weight: bold;
        }
        .messages-display {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .bubble {
            max-width: 65%;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 14px;
            line-height: 1.4;
            overflow-wrap: break-word;
            word-break: break-word;
            width: fit-content; 
        }
        .bubble.customer {
            background: #3a3a3a;
            color: white;
            align-self: flex-start;
        }
        .bubble.staff {
            background: #007bff;
            color: white;
            align-self: flex-end;
            margin-left: auto;
        }

        .workspace-chat {
            background: #252525;
            padding: 15px 20px;
            display: flex;
            gap: 12px;
            border-top: 1px solid #333;
        }
        .workspace-chat input {
            flex: 1;
            padding: 12px;
            background: #333;
            border: 1px solid #444;
            border-radius: 6px;
            color: white;
            outline: none;
        }
        .workspace-chat button {
            background: #007bff;
            color: white;
            border: none;
            padding: 0 24px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }
        .workspace-chat button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

    <!-- Sidebar thing -->
    <div class="chat-sessions-sidebar">
        <div class="sidebar-header">Active Conversations</div>
        <div class="sessions-list" id="sessionsList">>
        </div>
    </div>

    <div class="workspace-panel">
        <div class="workspace-header" id="currentChatHeader">Select a chat from the sidebar to begin</div>
        
        <div class="messages-display" id="chatStream">
            <!-- Messages stream -->
        </div>

        <div class="workspace-chat">
            <input type="text" id="staffReplyField" placeholder="Type a response to the customer..." onkeypress="checkEnter(event)">
            <button onclick="submitStaffReply()">Send Reply</button>
        </div>
    </div>

<script>
    let currentActiveSession = null;
    let messageTimer = null;
    let roomTrackerTimer = null;

    window.onload = function() {
        fetchActiveRooms();
        roomTrackerTimer = setInterval(fetchActiveRooms, 4000);
    };

    function fetchActiveRooms() {
        fetch('/chat/rooms')
            .then(res => res.json())
            .then(rooms => {
                const sidebarContainer = document.getElementById('sessionsList');
                sidebarContainer.innerHTML = ''; 

                if (rooms.length === 0) {
                    sidebarContainer.innerHTML = '<div style="padding: 15px; color: #888;">No active chats</div>';
                    return;
                }

                rooms.forEach(room => {
                    const button = document.createElement('button');
                    button.className = 'session-card';

                    if (currentActiveSession === room.session_id) {
                        button.classList.add('active');
                    }

                    const cleanName = room.session_id.replace('_', ' #');
                    button.innerText = cleanName.charAt(0).toUpperCase() + cleanName.slice(1);

                    button.onclick = function() {
                        selectChat(room.session_id);
                    };

                    sidebarContainer.appendChild(button);
                });
            })
            .catch(err => console.error('Error listing rooms:', err));
    }

    function selectChat(sessionId) {
        currentActiveSession = sessionId;
        document.getElementById('currentChatHeader').innerText = `Managing Conversation: ${sessionId}`;

        fetchMessages();

        clearInterval(messageTimer);
        messageTimer = setInterval(fetchMessages, 3000);
    }

    function fetchMessages() {
        if (!currentActiveSession) return;

        fetch(`/chat/messages/${currentActiveSession}`)
            .then(res => res.json())
            .then(messages => {
                const stream = document.getElementById('chatStream');
                stream.innerHTML = '';

                messages.forEach(msg => {
                    const bubble = document.createElement('div');
                    bubble.className = `bubble ${msg.sender_type}`;
                    bubble.innerText = msg.message;
                    stream.appendChild(bubble);
                });
                stream.scrollTop = stream.scrollHeight; 
            });
    }

    // 4. Send Response Action
    function submitStaffReply() {
        const inputField = document.getElementById('staffReplyField');
        const txt = inputField.value.trim();
        if (txt === '' || !currentActiveSession) return;

        fetch('/chat/send', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                session_id: currentActiveSession,
                sender_type: 'staff',
                message: txt
            })
        }).then(() => {
            inputField.value = '';
            fetchMessages();
        });
    }

    function checkEnter(e) {
        if (e.key === 'Enter') submitStaffReply();
    }
</script>

</body>
</html>

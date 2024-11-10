<?php
include_once '../../app/Controllers/db.php';
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
?>
<?php include(__DIR__ . '/../components/header.php'); ?>
<?php include(__DIR__ . '/../components/sidebar.php'); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat com Suporte</title>
    <style>
        * { font-family: Arial, sans-serif; box-sizing: border-box; }
        .chat-popup {
            width: 90%; max-width: 900px; padding: 20px;
            background-color: #fff; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px; position: relative; margin: 60px auto;
        }
        .messages {
            border: 1px solid #ddd; padding: 10px; height: 300px; overflow-y: auto; margin-bottom: 10px;
            background-color: #f9f9f9; border-radius: 5px;
        }
        .message {
            display: flex; align-items: center; margin-bottom: 10px; padding: 8px;
            border-radius: 8px; max-width: 80%;
        }
        .message.user { justify-content: flex-start; background-color: #e0f7fa; text-align: left; }
        .message.admin { justify-content: flex-end; background-color: #d1c4e9; text-align: right; }
        .avatar {
            width: 40px; height: 40px; border-radius: 50%; background-color: #4a3aff; color: #fff;
            display: flex; align-items: center; justify-content: center; font-weight: bold; margin-right: 10px;
        }
        .input-container { display: flex; gap: 10px; align-items: center; }
        input[type="text"], input[type="file"] { flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        button { padding: 10px; background-color: #4a3aff; color: #fff; cursor: pointer; border: none; }
        button.audio-button, button.close-chat { background-color: #ff5252; }
        .chat-history {
            margin-top: 15px; background-color: #f5f5f5; padding: 10px; border-radius: 5px;
        }
        .chat-history table { width: 100%; border-collapse: collapse; }
        .chat-history th, .chat-history td { padding: 10px; border: 1px solid #ddd; text-align: center; }
        #chat-view-popup { position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: white;
            padding: 20px; border-radius: 8px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); display: none; z-index: 1000;
        }
    </style>
</head>
<body>
    <div class="chat-popup" id="chat-popup">
        <h2>Chat com o Suporte</h2>
        <button class="close-chat" onclick="closeChat()">Fechar Chat</button>
        <button onclick="newChat()">Novo Chat</button>
        <div class="messages" id="messages"></div>
        <form id="chat-form" enctype="multipart/form-data">
            <div class="input-container">
                <input type="text" id="message" name="message" placeholder="Digite sua mensagem">
                <button type="button" class="audio-button" onclick="startRecording()">🎤</button>
                <input type="file" id="file" name="file">
                <button type="submit">Enviar</button>
            </div>
        </form>
        <div class="chat-history">
            <h4>Conversas Anteriores</h4>
            <table>
                <thead>
                    <tr><th>ID do Chat</th><th>Data</th><th>Ações</th></tr>
                </thead>
                <tbody id="chat-history-list"></tbody>
            </table>
        </div>
    </div>

    <div id="chat-view-popup">
        <div class="popup-content">
            <h4>Mensagens do Chat</h4>
            <div id="popup-messages"></div>
            <button onclick="closePopup()">Fechar</button>
        </div>
    </div>

    <script>
        let mediaRecorder;
        let audioChunks = [];

        function startRecording() {
            navigator.mediaDevices.getUserMedia({ audio: true }).then(stream => {
                mediaRecorder = new MediaRecorder(stream);
                mediaRecorder.start();
                mediaRecorder.ondataavailable = event => audioChunks.push(event.data);
                mediaRecorder.onstop = sendAudio;
            });
        }

        function sendAudio() {
            const audioBlob = new Blob(audioChunks, { type: 'audio/mpeg' });
            const formData = new FormData();
            formData.append('file', audioBlob, 'recording.mp3');
            fetch('../../app/Controllers/send_message.php', { method: 'POST', body: formData }).then(() => loadMessages());
            audioChunks = [];
        }

        function closeChat() {
            fetch('../../app/Controllers/close_chat.php', { method: 'POST' })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') alert(data.message);
                    loadChatHistory();
                });
        }

        function newChat() {
            fetch('../../app/Controllers/new_chat.php', { method: 'POST' })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') alert(data.message);
                    loadChatHistory();
                });
        }

        function loadMessages() {
            fetch(`../../app/Controllers/fetch_messages.php?user_id=<?php echo $user_id; ?>`)
                .then(response => response.json())
                .then(data => {
                    const messagesDiv = document.getElementById('messages');
                    messagesDiv.innerHTML = '';
                    data.forEach(msg => {
                        const messageDiv = document.createElement('div');
                        messageDiv.classList.add('message', msg.sender.toLowerCase());
                        const text = document.createElement('div');
                        text.innerHTML = msg.message ? `<p>${msg.message}</p>` : `<a href="../../public/uploads/${msg.file_path}" download>Baixar Arquivo</a>`;
                        messageDiv.appendChild(text);
                        messagesDiv.appendChild(messageDiv);
                    });
                    messagesDiv.scrollTop = messagesDiv.scrollHeight;
                });
        }

        function loadChatHistory() {
            fetch('../../app/Controllers/chat_history.php?user_id=<?php echo $user_id; ?>')
                .then(response => response.json())
                .then(data => {
                    const historyList = document.getElementById('chat-history-list');
                    historyList.innerHTML = '';
                    data.forEach(chat => {
                        const row = document.createElement('tr');
                        row.innerHTML = `<td>${chat.id}</td><td>${chat.date}</td><td><button onclick="viewChat(${chat.id})">Ver Chat</button></td>`;
                        historyList.appendChild(row);
                    });
                });
        }

        function viewChat(chatId) {
            fetch(`../../app/Controllers/fetch_chat.php?chat_id=${chatId}`)
                .then(response => response.json())
                .then(data => {
                    const popupMessages = document.getElementById('popup-messages');
                    popupMessages.innerHTML = '';
                    data.forEach(msg => {
                        const messageDiv = document.createElement('div');
                        messageDiv.classList.add('message', msg.sender.toLowerCase());
                        messageDiv.textContent = msg.message || `Arquivo: ${msg.file_path}`;
                        popupMessages.appendChild(messageDiv);
                    });
                    document.getElementById('chat-view-popup').style.display = 'block';
                });
        }

        function closePopup() {
            document.getElementById('chat-view-popup').style.display = 'none';
        }

        loadChatHistory();
        setInterval(loadMessages, 2000);
    </script>
</body>
</html>

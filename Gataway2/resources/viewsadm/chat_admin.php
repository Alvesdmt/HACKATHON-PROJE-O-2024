<?php
include_once '../../app/Controllers/db.php';
session_start();

// Verifica se o administrador está logado
if (!isset($_SESSION['admin_id'])) {
    header("Location: /Gataway2/resources/views/loginadm.php");
    exit();
}

// Busca todos os usuários com chats abertos
$sql = "SELECT users.id, users.name 
        FROM users 
        INNER JOIN tickets ON users.id = tickets.user_id 
        WHERE tickets.status = 'Aberto'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include(__DIR__ . '/components\headeradm.php'); ?>

<?php include(__DIR__ . '/components\sidebaradm.php'); ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Chat do Administrador</title>
   <style>
        * { font-family: Arial, sans-serif; box-sizing: border-box; }
        .chat-popup {
            width: 90%;
            max-width: 900px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px; 
            margin: 60px auto;
            position: relative;
        }
        .messages {
            border: 1px solid #ddd;
            padding: 10px;
            height: 300px;
            overflow-y: auto;
            margin-bottom: 10px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        .message {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            padding: 8px;
            border-radius: 8px;
            max-width: 80%;
        }
        .message.user {
            justify-content: flex-start;
            background-color: #e0f7fa;
            text-align: left;
        }
        .message.admin {
            justify-content: flex-end;
            background-color: #d1c4e9;
            text-align: right;
        }
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #4a3aff;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 10px;
        }
        .input-container {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        input[type="text"], input[type="file"] {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            padding: 10px;
            background-color: #4a3aff;
            color: #fff;
            cursor: pointer;
            border: none;
        }
        button.audio-button, button.close-chat {
            background-color: #ff5252;
        }
        select {
            margin-bottom: 15px;
            padding: 10px;
        }
        .chat-history {
            margin-top: 15px;
            background-color: #f5f5f5;
            padding: 10px;
            border-radius: 5px;
        }
        .chat-history h4 {
            margin-bottom: 5px;
        }
        .chat-history ul {
            list-style: none;
            padding: 0;
        }
        .chat-history ul li {
            padding: 5px;
            cursor: pointer;
            background-color: #e8eaf6;
            margin-bottom: 5px;
            border-radius: 5px;
        }
        .chat-history ul li:hover {
            background-color: #c5cae9;
        }
    </style>
</head>
<body>
    <div class="chat-popup" id="chat-popup">
        <h2>Painel de Chat do Administrador</h2>
        <button class="close-chat" onclick="closeChat()">Fechar Chat</button>
        <button onclick="newChat()">Novo Chat</button>

        <!-- Seleção do usuário -->
        <label for="user_select">Selecionar Usuário:</label>
        <select id="user_select" onchange="loadMessages()">
            <option value="">Escolha um usuário</option>
            <?php foreach ($users as $user): ?>
                <option value="<?php echo $user['id']; ?>"><?php echo htmlspecialchars($user['name']); ?></option>
            <?php endforeach; ?>
        </select>
        
        <div class="messages" id="messages"></div>
        
        <!-- Formulário de envio de mensagens e arquivos -->
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
            <ul id="chat-history-list">
                <!-- Lista de chats anteriores será carregada aqui -->
            </ul>
        </div>
    </div>

    <script>
        let selectedUserId = null;
        let mediaRecorder;
        let audioChunks = [];

        // Função para iniciar um novo chat
        function newChat() {
            const userId = prompt("Digite o ID do usuário para iniciar um novo chat:");
            if (userId) {
                fetch('../../app/Controllers/start_new_chat.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `user_id=${userId}`
                }).then(response => response.json()).then(data => {
                    if (data.status === 'success') {
                        alert('Novo chat iniciado.');
                        loadUsers();
                    } else {
                        alert('Erro ao iniciar novo chat.');
                    }
                });
            }
        }

        // Função para fechar o chat
        function closeChat() {
            if (!selectedUserId) {
                alert('Selecione um usuário para fechar o chat.');
                return;
            }
            fetch('../../app/Controllers/close_chat.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `user_id=${selectedUserId}`
            }).then(response => response.json()).then(data => {
                if (data.status === 'success') {
                    alert('Chat fechado com sucesso.');
                    loadUsers();
                } else {
                    alert('Erro ao fechar o chat.');
                }
            });
        }

        function startRecording() {
            navigator.mediaDevices.getUserMedia({ audio: true })
                .then(stream => {
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
            formData.append('user_id', selectedUserId);

            fetch('../../app/Controllers/send_message.php', {
                method: 'POST',
                body: formData
            }).then(response => response.json()).then(() => loadMessages());
            audioChunks = [];
        }

        function loadMessages() {
            selectedUserId = document.getElementById('user_select').value;
            if (!selectedUserId) {
                document.getElementById('messages').innerHTML = '<p>Selecione um usuário para ver as mensagens.</p>';
                return;
            }
            fetch(`../../app/Controllers/fetch_messages.php?user_id=${selectedUserId}`)
                .then(response => response.json())
                .then(data => {
                    const messagesDiv = document.getElementById('messages');
                    messagesDiv.innerHTML = '';
                    data.forEach(msg => {
                        const messageDiv = document.createElement('div');
                        messageDiv.classList.add('message', msg.sender.toLowerCase());

                        const avatar = document.createElement('div');
                        avatar.classList.add('avatar');
                        avatar.innerText = msg.sender === 'User' ? msg.sender.charAt(0).toUpperCase() : 'A';

                        const text = document.createElement('div');
                        text.innerHTML = msg.message ? `<p>${msg.message}</p>` : `<a href="../../public/uploads/${msg.file_path}" download>Baixar Arquivo</a>`;
                        
                        messageDiv.appendChild(avatar);
                        messageDiv.appendChild(text);
                        messagesDiv.appendChild(messageDiv);
                    });
                    messagesDiv.scrollTop = messagesDiv.scrollHeight;
                });
        }

        setInterval(() => {
            if (selectedUserId) loadMessages();
        }, 2000);

        document.getElementById('chat-form').addEventListener('submit', function(event) {
            event.preventDefault();
            if (!selectedUserId) {
                alert('Selecione um usuário para enviar uma mensagem.');
                return;
            }
            const formData = new FormData(this);
            formData.append('user_id', selectedUserId);
            fetch('../../app/Controllers/send_message.php', {
                method: 'POST',
                body: formData
            }).then(response => response.json()).then(() => {
                document.getElementById('message').value = '';
                document.getElementById('file').value = '';
                loadMessages();
            });
        });

        function loadUsers() {
            location.reload();
        }
    </script>
</body>
</html>

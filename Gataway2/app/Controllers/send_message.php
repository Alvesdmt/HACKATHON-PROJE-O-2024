<?php
include_once '/db.php';
session_start();

$user_id = $_POST['user_id'] ?? null; // ID do usuário para quem a mensagem será enviada
$admin_id = $_SESSION['admin_id'] ?? null;
$sender = $admin_id ? 'Admin' : 'User';
$message = $_POST['message'] ?? '';
$file_path = null;

// Verifica se o ID do usuário está definido
if (!$user_id) {
    echo json_encode(['status' => 'error', 'message' => 'Usuário não definido']);
    exit();
}

try {
    // Processa o upload do arquivo, se houver
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $target_dir = '../../public/uploads/';
        $file_path = $target_dir . basename($_FILES['file']['name']);
        move_uploaded_file($_FILES['file']['tmp_name'], $file_path);
        $file_path = basename($file_path); // Apenas o nome do arquivo para salvar no banco
    }

    // Insere a mensagem ou o arquivo no banco de dados
    $sql = "INSERT INTO chat_messages (sender, user_id, admin_id, message, file_path) VALUES (:sender, :user_id, :admin_id, :message, :file_path)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':sender', $sender);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':admin_id', $admin_id);
    $stmt->bindParam(':message', $message);
    $stmt->bindParam(':file_path', $file_path);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao salvar mensagem']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao enviar mensagem']);
}
?>

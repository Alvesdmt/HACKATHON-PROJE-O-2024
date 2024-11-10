<?php
include_once '/db.php';
session_start();

// Verifica se o admin ou o user está logado
if (!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])) {
    echo json_encode([]);
    exit();
}

$user_id = $_GET['user_id'] ?? null; // ID do usuário para o qual estamos buscando mensagens

// Verifica se o ID do usuário foi fornecido
if (!$user_id) {
    echo json_encode([]);
    exit();
}

try {
    // Recupera todas as mensagens entre o usuário e o administrador
    $sql = "SELECT * FROM chat_messages WHERE user_id = :user_id ORDER BY sent_at ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($messages);
} catch (Exception $e) {
    echo json_encode([]);
}
?>

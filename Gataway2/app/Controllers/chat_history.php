<?php
include_once '/db.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;

if ($user_id) {
    $sql = "SELECT id, created_at AS date FROM tickets WHERE user_id = :user_id ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $chats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($chats);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Usuário não autenticado.']);
}
?>

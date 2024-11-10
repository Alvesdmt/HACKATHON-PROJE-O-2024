<?php
include_once '/db.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;

if ($user_id) {
    // Atualiza o status do ticket para "Concluído"
    $sql = "UPDATE tickets SET status = 'Concluído', updated_at = NOW() WHERE user_id = :user_id AND status = 'Aberto'";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Chat fechado com sucesso.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao fechar o chat.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Usuário não autenticado.']);
}
?>

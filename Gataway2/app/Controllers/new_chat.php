<?php
include_once '/db.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;

if ($user_id) {
    // Verifica se o usuário já tem um chat aberto
    $sql = "SELECT id FROM tickets WHERE user_id = :user_id AND status = 'Aberto'";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    if ($stmt->rowCount() == 0) {
        // Cria um novo chat para o usuário
        $sql = "INSERT INTO tickets (user_id, status, created_at) VALUES (:user_id, 'Aberto', NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Novo chat iniciado.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao iniciar um novo chat.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Você já possui um chat aberto.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Usuário não autenticado.']);
}
?>

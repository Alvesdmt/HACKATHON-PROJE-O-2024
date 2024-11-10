<?php
include_once 'db.php';

$user_id = $_POST['user_id'];
$status = $_POST['status'];

// Atualiza o status de ambientação
$sql = "INSERT INTO onboarding_status (user_id, status) VALUES (?, ?) ON DUPLICATE KEY UPDATE status = VALUES(status)";
$stmt = $conn->prepare($sql);
$success = $stmt->execute([$user_id, $status]);

echo json_encode(['status' => $success ? 'success' : 'error']);
?>

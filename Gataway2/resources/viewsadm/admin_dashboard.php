<?php
include_once '../../app/Controllers/db.php';
session_start();

// Verifica se o administrador está logado
if (!isset($_SESSION['admin_id'])) {
    header("Location: /Gataway2/resources/views/loginadm.php");
    exit();
}

$message = '';

// Processa a resposta do administrador
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['requirement_id']) && isset($_POST['admin_response'])) {
    $requirement_id = $_POST['requirement_id'];
    $admin_response = $_POST['admin_response'];
    $status = $_POST['status'];

    $sql = "UPDATE requirements SET admin_response = :admin_response, status = :status WHERE id = :requirement_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':admin_response', $admin_response);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':requirement_id', $requirement_id);

    if ($stmt->execute()) {
        $message = "Resposta enviada com sucesso!";
    } else {
        $message = "Erro ao enviar resposta.";
    }
}

// Busca os requerimentos enviados pelos usuários
$sql_requirements = "SELECT r.id, r.requirement_type, r.file_path, r.upload_date, r.status, r.admin_response, u.name AS user_name 
                     FROM requirements r 
                     JOIN users u ON r.user_id = u.id 
                     ORDER BY r.upload_date DESC";
$stmt_requirements = $conn->prepare($sql_requirements);
$stmt_requirements->execute();
$requirements = $stmt_requirements->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include(__DIR__ . '/components\headeradm.php'); ?>

<?php include(__DIR__ . '/components\sidebaradm.php'); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Requerimentos - Admin</title>
    <style>
        * { font-family: Arial, sans-serif; }
        .container { max-width: 800px; margin: 0 auto; padding: 20px; background-color: #f7f9fc; }
        h2 { text-align: center; color: #333; }
        .message { text-align: center; padding: 10px; background-color: #dff0d8; color: #3c763d; border-radius: 5px; margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { padding: 10px; border: 1px solid #ddd; }
        .table th { background-color: #4a3aff; color: #fff; }
        .table tr:hover { background-color: #f1f1f1; }
        .form-response { margin-top: 10px; display: flex; flex-direction: column; }
        textarea { width: 100%; padding: 10px; margin-bottom: 10px; }
        .form-response button { padding: 10px; background-color: #4a3aff; color: #fff; border: none; cursor: pointer; }
        .form-response button:hover { background-color: #3b2ecf; }
    </style>
</head>
<body>
    <div class="container">
        <br><br><br><h2>Painel de Requerimentos</h2>
        <?php if ($message): ?><p class="message"><?php echo htmlspecialchars($message); ?></p><?php endif; ?>

        <table class="table">
            <thead>
                <tr>
                    <th>Usuário</th>
                    <th>Tipo de Requerimento</th>
                    <th>Data de Envio</th>
                    <th>Arquivo</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($requirements as $requirement): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($requirement['user_name']); ?></td>
                        <td><?php echo htmlspecialchars($requirement['requirement_type']); ?></td>
                        <td><?php echo htmlspecialchars($requirement['upload_date']); ?></td>
                        <td>
                            <?php if ($requirement['file_path']): ?>
                                <a href="../../public/uploads/<?php echo htmlspecialchars($requirement['file_path']); ?>" download>Baixar</a>
                            <?php else: ?>
                                Não disponível
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($requirement['status']); ?></td>
                        <td>
                            <!-- Formulário de Resposta do Admin -->
                            <form class="form-response" method="POST">
                                <input type="hidden" name="requirement_id" value="<?php echo $requirement['id']; ?>">
                                <label>Responder:</label>
                                <textarea name="admin_response"><?php echo htmlspecialchars($requirement['admin_response']); ?></textarea>
                                <label>Status:</label>
                                <select name="status" required>
                                    <option value="Pendente" <?php if ($requirement['status'] === 'Pendente') echo 'selected'; ?>>Pendente</option>
                                    <option value="Em Análise" <?php if ($requirement['status'] === 'Em Análise') echo 'selected'; ?>>Em Análise</option>
                                    <option value="Concluído" <?php if ($requirement['status'] === 'Concluído') echo 'selected'; ?>>Concluído</option>
                                </select>
                                <button type="submit">Enviar Resposta</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

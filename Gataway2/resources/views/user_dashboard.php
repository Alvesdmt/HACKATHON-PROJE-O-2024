<?php
include_once '../../app/Controllers/db.php';
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';

// Solicitação de documento
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['document_type'])) {
    $document_type = $_POST['document_type'];
    $sql = "INSERT INTO document_requests (user_id, document_type, status) VALUES (:user_id, :document_type, 'Pendente')";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':document_type', $document_type);
    if ($stmt->execute()) {
        $message = "Solicitação de documento enviada com sucesso!";
    } else {
        $message = "Erro ao enviar solicitação de documento.";
    }
}

// Upload de arquivo para requisitos específicos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['requirement_type']) && isset($_FILES['requirement_file'])) {
    $requirement_type = $_POST['requirement_type'];
    $file_path = '../../public/uploads/' . basename($_FILES['requirement_file']['name']);
    if (move_uploaded_file($_FILES['requirement_file']['tmp_name'], $file_path)) {
        $sql = "INSERT INTO requirements (user_id, requirement_type, file_path) VALUES (:user_id, :requirement_type, :file_path)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':requirement_type', $requirement_type);
        $stmt->bindParam(':file_path', $file_path);
        if ($stmt->execute()) {
            $message = "Arquivo anexado com sucesso!";
        } else {
            $message = "Erro ao anexar arquivo.";
        }
    } else {
        $message = "Erro ao fazer upload do arquivo.";
    }
}

// Busca as solicitações de documentos do usuário
$sql_documents = "SELECT * FROM document_requests WHERE user_id = :user_id ORDER BY request_date DESC";
$stmt_documents = $conn->prepare($sql_documents);
$stmt_documents->bindParam(':user_id', $user_id);
$stmt_documents->execute();
$document_requests = $stmt_documents->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include(__DIR__ . '/../components/header.php'); ?>
<?php include(__DIR__ . '/../components/sidebar.php'); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área do Aluno</title>
    <style>
        * { font-family: Arial, sans-serif; box-sizing: border-box; }
        body {
            display: flex;
            height: 100vh;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f7f9fc;
        }
        h2 { color: #333; margin-bottom: 20px; text-align: center; }
        .message { color: green; padding: 10px; margin-bottom: 20px; border-radius: 5px; text-align: center; }
        form { display: flex; flex-direction: column; margin: 60   auto; margin-bottom: 20px; }
        label, select, button { margin-bottom: 15px; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        button { background-color: #4a3aff; color: #fff; cursor: pointer; transition: background-color 0.3s; }
        button:hover { background-color: #3b2ecf; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        .table th { background-color: #4a3aff; color: #fff; }
        .table td { background-color: #f9f9f9; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Área do Aluno</h2>
        <?php if ($message): ?><p class="message"><?php echo htmlspecialchars($message); ?></p><?php endif; ?>

        <!-- Solicitação de Documento -->
        <form method="POST">
            <label for="document_type">Solicitar Documento:</label>
            <select name="document_type" id="document_type" required>
                <option value="Declaração de Escolaridade">Declaração de Escolaridade</option>
                <option value="Histórico Escolar">Histórico Escolar</option>
                <option value="Diploma">Diploma</option>
            </select>
            <button type="submit">Enviar Solicitação</button>
        </form>

        <!-- Upload de Arquivo para Requisitos -->
        <form method="POST" enctype="multipart/form-data">
            <label for="requirement_type">Anexar Arquivo:</label>
            <select name="requirement_type" id="requirement_type" required>
                <option value="Atendimento Eletrônico">Atendimento Eletrônico</option>
                <option value="Requerimento Financeiro">Requerimento Financeiro</option>
            </select>
            <input type="file" name="requirement_file" required>
            <button type="submit">Anexar</button>
        </form>

        <!-- Exibir Solicitações de Documentos -->
        <h3>Solicitações de Documentos</h3>
        <table class="table">
            <thead><tr><th>Documento</th><th>Status</th><th>Data</th><th>Arquivo</th></tr></thead>
            <tbody>
                <?php foreach ($document_requests as $request): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($request['document_type']); ?></td>
                        <td><?php echo htmlspecialchars($request['status']); ?></td>
                        <td><?php echo htmlspecialchars($request['request_date']); ?></td>
                        <td>
                            <?php if ($request['document_file']): ?>
                                <a href="../../public/uploads/<?php echo htmlspecialchars($request['document_file']); ?>" download>Baixar</a>
                            <?php else: ?>
                                Não disponível
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

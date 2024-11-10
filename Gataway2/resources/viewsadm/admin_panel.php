
<?php
include_once '../../app/Controllers/db.php';
session_start();

// Verifica se o administrador está logado
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$message = '';

// Processa o upload do documento
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_id']) && isset($_FILES['document_file'])) {
    $request_id = $_POST['request_id'];
    $status = 'Concluído';

    // Diretório de upload
    $upload_dir = '../../public/uploads/';
    $document_file = basename($_FILES['document_file']['name']);
    $target_file = $upload_dir . $document_file;

    // Verifica se o arquivo foi enviado com sucesso
    if (move_uploaded_file($_FILES['document_file']['tmp_name'], $target_file)) {
        // Atualiza a solicitação na tabela `document_requests` com o status e o nome do arquivo
        $sql = "UPDATE document_requests SET status = :status, document_file = :document_file WHERE id = :request_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':document_file', $document_file);
        $stmt->bindParam(':request_id', $request_id);

        if ($stmt->execute()) {
            $message = "Documento enviado e status atualizado com sucesso!";
        } else {
            $message = "Erro ao atualizar o status no banco de dados.";
        }
    } else {
        $message = "Erro ao fazer upload do arquivo.";
    }
}

// Busca todas as solicitações pendentes na tabela `document_requests`
$sql = "SELECT dr.id AS request_id, u.name, dr.document_type, dr.status, dr.request_date 
        FROM document_requests dr
        JOIN users u ON dr.user_id = u.id
        WHERE dr.status = 'Pendente'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include(__DIR__ . '/components\headeradm.php'); ?>

<?php include(__DIR__ . '/components\sidebaradm.php'); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Admin - Gerenciar Solicitações</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

    

        .container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
        background-color: #f7f9fc;
        border-radius: 8px;
    }

        h2 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        .message {
            color: green;
            background-color: #dff0d8;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th, .table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .table th {
            background-color: #4a3aff;
            color: #fff;
        }

        .table td {
            background-color: #f9f9f9;
        }

        .table tr:hover {
            background-color: #f1f1f1;
        }

        button {
            padding: 8px 12px;
            background-color: #4a3aff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        button:hover {
            background-color: #3b2ecf;
        }

        input[type="file"] {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <br><br><br><h2>Painel de Administração - Gerenciar Solicitações</h2>

        <?php if ($message): ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <table class="table">
            <thead>
                <tr>
                    <th>Usuário</th>
                    <th>Documento Solicitado</th>
                    <th>Data da Solicitação</th>
                    <th>Status</th>
                    <th>Upload de Documento</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($requests as $request): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($request['name']); ?></td>
                        <td><?php echo htmlspecialchars($request['document_type']); ?></td>
                        <td><?php echo htmlspecialchars($request['request_date']); ?></td>
                        <td><?php echo htmlspecialchars($request['status']); ?></td>
                        <td>
                            <form action="admin_panel.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="request_id" value="<?php echo $request['request_id']; ?>">
                                <input type="file" name="document_file" required>
                                <button type="submit">Enviar Documento</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

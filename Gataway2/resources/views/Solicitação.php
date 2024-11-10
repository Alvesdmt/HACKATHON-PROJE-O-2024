<?php
include_once '../../app/Controllers/db.php';

$message = ''; // Mensagem para o usuário

$user_id = 1; // Substitua pelo ID do usuário logado

// Verifica se uma nova solicitação foi enviada
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['document_type'])) {
    $document_type = $_POST['document_type'];
    $status = 'Pendente';

    // Insere a nova solicitação de documento na tabela `document_requests`
    $sql = "INSERT INTO document_requests (user_id, document_type, status, request_date) VALUES (:user_id, :document_type, :status, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':document_type', $document_type);
    $stmt->bindParam(':status', $status);

    if ($stmt->execute()) {
        $message = "Solicitação de documento enviada com sucesso!";
    } else {
        $message = "Erro ao enviar solicitação de documento.";
    }
}

// Busca todas as solicitações do usuário na tabela `document_requests`
$sql = "SELECT document_type, status, request_date, document_file FROM document_requests WHERE user_id = :user_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include(__DIR__ . '/../components/header.php'); ?>
<?php include(__DIR__ . '/../components/sidebar.php'); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitação de Documentos</title>
    <link rel="stylesheet" href="../../public/css/solicitacao_documento.css">
</head>
<body>
    <div class="container">
        <h2>Solicitação de Documentos</h2>

        <?php if ($message): ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form method="POST">
            <label for="document_type">Selecione o tipo de documento:</label>
            <select name="document_type" id="document_type" required>
                <option value="">Escolha um documento</option>
                <option value="Histórico Escolar">Histórico Escolar</option>
                <option value="Declaração de Matrícula">Declaração de Matrícula</option>
                <option value="Diploma">Diploma</option>
            </select>
            <button type="submit">Solicitar Documento</button>
        </form>

        <!-- Tabela de Solicitações -->
        <h3>Solicitações Realizadas</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Documento Solicitado</th>
                    <th>Status</th>
                    <th>Data da Solicitação</th>
                    <th>Download</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($requests as $request): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($request['document_type']); ?></td>
                        <td><?php echo htmlspecialchars($request['status']); ?></td>
                        <td><?php echo htmlspecialchars($request['request_date']); ?></td>
                        <td>
                            <?php if ($request['document_file']): ?>
                                <a href="../../public/uploads/<?php echo htmlspecialchars($request['document_file']); ?>" target="_blank" download>Download</a>
                            <?php else: ?>
                                Não disponível
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <style>
        /* CSS para estilização */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            height: 100vh;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 60 auto;
            padding: 20px;
            background-color: #f7f9fc;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .message {
            color: green;
            background-color: #dff0d8;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 8px;
            text-align: left;
            color: #333;
            font-weight: bold;
        }

        select, button {
            padding: 10px;
            margin-bottom: 15px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        select {
            width: 100%;
        }

        button {
            background-color: #4a3aff;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #3b2ecf;
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

        a {
            color: #4a3aff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</body>
</html>

<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include_once '../../app/Controllers/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $nome_completo = $_POST['nome_completo'];
    $cpf = $_POST['cpf'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];
    $data_inicio_contrato = $_POST['data_inicio_contrato'];
    $data_pagamento_aluguel = $_POST['data_pagamento_aluguel'];

    function uploadFile($inputName) {
        $targetDir = "../../public/uploads/";
        $fileName = basename($_FILES[$inputName]['name']);
        $targetFilePath = $targetDir . $fileName;

        if (move_uploaded_file($_FILES[$inputName]['tmp_name'], $targetFilePath)) {
            return $fileName;
        }
        return null;
    }

    $rg_cnh_imagem = !empty($_FILES['rg_cnh_imagem']['name']) ? uploadFile('rg_cnh_imagem') : null;
    $foto_cliente = !empty($_FILES['foto_cliente']['name']) ? uploadFile('foto_cliente') : null;
    $contrato_anexo = !empty($_FILES['contrato_anexo']['name']) ? uploadFile('contrato_anexo') : null;

    $valor_aluguel = $_POST['valor_aluguel']; // Novo campo para valor do aluguel

    $sql = "INSERT INTO clientes (user_id, nome_completo, cpf, telefone, endereco, rg_cnh_imagem, foto_cliente, contrato_anexo, data_inicio_contrato, data_pagamento_aluguel, valor_aluguel, status_pagamento) 
            VALUES (:user_id, :nome_completo, :cpf, :telefone, :endereco, :rg_cnh_imagem, :foto_cliente, :contrato_anexo, :data_inicio_contrato, :data_pagamento_aluguel, :valor_aluguel, 0)";
    $stmt = $conn->prepare($sql);

    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':nome_completo', $nome_completo);
    $stmt->bindParam(':cpf', $cpf);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':endereco', $endereco);
    $stmt->bindParam(':rg_cnh_imagem', $rg_cnh_imagem);
    $stmt->bindParam(':foto_cliente', $foto_cliente);
    $stmt->bindParam(':contrato_anexo', $contrato_anexo);
    $stmt->bindParam(':data_inicio_contrato', $data_inicio_contrato);
    $stmt->bindParam(':data_pagamento_aluguel', $data_pagamento_aluguel, PDO::PARAM_INT);
    $stmt->bindParam(':valor_aluguel', $valor_aluguel);

    if ($stmt->execute()) {
        echo "<div class='success-message'>Cliente cadastrado com sucesso!</div>";
    } else {
        echo "<div class='error-message'>Erro ao cadastrar cliente.</div>";
    }
}
?>

<?php include(__DIR__ . '/../components/header.php'); ?>
<?php include(__DIR__ . '/../components/sidebar.php'); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Cliente</title>
    <link rel="stylesheet" href="../../public/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Cadastrar Cliente</h2>
        <form action="clientes.php" method="POST" enctype="multipart/form-data">
            <label for="nome_completo">Nome Completo *</label>
            <input type="text" id="nome_completo" name="nome_completo" required>

            <label for="cpf">CPF *</label>
            <input type="text" id="cpf" name="cpf" required>

            <label for="telefone">Telefone</label>
            <input type="text" id="telefone" name="telefone">

            <label for="endereco">Endereço</label>
            <input type="text" id="endereco" name="endereco">

            <label for="data_inicio_contrato">Data de Início do Contrato</label>
            <input type="date" id="data_inicio_contrato" name="data_inicio_contrato">

            <label for="data_pagamento_aluguel">Dia do Pagamento do Aluguel</label>
            <select id="data_pagamento_aluguel" name="data_pagamento_aluguel" required>
                <?php for ($i = 1; $i <= 31; $i++): ?>
                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                <?php endfor; ?>
            </select>

            <label for="valor_aluguel">Valor do Aluguel *</label>
            <input type="text" id="valor_aluguel" name="valor_aluguel" required>

            <label for="rg_cnh_imagem">RG ou CNH (imagem)</label>
            <input type="file" id="rg_cnh_imagem" name="rg_cnh_imagem" accept="image/*">

            <label for="foto_cliente">Foto do Cliente</label>
            <input type="file" id="foto_cliente" name="foto_cliente" accept="image/*">

            <label for="contrato_anexo">Anexar Contrato</label>
            <input type="file" id="contrato_anexo" name="contrato_anexo">

            <button type="submit">Cadastrar Cliente</button>
        </form>
    </div>
    <style>
        .container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f7f9fc;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
        }
        input[type="text"],
        input[type="file"],
        select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 15px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #4a3aff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="date"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 15px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .success-message, .error-message {
        text-align: center;
        padding: 10px;
        margin-top: 20px;
        border-radius: 5px;
    }

    .success-message {
        background-color: #dff0d8;
        color: #3c763d;
    }

    .error-message {
        background-color: #f2dede;
        color: #a94442;
    }
    </style>
</body>
</html>

<?php
include_once '../../app/Controllers/db.php';
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// Atualiza os dados do usuário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $course = $_POST['course'];
    
    // Processa o upload da foto de perfil
    $profile_picture = null;
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $target_dir = '../../public/uploads/';
        $profile_picture = $target_dir . basename($_FILES['profile_picture']['name']);
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $profile_picture);
        $profile_picture = basename($profile_picture); // Apenas o nome do arquivo para salvar no banco
    }

    $sql = "UPDATE users SET name = :name, email = :email, phone = :phone, address = :address, course = :course";
    if ($profile_picture) {
        $sql .= ", profile_picture = :profile_picture";
    }
    $sql .= " WHERE id = :user_id";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':course', $course);
    if ($profile_picture) {
        $stmt->bindParam(':profile_picture', $profile_picture);
    }
    $stmt->bindParam(':user_id', $user_id);
    
    if ($stmt->execute()) {
        $message = "Perfil atualizado com sucesso!";
    } else {
        $message = "Erro ao atualizar perfil.";
    }
}

// Busca os dados do usuário
$sql = "SELECT * FROM users WHERE id = :user_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<?php include(__DIR__ . '/../components/header.php'); ?>
<?php include(__DIR__ . '/../components/sidebar.php'); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Usuário</title>
    <style>
        * { font-family: Arial, sans-serif; box-sizing: border-box; }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f7f9fc;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
        }
        h2 { text-align: center; color: #333; }
        form { display: flex; flex-direction: column; gap: 10px; }
        label { font-weight: bold; color: #333; }
        input[type="text"], input[type="email"], input[type="file"] {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            padding: 10px;
            background-color: #4a3aff;
            color: #fff;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .message {
            text-align: center;
            color: green;
            margin-bottom: 10px;
        }
        .profile-picture {
            display: flex;
            justify-content: center;
            margin-bottom: 10px;
        }
        .profile-picture img {
            border-radius: 50%;
            width: 100px;
            height: 100px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Perfil do Usuário</h2>
        <?php if ($message): ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
        
        <!-- Foto de Perfil -->
        <div class="profile-picture">
            <?php if ($user['profile_picture']): ?>
                <img src="../../public/uploads/<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Foto de Perfil">
            <?php else: ?>
                <img src="../../public/images/default-profile.png" alt="Foto de Perfil">
            <?php endif; ?>
        </div>
        
        <form method="POST" enctype="multipart/form-data">
            <label for="name">Nome Completo</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            
            <label for="phone">Telefone</label>
            <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
            
            <label for="address">Endereço</label>
            <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>">

            <label for="course">Curso</label>
            <input type="text" id="course" name="course" value="<?php echo htmlspecialchars($user['course'] ?? ''); ?>">

            <label for="profile_picture">Foto de Perfil</label>
            <input type="file" id="profile_picture" name="profile_picture">
            
            <button type="submit">Atualizar Perfil</button>
        </form>
    </div>
</body>
</html>

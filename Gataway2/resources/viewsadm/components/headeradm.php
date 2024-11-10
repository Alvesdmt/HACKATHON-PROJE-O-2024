<?php
// Verifica se a sessão já foi iniciada antes de chamar session_start()
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}



// Resto do código do header.php...
?>


<link rel="stylesheet" href="../../public/css/header.css"> 

<div class="header">
    <span class="menu-icon" id="iconSidenav">
        <img src="../../public/images/menu.png" style="width: 25px; height: 20px;" alt="Menu" />
    </span>
    <div class="logo">
        <img src="../..\public\images\logo1.png " alt="Cashtime Logo">
    </div>
    <div class="user-info">
    <div class="user-icon">
        <?php 
            // Pega a primeira letra do nome do usuário armazenado na sessão
            echo isset($_SESSION['user_name']) ? strtoupper(substr($_SESSION['user_name'], 0, 1)) : 'U';
        ?>
    </div>
    <span class="user-name">
        <?php 
            // Exibe o nome completo do usuário
            echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Usuário';
        ?>
    </span>
</div>

</div>

<script>
    document.getElementById('iconSidenav').addEventListener('click', function() {
        const sidebar = document.getElementById('sidenav-main');
        sidebar.classList.toggle('hidden-sidebar');
    });
</script>

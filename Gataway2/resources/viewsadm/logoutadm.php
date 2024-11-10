<?php
// Inicia a sessão
session_start();

// Destrói todos os dados da sessão
session_unset();
session_destroy();

// Redireciona para a página de login
header("Location: ../views/loginadm.php"); // Caminho correto para sua página de login
exit;
?>

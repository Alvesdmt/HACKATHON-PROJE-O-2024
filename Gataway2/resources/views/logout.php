<?php
// Inicia a sessão
session_start();

// Destrói todos os dados da sessão
session_unset();
session_destroy();

// Redireciona para a página de login
header("Location: login.php"); // Substitua pelo caminho correto para sua página de login
exit;
?>

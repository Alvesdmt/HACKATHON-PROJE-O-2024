<?php
// db.php

$host = 'localhost';
$db = 'projecao';
$user = 'root';
$pass = ''; // Ajuste para a senha do seu MySQL

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}

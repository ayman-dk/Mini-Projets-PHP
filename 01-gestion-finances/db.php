<?php
$host = 'localhost';
$dbname = 'mp_gestion_finances';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("set names utf8mb4");
}
catch (PDOException $e){
    die("Erreur de connexion : " . $e->getMessage());
}
?>

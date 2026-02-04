<?php
require 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Génération d'une clé API unique de 64 caractères
    $apiKey = bin2hex(random_bytes(32));

    $sql = "INSERT INTO users (email, password, api_key) VALUES (:email, :pass, :key)";
    $stmt = $pdo->prepare($sql);

    try {
       $stmt->execute([
        ':email' => $email,
        ':pass'  => $password,
        ':key'   => $apiKey
        ]);
        echo "Inscription réussie ! Votre clé API est : " . $apiKey;
    } catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
    }
}
?>
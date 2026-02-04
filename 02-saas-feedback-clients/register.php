<?php
require 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Génération d'une clé API unique de 64 caractères
    $apiKey = bin2hex(random_bytes(32));

    $sql = "INSERT INTO users (email, password, api_key) VALUES (:email, :password, :api_key)";
}
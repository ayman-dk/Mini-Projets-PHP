<?php
session_start();
require 'includes/db.php';

$error = "";

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    try{
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Email ou mot de passe incorrect.";
        }
    } catch (Exception $e) {
        echo "Erreur lors de la connexion : " . $e->getMessage();
    }
}
?>
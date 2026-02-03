<?php
session_start();
require 'db.php';
require 'security.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérification du token CSRF
    if (!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token'])) {
        die("Erreur de sécurité. Veuillez réessayer.");
    }

    if (isset($_POST['id'])) {
        $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
        $user_id = $_SESSION['user_id'];
        
        try {
            $sql = "DELETE FROM transactions WHERE id = :id AND user_id = :uid";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id, ':uid' => $user_id]);

            header("Location: index.php");
            exit();
        } catch (PDOException $e) {
            die("Erreur lors de la suppression de la transaction : " . $e->getMessage());
        }
    } else {
        die("ID de transaction non spécifié.");
    }
} else {
    // Redirection si accès direct en GET
    header("Location: index.php");
    exit();
}
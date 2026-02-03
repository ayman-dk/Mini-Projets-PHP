<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $user_id = $_SESSION['user_id'];
    
    try {
        $sql = "DELETE FROM transactions WHERE id = :id AND user_id = :uid";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id, ':uid' => $_SESSION['user_id']]);

        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        die("Erreur lors de la suppression de la transaction : " . $e->getMessage());
    }
} else {
    die("ID de transaction non spécifié.");
}
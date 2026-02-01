<?php
require 'db.php';
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $sql = "DELETE FROM transactions WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        die("Erreur lors de la suppression de la transaction : " . $e->getMessage());
    }
} else {
    die("ID de transaction non spécifié.");
}
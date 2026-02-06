<?php
 session_start();
 require 'includes/db.php';
 // Si la session n'existe pas, on renvoie vers le login
    if(!isset($_SESSION['user_id'])){
        header('location: login.php');
        exit();
    }
    echo "Bienvenue sur votre dashboard, " . $_SESSION['user_email'] . " !";

    $sql = "SELECT api_key FROM users WHERE id = :id";
    ?>
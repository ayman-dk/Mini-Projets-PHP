<?php
// Fonctions utilitaires pour la sécurité
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function sanitizeInput($data) {
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function validateAmount($amount) {
    // Vérifier que c'est un nombre positif
    return is_numeric($amount) && $amount > 0 && $amount <= 999999.99;
}

function validateDate($date) {
    // Vérifier que la date est valide et pas dans le futur
    $dateTime = DateTime::createFromFormat('Y-m-d', $date);
    return $dateTime && $dateTime->format('Y-m-d') === $date && $dateTime <= new DateTime();
}
?>
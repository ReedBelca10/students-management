<?php
session_start();

// Génération du jeton CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Vérifier si l'utilisateur a le profil admin pour les pages sensibles
function requireAdmin() {
    if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
        header("Location: students_list.php");
        exit;
    }
}

// Vérification du jeton CSRF
function verify_csrf_token($token) {
    if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        die("Erreur de sécurité : Jeton CSRF invalide.");
    }
}
?>
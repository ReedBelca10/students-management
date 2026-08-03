<?php
require_once 'pages/config/db.php';
session_start();

// Vérifier si l'utilisateur est connecté et est admin
if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Vérifier si un code de filière a été fourni
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['stream_code'])) {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Erreur de sécurité : Jeton CSRF invalide.");
    }
    $stream_code = $_POST['stream_code'];
    
    try {
        // Commencer une transaction
        $pdo->beginTransaction();

        // Mettre à NULL le stream_code des étudiants liés à cette filière
        $stmt = $pdo->prepare("UPDATE students SET stream_code = NULL WHERE stream_code = ?");
        $stmt->execute([$stream_code]);
        
        // Supprimer la filière
        $stmt = $pdo->prepare("DELETE FROM streams WHERE stream_code = ?");
        $stmt->execute([$stream_code]);
        
        // Valider la transaction
        $pdo->commit();
        
        $_SESSION['success'] = "La filière a été supprimée avec succès.";
    } catch (PDOException $e) {
        // Annuler la transaction en cas d'erreur
        $pdo->rollBack();
        $_SESSION['error'] = "Une erreur est survenue lors de la suppression.";
    }
}

// Rediriger vers la liste des filières
header('Location: streams_list.php');
exit();
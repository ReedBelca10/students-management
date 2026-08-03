<?php
require_once 'pages/config/db.php';
session_start();

// Vérifier si l'utilisateur est connecté et est admin
if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Vérifier si un ID d'étudiant a été fourni
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['student_id'])) {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Erreur de sécurité : Jeton CSRF invalide.");
    }
    $student_id = $_POST['student_id'];
    
    try {
        // Supprimer l'étudiant
        $stmt = $pdo->prepare("DELETE FROM students WHERE stu_id = ?");
        $stmt->execute([$student_id]);
        
        $_SESSION['success'] = "L'étudiant a été supprimé avec succès.";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Une erreur est survenue lors de la suppression.";
    }
}

// Rediriger vers la liste des étudiants
header('Location: students_list.php');
exit();
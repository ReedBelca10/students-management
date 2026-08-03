<?php
    $host = "gateway01.eu-central-1.prod.aws.tidbcloud.com";
    $port = "4000";
    $user = "4HZ3thAh6jkB9a8.root";
    $pass = "03Obyxcz7K0945gs";
    $db   = "university_db";

    try {
        $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4", $user, $pass, [
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
            PDO::MYSQL_ATTR_SSL_CA => 'C:\MyWamp\php8.4.4\extras\ssl\cacert.pem' // Fallback if CA is required, though verify false might suffice
        ]);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
?>
<?php
    $host = "gateway01.eu-central-1.prod.aws.tidbcloud.com";
    $port = "4000";
    $user = "4HZ3thAh6jkB9a8.root";
    $pass = "03Obyxcz7K0945gs";
    $db   = "sys";

    try {
        $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4", $user, $pass, [
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
        ]);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
?>
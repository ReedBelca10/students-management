<?php
    // Read from environment variables, fallback to local TiDB for development
    $host = getenv('DB_HOST') ?: "gateway01.eu-central-1.prod.aws.tidbcloud.com";
    $port = getenv('DB_PORT') ?: "4000";
    $user = getenv('DB_USER') ?: "4HZ3thAh6jkB9a8.root";
    $pass = getenv('DB_PASS') ?: "03Obyxcz7K0945gs";
    $db   = getenv('DB_NAME') ?: "university_db";

    try {
        $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4", $user, $pass, [
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
            PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-certificates.crt' // Default for most Linux environments like Render
        ]);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
?>
<?php
require_once 'pages/config/db.php';

try {
    $sql1 = "ALTER TABLE users ADD COLUMN email VARCHAR(150)";
    $pdo->exec($sql1);
    echo "Column email added successfully.\n";
    
    $sql2 = "ALTER TABLE users ADD CONSTRAINT unique_email UNIQUE (email)";
    $pdo->exec($sql2);
    echo "Unique constraint added successfully.\n";
} catch(PDOException $e) {
    if ($e->getCode() == '42S21') { // 42S21: Duplicate column name
        echo "Column email already exists.\n";
        try {
            $sql2 = "ALTER TABLE users ADD CONSTRAINT unique_email UNIQUE (email)";
            $pdo->exec($sql2);
            echo "Unique constraint added successfully.\n";
        } catch (PDOException $e2) {
             echo "Error adding unique constraint: " . $e2->getMessage() . "\n";
        }
    } else {
        echo "Error: " . $e->getMessage() . "\n";
    }
}
?>

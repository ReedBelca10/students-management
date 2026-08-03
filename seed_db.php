<?php
require_once 'pages/config/db.php';

function execute_sql_file($pdo, $filename) {
    if (!file_exists($filename)) {
        die("Le fichier $filename n'existe pas.\n");
    }
    
    $sql = file_get_contents($filename);
    try {
        $pdo->exec($sql);
        echo "Exécuté avec succès: $filename\n";
    } catch (PDOException $e) {
        echo "Erreur lors de l'exécution de $filename: " . $e->getMessage() . "\n";
    }
}

execute_sql_file($pdo, 'db/schema.sql');
execute_sql_file($pdo, 'db/seed.sql');
echo "Base de données initialisée.\n";
?>

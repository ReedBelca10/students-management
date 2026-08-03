<?php
require_once 'pages/config/db.php';
session_start();

// Vérifier si l'utilisateur est connecté et est admin
if (!isset($_SESSION['user_id']) || $_SESSION['profile'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Récupérer les statistiques
$stats = [
    'students' => $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn(),
    'streams' => $pdo->query("SELECT COUNT(*) FROM streams")->fetchColumn(),
    'users' => $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn()
];

include 'includes/header.php';
?>

<div class="container py-5">
    <h2 class="mb-4">Tableau de bord administrateur</h2>
    
    <!-- Cartes de statistiques -->
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card dashboard-card">
                <div class="card-body text-center">
                    <h5 class="card-title">Étudiants</h5>
                    <p class="display-4"><?php echo $stats['students']; ?></p>
                    <a href="students_list.php" class="btn btn-primary">Voir la liste</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card dashboard-card">
                <div class="card-body text-center">
                    <h5 class="card-title">Filières</h5>
                    <p class="display-4"><?php echo $stats['streams']; ?></p>
                    <a href="streams_list.php" class="btn btn-primary">Voir la liste</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card dashboard-card">
                <div class="card-body text-center">
                    <h5 class="card-title">Utilisateurs</h5>
                    <p class="display-4"><?php echo $stats['users']; ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Actions rapides -->
    <div class="row mt-4">
        <div class="col-12 col-md-6 mb-4 mb-md-0">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Actions rapides</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="student_add.php" class="btn btn-success btn-lg mb-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-user-plus me-2"></i>
                            <span>Ajouter un étudiant</span>
                        </a>
                        <a href="stream_add.php" class="btn btn-info btn-lg d-flex align-items-center justify-content-center">
                            <i class="fas fa-plus-circle me-2"></i>
                            <span>Ajouter une filière</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Dernières activités -->
        <div class="col-12 col-md-6">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Derniers étudiants inscrits</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php
                        $stmt = $pdo->query("SELECT students.*, streams.stream_name 
                                           FROM students 
                                           LEFT JOIN streams ON students.stream_code = streams.stream_code 
                                           ORDER BY students.stu_id DESC LIMIT 5");
                        while ($student = $stmt->fetch()) {
                            $fname = htmlspecialchars($student['stu_firstname']);
                            $lname = htmlspecialchars($student['stu_lastname']);
                            $sname = htmlspecialchars($student['stream_name']);
                            $sid = (int)$student['stu_id'];
                            echo "<div class='list-group-item'>
                                    <div class='d-flex justify-content-between align-items-center'>
                                        <div>
                                            <strong>{$fname} {$lname}</strong>
                                            <br>
                                            <small class='text-muted'>{$sname}</small>
                                        </div>
                                        <a href='student_edit.php?id={$sid}' 
                                           class='btn btn-sm btn-outline-primary'>
                                            <i class='fas fa-edit'></i>
                                        </a>
                                    </div>
                                </div>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

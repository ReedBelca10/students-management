<?php
require_once 'pages/config/db.php';
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Récupérer la liste des étudiants avec leurs filières
$query = "SELECT 
            students.*, 
            streams.stream_name
          FROM students 
          LEFT JOIN streams ON students.stream_code = streams.stream_code 
          ORDER BY students.stu_lastname, students.stu_firstname";

$stmt = $pdo->query($query);
$students = $stmt->fetchAll();

include 'includes/header.php';
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>LISTE DES ÉTUDIANTS</h2>
        <?php if (isset($_SESSION['profile']) && $_SESSION['profile'] === 'admin'): ?>
        <a href="student_add.php" class="btn btn-primary">
            <i class="fas fa-user-plus me-2"></i>Ajouter un étudiant
        </a>
        <?php endif; ?>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php 
            echo htmlspecialchars($_SESSION['success']);
            unset($_SESSION['success']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">N°</th>
                            <th scope="col">NOM</th>
                            <th scope="col">PRÉNOMS</th>
                            <th scope="col">ANNÉE D'ÉTUDES</th>
                            <th scope="col">FILIÈRE</th>
                            <?php if (isset($_SESSION['profile']) && $_SESSION['profile'] === 'admin'): ?>
                                <th scope="col">ACTIONS</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $counter = 1;
                        foreach ($students as $student): 
                        ?>
                        <tr>
                            <td><?php echo $counter++; ?></td>
                            <td class="text-uppercase"><?php echo htmlspecialchars($student['stu_lastname']); ?></td>
                            <td style="text-transform: capitalize;"><?php echo strtolower(htmlspecialchars($student['stu_firstname'])); ?></td>
                            <td class="text-uppercase"><?php echo htmlspecialchars($student['stu_level']); ?></td>
                            <td style="text-transform: capitalize;"><?php echo strtolower(htmlspecialchars($student['stream_name'])); ?></td>
                            <?php if (isset($_SESSION['profile']) && $_SESSION['profile'] === 'admin'): ?>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="student_edit.php?id=<?php echo $student['stu_id']; ?>" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger" 
                                                title="Supprimer"
                                                onclick="confirmDelete(<?php echo $student['stu_id']; ?>)">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (count($students) === 0): ?>
                        <tr>
                            <td colspan="<?php echo isset($_SESSION['profile']) && $_SESSION['profile'] === 'admin' ? '6' : '5'; ?>" 
                                class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Aucun étudiant enregistré
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmation de suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer cet étudiant ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="deleteForm" action="student_delete.php" method="POST" style="display: inline;">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="hidden" name="student_id" id="studentIdToDelete">
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(studentId) {
    document.getElementById('studentIdToDelete').value = studentId;
    var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}
</script>

<?php include 'includes/footer.php'; ?>
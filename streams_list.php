<?php
require_once 'pages/config/db.php';
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Récupérer la liste des filières
$query = "SELECT * FROM streams ORDER BY stream_name";
$stmt = $pdo->query($query);
$streams = $stmt->fetchAll();

include 'includes/header.php';
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>LISTE DES FILIÈRES</h2>
        <?php if (isset($_SESSION['profile']) && $_SESSION['profile'] === 'admin'): ?>
        <a href="stream_add.php" class="btn btn-primary">
            <i class="fas fa-plus-circle me-2"></i>Ajouter une filière
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
                            <th scope="col">CODE</th>
                            <th scope="col">NOM DE LA FILIÈRE</th>
                            <?php if (isset($_SESSION['profile']) && $_SESSION['profile'] === 'admin'): ?>
                                <th scope="col">ACTIONS</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $counter = 1;
                        foreach ($streams as $stream): 
                        ?>
                        <tr>
                            <td><?php echo $counter++; ?></td>
                            <td class="text-uppercase"><?php echo htmlspecialchars($stream['stream_code']); ?></td>
                            <td style="text-transform: capitalize;"><?php echo strtolower(htmlspecialchars($stream['stream_name'])); ?></td>
                            <?php if (isset($_SESSION['profile']) && $_SESSION['profile'] === 'admin'): ?>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="stream_edit.php?code=<?php echo urlencode($stream['stream_code']); ?>" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger" 
                                                title="Supprimer"
                                                onclick="confirmDelete('<?php echo htmlspecialchars($stream['stream_code']); ?>')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (count($streams) === 0): ?>
                        <tr>
                            <td colspan="<?php echo isset($_SESSION['profile']) && $_SESSION['profile'] === 'admin' ? '4' : '3'; ?>" 
                                class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Aucune filière enregistrée
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
                <p>Êtes-vous sûr de vouloir supprimer cette filière ?</p>
                <p class="text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Attention : Cette action supprimera également le lien avec tous les étudiants associés à cette filière.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="deleteForm" action="stream_delete.php" method="POST" style="display: inline;">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="hidden" name="stream_code" id="streamCodeToDelete">
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(streamCode) {
    document.getElementById('streamCodeToDelete').value = streamCode;
    var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}
</script>

<?php include 'includes/footer.php'; ?>

<?php
require_once 'pages/config/db.php';
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Vérifier si l'utilisateur est connecté et est admin
if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Vérifier si un code de filière a été fourni
if (!isset($_GET['code'])) {
    header('Location: streams_list.php');
    exit();
}

$stream_code = $_GET['code'];

// Récupérer les informations de la filière
$stmt = $pdo->prepare("SELECT * FROM streams WHERE stream_code = ?");
$stmt->execute([$stream_code]);
$stream = $stmt->fetch();

if (!$stream) {
    $_SESSION['error'] = "Filière non trouvée.";
    header('Location: streams_list.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Erreur de sécurité : Jeton CSRF invalide.");
    }
    $new_code = $_POST['stream_code'];
    $name = $_POST['stream_name'];
    
    try {
        if ($new_code !== $stream_code) {
            // Si le code est modifié, utiliser une transaction pour mettre à jour les références
            $pdo->beginTransaction();
            
            // Mettre à jour les références dans la table students
            $stmt = $pdo->prepare("UPDATE students SET stream_code = ? WHERE stream_code = ?");
            $stmt->execute([$new_code, $stream_code]);
            
            // Mettre à jour la filière
            $stmt = $pdo->prepare("UPDATE streams SET stream_code = ?, stream_name = ? WHERE stream_code = ?");
            $stmt->execute([$new_code, $name, $stream_code]);
            
            $pdo->commit();
        } else {
            // Si seul le nom est modifié
            $stmt = $pdo->prepare("UPDATE streams SET stream_name = ? WHERE stream_code = ?");
            $stmt->execute([$name, $stream_code]);
        }
        
        $_SESSION['success'] = "La filière a été mise à jour avec succès.";
        header('Location: streams_list.php');
        exit();
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $error = "Ce code de filière existe déjà.";
            if (isset($pdo)) $pdo->rollBack();
        } else {
            $error = "Une erreur est survenue lors de la mise à jour.";
            if (isset($pdo)) $pdo->rollBack();
        }
    }
}

include 'includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h2 class="card-title h5 mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Modifier une filière
                    </h2>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form method="POST" action="" class="needs-validation" novalidate>
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <div class="mb-3">
                            <label for="stream_code" class="form-label required-field">Code de la filière</label>
                            <input type="text" class="form-control text-uppercase" id="stream_code" name="stream_code" 
                                   required value="<?php echo htmlspecialchars($stream['stream_code']); ?>">
                            <div class="form-text">Le code doit être unique et en majuscules.</div>
                        </div>

                        <div class="mb-4">
                            <label for="stream_name" class="form-label required-field">Nom de la filière</label>
                            <input type="text" class="form-control" id="stream_name" name="stream_name" 
                                   required value="<?php echo htmlspecialchars($stream['stream_name']); ?>">
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="streams_list.php" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Validation des formulaires Bootstrap
(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})()

// Convertir le code en majuscules
document.getElementById('stream_code').addEventListener('input', function(e) {
    this.value = this.value.toUpperCase();
});
</script>

<?php include 'includes/footer.php'; ?>

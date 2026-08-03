<?php
require_once 'pages/config/db.php';
session_start();

// Générer le jeton CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Vérifier si l'utilisateur est connecté et est admin
if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Récupérer la liste des filières pour le select
$stmt = $pdo->query("SELECT * FROM streams ORDER BY stream_name");
$streams = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Erreur de sécurité : Jeton CSRF invalide.");
    }
    try {
        $stmt = $pdo->prepare("
            INSERT INTO students (
                stu_firstname, stu_lastname, stu_birthday, stu_birthplace,
                stu_gender, stu_address, stu_city, stu_country,
                stu_phone_number, stu_email, stu_degree, stu_level,
                stream_code
            ) VALUES (
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
            )
        ");

        $stmt->execute([
            $_POST['firstname'],
            $_POST['lastname'],
            $_POST['birthday'],
            $_POST['birthplace'],
            $_POST['gender'],
            $_POST['address'],
            $_POST['city'],
            $_POST['country'],
            $_POST['phone'],
            $_POST['email'],
            $_POST['degree'],
            $_POST['level'],
            $_POST['stream_code']
        ]);

        $_SESSION['success'] = "L'étudiant a été ajouté avec succès.";
        header('Location: students_list.php');
        exit();
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) { // Code d'erreur pour duplicata (email unique)
            $error = "Cette adresse email est déjà utilisée.";
        } else {
            $error = "Une erreur est survenue lors de l'ajout de l'étudiant.";
        }
    }
}

include 'includes/header.php';
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h2 class="card-title h5 mb-0">
                        <i class="fas fa-user-plus me-2"></i>
                        Ajouter un nouvel étudiant
                    </h2>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form method="POST" action="" class="needs-validation" novalidate>
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="lastname" class="form-label required-field">Nom</label>
                                <input type="text" class="form-control" id="lastname" name="lastname" 
                                       required value="<?php echo htmlspecialchars($_POST['lastname'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="firstname" class="form-label required-field">Prénoms</label>
                                <input type="text" class="form-control" id="firstname" name="firstname" 
                                       required value="<?php echo htmlspecialchars($_POST['firstname'] ?? ''); ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="birthday" class="form-label required-field">Date de naissance</label>
                                <input type="date" class="form-control" id="birthday" name="birthday" 
                                       required value="<?php echo htmlspecialchars($_POST['birthday'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="birthplace" class="form-label">Lieu de naissance</label>
                                <input type="text" class="form-control" id="birthplace" name="birthplace" 
                                       value="<?php echo htmlspecialchars($_POST['birthplace'] ?? ''); ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label required-field">Genre</label>
                                <select class="form-select" id="gender" name="gender" required>
                                    <option value="">Sélectionner...</option>
                                    <option value="M" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'M') ? 'selected' : ''; ?>>Masculin</option>
                                    <option value="F" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'F') ? 'selected' : ''; ?>>Féminin</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Téléphone</label>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label required-field">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Adresse</label>
                            <input type="text" class="form-control" id="address" name="address" 
                                   value="<?php echo htmlspecialchars($_POST['address'] ?? ''); ?>">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">Ville</label>
                                <input type="text" class="form-control" id="city" name="city" 
                                       value="<?php echo htmlspecialchars($_POST['city'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="country" class="form-label">Pays</label>
                                <input type="text" class="form-control" id="country" name="country" 
                                       value="<?php echo htmlspecialchars($_POST['country'] ?? ''); ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="degree" class="form-label required-field">Diplôme</label>
                                <select class="form-select" id="degree" name="degree" required>
                                    <option value="">Sélectionner...</option>
                                    <option value="Licence" <?php echo (isset($_POST['degree']) && $_POST['degree'] === 'Licence') ? 'selected' : ''; ?>>Licence</option>
                                    <option value="Master" <?php echo (isset($_POST['degree']) && $_POST['degree'] === 'Master') ? 'selected' : ''; ?>>Master</option>
                                    <option value="Doctorat" <?php echo (isset($_POST['degree']) && $_POST['degree'] === 'Doctorat') ? 'selected' : ''; ?>>Doctorat</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="level" class="form-label required-field">Niveau</label>
                                <select class="form-select" id="level" name="level" required>
                                    <option value="">Sélectionner...</option>
                                    <option value="L1" <?php echo (isset($_POST['level']) && $_POST['level'] === 'L1') ? 'selected' : ''; ?>>L1</option>
                                    <option value="L2" <?php echo (isset($_POST['level']) && $_POST['level'] === 'L2') ? 'selected' : ''; ?>>L2</option>
                                    <option value="L3" <?php echo (isset($_POST['level']) && $_POST['level'] === 'L3') ? 'selected' : ''; ?>>L3</option>
                                    <option value="M1" <?php echo (isset($_POST['level']) && $_POST['level'] === 'M1') ? 'selected' : ''; ?>>M1</option>
                                    <option value="M2" <?php echo (isset($_POST['level']) && $_POST['level'] === 'M2') ? 'selected' : ''; ?>>M2</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="stream_code" class="form-label required-field">Filière</label>
                                <select class="form-select" id="stream_code" name="stream_code" required>
                                    <option value="">Sélectionner...</option>
                                    <?php foreach ($streams as $stream): ?>
                                        <option value="<?php echo $stream['stream_code']; ?>"
                                            <?php echo (isset($_POST['stream_code']) && $_POST['stream_code'] === $stream['stream_code']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($stream['stream_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="students_list.php" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Enregistrer
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
</script>

<?php include 'includes/footer.php'; ?>
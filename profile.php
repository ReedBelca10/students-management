<?php
require_once 'pages/config/db.php';
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Récupérer les informations de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Traitement de la mise à jour du profil
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Traitement de l'upload de la photo
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $maxSize = 5 * 1024 * 1024; // 5MB
            
            if (!in_array($_FILES['profile_image']['type'], $allowedTypes)) {
                throw new Exception("Le type de fichier n'est pas autorisé. Utilisez JPG, PNG ou GIF.");
            }
            
            if ($_FILES['profile_image']['size'] > $maxSize) {
                throw new Exception("La taille du fichier ne doit pas dépasser 5MB.");
            }
            
            // Créer le dossier s'il n'existe pas
            $uploadDir = __DIR__ . '/assets/img/profiles/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            // Générer un nom de fichier unique
            $extension = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
            $filename = 'profile_' . $_SESSION['user_id'] . '_' . time() . '.' . $extension;
            $filepath = $uploadDir . $filename;
            
            // Supprimer l'ancienne photo si elle existe
            if ($user['user_profile_image'] && file_exists($uploadDir . basename($user['user_profile_image']))) {
                unlink($uploadDir . basename($user['user_profile_image']));
            }
            
            // Déplacer le fichier uploadé
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $filepath)) {
                $profile_image = 'assets/img/profiles/' . $filename;
            } else {
                throw new Exception("Erreur lors de l'upload du fichier.");
            }
        }
        
        // Mise à jour des informations
        $updates = [];
        $params = [];
        
        if (!empty($_POST['firstname'])) {
            $updates[] = "user_firstname = ?";
            $params[] = $_POST['firstname'];
        }
        
        if (!empty($_POST['lastname'])) {
            $updates[] = "user_lastname = ?";
            $params[] = $_POST['lastname'];
        }
        
        if (!empty($_POST['email'])) {
            $updates[] = "email = ?";
            $params[] = $_POST['email'];
        }
        
        if (!empty($profile_image)) {
            $updates[] = "user_profile_image = ?";
            $params[] = $profile_image;
        }
        
        if (!empty($_POST['current_password']) && !empty($_POST['new_password'])) {
            // Vérifier l'ancien mot de passe
            if (password_verify($_POST['current_password'], $user['password'])) {
                $updates[] = "password = ?";
                $params[] = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
            } else {
                throw new Exception("Le mot de passe actuel est incorrect.");
            }
        }
        
        if (!empty($updates)) {
            $params[] = $_SESSION['user_id'];
            $sql = "UPDATE users SET " . implode(", ", $updates) . " WHERE user_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            
            $_SESSION['success'] = "Votre profil a été mis à jour avec succès.";
            header('Location: profile.php');
            exit();
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

include 'includes/header.php';
?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-body text-center">
                    <div class="profile-image-container mb-3">
                        <?php if ($user['user_profile_image']): ?>
                            <img src="<?php echo htmlspecialchars($user['user_profile_image']); ?>" 
                                 class="profile-image rounded-circle" 
                                 alt="Photo de profil">
                        <?php else: ?>
                            <div class="profile-image-placeholder rounded-circle">
                                <i class="fas fa-user"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <h3 class="h5 mb-2">
                        <?php echo htmlspecialchars($user['user_firstname'] . ' ' . $user['user_lastname']); ?>
                    </h3>
                    <p class="text-muted mb-3"><?php echo htmlspecialchars($user['username']); ?></p>
                    <p class="text-muted mb-3">
                        <i class="fas fa-user-shield me-2"></i>
                        <?php echo $user['profile'] === 'admin' ? 'Administrateur' : 'Utilisateur'; ?>
                    </p>
                    <button type="button" 
                            class="btn btn-outline-primary btn-sm" 
                            onclick="document.getElementById('profile_image').click()">
                        <i class="fas fa-camera me-2"></i>Changer la photo
                    </button>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h2 class="card-title h5 mb-0">
                        <i class="fas fa-user-edit me-2"></i>
                        Modifier mon profil
                    </h2>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success">
                            <?php 
                            echo $_SESSION['success'];
                            unset($_SESSION['success']);
                            ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <input type="file" 
                               id="profile_image" 
                               name="profile_image" 
                               accept="image/*" 
                               style="display: none;"
                               onchange="document.getElementById('upload_form').submit()">
                    </form>

                    <form method="POST" action="" class="needs-validation" novalidate>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="firstname" class="form-label">Prénom</label>
                                <input type="text" class="form-control" id="firstname" name="firstname"
                                       value="<?php echo htmlspecialchars($user['user_firstname']); ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="lastname" class="form-label">Nom</label>
                                <input type="text" class="form-control" id="lastname" name="lastname"
                                       value="<?php echo htmlspecialchars($user['user_lastname']); ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                   value="<?php echo htmlspecialchars($user['email']); ?>">
                        </div>

                        <hr class="my-4">

                        <h5 class="mb-3">Changer le mot de passe</h5>
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Mot de passe actuel</label>
                            <input type="password" class="form-control" id="current_password" name="current_password">
                        </div>

                        <div class="mb-4">
                            <label for="new_password" class="form-label">Nouveau mot de passe</label>
                            <input type="password" class="form-control" id="new_password" name="new_password">
                        </div>

                        <div class="d-flex justify-content-end">
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

<style>
.profile-image-container {
    position: relative;
    width: 150px;
    height: 150px;
    margin: 0 auto;
}

.profile-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.profile-image-placeholder {
    width: 100%;
    height: 100%;
    background-color: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    color: #adb5bd;
}
</style>

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

// Soumission automatique du formulaire lors de la sélection d'une image
document.getElementById('profile_image').addEventListener('change', function() {
    if (this.files && this.files[0]) {
        document.querySelector('form[enctype="multipart/form-data"]').submit();
    }
});
</script>

<?php include 'includes/footer.php'; ?>

<?php
require_once 'pages/config/db.php';
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (isset($_POST['register'])) {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Erreur de sécurité : Jeton CSRF invalide.");
    }
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    
    // Validation
    $errors = [];
    
    // Vérification de la longueur du nom d'utilisateur
    if (strlen($username) < 3 || strlen($username) > 50) {
        $errors[] = "Le nom d'utilisateur doit contenir entre 3 et 50 caractères";
    }
    
    // Vérifier si le nom d'utilisateur existe déjà
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetchColumn() > 0) {
        $errors[] = "Ce nom d'utilisateur est déjà utilisé";
    }
    
    // Vérifier si l'email existe déjà
    $email = trim($_POST['email']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'adresse email n'est pas valide";
    } else {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = "Cette adresse email est déjà utilisée";
        }
    }
    
    // Vérifier les mots de passe
    if ($password !== $confirmPassword) {
        $errors[] = "Les mots de passe ne correspondent pas";
    }
    
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("INSERT INTO users (username, email, user_firstname, user_lastname, password, profile) VALUES (?, ?, ?, ?, ?, 'invite')");
        try {
            $stmt->execute([$username, $email, $firstname, $lastname, $hashedPassword]);
            header("Location: login.php?registered=1");
            exit();
        } catch(PDOException $e) {
            $errors[] = "Erreur lors de l'inscription: " . $e->getMessage();
        }
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - GestionEtudiants</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

<div class="auth-page">
    <div class="auth-left">
        <div class="login-form">
            <div class="text-center mb-4">
                <h1 class="fw-bold text-primary">Inscription</h1>
                <h2 class="text-dark fs-6 fw-normal">Créez votre compte pour commencer</h2>
            </div>
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <p class="mb-0"><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="" id="registerForm">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="firstname" class="form-label required-field">Prénom</label>
                            <i class="fas fa-user"></i>
                            <input type="text" class="form-control" id="firstname" name="firstname" 
                                   placeholder="Votre prénom" required>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="lastname" class="form-label required-field">Nom</label>
                            <i class="fas fa-user"></i>
                            <input type="text" class="form-control" id="lastname" name="lastname" 
                                   placeholder="Votre nom" required>
                        </div>
                    </div>
                </div>
            
                <div class="form-group mb-3">
                    <label for="username" class="form-label required-field">Nom d'utilisateur</label>
                    <i class="fas fa-user-circle"></i>
                    <input type="text" class="form-control" id="username" name="username" 
                           placeholder="Choisissez un nom d'utilisateur" required>
                </div>

                <div class="form-group mb-3">
                    <label for="email" class="form-label required-field">Adresse email</label>
                    <i class="fas fa-envelope"></i>
                    <input type="email" class="form-control" id="email" name="email" 
                           placeholder="Votre adresse email" required>
                </div>
                
                <div class="form-group mb-3">
                    <label for="password" class="form-label required-field">Mot de passe</label>
                    <i class="fas fa-lock"></i>
                    <input type="password" class="form-control" id="password" name="password" 
                           placeholder="Votre mot de passe" required>
                </div>
                
                <div class="form-group mb-4">
                    <label for="confirmPassword" class="form-label required-field">Confirmer le mot de passe</label>
                    <i class="fas fa-lock"></i>
                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" 
                           placeholder="Confirmez votre mot de passe" required>
                </div>
                
                <div class="d-flex justify-content-between align-items-center">
                    <div class="login-prompt">
                        <span class="text-dark">Déjà inscrit ? </span>
                        <a href="login.php" class="text-primary text-decoration-none">Se connecter</a>
                    </div>
                    <button type="submit" name="register" class="btn btn-primary">S'inscrire</button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="auth-right">
        <div class="auth-welcome">
            <h1>Bienvenue dans</h1>
            <h2 class="gradient-text">GestionEtudiants</h2>
            <p class="welcome-description">Une mini application de gestion des étudiants</p>
        </div>
        <div class="auth-copyright">
            © ReedBelca 2025 tous droits réservés.
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include 'includes/footer.php'; ?>
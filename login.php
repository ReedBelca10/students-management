<?php
require_once 'pages/config/db.php';
session_start();

if (isset($_POST['login'])) {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Erreur de sécurité : Jeton CSRF invalide.");
    }

    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['profile'] = $user['profile'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['username'] = $user['username'];
        
        if ($_SESSION['profile'] === 'admin') {
            header("Location: dashboard.php");
        } else {
            header("Location: students_list.php");
        }
        exit();
    } else {
        $error = "Nom d'utilisateur ou mot de passe incorrect";
    }
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - GestionEtudiants</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

<div class="auth-page">
    <div class="auth-left">
        <div class="login-form">
            <div class="text-center mb-4">
                <h1 class="fw-bold text-primary">Connexion</h1>
                <h2 class="text-dark fs-6 fw-normal">Entrez vos informations pour vous connecter</h2>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <div class="form-group mb-3">
                    <label for="username" class="form-label required-field">Identifiant</label>
                    <i class="fas fa-user"></i>
                    <input type="text" class="form-control" id="username" name="username" 
                           placeholder="Saisir votre nom d'utilisateur" required>
                </div>
                
                <div class="form-group mb-3">
                    <label for="password" class="form-label required-field">Mot de passe</label>
                    <i class="fas fa-lock"></i>
                    <input type="password" class="form-control" id="password" name="password" 
                           placeholder="Saisir votre mot de passe" required>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check mb-0">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Se souvenir de moi</label>
                    </div>
                    <a href="forgot-password.php" class="forgot-password">Mot de passe oublié?</a>
                </div>
                
                <div class="d-flex justify-content-between align-items-center">
                    <div class="register-prompt">
                        <span class="text-dark">Vous n'avez pas de compte ? </span>
                        <a href="register.php" class="text-primary text-decoration-none">Créer un compte</a>
                    </div>
                    <button type="submit" name="login" class="btn btn-primary">Se connecter</button>
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
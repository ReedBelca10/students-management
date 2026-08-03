<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Étudiants - Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link href="assets/css/landing.css" rel="stylesheet">
</head>
<body>
    <div class="landing-page">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container">
                <a class="navbar-brand" href="#">GestionÉtudiants</a>
                <a href="login.php" class="btn btn-outline-primary">Se connecter</a>
            </div>
        </nav>

        <main class="container">
            <div class="hero-section">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <h1 class="animate__animated animate__fadeInLeft">
                            Plateforme de Gestion des Étudiants
                        </h1>
                        <p class="lead animate__animated animate__fadeInLeft animate__delay-1s">
                            Une solution moderne et intuitive pour gérer efficacement les dossiers étudiants et les filières d'études.
                        </p>
                        <div class="cta-buttons animate__animated animate__fadeInUp animate__delay-2s">
                            <a href="login.php" class="btn btn-primary btn-lg">Commencer</a>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="hero-image animate__animated animate__fadeInRight">
                            <img src="assets/img/hero-illustration.svg" alt="Illustration" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>

            <div class="features-section">
                <div class="row">
                    <div class="col-md-4 animate__animated animate__fadeInUp">
                        <div class="feature-card">
                            <i class="fas fa-user-graduate feature-icon"></i>
                            <h3>Gestion des Étudiants</h3>
                            <p>Gérez facilement les informations des étudiants, leurs parcours et leurs résultats.</p>
                        </div>
                    </div>
                    <div class="col-md-4 animate__animated animate__fadeInUp animate__delay-1s">
                        <div class="feature-card">
                            <i class="fas fa-graduation-cap feature-icon"></i>
                            <h3>Gestion des Filières</h3>
                            <p>Organisez et suivez les différentes filières et leurs programmes d'études.</p>
                        </div>
                    </div>
                    <div class="col-md-4 animate__animated animate__fadeInUp animate__delay-2s">
                        <div class="feature-card">
                            <i class="fas fa-chart-line feature-icon"></i>
                            <h3>Tableau de Bord</h3>
                            <p>Visualisez les statistiques et les indicateurs clés en temps réel.</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <footer class="footer">
            <div class="container text-center">
                <p>&copy; 2025 Développé par ReedBelca</p>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/your-kit-code.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>

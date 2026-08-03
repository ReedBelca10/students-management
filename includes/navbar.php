<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar navbar-expand-lg navbar-light sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="<?php echo (isset($_SESSION['profile']) && $_SESSION['profile'] === 'admin') ? 'dashboard.php' : 'students_list.php'; ?>">
            <i class="fas fa-graduation-cap me-2"></i>
            <span class="d-none d-sm-inline">Gestion des Étudiants</span>
            <span class="d-inline d-sm-none">GE</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page == 'students_list.php' ? 'active' : ''; ?>" 
                       href="students_list.php">
                       <i class="fas fa-users me-1 d-lg-none"></i>
                       Liste des étudiants
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page == 'streams_list.php' ? 'active' : ''; ?>" 
                       href="streams_list.php">
                       <i class="fas fa-stream me-1 d-lg-none"></i>
                       Liste des filières
                    </a>
                </li>
                <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" 
                       data-bs-toggle="dropdown" aria-expanded="false">
                       <i class="fas fa-cog me-1 d-lg-none"></i>
                       Administration
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="adminDropdown">
                        <li>
                            <a class="dropdown-item" href="dashboard.php">
                                <i class="fas fa-tachometer-alt me-2"></i>Tableau de bord
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="student_add.php">
                                <i class="fas fa-user-plus me-2"></i>Ajouter un étudiant
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="stream_add.php">
                                <i class="fas fa-plus-circle me-2"></i>Ajouter une filière
                            </a>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['user_id'])): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle me-1"></i>
                        <span class="d-none d-lg-inline"><?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Mon compte'; ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown">
                        <li>
                            <a class="dropdown-item" href="profile.php">
                                <i class="fas fa-user-circle me-2"></i>Mon profil
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="logout.php">
                                <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                            </a>
                        </li>
                    </ul>
                </li>
                <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page == 'login.php' ? 'active' : ''; ?>" 
                       href="login.php">
                       <i class="fas fa-sign-in-alt me-1 d-lg-none"></i>
                       Connexion
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page == 'register.php' ? 'active' : ''; ?>" 
                       href="register.php">
                       <i class="fas fa-user-plus me-1 d-lg-none"></i>
                       Inscription
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
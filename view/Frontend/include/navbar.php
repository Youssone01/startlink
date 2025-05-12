<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
    <a href="index.php" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
        <img src="assets/img/logo.PNG" alt="Logo" class="me-2" style="height: 70px; width: 70px;">
        <h2 class="m-0 text-primary">StartLink</h2>
    </a>

    <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav ms-auto p-4 p-lg-0">
            <a href="index.php" class="nav-item nav-link active"><i class="fas fa-home"></i> Home</a>
            <a href="offres.php" class="nav-item nav-link"><i class="fas fa-briefcase"></i> Offres</a>
            <a href="Formations.php" class="nav-item nav-link"><i class="fas fa-chalkboard-teacher"></i> Formation</a>
            <a href="afficherEvent.php" class="nav-item nav-link"><i class="fas fa-calendar-alt"></i> Événements</a>
            <a href="blog.php" class="nav-item nav-link"><i class="fas fa-blog"></i> Blog</a>
            <a href="quizz.php" class="nav-item nav-link"><i class="fas fa-question-circle"></i> Quiz</a>
            <a href="listcertif.php" class="nav-item nav-link"><i class="fas fa-certificate"></i> Certifications</a>
            <a href="contact.php" class="nav-item nav-link"><i class="fas fa-envelope"></i> Contact</a>
        </div>

        <div class="d-flex align-items-center pe-4">
            <?php if (isset($_SESSION['user_id'])): ?>
    <div class="dropdown">
        <a class="btn btn-outline-primary dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="<?= isset($_SESSION['photo']) ? $_SESSION['photo'] : 'uploads/default.jpg'; ?>" 
                 alt="Profile" 
                 style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover; margin-right: 8px;">
            <?= $_SESSION['user_name'] ?? 'Profil' ?>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user"></i> Profil</a></li>
            <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
        </ul>
    </div>
<?php else: ?>
    <a href="register.php" class="btn btn-primary py-3 px-4 ms-2"><i class="fas fa-sign-in-alt"></i> Connexion / Inscription</a>
<?php endif; ?>

        </div>
    </div>
</nav>
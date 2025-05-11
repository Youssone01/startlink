<?php
session_start();
require_once __DIR__ . '/../../controller/OffreController.php';
require_once __DIR__ . '/../../controller/CandidatureController.php';

// Vérification du rôle investisseur et authentification
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SESSION['role'] !== 'investisseur') {
    header('Location: index.php'); // Redirige vers l'accueil si pas investisseur
    exit;
}

$offreController = new OffreController();
$candidatureController = new CandidatureController();
$mesOffres = [];

try {
    // Récupérer les offres associées à l'investisseur
    $mesOffres = $offreController->getOffresByUser($_SESSION['user_id']);
} catch (Exception $e) {
    error_log("Erreur récupération offres: " . $e->getMessage());
    $errorMessage = "Une erreur est survenue lors du chargement de vos offres.";
}
?>

<?php include('include/header.php') ?>
<?php include('include/navbar.php') ?>

<body>
    <?php include('include/spinner.php') ?>

    <div class="container-xxl py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h6 class="section-title bg-white text-start text-primary pe-3">Espace Investisseur</h6>
                    <h1 class="mb-0">Mes offres d'emploi</h1>
                </div>
                <a href="ajouter_offre.php" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Créer une nouvelle offre
                </a>
            </div>

            <?php if (isset($errorMessage)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></div>
            <?php endif; ?>

            <?php if (empty($mesOffres)): ?>
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-briefcase fa-4x text-muted mb-4"></i>
                        <h3>Vous n'avez pas encore créé d'offre</h3>
                        <p class="text-muted">Commencez par publier votre première offre d'emploi</p>
                        <a href="ajouter_offre.php" class="btn btn-primary mt-3">
                            Publier une offre
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($mesOffres as $offre): 
                        $nbCandidatures = count($candidatureController->getCandidaturesParOffre($offre['id']));
                    ?>
                    <div class="col-lg-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-primary text-white d-flex justify-content-between">
                                <div>
                                    <h5 class="mb-0"><?= htmlspecialchars($offre['titre']) ?></h5>
                                    <small>Publié le <?= date('d/m/Y', strtotime($offre['date_publication'])) ?></small>
                                </div>
                                <span class="badge bg-light text-dark">
                                    <?= $nbCandidatures ?> candidature<?= $nbCandidatures > 1 ? 's' : '' ?>
                                </span>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <span class="badge bg-secondary me-2">
                                        <?= htmlspecialchars($offre['type_contrat']) ?>
                                    </span>
                                    <span class="text-muted">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        <?= htmlspecialchars($offre['lieu']) ?>
                                    </span>
                                </div>

                                <p class="card-text"><?= nl2br(htmlspecialchars(substr($offre['description'], 0, 200))) ?>...</p>

                                <div class="d-flex justify-content-between align-items-center mt-4">
                                    <a href="cand_inv.php?id=<?= $offre['id'] ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i> Voir mes candidatures
                                    </a>
                                    <small class="text-muted">
                                        <?= $offre['date_publication'] == $offre['date_publication'] ? 'Créée' : 'Modifiée' ?>
                                        le <?= date('d/m/Y', strtotime($offre['date_publication'])) ?>
                                    </small>
                                    <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?= $offre['id'] ?>)">
                                        <i class="fas fa-trash me-1"></i> Supprimer
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include('include/footer.php') ?>
    <?php include('include/js.php') ?>

    <script>
        // Script pour confirmer la suppression d'une offre
        function confirmDelete(offreId) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette offre ?')) {
                window.location.href = 'supprimer_offre.php?id=' + offreId;
            }
        }
    </script>
</body>
</html>
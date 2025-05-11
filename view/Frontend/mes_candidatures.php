<?php
session_start();
require_once __DIR__ . '/../../controller/CandidatureController.php';

// Vérification de la connexion de l'utilisateur
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Création de l'instance du contrôleur et récupération des candidatures de l'utilisateur
$candidatureController = new CandidatureController();
$mesCandidatures = $candidatureController->getCandidaturesByUser($_SESSION['user_id']);
?>

<?php include('include/header.php') ?>
<?php include('include/navbar.php') ?>

<body>
    <?php include('include/spinner.php') ?>

    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h6 class="section-title bg-white text-center text-primary px-3">Mes Candidatures</h6>
                <h1 class="mb-5">Suivi de mes postulations</h1>
            </div>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Offre</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($mesCandidatures): ?>
                            <?php foreach ($mesCandidatures as $candidature): ?>
                                <tr>
                                    <td><?= htmlspecialchars($candidature['offre_titre']) ?></td>
                                    <td>
                                        <span class="badge 
                                            <?= $candidature['statut'] === 'acceptee' ? 'bg-success' : 
                                               ($candidature['statut'] === 'rejetee' ? 'bg-danger' : 'bg-warning') ?>">
                                            <?= ucfirst(str_replace('_', ' ', $candidature['statut'])) ?>
                                        </span>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($candidature['date_postulation'])) ?></td>
                                    <td>
                                        <a href="details_candidature.php?id=<?= $candidature['id'] ?>" class="btn btn-sm btn-primary">
                                            Détails
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center">Aucune candidature trouvée</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php include('include/footer.php') ?>
    <?php include('include/js.php') ?>
</body>
</html>
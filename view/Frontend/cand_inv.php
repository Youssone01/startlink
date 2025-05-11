<?php
session_start();
require_once __DIR__ . '/../../controller/CandidatureController.php'; 
require_once __DIR__ . '/../../controller/OffreController.php'; // Contrôleur pour récupérer les candidatures

// Vérification de la connexion de l'utilisateur
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Vérification si l'ID de l'offre est passé en paramètre dans l'URL
$offreId = isset($_GET['id']) ? $_GET['id'] : null; // Récupère l'ID de l'offre depuis l'URL

// Vérifier si l'ID de l'offre existe
if (is_null($offreId)) {
    echo "Aucune offre sélectionnée. Veuillez revenir à la page des offres.";
    exit;
}

// Créer une instance du contrôleur
$candidatureController = new CandidatureController();

// Gérer l'action d'acceptation ou de rejet d'une candidature
if (isset($_POST['action'])) {
    $candidatureId = $_POST['candidature_id'];
    $action = $_POST['action'];

    // Mettre à jour le statut de la candidature
    if ($action === 'accepter') {
        $candidatureController->mettreAJourStatutCandidature($candidatureId, 'acceptee');
    } elseif ($action === 'rejeter') {
        $candidatureController->mettreAJourStatutCandidature($candidatureId, 'rejetee');
    }

    // Redirection pour éviter la soumission multiple du formulaire
    header("Location: cand_inv.php?id=" . $offreId); // Ensure using 'id' instead of 'offre_id'
    exit;
}

// Récupérer les candidatures pour l'offre spécifiée
$mesCandidatures = $candidatureController->getCandidaturesParOffre($offreId);

// Vérifier si des candidatures ont été récupérées
if (empty($mesCandidatures)) {
    echo "Aucune candidature trouvée pour cette offre.";
}
?>

<?php include('include/header.php') ?>
<?php include('include/navbar.php') ?>

<body>
    <?php include('include/spinner.php') ?>

    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h6 class="section-title bg-white text-center text-primary px-3">Candidatures</h6>
                <h1 class="mb-5">Gestion des candidatures</h1>
            </div>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nom du Candidat</th>
                            <th>Email du Candidat</th>
                            <th>Date de la Candidature</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($mesCandidatures)): ?>
                            <?php foreach ($mesCandidatures as $candidature): ?>
                                <tr class="<?= $candidature['statut'] === 'acceptee' ? 'table-success' : ($candidature['statut'] === 'rejetee' ? 'table-danger' : '') ?>">
                                    <td><?= htmlspecialchars($candidature['fullname']) ?></td>
                                    <td><?= htmlspecialchars($candidature['email']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($candidature['date_postulation'])) ?></td>
                                    <td><?= htmlspecialchars($candidature['statut']) ?></td>
                                    <td>
                                        <!-- Formulaire pour accepter ou rejeter la candidature -->
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="candidature_id" value="<?= $candidature['id'] ?>">
                                            <?php if ($candidature['statut'] !== 'acceptee'): ?>
                                                <button type="submit" name="action" value="accepter" class="btn btn-sm btn-primary" <?= $candidature['statut'] === 'rejetee' ? 'disabled' : '' ?>>
                                                    Accepter
                                                </button>
                                            <?php endif; ?>
                                            <?php if ($candidature['statut'] !== 'rejetee'): ?>
                                                <button type="submit" name="action" value="rejeter" class="btn btn-sm btn-danger" <?= $candidature['statut'] === 'acceptee' ? 'disabled' : '' ?>>
                                                    Rejeter
                                                </button>
                                            <?php endif; ?>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center">Aucune candidature trouvée</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Bouton Retour -->
            <div class="text-center mt-4">
                <a href="mes_offres.php" class="btn btn-secondary">Retour à la liste des offres</a>
            </div>

        </div>
    </div>

    <?php include('include/footer.php') ?>
    <?php include('include/js.php') ?>
</body>
</html>
<?php
session_start();

// Redirect to login page if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include('include/header.php');
include('include/navbar.php');

// Assuming you have a class or method to fetch job offers from the database
require_once __DIR__ . '/../../controller/OffreController.php';

// Fetch job offers from the database
$offreController = new OffreController();
$offres = $offreController->getOffres(); // Assuming this method returns an array of offers
?>

<div class="container mt-5">
    <h2 class="text-center">Offres d'Emploi</h2>

    <!-- Display success or error message if any -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_SESSION['message']); ?>
            <?php unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>

    <!-- Check if there are any offers -->
    <?php if (empty($offres)): ?>
        <div class="alert alert-warning">
            Aucune offre disponible pour le moment.
        </div>
    <?php else: ?>
        <div class="list-group">
            <?php foreach ($offres as $offre): ?>
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <h5><?= htmlspecialchars($offre['titre']); ?></h5>
                    <p><?= htmlspecialchars($offre['description']); ?></p>

                    <a href="postuler.php?offre_id=<?= $offre['id'] ?>" class="btn btn-primary">Postuler</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include('include/footer.php'); ?>
<?php include('include/js.php');?>
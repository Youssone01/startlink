<?php
session_start();
require_once __DIR__ . '/../../controller/CandidatureController.php';


// Redirect to login page if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Vérifier si l'ID de l'offre a été passé via GET
if (isset($_GET['offre_id'])) {
    $offreId = $_GET['offre_id'];

    // Connexion à la base de données pour récupérer les informations de l'offre
    require_once __DIR__ . '/../../Controllers/config.php';
    $db = config::getConnexion();
    $query = $db->prepare("SELECT * FROM offres WHERE id = :id");
    $query->execute(['id' => $offreId]);
    $offre = $query->fetch();

    // Si l'offre n'existe pas
    if (!$offre) {
        $_SESSION['error_message'] = "L'offre demandée n'existe pas.";
        header("Location: offres.php"); // Rediriger vers la page des offres
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Vérifier que le message et le fichier CV sont bien soumis
        if (isset($_POST['message'], $_FILES['cv'])) {
            $message = $_POST['message'];
            $cv = $_FILES['cv'];

            // Validation du fichier CV
            if ($cv['error'] !== 0) {
                $_SESSION['error_message'] = "Erreur lors du téléchargement du fichier.";
                header("Location: postuler.php?offre_id=" . $offreId);
                exit();
            }

            // Vérification de l'extension du fichier CV (PDF uniquement)
            $allowedExtensions = ['pdf'];
            $fileExtension = strtolower(pathinfo($cv['name'], PATHINFO_EXTENSION));
            if (!in_array($fileExtension, $allowedExtensions)) {
                $_SESSION['error_message'] = "Le fichier doit être un PDF.";
                header("Location: postuler.php?offre_id=" . $offreId);
                exit();
            }

            // Vérification de la taille du fichier (maximum 2MB)
            if ($cv['size'] > 2 * 1024 * 1024) { // 2MB
                $_SESSION['error_message'] = "Le fichier est trop volumineux. La taille maximale est de 2MB.";
                header("Location: postuler.php?offre_id=" . $offreId);
                exit();
            }

            // Créer une instance du contrôleur Candidature
            $candidatureController = new CandidatureController();
            
            // Ajouter la candidature
            $result = $candidatureController->ajouterCandidature($_SESSION['user_id'], $offreId, $message, $cv);

            if ($result) {
                $_SESSION['success_message'] = "Votre candidature a été envoyée avec succès.";
                header("Location: mes_candidatures.php"); // Rediriger vers la page des candidatures
                exit();
            } else {
                $_SESSION['error_message'] = "Une erreur est survenue lors de l'envoi de votre candidature.";
            }
        }
    }
} else {
    $_SESSION['error_message'] = "Aucune offre sélectionnée.";
    header("Location: offres.php"); // Rediriger vers la page des offres
    exit();
}
?>

<?php include('include/header.php'); ?>
<?php include('include/navbar.php'); ?>

<body>
    <?php include('include/spinner.php'); ?>

    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h6 class="section-title bg-white text-center text-primary px-3">Postuler à l'offre</h6>
                <h1 class="mb-5"><?= htmlspecialchars($offre['titre']) ?></h1>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <form id="postulerForm" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="offre_id" value="<?= $offre['id'] ?>">

                        <div class="row g-3">
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <textarea class="form-control" id="message" name="message" placeholder="Votre message" style="height: 150px" required></textarea>
                                    <label for="message">Pourquoi êtes-vous intéressé par cette offre ?</label>
                                    <span id="messageError" class="error-message"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="cv">Télécharger votre CV (PDF uniquement, max 2MB)</label>
                                    <input type="file" class="form-control" id="cv" name="cv" accept=".pdf" required>
                                    <span id="cvError" class="error-message"></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary w-100 py-3" type="submit">Envoyer ma candidature</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include('include/footer.php'); ?>
    <?php include('include/js.php'); ?>

</body>
</html>

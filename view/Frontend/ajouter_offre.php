<?php
include('include/header.php');
include('include/navbar.php');
require_once __DIR__ . '/../../model/Offre.php';
require_once __DIR__ . '/../../controller/OffreController.php';
require_once __DIR__ . '/../../Controllers/config.php';

// Vérifier si la session est déjà démarrée, sinon la démarrer
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifier que les champs sont bien remplis
    if (isset($_POST['titre'], $_POST['description'], $_POST['competences_requises'], $_POST['type_contrat'], $_POST['salaire'], $_POST['lieu'])) {

        // Récupérer les valeurs du formulaire
        $titre = $_POST['titre'];
        $description = $_POST['description'];
        $competences_requises = $_POST['competences_requises'];
        $type_contrat = $_POST['type_contrat'];
        $salaire = $_POST['salaire'];
        $lieu = $_POST['lieu'];

        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error_message'] = "Vous devez être connecté pour ajouter une offre.";
            header("Location: login.php");
            exit();
        }

        // Récupérer l'ID de l'utilisateur connecté
        $userId = $_SESSION['user_id'];

        // Créer l'objet Offre
        $offre = new Offre($titre, $description, $competences_requises, $type_contrat, $salaire, $lieu, $userId);

        // Créer l'instance du contrôleur
        $controller = new OffreController();

        // Ajouter l'offre
        $result = $controller->createOffre($offre);

        if ($result) {
            $_SESSION['success_message'] = "L'offre a été ajoutée avec succès!";
            header("Location: offres.php"); // Rediriger vers la liste des offres
            exit();
        } else {
            $_SESSION['error_message'] = "Une erreur est survenue lors de l'ajout de l'offre.";
            header("Location: ajouter_offre.php"); // Rediriger vers le formulaire d'ajout avec un message d'erreur
            exit();
        }
    } else {
        $_SESSION['error_message'] = "Veuillez remplir tous les champs.";
    }
}
?>

<body>
    <?php include('include/spinner.php') ?>

    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h6 class="section-title bg-white text-center text-primary px-3">Offres d'emploi</h6>
                <h1 class="mb-5">Ajouter une nouvelle offre</h1>
            </div>

            <!-- Message de succès ou erreur -->
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success">
                    <?php echo $_SESSION['success_message']; ?>
                    <?php unset($_SESSION['success_message']); ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger">
                    <?php echo $_SESSION['error_message']; ?>
                    <?php unset($_SESSION['error_message']); ?>
                </div>
            <?php endif; ?>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <form id="offreForm" method="POST" enctype="multipart/form-data">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="titre" name="titre" placeholder="Titre de l'offre" required>
                                    <label for="titre">Titre de l'offre</label>
                                    <span id="titreError" class="error-message"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <textarea class="form-control" id="description" name="description" placeholder="Description" style="height: 150px" required></textarea>
                                    <label for="description">Description détaillée</label>
                                    <span id="descriptionError" class="error-message"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="competences_requises" name="competences_requises" placeholder="Compétences requises" required>
                                    <label for="competences_requises">Compétences requises</label>
                                    <span id="competencesError" class="error-message"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select" id="type_contrat" name="type_contrat" required>
                                        <option value="">Sélectionnez un type</option>
                                        <option value="CDI">CDI</option>
                                        <option value="CDD">CDD</option>
                                        <option value="Freelance">Freelance</option>
                                        <option value="Stage">Stage</option>
                                        <option value="Alternance">Alternance</option>
                                    </select>
                                    <label for="type_contrat">Type de contrat</label>
                                    <span id="typeError" class="error-message"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="salaire" name="salaire" placeholder="Salaire" required>
                                    <label for="salaire">Salaire</label>
                                    <span id="salaireError" class="error-message"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="lieu" name="lieu" placeholder="Lieu" required>
                                    <label for="lieu">Lieu</label>
                                    <span id="lieuError" class="error-message"></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary w-100 py-3" type="submit">Publier l'offre</button>
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

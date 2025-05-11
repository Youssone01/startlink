<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/user-startlink-ines/controller/ParticipantEventC.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/user-startlink-ines/controller/userController.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/user-startlink-ines/controller/EventC.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/user-startlink-ines/config/config.php';

$participationC = new ParticipationC();
$userC = new StartlinkUserController();
$eventC = new EventC();

$errors = [];
$successMessage = ''; // Pour afficher un message de succès

if (!isset($_GET['event_id'])) {
    echo "ID d'événement manquant.";
    exit();
}

$evenement_id = $_GET['event_id'];
$event = $eventC->getEventById($evenement_id);

if (!$event) {
    echo "Événement introuvable.";
    exit();
}

$eventName = $event->getTitre();
$users = $userC->getAllUsers();
$dateInscription = date('Y-m-d');

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_POST['utilisateur_id'] ?? null;
    $dateInscriptionPost = $_POST['dateInscription'] ?? null;

    // Validation
    if (!$user_id) {
        $errors['utilisateur_id'] = "Veuillez sélectionner un utilisateur.";
    }

    if ($dateInscriptionPost !== $dateInscription) {
        $errors['dateInscription'] = "La date d'inscription doit être celle d'aujourd'hui.";
    }

    if (empty($errors)) {
        $participationC->ajouterParticipant($user_id, $dateInscriptionPost);
        
        // Message de succès
        $successMessage = 'Participant ajouté avec succès!';

        // Compter le nombre de participants
        $totalParticipants = $participationC->getTotalParticipantsByEvent($evenement_id);
    }
}
?>

<?php include('include/header.php') ?>

<body>
    <?php include('include/spinner.php') ?>
    <?php include('include/navbar.php') ?>

    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h6 class="section-title bg-white text-center text-primary px-3">Participants</h6>
                <h1 class="mb-5">Ajouter un Participant à : <?= htmlspecialchars($eventName) ?></h1>
            </div>

            <?php if (!empty($successMessage)): ?>
                <div class="alert alert-success text-center">
                    <?= htmlspecialchars($successMessage) ?>
                </div>
            <?php endif; ?>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="form-container wow fadeInUp" data-wow-delay="0.3s">
                        <form action="" method="POST">
                            <div class="mb-4">
                                <label for="utilisateur_id" class="form-label">Utilisateur</label>
                                <select class="form-control" name="utilisateur_id" id="utilisateur_id" required>
                                    <option value="">-- Choisir un utilisateur --</option>
                                    <?php foreach ($users as $user): ?>
                                        <option value="<?= $user['id'] ?>">
                                            <?= htmlspecialchars($user['fullname']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (isset($errors['utilisateur_id'])): ?>
                                    <div class="error-message"><?= $errors['utilisateur_id'] ?></div>
                                <?php endif; ?>
                            </div>

                            <input type="hidden" name="evenement_id" value="<?= $evenement_id ?>">

                            <div class="mb-4">
                                <label for="dateInscription" class="form-label">Date d'inscription</label>
                                <input type="date" class="form-control" name="dateInscription" id="dateInscription"
                                       value="<?= $dateInscription ?>" readonly>
                                <?php if (isset($errors['dateInscription'])): ?>
                                    <div class="error-message"><?= $errors['dateInscription'] ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-startlink me-3">
                                    <i class="fas fa-user-plus me-2"></i>Ajouter
                                </button>
                                <a href="afficherEvent.php" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Annuler
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <?php if (isset($totalParticipants)): ?>
                <div class="text-center mt-5">
                    <h4>Total de participants inscrits : <?= $totalParticipants ?></h4>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include('include/footer.php') ?>
    <?php include('include/js.php') ?>
</body>
</html>
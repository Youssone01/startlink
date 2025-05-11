<?php
session_start();
require_once __DIR__ . '/../../controller/ParticipantC.php';
require_once __DIR__ . '/../../controller/userController.php';
require_once __DIR__ . '/../../controller/EventC.php';

// Instancier les contrôleurs
$participantC = new ParticipantC();  // Assurez-vous que l'objet ParticipantC est bien instancié
$userC = new StartlinkUserController();
$eventC = new EventC();  // Assurez-vous d'instancier EventC pour accéder à ses méthodes

// Vérification de l'ID événement
$evenement_id = isset($_GET['id']) ? $_GET['id'] : null;
if ($evenement_id === null) {
    die("Erreur : L'ID de l'événement est manquant.");
}
$event = $eventC->getEventById($evenement_id);

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $nom = isset($_POST['nom']) ? $_POST['nom'] : '';
    $prenom = isset($_POST['prenom']) ? $_POST['prenom'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $tel = isset($_POST['tel']) ? $_POST['tel'] : '';

    // Vérifier que les champs nécessaires sont remplis
    if (empty($nom) || empty($prenom) || empty($email) || empty($tel)) {
        $_SESSION['error'] = "Tous les champs sont requis.";
        header("Location: ajouterParticipant.php?id=" . $evenement_id);
        exit();
    }

    try {
        // Appel à la méthode ajouterParticipant pour ajouter le participant à la base de données
        $participantC->ajouterParticipant($nom, $prenom, $email, $tel, $evenement_id);

        // Message de succès
        $_SESSION['success'] = "Participant ajouté avec succès";
        header("Location: voirParticipants.php?id=" . $evenement_id);
        exit();
    } catch (Exception $e) {
        // Message d'erreur si l'ajout échoue
        $_SESSION['error'] = "Erreur lors de l'ajout du participant: " . $e->getMessage();
        header("Location: ajouterParticipant.php?id=" . $evenement_id);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter Participant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h3>Ajouter un participant à: <?= htmlspecialchars($event['titre'] ?? '') ?></h3>
            </div>
            <div class="card-body">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                        <?= htmlspecialchars($_SESSION['error']) ?>
                        <?php unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success">
                        <?= htmlspecialchars($_SESSION['success']) ?>
                        <?php unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom:</label>
                        <input type="text" class="form-control" id="nom" name="nom" required>
                    </div>
                    <div class="mb-3">
                        <label for="prenom" class="form-label">Prénom:</label>
                        <input type="text" class="form-control" id="prenom" name="prenom" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="tel" class="form-label">Numéro de téléphone:</label>
                        <input type="tel" class="form-control" id="tel" name="tel" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                    <a href="listeEvenements.php" class="btn btn-secondary">Annuler</a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

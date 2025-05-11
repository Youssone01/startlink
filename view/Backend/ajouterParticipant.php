<?php

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

// Récupérer tous les utilisateurs
$utilisateurs = $userC->getAllUsers();

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['utilisateur_id'])) {
    try {
        // Récupérer l'ID de l'utilisateur depuis le formulaire
        $utilisateur_id = $_POST['utilisateur_id'];  

        // Variables pour l'ajout du participant
        $notificationEnvoyee = 0; 
        $dateInscription = date('Y-m-d'); 

        // Préparation de la requête d'ajout de participant
        $query = $participantC->getEventById()->prepare("
            INSERT INTO participationevenement ($nom, $prenom, $email, $tel, $evenement_id)
            VALUES (?,?,?,?)
        ");
        
        // Lier les paramètres
        $query->bindParam(':utilisateur_id', $utilisateur_id, PDO::PARAM_INT);
        $query->bindParam(':evenement_id', $evenement_id, PDO::PARAM_INT);
        $query->bindParam(':notificationEnvoyee', $notificationEnvoyee, PDO::PARAM_INT);
        $query->bindParam(':dateInscription', $dateInscription);

        // Exécution de la requête
        $query->execute();

        // Message de succès
        $_SESSION['success'] = "Participation enregistrée avec succès";
        header("Location: voirParticipants.php?id=" . $evenement_id);
        exit();
    } catch (Exception $e) {
        // Gérer l'erreur et l'afficher dans la session
        $_SESSION['error'] = "Erreur lors de l'ajout de la participation: " . $e->getMessage();
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
                        <label for="utilisateur_id" class="form-label">Sélectionnez un utilisateur:</label>
                        <select class="form-select" id="utilisateur_id" name="utilisateur_id" required>
                            <?php foreach ($utilisateurs as $user): ?>
                                <option value="<?= $user['id'] ?>">  <!-- Assurez-vous que le champ id_user existe dans la table utilisateur -->
                                    <?= htmlspecialchars($user['fullname']) ?>  <!-- Affichage complet du nom -->
                                </option>
                            <?php endforeach; ?>
                        </select>
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

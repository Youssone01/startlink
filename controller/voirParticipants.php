<?php
include_once "../controller/EventC.php";
include_once "../controller/ParticipantEventC.php";

$eventC = new EventC();
$participationC = new ParticipationC();

// Récupérer l'événement par son ID
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $idEvent = $_GET['id'];
    $event = $eventC->getEventById($idEvent);
    
    // Récupérer les participants de cet événement
    $participants = $participationC->getParticipantsByEvent($idEvent);
} else {
    die("ID de l'événement non spécifié.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Participants de l'Événement</title>
</head>
<body>

    <center>
        <h1>Participants de l'Événement: <?= htmlspecialchars($event->getTitre()) ?></h1>
        <button><a href="afficherEvent.php">Retour à la liste des événements</a></button>
    </center>

    <br><br>

    <table>
        <thead>
            <tr>
                <th>ID Participant</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($participants as $participant) { ?>
                <tr>
                    <td><?= htmlspecialchars($participant['idParti']) ?></td>
                    <td><?= htmlspecialchars($participant['nom_user']) ?></td>
                    <td><?= htmlspecialchars($participant['email_user']) ?></td>
                    <td>
                        <!-- Ajouter des actions possibles pour chaque participant (par exemple, supprimer la participation) -->
                        <a class="button" href="supprimerParticipant.php?id=<?= $participant['idParti'] ?>">Supprimer</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

</body>
</html>

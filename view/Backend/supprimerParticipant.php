<?php
// supprimerParticipant.php
session_start();



require_once __DIR__ . '/../../controller/config.php';
require_once __DIR__ . '/../../controller/ParticipantC.php';

if (!isset($_GET['id']) || !isset($_GET['idEvenement'])) {
    $_SESSION['error'] = "Paramètres manquants pour la suppression";
    header('Location: voirParticipants.php');
    exit();
}

$participantC = new ParticipantC();

$idParticipant = $_GET['id'];
$idEvenement = $_GET['idEvenement'];

try {
    $success = $participantC->supprimerParticipant($idParticipant);
    
    if ($success) {
        $_SESSION['success'] = "Participant supprimé avec succès";
    } else {
        $_SESSION['error'] = "Échec de la suppression du participant";
    }
} catch (Exception $e) {
    $_SESSION['error'] = "Erreur : " . $e->getMessage();
}

header("Location: voirParticipants.php?id=$idEvenement");
exit();
?>
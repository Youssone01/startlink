<?php
require_once __DIR__ . '/../../Controllers/config.php';
require_once __DIR__ . '/../../controller/FormationController.php';
require_once __DIR__ . '/../../model/inscriptionformation.php';

session_start();
$controller = new FormationController();

// Vérifier si l'ID du participant à supprimer est passé en GET
if (isset($_GET['delete_participant_id']) && filter_var($_GET['delete_participant_id'], FILTER_VALIDATE_INT)) {
    try {
        $idInscription = (int) $_GET['delete_participant_id'];
        $idFormation = (int) $_GET['participants_id'];

        // Supprimer l'inscription
        $success = $controller->deleteInscriptionFormation($idInscription);

        // Message de confirmation ou d'erreur
        $_SESSION[$success ? 'success' : 'error'] = $success ? "Participant supprimé avec succès !" : "Erreur lors de la suppression du participant.";

    } catch (Exception $e) {
        $_SESSION['error'] = "Erreur : " . $e->getMessage();
    }

    // Rediriger vers la page des participants de la formation après la suppression
    header("Location: formation_bo.php?participants_id=" . $idFormation);
    exit;
} else {
    // Si l'ID du participant n'est pas valide
    $_SESSION['error'] = "ID du participant invalide.";
    header("Location: formation_bo.php");
    exit;
}

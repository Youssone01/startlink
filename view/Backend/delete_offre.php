<?php
session_start();
require_once __DIR__ . '/../../controller/OffreController.php';

// Vérification admin


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $offreController = new OffreController();
    
    if ($offreController->deleteOffre($_GET['id'])) {
        header('Location: gestion_offres.php?success=1');
    } else {
        header('Location: gestion_offres.php?error=1');
    }
    exit;
}

header('Location: gestion_offres.php');
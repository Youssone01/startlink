<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../controllers/FormationController.php';

if (isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $idFormation = (int)$_GET['id'];
    $pdo = Database::getInstance()->getConnection();
    $formationController = new FormationController($pdo);

    try {
        if ($formationController->deleteFormation($idFormation)) {
            header("Location: formation_bo.php?success=Formation supprimée avec succès.");
            exit();
        } else {
            echo "Erreur lors de la suppression.";
        }
    } catch (Exception $e) {
        echo "Erreur : " . htmlspecialchars($e->getMessage());
    }
} else {
    echo "ID de formation invalide.";
}

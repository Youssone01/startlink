<?php
// Inclure les fichiers nécessaires pour se connecter à la base de données et la logique de gestion des certifications
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../controller/certifC.php';

// Vérifier si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_user = $_POST['id_user'];
    $id_formation = $_POST['id_formation'];
    $date_obtention = $_POST['date_obtention'];
    $score_quiz = $_POST['score_quiz'];
    $statut = $_POST['statut'];

    // Créer une instance de CertifC et appeler la méthode pour ajouter une certification
    $certifC = new CertifC();
    $certifC->ajouterCertification($id_user, $id_formation, $date_obtention, $score_quiz, $statut);
    
    // Redirection vers la liste des certifications après ajout
    header("Location: listcertif.php");
    exit();
}
?>

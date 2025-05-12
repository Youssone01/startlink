<?php
// Inclure le fichier de configuration et le contrôleur CertifC
require_once __DIR__ . '/../../Controllers/config.php';
require_once __DIR__ . '/../../controller/certifC.php';

$certifC = new CertifC();

// Vérifier si l'ID de certification est fourni
if (isset($_GET['id'])) {
    $id_certification = $_GET['id'];

    // Supprimer la certification
    $certifC->supprimerCertification($id_certification);
    header('Location: listcertif.php');
    exit;
} else {
    echo "ID de certification non fourni.";
    exit;
}
?>

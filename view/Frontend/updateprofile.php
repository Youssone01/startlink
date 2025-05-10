<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploadsDir = 'uploads/';
    $photoPath = '';

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['photo']['tmp_name'];
        $originalName = basename($_FILES['photo']['name']);
        $photoPath = $uploadsDir . uniqid() . '_' . $originalName;
        move_uploaded_file($tmpName, $photoPath);

        // Sauvegarder le chemin dans la session
        $_SESSION['photo'] = $photoPath;
    }

    // Mettre à jour les autres données (adresse, date de naissance, rôle)
    $_SESSION['adresse'] = $_POST['adresse'];
    $_SESSION['date_n'] = $_POST['date_n'];
    $_SESSION['role'] = $_POST['role'];

    header('Location: profile.php?updated=1');
    exit();
}
?>

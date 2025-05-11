<?php
session_start();



require_once __DIR__ . '/../../controller/config.php';
require_once __DIR__ . '/../../controller/ParticipantC.php';

$participantC = new ParticipantC();
$db = config::getConnexion();

if (!isset($_GET['id'])) {
    header('Location: voirParticipants.php');
    exit();
}

$idParticipant = $_GET['id'];
$participant = $participantC->getParticipantById($idParticipant);

if (!$participant) {
    $_SESSION['error'] = "Participant introuvable";
    header('Location: voirParticipants.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $tel = $_POST['tel'];
    
    try {
        $participantC->modifierParticipant($idParticipant, $nom, $prenom, $email, $tel);
        $_SESSION['success'] = "Participant mis à jour avec succès";
        header("Location: voirParticipants.php?id=" . $participant['evenement_id']);
        exit();
    } catch (Exception $e) {
        $_SESSION['error'] = "Erreur lors de la modification : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Modifier Participant - Dashboard Admin</title>
    <?php include('include/head.php') ?>
    <style>
        .form-container {
            background-color: #f0fafc;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: 0 auto;
        }
        .form-label {
            font-weight: 600;
            color: #13a7cd;
        }
        .form-control {
            border-radius: 6px;
            padding: 10px 15px;
            border: 1px solid #ced4da;
        }
        .btn-save {
            background-color: #13a7cd;
            color: white;
            border: none;
            padding: 10px 20px;
        }
        .btn-save:hover {
            background-color: #0d8bb7;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <?php include('include/sidebar.php') ?>

        <div class="main-panel">
            <div class="main-header">
                <div class="main-header-logo">
                    <div class="logo-header" data-background-color="dark">
                        <a href="dashboard.php" class="logo">
                            <img src="assets/img/kaiadmin/logo_dark.png" alt="navbar brand" class="navbar-brand" height="20">
                        </a>
                    </div>
                </div>
                <?php include('include/navhead.php') ?>
            </div>

            <div class="container-xxl py-5">
                <div class="container">
                    <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                        <h6 class="section-title bg-white text-center text-primary px-3">Gestion</h6>
                        <h1 class="mb-5">Modifier un Participant</h1>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="form-container wow fadeInUp" data-wow-delay="0.3s">
                                <?php if (isset($_SESSION['error'])): ?>
                                    <div class="alert alert-danger">
                                        <?= $_SESSION['error'] ?>
                                        <?php unset($_SESSION['error']); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <form method="POST">
                                    <div class="mb-4">
                                        <label for="nom" class="form-label">Nom</label>
                                        <input type="text" class="form-control" id="nom" name="nom" 
                                               value="<?= htmlspecialchars($participant['nom_user']) ?>" required>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="prenom" class="form-label">Prénom</label>
                                        <input type="text" class="form-control" id="prenom" name="prenom" 
                                               value="<?= htmlspecialchars($participant['prenom_user']) ?>" required>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               value="<?= htmlspecialchars($participant['email_user']) ?>" required>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="tel" class="form-label">Téléphone</label>
                                        <input type="tel" class="form-control" id="tel" name="tel" 
                                               value="<?= htmlspecialchars($participant['tel_user'] ?? '') ?>">
                                    </div>
                                    
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-save me-3">
                                            <i class="fas fa-save me-2"></i>Enregistrer
                                        </button>
                                        <a href="voirParticipants.php?id=<?= $participant['evenement_id'] ?>" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left me-2"></i>Annuler
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php include('include/footer.php') ?>
        </div>
    </div>

    <?php include('include/js.php') ?>
</body>
</html>
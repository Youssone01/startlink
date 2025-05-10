<?php

require_once __DIR__ . '/../../Controllers/config.php';
require_once __DIR__ . '/../../controller/FormationController.php';
require_once __DIR__ . '/../../model/inscriptionformation.php';

$controller = new FormationController();

$participant = null;
if (isset($_GET['edit_id'])) {
    // Récupérer les participants par ID de formation
    $participants = $controller->getInscriptionsByFormationId($_GET['participants_id']);
    
    // Recherche du participant spécifique par id
    foreach ($participants as $part) {
        if ($part['idInscription'] == $_GET['edit_id']) {
            $participant = $part;
            break;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_participant'];
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $dateInscription = $_POST['date_inscription']; // Capture de la date d'inscription
    
    // Mise à jour du participant avec la nouvelle date
    $success = $controller->updateInscriptionFormation($id, $nom, $email, $dateInscription);
    if ($success) {
        $_SESSION['success'] = "Participant mis à jour avec succès !";
        header("Location: formation_bo.php?participants_id=" . $_POST['id_formation']);
        exit;
    } else {
        $_SESSION['error'] = "Erreur lors de la mise à jour du participant.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Participant</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Roboto', sans-serif; background-color: #f7f9fc; margin: 0; padding: 0; }
        .container { 
            max-width: 600px; 
            margin: 50px auto; 
            padding: 30px; 
            background-color: #ffffff; 
            border-radius: 12px; 
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); 
        }
        h1 { 
            text-align: center; 
            color: #0a1b89; 
            font-size: 2rem;
            margin-bottom: 30px;
        }
        .form-group { margin-bottom: 20px; }
        label { 
            font-weight: 500; 
            color: #333; 
            font-size: 1rem; 
        }
        input, button { 
            width: 100%; 
            padding: 12px; 
            border: 1px solid #ddd; 
            border-radius: 8px; 
            font-size: 1rem; 
            transition: 0.3s;
        }
        input:focus, button:focus { 
            outline: none; 
            border-color: #0a1b89; 
        }
        button { 
            background-color: #0a1b89; 
            color: white; 
            font-size: 16px; 
            font-weight: bold; 
            cursor: pointer; 
            margin-top: 20px; 
        }
        button:hover { 
            background-color: #007bff; 
        }
        .alert { 
            padding: 15px; 
            margin-bottom: 20px; 
            border-radius: 6px; 
            font-weight: bold; 
        }
        .alert-success { background-color: #d4edda; color: #155724; }
        .alert-danger { background-color: #f8d7da; color: #721c24; }
        .form-footer { 
            text-align: center; 
            margin-top: 30px;
        }
        .form-footer a { 
            text-decoration: none; 
            color: #0a1b89; 
            font-weight: bold; 
        }
        .form-footer a:hover { text-decoration: underline; }

        /* Icon Style */
        .icon-calendar {
            font-size: 1.5rem;
            color: #0a1b89;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Modifier Participant</h1>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <?php if ($participant): ?>
        <form method="POST">
            <input type="hidden" name="id_participant" value="<?= $participant['idInscription'] ?>">
            <input type="hidden" name="id_formation" value="<?= $_GET['participants_id'] ?>">
            
            <div class="form-group">
                <label for="nom">Nom du Participant</label>
                <input type="text" name="nom" id="nom" value="<?= htmlspecialchars($participant['nomParticipant']) ?>">
            </div>
            
            <div class="form-group">
                <label for="email">Email du Participant</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($participant['emailParticipant']) ?>">
            </div>

            <div class="form-group">
                <label for="date_inscription">Date d'Inscription</label>
                <div style="display: flex; align-items: center;">
                    <input type="date" name="date_inscription" id="date_inscription" value="<?= htmlspecialchars($participant['dateInscription']) ?>">
                    <span class="icon-calendar" onclick="document.getElementById('date_inscription').focus();">&#x1F4C5;</span>
                </div>
            </div>
            
            <button type="submit">Mettre à jour</button>
        </form>
    <?php else: ?>
        <div class="alert alert-danger">Participant introuvable.</div>
    <?php endif; ?>

    <div class="form-footer">
        <a href="formation_bo.php?participants_id=<?= $_GET['participants_id'] ?>">Retour à la liste des participants</a>
    </div>
</div>

</body>
</html>

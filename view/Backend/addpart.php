<?php  
require_once __DIR__ . '/../../Controllers/config.php'; // Corrected to _DIR_
require_once __DIR__ . '/../../controller/FormationController.php'; // Corrected to _DIR_
require_once __DIR__ . '/../../model/formation.php'; // Corrected to _DIR_

$db = config::getConnexion();
$controller = new FormationController($db);
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get values from the form
    $idFormation = $_POST['id_formation'];
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $dateInscription = $_POST['date_inscription'];  // Get the registration date chosen by the user
    
    // Validate fields
    if (empty($nom) || empty($email) || empty($dateInscription)) {
        $message = "❌ Tous les champs sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "❌ L'email est invalide.";
    } else {
        // Add the participant with the specified date
        $success = $controller->addParticipantToFormation($idFormation, $nom, $email, $dateInscription);
        if ($success) {
            $_SESSION['success'] = "Participant ajouté avec succès !";
            header("Location: formation_bo.php?participants_id=" . $idFormation);
            exit;
        } else {
            $_SESSION['error'] = "Erreur lors de l'ajout du participant.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter Participant</title>
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
    <h1>Ajouter un Participant</h1>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="id_formation" value="<?= $_GET['idFormation'] ?>">
        
        <div class="form-group">
            <label for="nom">Nom du Participant</label>
            <input type="text" name="nom" id="nom">
        </div>
        
        <div class="form-group">
            <label for="email">Email du Participant</label>
            <input type="email" name="email" id="email">
        </div>

        <div class="form-group">
            <label for="date_inscription">Date d'Inscription</label>
            <div style="display: flex; align-items: center;">
                <input type="date" name="date_inscription" id="date_inscription">
                <span class="icon-calendar" onclick="document.getElementById('date_inscription').focus();">&#x1F4C5;</span>
            </div>
        </div>
        
        <button type="submit">Ajouter</button>
    </form>

    <div class="form-footer">
    <a href="formation_bo.php">← Retour à la liste des participants</a>    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const nomInput = document.getElementById('nom');
        const emailInput = document.getElementById('email');
        const dateInput = document.getElementById('date_inscription');
        const errorMessageDiv = document.createElement('div');
        errorMessageDiv.classList.add('alert', 'alert-danger', 'd-none');
        form.insertBefore(errorMessageDiv, form.firstChild);

        form.addEventListener('submit', function(event) {
            let errors = [];

            // Validation du nom
            if (nomInput.value.trim() === '') {
                errors.push("Le nom du participant est requis.");
            }

            // Validation de l'email
            if (emailInput.value.trim() === '') {
                errors.push("L'email est requis.");
            } else if (!validateEmail(emailInput.value.trim())) {
                errors.push("L'email est invalide.");
            }

            // Validation de la date d'inscription
            if (dateInput.value.trim() === '') {
                errors.push("La date d'inscription est requise.");
            }

            // Si des erreurs sont présentes, empêche l'envoi du formulaire
            if (errors.length > 0) {
                event.preventDefault();
                errorMessageDiv.classList.remove('d-none');
                errorMessageDiv.innerHTML = errors.join('<br>');
            }
        });

        // Fonction de validation de l'email
        function validateEmail(email) {
            const regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            return regex.test(email);
        }
    });
</script>
</body>
</html>  
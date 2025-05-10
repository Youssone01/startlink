<?php  
// Configuration et inclusion
require_once dirname(__FILE__) . '/../../config/config.php';
require_once dirname(__FILE__) . '/../../controllers/FormationController.php';

// Initialisation de la connexion et du contrôleur
$pdo = Database::getInstance()->getConnection();
$controller = new FormationController($pdo);

// Récupération de l'ID de la formation via l'URL
$formationId = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;

// Vérifiez si un ID est fourni
if ($formationId > 0) {
    // Récupération des détails de la formation
    $formation = $controller->getFormationById($formationId);
    
    if ($formation) {
        // Récupération des participants pour la formation donnée
        $participants = $controller->getParticipantsByFormation($formationId);
    } else {
        $error = "Formation non trouvée.";
        $participants = [];
    }
} else {
    $error = "ID de formation invalide.";
    $participants = [];
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Participants</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Styles de base pour la mise en page */
        body { 
            margin: 0; 
            background: #f4f9fd; 
            font-family: 'Segoe UI', sans-serif; 
        }
        .sidebar { 
            position: fixed; 
            height: 100vh; 
            width: 230px; 
            background: #2c3e50; 
            color: white; 
            padding: 20px; 
        }
        .sidebar h2 { 
            text-align: center; 
            margin-bottom: 40px; 
        }
        .sidebar a { 
            display: block; 
            color: white; 
            text-decoration: none; 
            margin: 10px 0; 
            padding: 10px; 
            border-radius: 8px; 
            transition: background 0.3s; 
        }
        .sidebar a:hover { 
            background: #3498db; 
        }
        .main { 
            margin-left: 250px; 
            padding: 40px; 
        }
        table { 
            width: 100%; 
            background: white; 
            border-collapse: collapse; 
            border-radius: 12px; 
            overflow: hidden; 
        }
        th, td { 
            padding: 14px; 
            text-align: center; 
            border-bottom: 1px solid #eee; 
        }
        th { 
            background-color: #3498db; 
            color: white; 
        }
        .alert {
            background-color: #f44336;
            color: white;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Dashboard</h2>
        <a href="addform.php">Ajouter une Formation</a>
        <a href="listeform.php">Liste des Formations</a>
    </div>

    <div class="main">
        <h1>Liste des Participants</h1>

        <?php if (!empty($error)): ?>
            <div class="alert"><?= $error ?></div>
        <?php elseif ($formationId > 0 && !empty($participants)): ?>
            <h3>Participants pour la formation : <?= htmlspecialchars($formation['nomFormation']) ?></h3>
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Formation</th>
                        <th>Date d'Inscription</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($participants as $participant): ?>
                        <tr>
                            <td><?= htmlspecialchars($participant['nomParticipant']) ?></td> <!-- Nom du participant -->
                            <td><?= htmlspecialchars($participant['emailParticipant']) ?></td> <!-- Email du participant -->
                            <td><?= htmlspecialchars($participant['nomFormation']) ?></td> <!-- Nom de la formation -->
                            <td><?= htmlspecialchars($participant['dateInscription']) ?></td> <!-- Date d'inscription -->
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif ($formationId > 0): ?>
            <p>Aucun participant trouvé pour cette formation.</p>
        <?php endif; ?>

        <!-- Affichage des informations de la formation, y compris le nombre de places disponibles -->
        <h3>Détails de la formation</h3>
        <?php if ($formation): ?>
            <ul>
                <li><strong>Nom de la formation :</strong> <?= htmlspecialchars($formation['nomFormation']) ?></li>
                <li><strong>Durée :</strong> <?= htmlspecialchars($formation['duree']) ?> heures</li>
                <li><strong>Places disponibles :</strong> <?= htmlspecialchars($formation['places_disponibles']) ?></li>
                <li><strong>Date de début :</strong> <?= !empty($formation['date_debut']) ? date('d M Y', strtotime($formation['date_debut'])) : 'Non définie' ?></li>
                <li><strong>Date de fin :</strong> <?= !empty($formation['date_fin']) ? date('d M Y', strtotime($formation['date_fin'])) : 'Non définie' ?></li>
            </ul>
        <?php endif; ?>
    </div>
</body>
</html>

<?php 
session_start();

if (!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../../controller/FormationController.php';
$controller = new FormationController();
$formations = $controller->getAllFormations();

include_once __DIR__ . '/include/header.php';
include_once __DIR__ . '/include/navbar.php';

$inscriptionSuccess = isset($_GET['success']) && $_GET['success'] === 'true';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Catalogue des Formations</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(to right, #e6f0ff, #ffffff);
            margin: 0;
            padding: 0;
            color: #333;
        }

        h1 {
            text-align: center;
            padding: 40px 0 10px;
            font-size: 3em;
            color: #002B5B;
            font-weight: 600;
            text-transform: uppercase;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            padding: 30px;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            justify-content: center;
            animation: fadeIn 1.2s ease-in-out;
        }

        @keyframes fadeIn {
            from {opacity: 0; transform: translateY(20px);}
            to {opacity: 1; transform: translateY(0);}
        }

        .service-item {
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            padding: 25px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            flex: 1 1 280px;
            max-width: 320px;
            position: relative;
        }

        .service-item:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }

        .service-item i {
            font-size: 3em;
            color: #00bcd4;
            margin-bottom: 15px;
        }

        .service-item h5 {
            font-size: 1.5em;
            margin-bottom: 10px;
            color:rgb(26, 148, 182);
        }

        .service-item p {
            margin: 6px 0;
            font-size: 0.95em;
        }

        .btn {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            background: linear-gradient(to right, #00bcd4, #0097a7);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            text-decoration: none;
            transition: background 0.3s ease;
        }

        .btn:hover {
            background: linear-gradient(to right,rgb(15, 153, 174),rgb(21, 142, 164));
        }

        .places-restantes {
            margin-top: 10px;
            font-weight: bold;
            color: #003366;
        }

        .badge-niveau {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: 600;
            color: white;
        }

        .niveau-Débutant { background-color: #4caf50; }
        .niveau-Intermédiaire { background-color: #ff9800; }
        .niveau-Avancé { background-color: #f44336; }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border: 1px solid #c3e6cb;
            border-radius: 10px;
            text-align: center;
            width: 80%;
            margin: 20px auto;
            font-size: 1.1em;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 2.2em;
            }

            .service-item {
                max-width: 90%;
            }
        }
    </style>
</head>
<body>

    <h1>Catalogue des Formations</h1>

    <?php if ($inscriptionSuccess): ?>
        <div class="success-message">
            ✅ Inscription réussie ! Vous avez bien été inscrit à la formation.
        </div>
    <?php endif; ?>

    <div class="container">
        <div class="row">
            <?php if (empty($formations)): ?>
                <p style="text-align: center; color: #777; font-size: 1.2em;">Aucune formation disponible pour le moment.</p>
            <?php else: ?>
                <?php foreach ($formations as $f): 
                    $participants = $controller->getParticipantsByFormation($f['idFormation']);
                    $nbParticipants = count($participants);
                    $placesRestantes = max(0, $f['places_disponibles']);
                    $niveauClass = "niveau-" . htmlspecialchars($f['niveau']);
                ?>
                    <div class="service-item">
                        <div class="badge-niveau <?= $niveauClass ?>">
                            <?= htmlspecialchars($f['niveau']) ?>
                        </div>
                        <i class="fas fa-graduation-cap"></i>
                        <h5><?= htmlspecialchars($f['nomFormation']) ?></h5>
                        <p><strong>Durée :</strong> <?= (int)$f['duree'] ?>h</p>
                        <p><strong>Début :</strong> <?= htmlspecialchars($f['date_debut']) ?></p>
                        <p><strong>Fin :</strong> <?= htmlspecialchars($f['date_fin']) ?></p>
                        <div class="places-restantes">
                            <strong>Places restantes :</strong> <?= $placesRestantes ?>
                        </div>
                        <?php if ($placesRestantes > 0): ?>
                            <a class="btn" href="inscription.php?idFormation=<?= $f['idFormation'] ?>">S'inscrire</a>
                        <?php else: ?>
                            <p style="color: #f44336; font-weight: bold;">Formation complète</p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <?php include_once __DIR__ . '/include/footer.php'; ?>
</body>
</html>

<?php
include('include/header.php');
include('include/navbar.php');

require_once __DIR__ . '/../../Controllers/config.php';

try {
    $pdo = config::getConnexion();

    // Requête pour récupérer les certifications
    $sql = "SELECT id_certification, id_user, id_formation, date_obtention, score_quiz, niveau FROM certifications";
    $certifications = $pdo->query($sql)->fetchAll();

} catch (PDOException $e) {
    echo '<div class="alert alert-danger text-center my-5">Erreur : ' . htmlspecialchars($e->getMessage()) . '</div>';
    include('include/footer.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Certifications</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f8fa;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
        }

        .certif-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .certif-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            padding: 15px;
        }

        .certif-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .certif-header {
            background-color: #003366;
            color: white;
            padding: 10px;
            font-size: 1.1rem;
            text-align: center;
            font-weight: bold;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .certif-body {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .certif-item {
            display: flex;
            justify-content: space-between;
            font-size: 0.9rem;
            color: #555;
        }

        .certif-item span {
            font-weight: bold;
            color: #003366;
        }

        .badge {
            display: inline-block;
            padding: 5px 10px;
            font-size: 0.8rem;
            font-weight: bold;
            border-radius: 8px;
            margin-top: 10px;
            text-align: center;
        }

        .badge-success {
            background-color: #28a745;
            color: white;
        }

        .badge-primary {
            background-color: #007bff;
            color: white;
        }

        .badge-info {
            background-color: #17a2b8;
            color: white;
        }

        .badge-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-repasser {
            display: block;
            margin: 20px auto;
            background-color: #ff9f00;
            color: white;
            text-transform: uppercase;
            padding: 12px 20px;
            font-size: 1rem;
            font-weight: bold;
            text-align: center;
            border-radius: 8px;
            transition: background-color 0.3s ease;
            text-decoration: none;
        }

        .btn-repasser:hover {
            background-color: #e68900;
        }
    </style>
</head>
<body>
    <main>
        <div class="container">
            <h1 class="text-center mb-4">Liste des Certifications</h1>
            
            <!-- Bouton pour certifications à repasser -->
            <a href="certifications_a_repasser.php" class="btn-repasser">Voir mes certifications à repasser</a>
            
            <div class="certif-grid">
                <?php if (count($certifications) > 0): ?>
                    <?php foreach ($certifications as $certif): ?>
                        <?php
                            $scorePercent = $certif['score_quiz'] * 10; // Supposons que le score max est 10
                            $badgeClass = 'badge-danger';
                            if ($certif['niveau'] === 'Débutant') {
                                $badgeClass = 'badge-info';
                            } elseif ($certif['niveau'] === 'Intermédiaire') {
                                $badgeClass = 'badge-primary';
                            } elseif ($certif['niveau'] === 'Avancé') {
                                $badgeClass = 'badge-success';
                            }
                        ?>
                        <div class="certif-card">
                            <div class="certif-header">
                                Certification ID: <?= htmlspecialchars($certif['id_certification']); ?>
                            </div>
                            <div class="certif-body">
                                <div class="certif-item">
                                    <span>ID Utilisateur :</span>
                                    <span><?= htmlspecialchars($certif['id_user']); ?></span>
                                </div>
                                <div class="certif-item">
                                    <span>ID Formation :</span>
                                    <span><?= htmlspecialchars($certif['id_formation']); ?></span>
                                </div>
                                <div class="certif-item">
                                    <span>Date d'obtention :</span>
                                    <span><?= htmlspecialchars($certif['date_obtention']); ?></span>
                                </div>
                                <div class="certif-item">
                                    <span>Score Quiz :</span>
                                    <span class="text-primary"><?= round($scorePercent); ?>%</span>
                                </div>
                                <div class="certif-item">
                                    <span>Niveau :</span>
                                    <span class="badge <?= $badgeClass; ?>"><?= htmlspecialchars($certif['niveau']); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center">Aucune certification trouvée.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>
    <?php include('include/footer.php'); ?>
</body>
</html>

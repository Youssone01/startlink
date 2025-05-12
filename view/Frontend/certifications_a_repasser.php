<?php
session_start();
include __DIR__ . '/include/header.php';
include __DIR__ . '/include/navbar.php';
// Inclusion de la configuration et de la base de données
require_once __DIR__ . '/../../Controllers/config.php';
$db = config::getConnexion();

// Récupérer l'ID de l'utilisateur
$user_id = $_SESSION['user_id']; // L'ID de l'utilisateur est stocké dans la session

// Récupérer les certifications avec un score <= 3 pour cet utilisateur
$stmt = $db->prepare('
    SELECT * 
    FROM certifications 
    WHERE id_user = :user_id AND score_quiz <= 3
');
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$certificationsNonValidees = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certifications à Repasser</title>
    <link rel="stylesheet" href="path/to/your/style.css"> <!-- Chemin vers ton fichier CSS -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f8fa;
            color: #333;
            margin: 0;
            padding: 0;
        }

        main {
            width: 100%;
            padding: 40px 20px;
            box-sizing: border-box;
        }

        h3 {
            font-size: 28px;
            margin-bottom: 30px;
            color: #003366;
            text-align: center;
            font-weight: 600;
        }

        .certifications-container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-top: 20px;
            margin-bottom: 20px;
            max-width: 900px;
            margin-left: auto;
            margin-right: auto;
        }

        .certification-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        .certification-item:last-child {
            border-bottom: none;
        }

        .certification-details {
            font-size: 16px;
            color: #555;
            font-weight: 500;
        }

        .btn-repasser {
            background-color: #ff9f00;
            color: white;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s ease, transform 0.3s ease;
            font-weight: 600;
        }

        .btn-repasser:hover {
            background-color: #e68900;
            transform: scale(1.05);
        }

        .no-certifications-message {
            font-size: 18px;
            color: #666;
            padding: 15px;
            background-color: #f0f0f0;
            border-radius: 6px;
            text-align: center;
        }
    </style>
</head>
<body>

<main>
    <div class="container">
        <h3>Certifications à Repasser</h3>

        <div class="certifications-container">
            <?php if ($certificationsNonValidees): ?>
                <ul>
                    <?php foreach ($certificationsNonValidees as $certification): ?>
                        <li class="certification-item">
                            <div class="certification-details">
                                <strong>Certification :</strong> <?= htmlspecialchars($certification['id_certification']); ?><br>
                                <strong>Score :</strong> <?= $certification['score_quiz']; ?>/5
                            </div>
                            <a href="repasser_quiz.php?id_quiz=<?= htmlspecialchars($certification['id_quiz']); ?>" class="btn-repasser">Repasser</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <div class="no-certifications-message">
                    Vous avez validé toutes vos certifications. Félicitations !
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

</body>
</html>
<?php include __DIR__ . '/include/footer.php'; ?>

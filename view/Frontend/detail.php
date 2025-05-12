<?php
session_start();

// Inclusion du header & navbar StartLink
include __DIR__ . '/include/header.php';
include __DIR__ . '/include/navbar.php';

// Connexion PDO
require_once realpath(__DIR__ . '/../../Controllers/config.php');
$db = config::getConnexion();

// Validation de l'ID envoyé en POST
if (empty($_POST['id_quiz']) || !is_numeric($_POST['id_quiz'])) {
    echo '<div class="alert alert-danger text-center my-5">Quiz introuvable.</div>';
    include __DIR__ . '/include/footer.php';
    exit;
}
$id_quiz = (int) $_POST['id_quiz'];

// Récupération des détails du quiz
try {
    $stmt = $db->prepare(
        'SELECT titre, description, duree
         FROM quiz
         WHERE id_quiz = :id'
    );
    $stmt->bindValue(':id', $id_quiz, PDO::PARAM_INT);
    $stmt->execute();
    $quiz = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$quiz) {
        echo '<div class="alert alert-warning text-center my-5">Aucun quiz trouvé.</div>';
        include __DIR__ . '/include/footer.php';
        exit;
    }
} catch (PDOException $e) {
    echo '<div class="alert alert-danger text-center my-5">Erreur SQL : ' . htmlspecialchars($e->getMessage()) . '</div>';
    include __DIR__ . '/include/footer.php';
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détail Quiz</title>
    <style>
        :root {
            --sl-primary:  #1565c0; /* Bleu foncé */
            --sl-secondary: rgb(173, 190, 229); /* Gris clair */
            --sl-accent: #f7941d; /* Orange */
            --sl-background: rgb(239, 239, 239); /* Bleu clair */
            --sl-light-bg:#f0f4f8; /* Blanc cassé */
            --sl-card-bg: #fff; /* Fond des cartes */
        }

        body {
            background-color: var(--sl-background);
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            color: var(--sl-primary);
        }

        .breadcrumb {
            background: transparent;
            padding: 0;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }

        .breadcrumb a {
            color: var(--sl-primary);
            text-decoration: none;
            font-weight: 500;
        }

        .breadcrumb a:hover {
            color: var(--sl-accent);
            text-decoration: underline;
        }

        .card {
            background-color: var(--sl-card-bg);
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 960px;
            margin: auto;
        }

        .card-header {
            background: var(--sl-primary);
            color: white;
            text-align: center;
            padding: 20px;
            font-size: 1.8rem;
            font-weight: bold;
            border-bottom: 5px solid var(--sl-accent);
        }

        .card-body {
            padding: 30px;
        }

        .card-body h5 {
            font-size: 1.2rem;
            color: var(--sl-primary);
            font-weight: 600;
            margin-bottom: 10px;
        }

        .card-body p {
            font-size: 1rem;
            line-height: 1.6;
            color: #555;
        }

        .card-body .text-muted {
            color: #777;
            font-style: italic;
        }

        .btn {
            padding: 10px 20px;
            font-size: 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .btn:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .btn-sl-primary {
            background: var(--sl-primary);
            color: white;
        }

        .btn-sl-primary:hover {
            background:rgb(49, 113, 186);
        }

        .btn-sl-accent {
            background: var(--sl-accent);
            color: white;
        }

        .btn-sl-accent:hover {
            background: #d67c17;
        }

        .d-flex {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container py-5">
    <!-- Fil d’Ariane -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">StartLink</a></li>
            <li class="breadcrumb-item"><a href="quizz.php">Quiz</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($quiz['titre']) ?></li>
        </ol>
    </nav>

    <!-- Carte Détail Quiz -->
    <div class="card">
        <div class="card-header">
            <?= htmlspecialchars($quiz['titre']) ?>
        </div>
        <div class="card-body">
            <h5>Description</h5>
            <p class="text-muted"><?= nl2br(htmlspecialchars($quiz['description'])) ?></p>

            <h5 class="mt-4">Durée</h5>
            <p class="text-muted"><?= htmlspecialchars($quiz['duree']) ?> minutes</p>

            <div class="d-flex">
                <form action="start.php" method="POST">
                    <input type="hidden" name="id_quiz" value="<?= $id_quiz ?>">
                    <button type="submit" class="btn btn-sl-accent">Commencer le Quiz</button>
                </form>
                <a href="quizz.php" class="btn btn-sl-primary">Retour à la liste</a>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/include/footer.php'; ?>
</body>
</html>

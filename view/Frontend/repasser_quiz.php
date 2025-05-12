<?php  
require_once __DIR__ . '/../../Controllers/config.php';

session_start();

// Connexion à la base de données
try {
    $db = config::getConnexion();
} catch (PDOException $e) {
    echo 'Erreur de connexion à la base de données : ' . $e->getMessage();
    exit;
}

// Vérifier que l'ID de la certification est passé dans l'URL
if (!isset($_GET['id_certification']) || !is_numeric($_GET['id_certification'])) {
    echo 'ID de certification invalide. Veuillez vérifier le lien.';
    exit;
}

$id_certification = intval($_GET['id_certification']);

// Récupérer les détails de la certification et vérifier le score
try {
    $stmt = $db->prepare('SELECT id_quiz, score_quiz FROM certifications WHERE id_certification = :id_certification');
    $stmt->bindValue(':id_certification', $id_certification, PDO::PARAM_INT);
    $stmt->execute();
    $certification = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Erreur lors de la récupération de la certification : ' . $e->getMessage();
    exit;
}

// Vérifier si une certification a été trouvée
if (!$certification) {
    echo 'Certification non trouvée ou déjà validée.';
    exit;
}

// Vérifier que le score est inférieur à 3
if ($certification['score_quiz'] >= 3) {
    echo 'Cette certification a déjà un score suffisant. Vous ne pouvez pas repasser ce quiz.';
    exit;
}

$id_quiz = $certification['id_quiz']; // ID du quiz lié à la certification

// Récupérer les détails du quiz
try {
    $stmt = $db->prepare('SELECT * FROM quiz WHERE id_quiz = :id_quiz');
    $stmt->bindValue(':id_quiz', $id_quiz, PDO::PARAM_INT);
    $stmt->execute();
    $quiz = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Erreur lors de la récupération du quiz : ' . $e->getMessage();
    exit;
}

// Vérifier si le quiz existe
if (!$quiz) {
    echo 'Quiz non trouvé. Veuillez contacter l\'administrateur.';
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Repasser le Quiz</title>
    <link rel="stylesheet" href="path/to/your/style.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f8fa;
            color: #333;
            margin: 0;
            padding: 0;
        }
        main {
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #003366;
            text-align: center;
            margin-bottom: 20px;
        }
        .quiz-detail {
            font-size: 18px;
            line-height: 1.6;
        }
        .btn-start {
            display: inline-block;
            background-color: #003366;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            font-size: 16px;
            border-radius: 5px;
            text-align: center;
            margin-top: 20px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .btn-start:hover {
            background-color: #0055aa;
            transform: scale(1.05);
        }
    </style>
</head>
<body>

<main>
    <h2>Détails du Quiz</h2>
    <div class="quiz-detail">
        <strong>Titre :</strong> <?= htmlspecialchars($quiz['titre']); ?><br>
        <strong>Description :</strong> <?= htmlspecialchars($quiz['description']); ?><br>
        <strong>Durée :</strong> <?= htmlspecialchars($quiz['duree']); ?> minutes<br>
        <strong>Question :</strong> <?= !empty($quiz['question']) ? htmlspecialchars($quiz['question']) : 'Aucune question définie'; ?><br>
        <strong>Réponse correcte :</strong> <?= !empty($quiz['correct_answer']) ? htmlspecialchars($quiz['correct_answer']) : 'Aucune réponse définie'; ?><br>
    </div>

    <?php if (!empty($quiz['id_quiz'])) : ?>
        <a href="start_quiz.php?id_quiz=<?= $quiz['id_quiz']; ?>" class="btn-start">Commencer le Quiz</a>
    <?php else : ?>
        <p>Impossible de générer le lien vers le quiz. Veuillez réessayer.</p>
    <?php endif; ?>
</main>

</body>
</html>

<?php
require_once __DIR__ . '/../../Controllers/config.php';
require_once __DIR__ . '/../../controller/QuizController.php';
include('include/head.php');
include('include/sidebar.php');

// Vérifie si un ID de quiz a été passé en paramètre
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID de quiz manquant.");
}

$quizId = intval($_GET['id']);
$controller = new QuizController($db);

// Récupère les détails du quiz
$quiz = $controller->getQuizById($quizId);
if (!$quiz) {
    die("Quiz non trouvé.");
}

// Récupère les questions et réponses du quiz
$questions = $controller->getQuestionsByQuizId($quizId);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Quiz</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #e9f1fb;
            margin: 0;
            padding: 0;
        }

        .container {
            margin-left: 260px;
            max-width: calc(100% - 260px);
            padding: 30px;
        }

        h1 {
            text-align: center;
            color: #0056b3;
            font-size: 2.5rem;
            margin-bottom: 30px;
        }

        .quiz-details {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .quiz-details h2 {
            color: #003366;
            font-size: 1.8rem;
            margin-bottom: 15px;
        }

        .quiz-details p {
            margin: 10px 0;
            font-size: 1.1rem;
            color: #444;
        }

        .questions {
            margin-top: 30px;
        }

        .question {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .question h3 {
            color: #0056b3;
            font-size: 1.2rem;
            margin-bottom: 10px;
        }

        .question p {
            margin: 5px 0;
            font-size: 1rem;
            color: #555;
        }

        .back-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #0056b3;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .back-button:hover {
            background-color: #003d80;
            transform: translateY(-3px);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Détails du Quiz</h1>
        <div class="quiz-details">
            <h2><?= htmlspecialchars($quiz['titre']); ?></h2>
            <p><strong>Description :</strong> <?= htmlspecialchars($quiz['description']); ?></p>
            <p><strong>Durée :</strong> <?= htmlspecialchars($quiz['duree']); ?> minutes</p>
        </div>

        <div class="questions">
            <h2>Questions et Réponses</h2>
            <?php if (!empty($questions)): ?>
                <?php foreach ($questions as $question): ?>
                    <div class="question">
                        <h3>Question : <?= htmlspecialchars($question['texte_question']); ?></h3>
                        <p><strong>Réponse correcte :</strong> <?= htmlspecialchars($question['reponse_correcte']); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucune question trouvée pour ce quiz.</p>
            <?php endif; ?>
        </div>

        <a href="listquiz.php" class="back-button">Retour à la liste</a>
    </div>
</body>
</html>

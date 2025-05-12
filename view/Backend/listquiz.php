<?php
require_once __DIR__ . '/../../Controllers/config.php';
require_once __DIR__ . '/../../controller/QuizController.php';
include('include/head.php');
include('include/sidebar.php');

$controller = new QuizController($db);
$quizzes = $controller->getAllQuizzes();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Quizzes</title>
    <style>
        /* Styles existants */
        :root {
            --primary-color: #0056b3;
            --primary-hover: #003d80;
            --secondary-color: #003366;
            --secondary-hover: #00264d;
            --danger-color: #dc3545;
            --danger-hover: #b02a37;
            --background-color: #e9f1fb;
            --card-bg-color: #ffffff;
            --card-shadow: rgba(0, 0, 0, 0.1);
            --hover-shadow: rgba(0, 0, 0, 0.2);
            --border-radius: 8px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--background-color);
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
            color: var(--primary-color);
            font-size: 2.5rem;
            margin-bottom: 30px;
        }

        .add-button {
            display: inline-block;
            padding: 12px 25px;
            background-color: var(--primary-color);
            color: #fff;
            font-size: 1.1rem;
            font-weight: bold;
            text-align: center;
            text-decoration: none;
            border-radius: var(--border-radius);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 12px var(--card-shadow);
            margin-bottom: 20px;
        }

        .add-button:hover {
            background-color: var(--primary-hover);
            transform: translateY(-3px);
            box-shadow: 0 6px 16px var(--hover-shadow);
        }

        .quiz-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .quiz-card {
            display: flex;
            flex-direction: column;
            gap: 10px;
            padding: 20px;
            border-radius: var(--border-radius);
            background-color: var(--card-bg-color);
            box-shadow: 0 4px 12px var(--card-shadow);
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }

        .quiz-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px var(--hover-shadow);
        }

        .quiz-card h3 {
            color: var(--primary-color);
            font-size: 1.8rem;
            margin: 0;
        }

        .quiz-card p {
            margin: 5px 0;
            font-size: 1rem;
            color: #444;
        }

        .actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 10px;
        }

        .actions a, .actions form button {
            text-decoration: none;
            padding: 10px 20px;
            border-radius: var(--border-radius);
            font-size: 0.95rem;
            font-weight: bold;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 100px;
        }

        .actions a {
            background-color: var(--secondary-color);
        }

        .actions a:hover {
            background-color: var(--secondary-hover);
        }

        .actions form button {
            background-color: var(--danger-color);
            border: none;
        }

        .actions form button:hover {
            background-color: var(--danger-hover);
        }

        .no-quizzes {
            text-align: center;
            font-size: 1.2rem;
            color: #666;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Liste des Quizzs</h1>
        <div style="text-align: right;">
            <a href="addquiz.php" class="add-button">Ajouter un Quiz</a>
            <a href="createquiz.php" class="add-button">Ajouter un Quiz Dynamiquement</a>

        </div>
        <div class="quiz-list">
            <?php if (!empty($quizzes)): ?>
                <?php foreach ($quizzes as $quiz): ?>
                    <div class="quiz-card">
                        <h3><?= htmlspecialchars($quiz['titre']); ?></h3>
                        <p><strong>Description :</strong> <?= htmlspecialchars($quiz['description']); ?></p>
                        <p><strong>Durée :</strong> <?= htmlspecialchars($quiz['duree']); ?> minutes</p>
                        <div class="actions">
                            <a href="quizdetails.php?id=<?= htmlspecialchars($quiz['id_quiz']); ?>">Afficher</a>
                            <a href="updatequiz.php?id=<?= htmlspecialchars($quiz['id_quiz']); ?>">Modifier</a>
                            <form method="POST" action="deletequiz.php" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce quiz ?');">
                                <input type="hidden" name="id_quiz" value="<?= htmlspecialchars($quiz['id_quiz']); ?>">
                                <button type="submit">Supprimer</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-quizzes">Aucun quiz trouvé.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

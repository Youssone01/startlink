<?php
require_once __DIR__ . '/../../Controllers/config.php';
require_once __DIR__ . '/../../controller/QuizController.php';
require_once __DIR__ . '/../../model/Quiz.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id_formation = $_POST['id_formation'] ?? '';
        $titre = $_POST['titre'] ?? '';
        $description = $_POST['description'] ?? '';
        $duree = isset($_POST['duree']) ? intval($_POST['duree']) : null;

        if (empty($id_formation) || empty($titre) || empty($description) || $duree === null) {
            throw new Exception("Tous les champs obligatoires doivent être remplis.");
        }
        if ($duree > 60) {
            throw new Exception("La durée ne peut pas dépasser 60 minutes.");
        }

        $questions = $_POST['questions'] ?? [];
        if (count($questions) < 1) {
            throw new Exception("Vous devez fournir au moins une question.");
        }

        foreach ($questions as $index => $question) {
            if (empty($question['text']) || empty($question['correct']) || empty($question['incorrect1']) || empty($question['incorrect2'])) {
                throw new Exception("Toutes les réponses pour la question " . ($index + 1) . " doivent être fournies.");
            }
        }

        $quiz = new Quiz($titre, $description, $duree, $questions, $id_formation, $questions[0]['correct']);
        $controller = new QuizController();

        if ($controller->createQuiz($quiz, $questions)) {
            $message = "<p class='success-message'>✅ Quiz ajouté avec succès.</p>";
        } else {
            throw new Exception("Une erreur est survenue lors de l'ajout du quiz.");
        }
    } catch (Exception $e) {
        $message = "<p class='error-message'>Erreur : " . $e->getMessage() . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Quiz</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #b3d9ff, #e6f2ff);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .form-container {
            width: 100%;
            max-width: 600px;
            background: #fff;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            overflow-y: auto;
            max-height: 90vh;
        }
        h2 {
            text-align: center;
            color: #4a4a4a;
            margin-bottom: 15px;
            font-size: 1.5rem;
        }
        input, textarea, button {
            width: 100%;
            margin-bottom: 10px;
            padding: 8px;
            font-size: 0.9rem;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        textarea {
            resize: none;
        }
        button {
            background-color: #173261;
            color: white;
            border: none;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
            padding: 10px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .back-button {
            background-color: #ccc;
            color: #333;
            font-size: 0.9rem;
            padding: 8px 12px;
            border: 1px solid #aaa;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
            display: inline-block;
            margin-bottom: 15px;
            transition: background-color 0.3s;
        }
        .back-button:hover {
            background-color: #bbb;
        }
        .question-block {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .add-question {
            background-color: #4CAF50;
            margin-top: 10px;
        }
        .remove-question {
            background-color: #e74c3c;
            margin-top: 5px;
            display: block;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <a href="listquiz.php" class="back-button">⬅ Retour</a>
        <h2>Ajouter un Quiz</h2>
        <?php if (isset($message)) echo $message; ?>
        <form id="quizForm" method="POST" action="addquiz.php">
            <input type="text" name="id_formation" placeholder="ID Formation" required>
            <input type="text" name="titre" placeholder="Titre du Quiz" required>
            <textarea name="description" placeholder="Description" rows="2" required></textarea>
            <input type="number" name="duree" placeholder="Durée (minutes)" min="1" max="60" required>

            <div id="questions-container">
                <div class="question-block">
                    <textarea name="questions[0][text]" placeholder="texte_question" rows="1" required></textarea>
                    <input type="text" name="questions[0][correct]" placeholder=" reponse_correcte" required>
                    <input type="text" name="questions[0][incorrect1]" placeholder="reponse_incorrecte1" required>
                    <input type="text" name="questions[0][incorrect2]" placeholder="reponse_incorrecte2" required>
                    <button type="button" class="remove-question">Supprimer</button>
                </div>
            </div>

            <button type="button" id="add-question" class="add-question">Ajouter une question</button>
            <button type="submit">Ajouter le Quiz</button>
        </form>
    </div>

    <script>
        const questionsContainer = document.getElementById('questions-container');
        const addQuestionButton = document.getElementById('add-question');

        addQuestionButton.addEventListener('click', () => {
            const questionCount = questionsContainer.children.length;
            const questionBlock = document.createElement('div');
            questionBlock.className = 'question-block';
            questionBlock.innerHTML = `
                <textarea name="questions[${questionCount}][text]" placeholder="texte_question" rows="1" required></textarea>
                <input type="text" name="questions[${questionCount}][correct]" placeholder="reponse_correcte" required>
                <input type="text" name="questions[${questionCount}][incorrect1]" placeholder="reponse_incorrecte1" required>
                <input type="text" name="questions[${questionCount}][incorrect2]" placeholder="reponse_incorrecte2" required>
                <button type="button" class="remove-question">Supprimer</button>
            `;
            questionsContainer.appendChild(questionBlock);

            questionBlock.querySelector('.remove-question').addEventListener('click', () => {
                questionBlock.remove();
            });
        });

        questionsContainer.addEventListener('click', (event) => {
            if (event.target.classList.contains('remove-question')) {
                event.target.closest('.question-block').remove();
            }
        });
    </script>
</body>
</html>

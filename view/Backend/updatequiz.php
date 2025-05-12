<?php
session_start();

// Vérifier si l'ID est présent dans la requête GET
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_quiz = intval($_GET['id']);
} else {
    $_SESSION['message'] = "<div style='color: red;'>ID de quiz manquant. Veuillez sélectionner un quiz à modifier.</div>";
    header('Location: listquiz.php');
    exit;
}

// Connexion à la base de données
require_once __DIR__ . '/../../Controllers/config.php';

try {
    $db = Config::getConnexion();

    // Requête pour récupérer le quiz par son ID
    $query = $db->prepare("SELECT id_quiz, titre, description, duree FROM quiz WHERE id_quiz = :id_quiz");
    $query->bindParam(':id_quiz', $id_quiz, PDO::PARAM_INT);
    $query->execute();

    $quiz = $query->fetch();

    if (!$quiz) {
        $_SESSION['message'] = "<div style='color: red;'>Quiz introuvable pour l'ID : $id_quiz.</div>";
        header('Location: listquiz.php');
        exit;
    }
} catch (PDOException $e) {
    $_SESSION['message'] = "<div style='color: red;'>Erreur : " . $e->getMessage() . "</div>";
    header('Location: listquiz.php');
    exit;
}

$message = "";

// Traitement de la mise à jour
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['titre'], $_POST['description'], $_POST['duree'])) {
        $titre = htmlspecialchars($_POST['titre']);
        $description = htmlspecialchars($_POST['description']);
        $duree = intval($_POST['duree']);

        try {
            $updateQuery = $db->prepare(
                "UPDATE quiz SET 
                titre = :titre, 
                description = :description, 
                duree = :duree 
                WHERE id_quiz = :id_quiz"
            );

            $updateQuery->bindParam(':id_quiz', $id_quiz, PDO::PARAM_INT);
            $updateQuery->bindParam(':titre', $titre, PDO::PARAM_STR);
            $updateQuery->bindParam(':description', $description, PDO::PARAM_STR);
            $updateQuery->bindParam(':duree', $duree, PDO::PARAM_INT);

            $updateQuery->execute();

            $_SESSION['message'] = "<div style='color: green;'>Le quiz a été mis à jour avec succès !</div>";
            header('Location: listquiz.php');
            exit;
        } catch (PDOException $e) {
            $message = "<div style='color: red;'>Erreur de mise à jour : " . $e->getMessage() . "</div>";
        }
    } else {
        $message = "<div style='color: red;'>Veuillez remplir tous les champs.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Quiz</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            max-width: 600px;
            width: 100%;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            padding: 40px;
            transition: transform 0.3s ease-in-out;
        }

        .form-container:hover {
            transform: translateY(-5px);
        }

        h2 {
            text-align: center;
            color: #1a237e;
            margin-bottom: 20px;
            font-weight: 600;
        }

        input, textarea, button {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif; /* Changement de police dans les champs */
        }

        input, textarea {
            background-color: #f9f9f9;
            color: #333;
        }

        input:focus, textarea:focus {
            border-color: #3949ab;
            outline: none;
        }

        button {
            background-color: #3949ab;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #283593;
        }

        .back-btn {
            background-color: #757575;
        }

        .back-btn:hover {
            background-color: #616161;
        }

        .message {
            text-align: center;
            margin-bottom: 10px;
            font-size: 1.1rem;
        }

        .message div {
            margin: 10px 0;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .form-container {
                padding: 25px;
            }

            h2 {
                font-size: 1.8rem;
            }

            input, textarea, button {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Modifier un Quiz</h2>
        <?php if ($quiz): ?>
            <form method="POST" action="">
                <input type="text" name="titre" value="<?= htmlspecialchars($quiz['titre']) ?>" placeholder="Titre du Quiz" required>
                <textarea name="description" placeholder="Description" required><?= htmlspecialchars($quiz['description']) ?></textarea>
                <input type="number" name="duree" value="<?= htmlspecialchars($quiz['duree']) ?>" placeholder="Durée en minutes" required step="1" min="1">
                <button type="submit">Mettre à jour</button>
                <div class="message"><?= $message ?></div>
            </form>
            <form action="listquiz.php">
                <button class="back-btn">Retour</button>
            </form>
        <?php else: ?>
            <p style="color: red; text-align: center;">Aucun quiz sélectionné pour la modification.</p>
        <?php endif; ?>
    </div>
</body>
</html>

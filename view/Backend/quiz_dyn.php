<?php
// Inclure le fichier de configuration
require_once __DIR__ . '/../../Controllers/config.php';

// Établir la connexion à la base de données en utilisant la méthode statique
try {
    $conn = Config::getConnexion();
} catch (Exception $e) {
    die("Erreur de connexion: " . $e->getMessage());
}

// Traitement de l'ajout à la base de données
if (isset($_POST['save_question'])) {
    $id_quiz = isset($_POST['quiz_id']) ? intval($_POST['quiz_id']) : (isset($_GET['quiz_id']) ? intval($_GET['quiz_id']) : null);
    
    if (!$id_quiz) {
        $message = "Erreur: Aucun quiz sélectionné.";
        $message_type = "danger";
    } else {
        $texte_question = $_POST['texte_question'];
        $reponse_correcte = $_POST['reponse_correcte']; 
        $reponse_incorrecte1 = $_POST['reponse_incorrecte1'];
        $reponse_incorrecte2 = $_POST['reponse_incorrecte2'];
        
        $sql = "INSERT INTO questions (id_quiz, texte_question, reponse_correcte, reponse_incorrecte1, reponse_incorrecte2) 
                VALUES (:id_quiz, :texte_question, :reponse_correcte, :reponse_incorrecte1, :reponse_incorrecte2)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_quiz', $id_quiz, PDO::PARAM_INT);
        $stmt->bindParam(':texte_question', $texte_question, PDO::PARAM_STR);
        $stmt->bindParam(':reponse_correcte', $reponse_correcte, PDO::PARAM_STR);
        $stmt->bindParam(':reponse_incorrecte1', $reponse_incorrecte1, PDO::PARAM_STR);
        $stmt->bindParam(':reponse_incorrecte2', $reponse_incorrecte2, PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            $message = "Question ajoutée avec succès!";
            $message_type = "success";
        } else {
            $message = "Erreur: " . $sql . "<br>" . $conn->error;
            $message_type = "danger";
        }
    }
    
    // Récupérer une nouvelle question
    $category = isset($_POST['category']) ? intval($_POST['category']) : (isset($_GET['category']) ? intval($_GET['category']) : 9); // Par défaut: General Knowledge
    header("Location: " . $_SERVER['PHP_SELF'] . "?category=" . $category . "&quiz_id=" . $id_quiz . "&message=" . urlencode($message) . "&type=" . $message_type);
    exit;
} 

// Traitement du rejet de la question
elseif (isset($_POST['reject_question'])) {
    $category = isset($_POST['category']) ? intval($_POST['category']) : (isset($_GET['category']) ? intval($_GET['category']) : 9);
    $quiz_id = isset($_POST['quiz_id']) ? intval($_POST['quiz_id']) : null;
    header("Location: " . $_SERVER['PHP_SELF'] . "?category=" . $category . "&quiz_id=" . $quiz_id . "&message=" . urlencode("Question ignorée. En voici une nouvelle.") . "&type=warning");
    exit;
}


// Récupération des catégories si aucune n'est sélectionnée
if (!isset($_GET['category'])) {
    $url = 'https://opentdb.com/api_category.php';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);
    $categories = $data['trivia_categories'] ?? [];
} else {
    // Vérification de l'ID du quiz
    $quiz_id = isset($_GET['quiz_id']) ? intval($_GET['quiz_id']) : null;
    
    // Si aucun quiz n'est sélectionné, rediriger vers la page de création de quiz
    if (!$quiz_id) {
        header("Location: create_quiz.php?category=" . intval($_GET['category']));
        exit;
    }
    
    // Récupérer les informations du quiz
    try {
        $stmt = $conn->prepare("SELECT * FROM quiz WHERE id_quiz = :id_quiz");
        $stmt->bindParam(':id_quiz', $quiz_id, PDO::PARAM_INT);
        $stmt->execute();
        $quiz = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$quiz) {
            $error_message = "Ce quiz n'existe pas.";
        }
    } catch (PDOException $e) {
        $error_message = "Erreur lors de la récupération du quiz: " . $e->getMessage();
    }
    
    // Récupérer une question basée sur la catégorie
    $category = intval($_GET['category']);
    $url = "https://opentdb.com/api.php?amount=1&category=$category&difficulty=medium&type=multiple";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);

    // Compter les questions existantes pour ce quiz
    try {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM questions WHERE id_quiz = :id_quiz");
        $stmt->bindParam(':id_quiz', $quiz_id, PDO::PARAM_INT);
        $stmt->execute();
        $question_count = $stmt->fetchColumn();
    } catch (PDOException $e) {
        $question_count = 0;
    }

    // Vérifier si des questions ont été trouvées
    if ($data['response_code'] === 0) {
        $question = $data['results'][0];
        
        // Décoder les entités HTML
        $question['question'] = html_entity_decode($question['question'], ENT_QUOTES, 'UTF-8');
        $question['correct_answer'] = html_entity_decode($question['correct_answer'], ENT_QUOTES, 'UTF-8');
        foreach ($question['incorrect_answers'] as $key => $value) {
            $question['incorrect_answers'][$key] = html_entity_decode($value, ENT_QUOTES, 'UTF-8');
        }
        
        // Prendre seulement les 2 premières réponses incorrectes
        $incorrect_answers = array_slice($question['incorrect_answers'], 0, 2);
    } else {
        $error_message = "Aucune question trouvée pour cette catégorie.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Tinder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f9f9f9;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: none;
            max-width: 500px;
            margin: 0 auto;
        }
        .card-header {
            background: linear-gradient(45deg,rgb(60, 109, 243), #ff655b);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            font-weight: bold;
            font-size: 1.5rem;
        }
        .swipe-buttons {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }
        .btn-reject {
            background-color: white;
            color:rgb(40, 79, 197);
            border: 2px solidrgb(117, 194, 239);
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            transition: all 0.3s;
        }
        .btn-accept {
            background-color: white;
            color: #2dce89;
            border: 2px solid #2dce89;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            transition: all 0.3s;
        }
        .btn-reject:hover {
            background-color:rgb(45, 85, 197);
            color: white;
            transform: scale(1.1);
        }
        .btn-accept:hover {
            background-color: #2dce89;
            color: white;
            transform: scale(1.1);
        }
        .answer-option {
            border: 1px solid #ddd;
            padding: 10px 15px;
            margin-bottom: 10px;
            border-radius: 10px;
            background-color: white;
        }
        .correct-answer {
            border-color: #2dce89;
            background-color: #f0fff4;
        }
        .logo {
            font-size: 2.5rem;
            background: -webkit-linear-gradient(45deg, #fe3c72, #ff655b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-align: center;
            margin-bottom: 20px;
        }
        .category-select {
            max-width: 500px;
            margin: 0 auto;
        }
        .quiz-progress {
            margin-bottom: 15px;
            text-align: center;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="logo">
            <i class="fa-solid fa-fire"></i> Quiz Tinder
        </div>
        
        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-<?= htmlspecialchars($_GET['type']) ?> text-center my-3">
                <?= htmlspecialchars($_GET['message']) ?>
            </div>
        <?php endif; ?>
        
        <?php if (!isset($_GET['category'])): ?>
            <!-- Sélection de catégorie -->
            <div class="card category-select">
                <div class="card-header text-center">
                    Choisissez un thème
                </div>
                <div class="card-body">
                    <form method="GET">
                        <div class="mb-3">
                            <select class="form-select" name="category" id="category" required>
                                <option value="" disabled selected>-- Swipez vers un thème --</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-play"></i> Commencer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        <?php elseif (isset($error_message)): ?>
            <!-- Message d'erreur -->
            <div class="alert alert-warning text-center my-5">
                <?= htmlspecialchars($error_message) ?>
                <p class="mt-3">
                    <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-outline-primary">
                        <i class="fa-solid fa-undo"></i> Retour
                    </a>
                </p>
            </div>
        <?php else: ?>
            <!-- Affichage de la question style Tinder -->
            <div class="card">
                <div class="card-header text-center">
                    <?= isset($quiz) ? htmlspecialchars($quiz['titre']) : 'Question' ?>
                </div>
                <div class="card-body">
                    <?php if (isset($question_count)): ?>
                    <div class="quiz-progress">
                        <span class="badge bg-primary"><?= $question_count ?></span> questions déjà dans ce quiz
                    </div>
                    <?php endif; ?>
                    
                    <h5 class="mb-4"><?= htmlspecialchars($question['question']) ?></h5>
                    
                    <div class="answer-option correct-answer">
                        <strong>Réponse correcte:</strong> <?= htmlspecialchars($question['correct_answer']) ?>
                    </div>
                    
                    <?php foreach ($incorrect_answers as $index => $answer): ?>
                        <div class="answer-option">
                            <strong>Réponse incorrecte <?= $index+1 ?>:</strong> <?= htmlspecialchars($answer) ?>
                        </div>
                    <?php endforeach; ?>
                    
                    <div class="swipe-buttons">
                        <form method="POST" action="">
                            <input type="hidden" name="category" value="<?= $category ?>">
                            <input type="hidden" name="quiz_id" value="<?= $quiz_id ?>">
                            <button type="submit" name="reject_question" class="btn-reject">
                                <i class="fa-solid fa-times"></i>
                            </button>
                        </form>
                        
                        <form method="POST" action="">
                            <input type="hidden" name="category" value="<?= $category ?>">
                            <input type="hidden" name="quiz_id" value="<?= $quiz_id ?>">
                            <input type="hidden" name="texte_question" value="<?= htmlspecialchars($question['question']) ?>">
                            <input type="hidden" name="reponse_correcte" value="<?= htmlspecialchars($question['correct_answer']) ?>">
                            <input type="hidden" name="reponse_incorrecte1" value="<?= htmlspecialchars($incorrect_answers[0]) ?>">
                            <input type="hidden" name="reponse_incorrecte2" value="<?= htmlspecialchars($incorrect_answers[1] ?? '') ?>">
                            <button type="submit" name="save_question" class="btn-accept">
                                <i class="fa-solid fa-heart"></i>
                            </button>
                        </form>
                    </div>
                    
                    <div class="mt-4">
                        <a href="listquiz.php?id=<?= $quiz_id ?>" class="btn btn-sm btn-outline-primary w-100">
                            <i class="fa-solid fa-eye"></i> Voir les questions de ce quiz
                        </a>
                    </div>
                </div>
                <div class="card-footer text-center text-muted">
                    <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="fa-solid fa-home"></i> Retour
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
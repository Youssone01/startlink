<?php
session_start();

// Inclusion du header & navbar StartLink
include __DIR__ . '/include/header.php';
include __DIR__ . '/include/navbar.php';

require_once realpath(__DIR__ . '/../../Controllers/config.php');
$db = config::getConnexion();

// Validation de l'ID de quiz
if (empty($_POST['id_quiz']) || !is_numeric($_POST['id_quiz'])) {
    echo '<div class="alert alert-danger text-center my-5">Quiz introuvable ou invalide.</div>';
    include __DIR__ . '/include/footer.php';
    exit;
}
$id_quiz = (int)$_POST['id_quiz'];

// Récupérer les données du quiz
$stmt = $db->prepare('SELECT * FROM questions WHERE id_quiz = :id');
$stmt->bindValue(':id', $id_quiz, PDO::PARAM_INT);
$stmt->execute();

// Récupérer toutes les questions
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$questions) {
    echo '<div class="alert alert-warning text-center my-5">Quiz introuvable ou aucune question disponible.</div>';
    include __DIR__ . '/include/footer.php';
    exit;
}

// Si l'utilisateur a soumis ses réponses
if (isset($_POST['reponses']) && is_array($_POST['reponses'])) {
    $userAnswers = $_POST['reponses'];
    $score = 0;

    foreach ($questions as $index => $question) {
        if (isset($userAnswers[$index]) && $userAnswers[$index] === $question['reponse_correcte']) {
            $score++;
        }
    }

    $total = count($questions);
    $percentage = ($score / $total) * 100;

    if ($percentage >= 80) {
        $niveau = "Avancé";
        $niveauColor = "success";
    } elseif ($percentage >= 60) {
        $niveau = "Moyen";
        $niveauColor = "primary";
    } elseif ($percentage >= 40) {
        $niveau = "Débutant";
        $niveauColor = "info";
    } else {
        $niveau = "Non qualifié";
        $niveauColor = "danger";
    }

    $id_user = $_SESSION['user_id'] ?? null;
    $date_obtention = date('Y-m-d');
    $certificationMessage = '';

    if ($id_user && $percentage >= 50) {
        $stmtCheck = $db->prepare('SELECT * FROM certifications WHERE id_user = :id_user AND id_formation = :id_formation');
        $stmtCheck->execute(['id_user' => $id_user, 'id_formation' => $id_quiz]);
        $certifExist = $stmtCheck->fetch();

        if (!$certifExist) {
            $stmtInsert = $db->prepare('
                INSERT INTO certifications (id_user, id_formation, date_obtention, score_quiz, niveau)
                VALUES (:id_user, :id_formation, :date_obtention, :score_quiz, :niveau)
            ');
            $stmtInsert->execute([
                'id_user' => $id_user,
                'id_formation' => $id_quiz,
                'date_obtention' => $date_obtention,
                'score_quiz' => $score,
                'niveau' => $niveau,
            ]);
            $certificationMessage = '<div class="alert alert-success p-4 rounded shadow text-center"><i class="bi bi-award-fill"></i> Félicitations ! Vous avez obtenu une certification pour le niveau <strong>' . $niveau . '</strong>.</div>';
        } else {
            $certificationMessage = '<div class="alert alert-info p-4 rounded shadow text-center"><i class="bi bi-info-circle-fill"></i> Vous avez déjà une certification pour ce quiz.</div>';
        }
    } elseif ($percentage < 50) {
        $certificationMessage = '<div class="alert alert-warning p-4 rounded shadow text-center"><i class="bi bi-emoji-frown-fill"></i> Désolé, vous n\'avez pas atteint le seuil requis pour une certification.</div>';
    }

    echo '
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header text-center bg-' . $niveauColor . ' text-white">
                        <h3>Résultat du Quiz</h3>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <h4>Votre score :</h4>
                            <div class="display-4 text-' . $niveauColor . '">' . $score . '/' . $total . '</div>
                            <small>(' . number_format($percentage, 2) . '%)</small>
                        </div>
                        <div class="text-center mb-4">
                            <h4>Niveau atteint :</h4>
                            <span class="badge bg-' . $niveauColor . ' fs-5 p-2">' . $niveau . '</span>
                        </div>
                        ' . $certificationMessage . '
                    </div>
                </div>
            </div>
        </div>
    </div>';

    include __DIR__ . '/include/footer.php';
    exit;
}
?>

<!-- Formulaire HTML pour afficher les questions -->
<div class="container py-5">
    <div class="card shadow-sm">
        <div class="card-header text-center">
            <h2>Quiz</h2>
        </div>
        <div class="card-body">
            <form method="POST" action="start.php">
                <input type="hidden" name="id_quiz" value="<?= htmlspecialchars($id_quiz) ?>">
                <?php foreach ($questions as $index => $question): ?>
                    <div class="mb-4">
                        <p><strong>Question <?= $index + 1 ?>:</strong> <?= htmlspecialchars($question['texte_question']) ?></p>
                        <?php if (isset($question['reponse_correcte'], $question['reponse_incorrecte1'], $question['reponse_incorrecte2'])): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="reponses[<?= $index ?>]" id="q<?= $index ?>_1" value="<?= htmlspecialchars($question['reponse_correcte']) ?>" required>
                                <label class="form-check-label" for="q<?= $index ?>_1"><?= htmlspecialchars($question['reponse_correcte']) ?></label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="reponses[<?= $index ?>]" id="q<?= $index ?>_2" value="<?= htmlspecialchars($question['reponse_incorrecte1']) ?>" required>
                                <label class="form-check-label" for="q<?= $index ?>_2"><?= htmlspecialchars($question['reponse_incorrecte1']) ?></label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="reponses[<?= $index ?>]" id="q<?= $index ?>_3" value="<?= htmlspecialchars($question['reponse_incorrecte2']) ?>" required>
                                <label class="form-check-label" for="q<?= $index ?>_3"><?= htmlspecialchars($question['reponse_incorrecte2']) ?></label>
                            </div>
                        <?php else: ?>
                            <p>Pas de réponses disponibles pour cette question.</p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Soumettre</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/include/footer.php'; ?>

<?php
// Inclure le fichier de configuration
require_once __DIR__ . '/../../Controllers/config.php';

// Établir la connexion à la base de données en utilisant la méthode statique
try {
    $conn = Config::getConnexion();
} catch (Exception $e) {
    die("Erreur de connexion: " . $e->getMessage());
}

// Traitement du formulaire de création de quiz
if (isset($_POST['create_quiz'])) {
    $titre = $_POST['titre'];
    $description = $_POST['description'] ?: null;
    $duree = isset($_POST['duree']) ? intval($_POST['duree']) : 10; // Durée par défaut de 10 minutes
    $category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : null;
    $id_formation = isset($_POST['id_formation']) ? intval($_POST['id_formation']) : null; // Ajout de l'id_formation

    try {
        $sql = "INSERT INTO quiz (id_formation, titre, description, duree) 
                VALUES (:id_formation, :titre, :description, :duree)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_formation', $id_formation, PDO::PARAM_INT);
        $stmt->bindParam(':titre', $titre, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':duree', $duree, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            $quiz_id = $conn->lastInsertId();
            // Rediriger vers la page des questions avec l'ID du quiz nouvellement créé
            header("Location: quiz_dyn.php?category={$category_id}&quiz_id={$quiz_id}&message=" . urlencode("Quiz '{$titre}' créé avec succès! Ajoutez maintenant des questions.") . "&type=success");
            exit;
        } else {
            $message = "Erreur lors de la création du quiz.";
            $message_type = "danger";
        }
    } catch (PDOException $e) {
        $message = "Erreur: " . $e->getMessage();
        $message_type = "danger";
    }
}

// Récupération des catégories
$url = 'https://opentdb.com/api_category.php';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
$categories = $data['trivia_categories'] ?? [];

// Récupérer la catégorie sélectionnée si elle existe
$category_id = isset($_GET['category']) ? intval($_GET['category']) : null;
$category_name = '';

if ($category_id) {
    foreach ($categories as $category) {
        if ($category['id'] == $category_id) {
            $category_name = $category['name'];
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Quiz - Quiz Tinder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
   <style>
    body {
        background-color: #f0f8ff; /* Teinte bleu clair */
    }
    .card {
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border: none;
        max-width: 600px;
        margin: 0 auto;
        background: linear-gradient(to right, #b3d9ff, #e6f2ff);
    }
    .card-header {
        background: linear-gradient(to right, #b3d9ff,rgb(121, 155, 192));
        color: white;
        border-radius: 15px 15px 0 0 !important;
        font-weight: bold;
        font-size: 1.5rem;
    }
    .logo {
        font-size: 2.5rem;
        background: -webkit-linear-gradient(45deg, #0077b6, #00b4d8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        text-align: center;
        margin-bottom: 20px;
    }
    .btn-primary {
        background-color: #0077b6;
        border-color: #0077b6;
    }
    .btn-primary:hover {
        background-color: #005f91;
        border-color: #005f91;
    }
    .btn-outline-secondary {
        color: #0077b6;
        border-color: #0077b6;
    }
    .btn-outline-secondary:hover {
        color: white;
        background-color: #0077b6;
        border-color: #0077b6;
    }
    
    /* Style pour les messages d'erreur */
    .error-message {
        color: red; /* Couleur rouge pour les messages d'erreur */
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
</style>


</head>
<body>
    <div class="container py-5">
        <div class="logo">
            <i class="fa-solid fa-fire"></i> Quiz Tinder
        </div>
        
        <?php if (isset($message)): ?>
            <div class="alert alert-<?= $message_type ?> text-center my-3">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
        
        <div class="card">
            <div class="card-header text-center">
                Créer un nouveau Quiz<?= $category_name ? ' - ' . htmlspecialchars($category_name) : '' ?>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <?php if ($category_id): ?>
                        <input type="hidden" name="category_id" value="<?= $category_id ?>">
                        <input type="hidden" name="category_name" value="<?= htmlspecialchars($category_name) ?>">
                    <?php else: ?>
                        <div class="mb-3">
                            <label for="category" class="form-label">Catégorie</label>
                            <select class="form-select" name="category_id" id="category" required>
                                <option value="" disabled selected>-- Choisissez une catégorie --</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="error-message" id="categoryError"></div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <label for="id_formation" class="form-label">ID Formation</label>
                        <input type="number" class="form-control" id="id_formation" name="id_formation" required>
                        <div class="error-message" id="idFormationError"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="titre" class="form-label">Titre du quiz</label>
                        <input type="text" class="form-control" id="titre" name="titre" required 
                               value="<?= isset($category_name) ? ' ' . htmlspecialchars($category_name) : '' ?>">
                        <div class="error-message" id="titreError"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description (optionnel)</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        <div class="error-message" id="descriptionError"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="duree" class="form-label">Durée (en minutes)</label>
                        <input type="number" class="form-control" id="duree" name="duree" min="1" max="120" value="" required>
                        <div class="error-message" id="dureeError"></div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" name="create_quiz" class="btn btn-primary" id="createQuizBtn">
                            <i class="fa-solid fa-plus"></i> Créer le Quiz
                        </button>
                        <a href="listquiz.php" class="btn btn-outline-secondary">
                            <i class="fa-solid fa-arrow-left"></i> Retour
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('createQuizBtn').addEventListener('click', function (event) {
        // Récupérer tous les champs du formulaire
        const idFormation = document.getElementById('id_formation').value.trim();
        const titre = document.getElementById('titre').value.trim();
        const description = document.getElementById('description').value.trim();
        const duree = document.getElementById('duree').value.trim();
        const category = document.getElementById('category') ? document.getElementById('category').value : '';

        let isValid = true;

        // Réinitialiser les messages d'erreur
        document.getElementById('idFormationError').textContent = '';
        document.getElementById('titreError').textContent = '';
        document.getElementById('descriptionError').textContent = '';
        document.getElementById('dureeError').textContent = '';
        document.getElementById('categoryError').textContent = '';

        // Vérifier si les champs sont vides
        if (!idFormation) {
            document.getElementById('idFormationError').textContent = 'Veuillez remplir ce champ.';
            isValid = false;
        }
        if (!titre) {
            document.getElementById('titreError').textContent = 'Veuillez remplir ce champ.';
            isValid = false;
        }
        if (!description) {
            document.getElementById('descriptionError').textContent = 'Veuillez ajouter une description.';
            isValid = false;
        }
        if (!duree) {
            document.getElementById('dureeError').textContent = 'Veuillez remplir ce champ.';
            isValid = false;
        }
        if (document.getElementById('category') && !category) {
            document.getElementById('categoryError').textContent = 'Veuillez choisir une catégorie.';
            isValid = false;
        }

        // Vérifier la durée (ne doit pas dépasser 60 minutes)
        if (duree && (parseInt(duree, 10) > 60 || parseInt(duree, 10) < 1)) {
            document.getElementById('dureeError').textContent = 'La durée doit être comprise entre 1 et 60 minutes.';
            isValid = false;
        }

        // Empêcher l'envoi du formulaire si des erreurs sont présentes
        if (!isValid) {
            event.preventDefault();
        }
    });
</script>

</body>
</html>

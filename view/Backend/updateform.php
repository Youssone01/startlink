<?php  
require_once __DIR__ . '/../../Controllers/config.php';
require_once __DIR__ . '/../../controller/FormationController.php';
require_once __DIR__ . '/../../model/formation.php';

// Utiliser directement le contrôleur pour obtenir la connexion
$controller = new FormationController();

$id = isset($_GET['id']) ? intval($_GET['id']) : null;
$message = '';

if (!$id) {
    die("ID de formation manquant.");
}

// Récupération des données de la formation par son ID
$data = $controller->getFormationById($id);
if (!$data) {
    die("Formation non trouvée.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des valeurs soumises par le formulaire
    $nomFormation = $_POST['nomFormation'];
    $description = $_POST['description'];
    $duree = $_POST['duree'];
    $niveau = $_POST['niveau'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $places_disponibles = $_POST['places_disponibles'];

    // Validation des champs
    if (empty($nomFormation) || empty($description) || empty($duree) || empty($niveau) || empty($date_debut) || empty($date_fin) || empty($places_disponibles)) {
        $message = "❌ Tous les champs sont obligatoires.";
    } elseif (!is_numeric($duree) || $duree <= 0 || $duree > 25) {
        $message = "❌ La durée doit être un nombre valide entre 1 et 25 heures.";
    } elseif (!in_array($niveau, ['débutant', 'intermédiaire', 'avancé'])) {
        $message = "❌ Le niveau doit être l'un des suivants : débutant, intermédiaire, avancé.";
    } elseif (strtotime($date_debut) >= strtotime($date_fin)) {
        $message = "❌ La date de début doit être antérieure à la date de fin.";
    } else {
        // Création d'un objet Formation avec les données du formulaire
        $formation = new Formation($nomFormation, $description, $duree, $niveau, $date_debut, $date_fin, $places_disponibles);
        $formation->setIdFormation($id);

        // Mise à jour de la formation dans la base de données
        if ($controller->updateFormation($formation)) {
            $message = "✅ Formation mise à jour avec succès.";
            $data = $controller->getFormationById($id);  // Rafraîchissement des données après mise à jour
        } else {
            $message = "❌ Erreur lors de la mise à jour.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier une Formation</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 40px 20px;
        }

        .container {
            max-width: 600px;
            background: #fff;
            margin: auto;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #0a1b89; /* Couleur StartLink */
            font-size: 26px;
            margin-bottom: 30px;
        }

        .message {
            text-align: center;
            font-weight: bold;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 16px;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 16px;
            color: #333;
            margin-bottom: 8px;
            margin-top: 20px;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        textarea,
        select {
            width: 100%;
            padding: 14px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box;
            transition: border 0.3s ease, background-color 0.3s ease;
        }

        input:focus,
        textarea:focus,
        select:focus {
            border-color: #0a1b89; /* Couleur StartLink */
            background-color: #f1f8ff;
            outline: none;
        }

        textarea {
            resize: vertical;
        }

        button {
            background-color: #0a1b89; /* Couleur StartLink */
            color: white;
            padding: 14px 20px;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        button:hover {
            background-color: #007bff;
            transform: scale(1.05);
        }

        button:active {
            transform: scale(1);
        }

        a {
            display: block;
            text-align: center;
            margin-top: 30px;
            color: #0a1b89; /* Couleur StartLink */
            font-size: 16px;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Modifier la Formation</h2>

        <?php if ($message): ?>
            <div class="message <?= strpos($message, '✅') !== false ? 'success' : 'error' ?>">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="updateform.php?id=<?= $id ?>">
            <label for="nomFormation">Nom de la formation</label>
            <input type="text" name="nomFormation" id="nomFormation" value="<?= htmlspecialchars($data['nomFormation']) ?>" required>

            <label for="description">Description</label>
            <textarea name="description" id="description" rows="4" required><?= htmlspecialchars($data['description']) ?></textarea>

            <label for="duree">Durée (heures)</label>
            <input type="number" name="duree" id="duree" value="<?= htmlspecialchars($data['duree']) ?>" required>

            <label for="niveau">Niveau</label>
            <select name="niveau" id="niveau" required>
                <option value="débutant" <?= ($data['niveau'] === 'débutant') ? 'selected' : '' ?>>Débutant</option>
                <option value="intermédiaire" <?= ($data['niveau'] === 'intermédiaire') ? 'selected' : '' ?>>Intermédiaire</option>
                <option value="avancé" <?= ($data['niveau'] === 'avancé') ? 'selected' : '' ?>>Avancé</option>
            </select>

            <label for="date_debut">Date de début</label>
            <input type="date" name="date_debut" id="date_debut" value="<?= htmlspecialchars($data['date_debut']) ?>" >

            <label for="date_fin">Date de fin</label>
            <input type="date" name="date_fin" id="date_fin" value="<?= htmlspecialchars($data['date_fin']) ?>" >

            <label for="places_disponibles">Places disponibles</label>
            <input type="number" name="places_disponibles" id="places_disponibles" value="<?= htmlspecialchars($data['places_disponibles']) ?>" required>

            <button type="submit">Enregistrer les modifications</button>
        </form>

        <a href="formation_bo.php">← Retour à la liste des formations</a>
    </div>
    <script>
document.querySelector("form").addEventListener("submit", function(e) {
    const nom = document.getElementById("nomFormation").value.trim();
    const description = document.getElementById("description").value.trim();
    const duree = parseInt(document.getElementById("duree").value);
    const niveau = document.getElementById("niveau").value;
    const dateDebut = document.getElementById("date_debut").value;
    const dateFin = document.getElementById("date_fin").value;
    const places = parseInt(document.getElementById("places_disponibles").value);

    let message = "";

    if (!nom || !description || isNaN(duree) || !niveau || !dateDebut || !dateFin || isNaN(places)) {
        message = "❌ Tous les champs sont obligatoires.";
    } else if (duree <= 0 || duree > 25) {
        message = "❌ La durée doit être entre 1 et 25 heures.";
    } else if (!["débutant", "intermédiaire", "avancé"].includes(niveau)) {
        message = "❌ Le niveau sélectionné est invalide.";
    } else if (new Date(dateDebut) >= new Date(dateFin)) {
        message = "❌ La date de début doit être antérieure à la date de fin.";
    } else if (places <= 0) {
        message = "❌ Le nombre de places doit être supérieur à zéro.";
    }

    if (message) {
        e.preventDefault();
        alert(message);
    }
});
</script>
</body>
</html>  

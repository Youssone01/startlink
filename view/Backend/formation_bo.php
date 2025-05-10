<?php 
require_once __DIR__ . '/../../Controllers/config.php';
require_once __DIR__ . '/../../controller/FormationController.php';
require_once __DIR__ . '/../../model/formation.php';

session_start();
$controller = new FormationController();

$participants = [];
$formationToEdit = null;

// Actions GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Supprimer une formation
    if (isset($_GET['delete_id']) && filter_var($_GET['delete_id'], FILTER_VALIDATE_INT)) {
        try {
            $success = $controller->deleteFormation((int) $_GET['delete_id']);
            $_SESSION[$success ? 'success' : 'error'] = $success ? "Formation supprimée avec succès !" : "Erreur lors de la suppression.";
        } catch (Exception $e) {
            $_SESSION['error'] = "Erreur : " . $e->getMessage();
        }
        header("Location: formation_bo.php");
        exit;
    }

    // Modifier une formation
    if (isset($_GET['edit_id']) && filter_var($_GET['edit_id'], FILTER_VALIDATE_INT)) {
        $formationToEdit = $controller->getFormationById((int) $_GET['edit_id']);
    }

    // Voir les participants
    if (isset($_GET['participants_id']) && filter_var($_GET['participants_id'], FILTER_VALIDATE_INT)) {
        $participants = $controller->getInscriptionsByFormationId((int) $_GET['participants_id']);
    }

    // Supprimer un participant
    if (isset($_GET['delete_participant_id']) && filter_var($_GET['delete_participant_id'], FILTER_VALIDATE_INT)) {
        try {
            $success = $controller->deleteInscriptionFormation((int) $_GET['delete_participant_id']);
            $_SESSION[$success ? 'success' : 'error'] = $success ? "Participant supprimé avec succès !" : "Erreur lors de la suppression du participant.";
        } catch (Exception $e) {
            $_SESSION['error'] = "Erreur : " . $e->getMessage();
        }
        header("Location: formation_bo.php?participants_id=" . $_GET['participants_id']);
        exit;
    }
}

// Actions POST (ajout ou mise à jour)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $idFormation = isset($_POST['id_formation']) ? (int) $_POST['id_formation'] : null;
        $nom = trim($_POST['nom_formation']);
        $desc = trim($_POST['description']);
        $duree = (int) $_POST['duree'];
        $niveau = $_POST['niveau'];
        $dateDebut = $_POST['date_debut'];
        $dateFin = $_POST['date_fin'];
        $places = (int) $_POST['places_disponibles'];

        if ($duree <= 0) throw new Exception("Durée invalide.");
        if (strtotime($dateFin) < strtotime($dateDebut)) throw new Exception("Date de fin invalide.");
        if ($places < 0) throw new Exception("Places disponibles invalides.");

        $formation = new Formation($nom, $desc, $duree, $niveau, $dateDebut, $dateFin, $places, $idFormation);
        $success = $idFormation ? $controller->updateFormation($formation) : $controller->addFormation($formation);
        $_SESSION[$success ? 'success' : 'error'] = $success ? "Formation " . ($idFormation ? "mise à jour" : "ajoutée") . " avec succès !" : "Erreur d’enregistrement.";

    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }
    header("Location: formation_bo.php");
    exit;
}

// Récupération des données
$formations = $controller->getAllFormations();
$success_message = $_SESSION['success'] ?? null;
$error_message = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);
?>

<!DOCTYPE html>

<html lang="fr">
 
<head>
    
    <meta charset="UTF-8">
    
    <title>Backoffice - Formations</title>
    <style>
        body { font-family: sans-serif; background: #eef2f7; padding: 20px; }
        .container { max-width: 1000px; margin: auto; background: #fff; padding: 25px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1, h2 { text-align: center; }
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 6px; font-weight: bold; }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-danger { background: #f8d7da; color: #721c24; }

        form .form-group { margin-bottom: 15px; }
        label { font-weight: bold; }
        input, select, textarea { width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc; }
        .btn { display: inline-block; padding: 10px 16px; border: none; border-radius: 6px; cursor: pointer; margin-top: 10px; text-decoration: none; }
        .btn-primary { background: #3498db; color: #fff; }
        .btn-warning { background: #f39c12; color: #fff; }
        .btn-danger { background: #e74c3c; color: #fff; }
        .btn-info { background: #17a2b8; color: #fff; }

        table { width: 100%; border-collapse: collapse; margin-top: 30px; }
        th, td { padding: 10px; border: 1px solid #ddd; }
        th { background: #3498db; color: #fff; }
        tr:nth-child(even) { background: #f2f2f2; }
    </style>
</head>

<body>

<div class="container">
    
    <h1>Gestion des Formations</h1>

    <?php if ($success_message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success_message) ?></div>
    <?php endif; ?>
    <?php if ($error_message): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>

    <form method="POST">
        <?php if ($formationToEdit): ?>
            <input type="hidden" name="id_formation" value="<?= $formationToEdit['idFormation'] ?>">
        <?php endif; ?>

        <div class="form-group">
            <label for="nom_formation">Nom de la formation</label>
            <input type="text" id="nom_formation" name="nom_formation" value="<?= $formationToEdit['nomFormation'] ?? '' ?>" >
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description"><?= $formationToEdit['description'] ?? '' ?></textarea>
        </div>

        <div class="form-group">
            <label for="duree">Durée (en heures)</label>
            <input type="number" id="duree" name="duree" value="<?= $formationToEdit['duree'] ?? '' ?>" min="1" >
        </div>

        <div class="form-group">
            <label for="niveau">Niveau</label>
            <select id="niveau" name="niveau" >
                <option value="Débutant" <?= isset($formationToEdit) && $formationToEdit['niveau'] == 'Débutant' ? 'selected' : '' ?>>Débutant</option>
                <option value="Intermédiaire" <?= isset($formationToEdit) && $formationToEdit['niveau'] == 'Intermédiaire' ? 'selected' : '' ?>>Intermédiaire</option>
                <option value="Avancé" <?= isset($formationToEdit) && $formationToEdit['niveau'] == 'Avancé' ? 'selected' : '' ?>>Avancé</option>
            </select>
        </div>

        <div class="form-group">
            <label for="date_debut">Date de début</label>
            <input type="date" id="date_debut" name="date_debut" value="<?= $formationToEdit['date_debut'] ?? '' ?>">
        </div>

        <div class="form-group">
            <label for="date_fin">Date de fin</label>
            <input type="date" id="date_fin" name="date_fin" value="<?= $formationToEdit['date_fin'] ?? '' ?>">
        </div>

        <div class="form-group">
            <label for="places_disponibles">Places disponibles</label>
            <input type="number" id="places_disponibles" name="places_disponibles" value="<?= $formationToEdit['places_disponibles'] ?? '' ?>" min="0">
        </div>

        <button type="submit" class="btn btn-primary"><?= $formationToEdit ? 'Mettre à jour' : 'Ajouter' ?> la formation</button>
    </form>

    <h2>Liste des Formations</h2>
    <table>
        <thead>
        <tr>
            <th>Nom</th>
            <th>Description</th>
            <th>Durée</th>
            <th>Niveau</th>
            <th>Date Début</th>
            <th>Date Fin</th>
            <th>Places disponibles</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($formations as $formation): ?>
            <tr>
                <td><?= htmlspecialchars($formation['nomFormation']) ?></td>
                <td><?= htmlspecialchars($formation['description']) ?></td>
                <td><?= htmlspecialchars($formation['duree']) ?>h</td>
                <td><?= htmlspecialchars($formation['niveau']) ?></td>
                <td><?= htmlspecialchars($formation['date_debut']) ?></td>
                <td><?= htmlspecialchars($formation['date_fin']) ?></td>
                <td><?= htmlspecialchars($formation['places_disponibles']) ?></td>

                <td>
                    <a href="?edit_id=<?= $formation['idFormation'] ?>" class="btn btn-warning">Modifier</a>
                    <a href="?delete_id=<?= $formation['idFormation'] ?>" class="btn btn-danger" onclick="return confirm('Supprimer cette formation ?')">Supprimer</a>
                    <a href="?participants_id=<?= $formation['idFormation'] ?>" class="btn btn-info">Participants</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <?php if (!empty($participants)): ?>
        <h2>Liste des Participants</h2>
        <button class="btn btn-primary" onclick="window.location.href='addpart.php?idFormation=<?= $_GET['participants_id'] ?>'">Ajouter un Participant</button>
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Date d'inscription</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($participants as $p): ?>
                <tr>
                    <td><?= $p['idInscription'] ?></td>
                    <td><?= htmlspecialchars($p['nomParticipant']) ?></td>
                    <td><?= htmlspecialchars($p['emailParticipant']) ?></td>
                    <td><?= htmlspecialchars($p['dateInscription']) ?></td>
                    <td>
                        <a href="updatepart.php?participants_id=<?= $_GET['participants_id'] ?>&edit_id=<?= $p['idInscription'] ?>" class="btn btn-warning">Modifier</a>
                        <a href="?delete_participant_id=<?= $p['idInscription'] ?>&participants_id=<?= $_GET['participants_id'] ?>" class="btn btn-danger" onclick="return confirm('Supprimer ce participant ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<script>
document.querySelector("form").addEventListener("submit", function(e) {
    let nom = document.getElementById("nomFormation").value.trim();
    let desc = document.getElementById("description").value.trim();
    let niveau = document.getElementById("niveau").value;
    let dateDebut = document.getElementById("date_debut").value;
    let dateFin = document.getElementById("date_fin").value;
    let places = document.getElementById("places_disponibles").value;

    let erreurs = [];

    if (nom === "") erreurs.push("Le nom de la formation est requis.");
    if (desc === "") erreurs.push("La description est requise.");
    if (duree === "" || isNaN(duree) || duree <= 0 || duree > 25) erreurs.push("La durée doit être un nombre entre 1 et 25 heures.");
    if (!["débutant", "intermédiaire", "avancé"].includes(niveau)) erreurs.push("Le niveau est invalide.");
    if (!dateDebut || !dateFin || new Date(dateDebut) >= new Date(dateFin)) erreurs.push("Les dates sont invalides ou incohérentes.");
    if (places === "" || isNaN(places) || places <= 0) erreurs.push("Le nombre de places doit être supérieur à 0.");

    if (erreurs.length > 0) {
        e.preventDefault();
        alert("Erreurs de saisie :\n\n" + erreurs.join("\n"));
    }
});
</script>
</body>
</html>

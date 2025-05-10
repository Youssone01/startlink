<?php   
// Configuration et inclusion
require_once dirname(__FILE__) . '/../../config/config.php';
require_once dirname(__FILE__) . '/../../controllers/FormationController.php';

// Initialisation de la connexion et du contrôleur
$pdo = Database::getInstance()->getConnection();
$controller = new FormationController($pdo);

// Recherche
$search = isset($_GET['search']) ? htmlspecialchars(trim($_GET['search'])) : '';

// Pagination
$limit = 8;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Suppression via POST pour plus de sécurité
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete']) && is_numeric($_POST['delete'])) {
    $formationId = (int)$_POST['delete'];
    $deleteSuccess = $controller->deleteFormation($formationId);
    $message = $deleteSuccess ? "Formation supprimée avec succès." : "Erreur lors de la suppression de la formation.";
    header("Location: listeform.php?" . ($deleteSuccess ? "success=" : "error=") . urlencode($message));
    exit;
}

// Récupération des formations avec pagination ou recherche
if ($search !== '') {
    $formations = $controller->searchFormations($search, $start, $limit); // Passer aussi $start et $limit
} else {
    $formations = $controller->getPaginatedFormations($start, $limit); // Assurez-vous que cette méthode prend aussi le décalage et la limite
}

// Statistiques
$stats = $controller->getFormationStats();
$totalFormations = $controller->getTotalFormations();
$totalPages = ($totalFormations > 0) ? ceil($totalFormations / $limit) : 1; // Assurez-vous que totalPages est toujours défini
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Formations</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Styles généraux */
        body { margin: 0; background: #f4f9fd; font-family: 'Segoe UI', sans-serif; }
        .sidebar { position: fixed; height: 100vh; width: 230px; background: #2c3e50; color: white; padding: 20px; }
        .sidebar h2 { text-align: center; margin-bottom: 40px; }
        .sidebar a { display: block; color: white; text-decoration: none; margin: 10px 0; padding: 10px; border-radius: 8px; transition: background 0.3s; }
        .sidebar a:hover { background: #3498db; }
        .main { margin-left: 250px; padding: 40px; }
        .stats { display: flex; gap: 20px; margin-bottom: 30px; }
        .card { background: white; padding: 20px; flex: 1; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); text-align: center; transition: transform 0.2s; }
        .card:hover { transform: translateY(-5px); }
        .card i { font-size: 2rem; margin-bottom: 10px; color: #3498db; }
        .search-bar { text-align: center; margin-bottom: 20px; }
        .search-bar input { width: 300px; padding: 10px; border-radius: 8px; border: 1px solid #ccc; }
        .search-bar button { padding: 10px 20px; background: #3498db; border: none; border-radius: 8px; color: white; cursor: pointer; }
        table { width: 100%; background: white; border-collapse: collapse; border-radius: 12px; overflow: hidden; }
        th, td { padding: 14px; text-align: center; border-bottom: 1px solid #eee; }
        th { background-color: #3498db; color: white; }
        .pagination { text-align: center; margin-top: 20px; }
        .pagination a { margin: 0 5px; text-decoration: none; padding: 10px 15px; border: 1px solid #ddd; border-radius: 5px; color: #3498db; }
        .pagination a:hover { background-color: #3498db; color: white; }

        /* Style des boutons */
        button, a.button {
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        /* Bouton de suppression */
        button[type="submit"] {
            background-color: #e74c3c; /* Rouge */
            color: white;
            border: none;
        }

        button[type="submit"]:hover {
            background-color: #c0392b; /* Rouge foncé */
            transform: scale(1.05);
        }

        /* Bouton de modification */
        a.button.edit {
            background-color: #3498db; /* Bleu */
            color: white;
        }

        a.button.edit:hover {
            background-color: #2980b9; /* Bleu foncé */
            transform: scale(1.05);
        }

        /* Bouton de liste des participants */
        a.button.participants {
            background-color: #2ecc71; /* Vert */
            color: white;
        }

        a.button.participants:hover {
            background-color: #27ae60; /* Vert foncé */
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Dashboard</h2>
        <a href="addform.php">Ajouter une Formation</a>
        <a href="listeform.php">Liste des Formations</a>
    </div>
    <div class="main">
        <div class="search-bar">
            <form method="GET">
                <input type="text" name="search" placeholder="Rechercher une formation..." value="<?= htmlspecialchars($search) ?>">
                <button type="submit">Rechercher</button>
            </form>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <p style="color: green;"><?= htmlspecialchars($_GET['success']) ?></p>
        <?php elseif (isset($_GET['error'])): ?>
            <p style="color: red;"><?= htmlspecialchars($_GET['error']) ?></p>
        <?php endif; ?>

        <!-- Statistiques des formations -->
        <div class="stats">
            <div class="card">
                <i class="fa fa-graduation-cap"></i>
                <h3><?= htmlspecialchars($stats['total_formations'] ?? 0) ?></h3>
                <p>Total des formations</p>
            </div>
            <div class="card">
                <i class="fa fa-user"></i>
                <h3><?= htmlspecialchars($stats['formations_debutant'] ?? 0) ?></h3>
                <p>Formations Débutant</p>
            </div>
            <div class="card">
                <i class="fa fa-cogs"></i>
                <h3><?= htmlspecialchars($stats['formations_intermediaire'] ?? 0) ?></h3>
                <p>Formations Intermédiaires</p>
            </div>
            <div class="card">
                <i class="fa fa-trophy"></i>
                <h3><?= htmlspecialchars($stats['formations_avance'] ?? 0) ?></h3>
                <p>Formations Avancées</p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Nom de la Formation</th>
                    <th>Description</th>
                    <th>Durée</th>
                    <th>Niveau</th>
                    <th>Date de Début</th>
                    <th>Date de Fin</th>
                    <th>Places Disponibles</th> <!-- Nouvelle colonne pour les places disponibles -->
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($formations)): ?>
                    <?php foreach ($formations as $formation): ?>
                        <tr>
                            <td><?= htmlspecialchars($formation['nomFormation']) ?></td>
                            <td><?= htmlspecialchars($formation['description']) ?></td>
                            <td><?= htmlspecialchars($formation['duree']) ?> heures</td>
                            <td><?= htmlspecialchars($formation['niveau']) ?></td>
                            <td>
                                <?php
                                if (!empty($formation['date_debut'])) {
                                    $date_debut = new DateTime($formation['date_debut']);
                                    echo $date_debut->format('d-m-Y');
                                } else {
                                    echo 'Non définie';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if (!empty($formation['date_fin'])) {
                                    $date_fin = new DateTime($formation['date_fin']);
                                    echo $date_fin->format('d-m-Y');
                                } else {
                                    echo 'Non définie';
                                }
                                ?>
                            </td>
                            <td><?= htmlspecialchars($formation['places_disponibles']) ?></td> <!-- Affichage des places disponibles -->
                            <td>
                                <form method="POST" style="display:inline;">
                                    <button type="submit" name="delete" value="<?= htmlspecialchars($formation['idFormation']) ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette formation ?')">Supprimer</button>
                                </form>
                                <a href="updateform.php?id=<?= htmlspecialchars($formation['idFormation']) ?>" class="button edit">Modifier</a> |
                                <a href="inscription_bo.php?id=<?= htmlspecialchars($formation['idFormation']) ?>" class="button participants">Liste des Participants</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">Aucune formation trouvée.</td> <!-- Changement ici, 8 colonnes au lieu de 7 -->
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="listeform.php?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>">Précédent</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="listeform.php?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
            <?php endfor; ?>
            <?php if ($page < $totalPages): ?>
                <a href="listeform.php?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>">Suivant</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php
session_start();

// Vérifie si l'utilisateur est admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: login.php?error=unauthorized');
    exit();
}

// Connexion à la base
try {
    $pdo = new PDO("mysql:host=localhost;dbname=startlink", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupération des offres
$stmt = $pdo->query("SELECT * FROM offres ORDER BY date_publication DESC");
$offres = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Offres - Backoffice</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f0f6ff;
            padding: 30px;
        }
        h2 {
            text-align: center;
            color: #0a1b89;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            background: white;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 14px;
            border: 1px solid #dfe6f1;
            text-align: center;
        }
        th {
            background: #0a1b89;
            color: white;
        }
        tr:hover {
            background-color: #f1f7ff;
        }
        a.btn {
            padding: 6px 12px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
        }
        .edit {
            background-color: #28a745;
            color: white;
        }
        .delete {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>
<body>

<h2>📋 Liste des Offres d'emploi Publiées</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Titre</th>
        <th>Entreprise</th>
        <th>Lieu</th>
        <th>Contrat</th>
        <th>Salaire (€)</th>
        <th>Date</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($offres as $offre): ?>
        <tr>
            <td><?= $offre['id'] ?></td>
            <td><?= htmlspecialchars($offre['titre']) ?></td>
            <td><?= htmlspecialchars($offre['entreprise']) ?></td>
            <td><?= htmlspecialchars($offre['lieu']) ?></td>
            <td><?= htmlspecialchars($offre['type_contrat']) ?></td>
            <td><?= htmlspecialchars($offre['salaire']) ?></td>
            <td><?= htmlspecialchars($offre['date_publication']) ?></td>
            <td>
                <a href="modifieroffre.php?id=<?= $offre['id'] ?>" class="btn edit">✏️ Modifier</a>
                <a href="supprimeroffre.php?id=<?= $offre['id'] ?>" class="btn delete" onclick="return confirm('Supprimer cette offre ?')">🗑️ Supprimer</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>

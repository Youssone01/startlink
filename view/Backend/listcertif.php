<?php
// Inclure le fichier de configuration et le contrôleur CertifC
require_once __DIR__ . '/../../Controllers/config.php';
require_once __DIR__ . '/../../controller/certifC.php';
include('include/head.php');
include('include/sidebar.php');

$certifC = new CertifC();
$certifications = $certifC->afficherCertifications(); // Récupérer toutes les certifications
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Certifications</title>
    <style>
        /* Importation de la police Poppins */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

        /* Style a */
        body {
            font-family: 'Poppins', sans-serif;
            background-color:rgb(214, 233, 255);
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Conteneur principal */
        .content-wrapper {
            margin-left: 250px; /* Largeur de la sidebar */
            width: calc(100% - 250px); /* S'assurer que le contenu prend tout l'espace restant */
            padding: 20px;
            box-sizing: border-box;
        }

        h2 {
            text-align: center;
            font-size: 2rem;
            color:rgb(17, 58, 111);
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        /* Tableau des certifications */
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 15px;
            text-align: left;
        }

        th {
            background-color:rgb(19, 45, 89);
            color: #fff;
        }

        td {
            background-color: #ecf0f1;
            color: #333;
        }

        tr:nth-child(even) td {
            background-color: #f9f9f9;
        }

        tr:hover td {
            background-color: #dfe6f3;
        }

        .actions a {
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            margin-right: 5px;
            color: white;
        }

        .actions .modify {
            background-color:rgb(52, 80, 121);
        }

        .actions .delete {
            background-color:rgb(208, 30, 30);
        }

        .actions a:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="content-wrapper">
        <h2>Liste des Certifications</h2>

        <!-- Tableau des Certifications -->
        <table>
            <thead>
                <tr>
                    <th>ID Certification</th>
                    <th>ID Utilisateur</th>
                    <th>ID Formation</th>
                    <th>Date d'Obtention</th>
                    <th>Score Quiz</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($certifications as $certif): ?>
                <tr>
                    <td><?= htmlspecialchars($certif['id_certification']); ?></td>
                    <td><?= htmlspecialchars($certif['id_user']); ?></td>
                    <td><?= htmlspecialchars($certif['id_formation']); ?></td>
                    <td><?= htmlspecialchars($certif['date_obtention']); ?></td>
                    <td><?= htmlspecialchars($certif['score_quiz']); ?></td>
                    <td class="actions">
                        <a class="modify" href="updatecertif.php?id=<?= htmlspecialchars($certif['id_certification']); ?>">Modifier</a>
                        <a class="delete" href="deletecertif.php?id=<?= htmlspecialchars($certif['id_certification']); ?>"
                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette certification ?');">
                           Supprimer
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

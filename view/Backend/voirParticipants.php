<?php
session_start();

require_once __DIR__ . '/../../Controllers/config.php';
$db = config::getConnexion();

try {
    $query = $db->query("
        SELECT 
            p.idParti,
            p.utilisateur_id,
            p.evenement_id,
            p.notificationEnvoyee,
            e.titre,
            e.dateEven as date_evenement,
            e.description,
            e.organisateur
        FROM participationevenement p
        JOIN evenement e ON p.evenement_id = e.idEvenement
        ORDER BY e.dateEven DESC
    ");
    $participations = $query->fetchAll();
} catch (PDOException $e) {
    die("Erreur de requête : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Participants - Dashboard Admin</title>
    <?php include('include/head.php') ?>
    <style>
        .participant-card {
            background-color: #f0fafc;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .badge-notification {
            background-color: #28a745;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 0.8rem;
        }
        .badge-notification.false {
            background-color: #dc3545;
        }
        .btn-action {
            padding: 5px 10px;
            font-size: 14px;
            margin: 2px;
        }
        .btn-modify {
            background-color: #13a7cd;
            color: white;
        }
        .btn-modify:hover {
            background-color: #0d8bb7;
        }
        .btn-delete {
            background-color: #e74c3c;
            color: white;
        }
        .btn-delete:hover {
            background-color: #c0392b;
        }
        .organisateur-cell {
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .add-participant-btn {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <?php include('include/sidebar.php') ?>

        <div class="main-panel">
            <div class="main-header">
                <div class="main-header-logo">
                    <div class="logo-header" data-background-color="dark">
                        <a href="dashboard.php" class="logo">
                            <img src="assets/img/kaiadmin/logo_dark.png" alt="navbar brand" class="navbar-brand" height="20">
                        </a>
                    </div>
                </div>
                <?php include('include/navhead.php') ?>
            </div>

            <div class="container-xxl py-5">
                <div class="container">
                    <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                        <h6 class="section-title bg-white text-center text-primary px-3">Gestion</h6>
                        <h1 class="mb-5">Liste des Participants aux Événements</h1>
                    </div>
                    
                    <div class="row justify-content-center">
                        <div class="col-lg-12">
                            <div class="participant-card wow fadeInUp" data-wow-delay="0.3s">
                                <?php if (empty($participations)): ?>
                                    <div class="alert alert-info text-center">
                                        Aucune participation enregistrée
                                    </div>
                                <?php else: ?>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Événement</th>
                                                    <th>Organisateur</th>
                                                    <th>Date</th>
                                                    <th>ID Utilisateur</th>
                                                    <th>Notification</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($participations as $part): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($part['idParti']) ?></td>
                                                    <td>
                                                        <strong><?= htmlspecialchars($part['titre']) ?></strong>
                                                        <small class="d-block text-muted"><?= htmlspecialchars($part['description']) ?></small>
                                                    </td>
                                                    <td class="organisateur-cell" title="<?= htmlspecialchars($part['organisateur'] ?? 'Non spécifié') ?>">
                                                        <?= htmlspecialchars($part['organisateur'] ?? 'Non spécifié') ?>
                                                    </td>
                                                    <td><?= date('d/m/Y', strtotime($part['date_evenement'])) ?></td>
                                                    <td><?= htmlspecialchars($part['utilisateur_id']) ?></td>
                                                    <td>
                                                        <span class="badge-notification <?= $part['notificationEnvoyee'] ? '' : 'false' ?>">
                                                            <?= $part['notificationEnvoyee'] ? 'Envoyée' : 'Non envoyée' ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="modifierParticipant.php?id=<?= $part['idParti'] ?>" 
                                                           class="btn btn-action btn-modify"
                                                           title="Modifier">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="supprimerParticipant.php?id=<?= $part['idParti'] ?>&idEvenement=<?= $part['evenement_id'] ?>" 
                                                           class="btn btn-action btn-delete"
                                                           title="Supprimer"
                                                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette participation ?')">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </a>
                                                        <a href="ajouterParticipant.php?id=<?= $part['idParti'] ?>&idEvenement=<?= $part['evenement_id'] ?>" 
                                                        class="add-participant-btn">
                                                         <i class="fas fa-plus-circle"></i> Ajouter une participation
                                                         </a>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>

                                <!-- Ajout du bouton "Ajouter un participant" -->
                                

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php include('include/footer.php') ?>
        </div>
    </div>

    <?php include('include/js.php') ?>
</body>
</html>
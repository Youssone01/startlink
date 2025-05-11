<?php
session_start();



include('include/head.php');
?>

<body>
    <div class="wrapper">
        <?php include('include/sidebar.php') ?>

        <div class="main-panel">
            <div class="main-header">
                <?php include('include/navhead.php') ?>
            </div>

            <div class="container">
                <div class="page-inner">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Liste des Offres d'Emploi</h4>
                                <a href="addoffre.php">
                                    <button class="btn btn-secondary d-flex align-items-center ms-auto">
                                        <span class="btn-label">
                                            <i class="fa fa-plus"></i>
                                        </span>
                                        Ajouter une offre
                                    </button>
                                </a>
                                <a href="statoffres.php">
                                    <button class="btn btn-info d-flex align-items-center">
                                        <span class="btn-label">
                                            <i class="fa fa-bar-chart"></i>
                                        </span>
                                        Voir les statistiques
                                    </button>
                                </a>
                                <a href="export_offres.php">
                                    <button class="btn btn-success d-flex align-items-center">
                                        <span class="btn-label">
                                            <i class="fa fa-download"></i>
                                        </span>
                                        Exporter en CSV
                                    </button>
                                </a>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="offres-table" class="display table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Titre</th>
                                                <th>Type</th>
                                                <th>Salaire</th>
                                                <th>Lieu</th>
                                                <th>Date Publication</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            require_once __DIR__ . '/../../Controllers/config.php';
                                            $db = config::getConnexion();
                                            
                                            // Jointure avec la table users pour récupérer le nom de l'entreprise
                                            $query = $db->query("
                                                SELECT o.*, u.fullname as entreprise 
                                                FROM offres o
                                                LEFT JOIN users u ON o.user_id = u.id
                                                ORDER BY o.date_publication DESC
                                            ");
                                            $offres = $query->fetchAll();
                                            
                                            foreach ($offres as $offre) {
                                                echo "<tr>";
                                                echo "<td>{$offre['id']}</td>";
                                                echo "<td>{$offre['titre']}</td>";
                                                echo "<td>{$offre['type_contrat']}</td>";
                                                echo "<td>".number_format($offre['salaire'], 2)."</td>";
                                                echo "<td>{$offre['lieu']}</td>";
                                                echo "<td>".date('d/m/Y H:i', strtotime($offre['date_publication']))."</td>";
                                                echo "<td>
                                                    <a href='edit_offre.php?id={$offre['id']}' class='btn btn-sm' style='background-color:#056ed1; color:white;'>
                                                        <i class='fa fa-edit'></i> Modifier
                                                    </a>
                                                    <a href='delete_offre.php?id={$offre['id']}' class='btn btn-danger btn-sm me-2' onclick=\"return confirm('Voulez-vous vraiment supprimer cette offre ?');\">
                                                        Supprimer
                                                    </a>
                                                </td>";
                                                echo "</tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php include('include/footer.php') ?>
        </div>
    </div>
    <?php include('include/js.php') ?>

    <script>
        $(document).ready(function() {
            $('#offres-table').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/French.json"
                },
                "columnDefs": [
                    { "width": "5%", "targets": 0 }, // ID
                    { "width": "25%", "targets": 1 }, // Titre
                    { "width": "15%", "targets": 2 }, // Type
                    { "width": "10%", "targets": 3 }, // Salaire
                    { "width": "15%", "targets": 4 }, // Lieu
                    { "width": "15%", "targets": 5 }, // Date
                    { "width": "15%", "targets": 6 }  // Actions
                ]
            });
        });
    </script>
</body>
</html>
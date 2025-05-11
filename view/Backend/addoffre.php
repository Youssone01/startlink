<?php
session_start();
require_once __DIR__ . '/../../controller/OffreController.php';
require_once __DIR__ . '/../../model/Offre.php';

// Vérification si l'utilisateur est connecté (admin ou investisseur)


$offreController = new OffreController();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validation des champs requis
        $requiredFields = ['titre', 'description', 'type_contrat', 'lieu'];
        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                throw new InvalidArgumentException("Le champ $field est requis.");
            }
        }

        // Création de l'objet Offre
        $offre = new Offre(
            htmlspecialchars($_POST['titre']),
            htmlspecialchars($_POST['description']),
            htmlspecialchars($_POST['competences'] ?? ''),
            htmlspecialchars($_POST['type_contrat']),
            htmlspecialchars($_POST['salaire'] ?? ''),
            htmlspecialchars($_POST['lieu'])
        );

        // Associer à l'investisseur s'il est connecté
        

        // Insertion en base de données
        if ($offreController->createOffre($offre)) {
            $_SESSION['success'] = "Offre ajoutée avec succès.";
            header('Location: gestion_offres.php');
            exit;
        } else {
            $error = "Erreur lors de l'ajout de l'offre.";
        }
    } catch (InvalidArgumentException $e) {
        $error = $e->getMessage();
    } catch (Exception $e) {
        error_log("Erreur ajout offre: " . $e->getMessage());
        $error = "Une erreur est survenue.";
    }
}
?>

<?php include('include/head.php') ?>

<body>
    <div class="wrapper">
        <?php include('include/sidebar.php') ?>

        <div class="main-panel">
            <div class="container">
                <div class="page-inner">
                    <div class="page-header">
                        <h4 class="page-title">Ajouter une offre</h4>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">Formulaire d'ajout</div>
                                </div>
                                <div class="card-body">

                                    <?php if (!empty($error)): ?>
                                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                                    <?php endif; ?>

                                    <form method="POST" id="addOffreForm">
                                        <div class="form-group">
                                            <label for="titre">Titre de l'offre *</label>
                                            <input type="text" class="form-control" id="titre" name="titre" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="description">Description *</label>
                                            <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label for="competences">Compétences requises</label>
                                            <textarea class="form-control" id="competences" name="competences" rows="3"></textarea>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="type_contrat">Type de contrat *</label>
                                                    <select class="form-control" id="type_contrat" name="type_contrat" required>
                                                        <option value="">-- Sélectionnez --</option>
                                                        <option value="CDI">CDI</option>
                                                        <option value="CDD">CDD</option>
                                                        <option value="Freelance">Freelance</option>
                                                        <option value="Stage">Stage</option>
                                                        <option value="Alternance">Alternance</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="salaire">Salaire</label>
                                                    <input type="text" class="form-control" id="salaire" name="salaire">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="lieu">Lieu *</label>
                                            <input type="text" class="form-control" id="lieu" name="lieu" required>
                                        </div>

                                        <div class="form-group mt-4">
                                            <button type="submit" class="btn btn-primary mr-2">
                                                <i class="fas fa-plus-circle mr-1"></i> Ajouter
                                            </button>
                                            <a href="gestion_offres.php" class="btn btn-secondary">
                                                <i class="fas fa-times mr-1"></i> Annuler
                                            </a>
                                        </div>
                                    </form>

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
        // Validation JS simple
        document.getElementById('addOffreForm').addEventListener('submit', function(e) {
            const champs = ['titre', 'description', 'type_contrat', 'lieu'];
            let valide = true;
            champs.forEach(id => {
                const champ = document.getElementById(id);
                if (!champ.value.trim()) {
                    alert(`Le champ ${champ.labels[0].innerText} est requis`);
                    champ.focus();
                    valide = false;
                    return;
                }
            });
            if (!valide) e.preventDefault();
        });
    </script>
</body>
</html>

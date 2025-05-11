<?php
session_start();
require_once __DIR__ . '/../../controller/OffreController.php';
require_once __DIR__ . '/../../model/Offre.php';



$offreController = new OffreController();
$error = '';
$success = '';

try {
    // Récupération de l'offre à modifier
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
        $offreId = (int)$_GET['id'];
        $offre = $offreController->getOffreById($offreId);
        
        if (!$offre) {
            throw new Exception("Offre introuvable");
        }
    }

    // Traitement de la mise à jour
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
            throw new Exception("ID d'offre invalide");
        }

        // Validation des données
        $requiredFields = ['titre', 'description', 'type_contrat', 'lieu'];
        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                throw new InvalidArgumentException("Le champ $field est requis");
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
        $offre->setId((int)$_POST['id']);

        // Mise à jour
        if ($offreController->updateOffre($offre)) {
            $_SESSION['success'] = "Offre mise à jour avec succès";
            header('Location: gestion_offres.php');
            exit;
        } else {
            throw new Exception("Échec de la mise à jour");
        }
    }
} catch (InvalidArgumentException $e) {
    $error = $e->getMessage();
} catch (Exception $e) {
    error_log("Erreur modification offre: " . $e->getMessage());
    $error = "Une erreur est survenue: " . $e->getMessage();
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
                        <h4 class="page-title">Modifier une offre</h4>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">Formulaire de modification</div>
                                </div>
                                <div class="card-body">

                                    <?php if (!empty($error)): ?>
                                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                                    <?php endif; ?>

                                    <form method="POST" id="editOffreForm">
                                        <input type="hidden" name="id" value="<?= $offre['id'] ?? '' ?>">

                                        <div class="form-group">
                                            <label for="titre">Titre de l'offre *</label>
                                            <input type="text" class="form-control" id="titre" name="titre" 
                                                   value="<?= htmlspecialchars($offre['titre'] ?? '') ?>" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="description">Description *</label>
                                            <textarea class="form-control" id="description" name="description" 
                                                      rows="5" required><?= htmlspecialchars($offre['description'] ?? '') ?></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label for="competences">Compétences requises</label>
                                            <textarea class="form-control" id="competences" name="competences" 
                                                      rows="3"><?= htmlspecialchars($offre['competences_requises'] ?? '') ?></textarea>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="type_contrat">Type de contrat *</label>
                                                    <select class="form-control" id="type_contrat" name="type_contrat" required>
                                                        <option value="">-- Sélectionnez --</option>
                                                        <option value="CDI" <?= (isset($offre['type_contrat']) && $offre['type_contrat'] === 'CDI' ? 'selected' : '') ?>>CDI</option>
                                                        <option value="CDD" <?= (isset($offre['type_contrat']) && $offre['type_contrat'] === 'CDD' ? 'selected' : '') ?>>CDD</option>
                                                        <option value="Freelance" <?= (isset($offre['type_contrat']) && $offre['type_contrat'] === 'Freelance' ? 'selected' : '') ?>>Freelance</option>
                                                        <option value="Stage" <?= (isset($offre['type_contrat']) && $offre['type_contrat'] === 'Stage' ? 'selected' : '') ?>>Stage</option>
                                                        <option value="Alternance" <?= (isset($offre['type_contrat']) && $offre['type_contrat'] === 'Alternance' ? 'selected' : '') ?>>Alternance</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="salaire">Salaire</label>
                                                    <input type="text" class="form-control" id="salaire" name="salaire" 
                                                           value="<?= htmlspecialchars($offre['salaire'] ?? '') ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="lieu">Lieu *</label>
                                            <input type="text" class="form-control" id="lieu" name="lieu" 
                                                   value="<?= htmlspecialchars($offre['lieu'] ?? '') ?>" required>
                                        </div>

                                        <div class="form-group mt-4">
                                            <button type="submit" class="btn btn-primary mr-2">
                                                <i class="fas fa-save mr-1"></i> Enregistrer
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
        // Validation côté client
        document.getElementById('editOffreForm').addEventListener('submit', function(e) {
            const requiredFields = ['titre', 'description', 'type_contrat', 'lieu'];
            let isValid = true;

            requiredFields.forEach(field => {
                const element = document.getElementById(field);
                if (!element.value.trim()) {
                    alert(`Le champ ${element.labels[0].textContent} est requis`);
                    element.focus();
                    isValid = false;
                    return false;
                }
            });

            if (!isValid) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>
<?php
require_once __DIR__ . '/../../controller/EventC.php';
require_once __DIR__ . '/../../model/Event.php';

$eventC = new EventC();
$errors = [];

// 1. Récupérer l'événement
if (!isset($_GET['id'])) {
    header("Location: afficherEvent.php");
    exit();
}

$event = $eventC->getEventById($_GET['id']);
$currentImage = $eventC->getEventImage($_GET['id']);

// 2. Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $dateEvent = $_POST['dateEvent'] ?? '';
    $description = trim($_POST['description'] ?? '');
    $organisateur = trim($_POST['organisateur'] ?? '');
    $prix = isset($_POST['prix']) && $_POST['prix'] !== '' ? (float)$_POST['prix'] : null;
    $imageFile = $_FILES['image'] ?? null;
    $removeImage = isset($_POST['removeImage']);

    // Validation
    if (empty($titre)) {
        $errors['titre'] = "Le titre est obligatoire";
    } elseif (strlen($titre) < 3) {
        $errors['titre'] = "Le titre doit contenir au moins 3 caractères";
    }

    if (empty($dateEvent)) {
        $errors['dateEvent'] = "La date est obligatoire";
    } elseif (strtotime($dateEvent) < strtotime('today')) {
        $errors['dateEvent'] = "La date doit être dans le futur";
    }

    if (empty($description)) {
        $errors['description'] = "La description est obligatoire";
    } elseif (strlen($description) < 10) {
        $errors['description'] = "La description doit contenir au moins 10 caractères";
    }

    if (empty($organisateur)) {
        $errors['organisateur'] = "L'organisateur est obligatoire";
    } elseif (strlen($organisateur) < 3) {
        $errors['organisateur'] = "Le nom de l'organisateur doit contenir au moins 3 caractères";
    }

    // Validation du prix
    if ($prix !== null) {
        if ($prix < 0) {
            $errors['prix'] = "Le prix ne peut pas être négatif";
        } elseif ($prix > 10000) {
            $errors['prix'] = "Le prix ne peut pas dépasser 10 000 €";
        }
    }

    // Validation de l'image
    if ($imageFile && $imageFile['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($imageFile['error'] !== UPLOAD_ERR_OK) {
            $errors['image'] = "Erreur lors de l'upload de l'image";
        } else {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $imageFile['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mime, $allowedTypes)) {
                $errors['image'] = "Seuls les formats JPEG, PNG et GIF sont autorisés";
            } elseif ($imageFile['size'] > 2 * 1024 * 1024) {
                $errors['image'] = "L'image ne doit pas dépasser 2MB";
            }
        }
    }

    if (empty($errors)) {
        $event->setTitre($titre);
        $event->setDateEvent($dateEvent);
        $event->setDescription($description);
        $event->setOrganisateur($organisateur);
        $event->setPrix($prix);
        
        // Gestion de l'image
$imageToUse = 'keep'; // Par défaut, on garde l'image existante

if ($removeImage) {
    $imageToUse = null; // Supprimer l'image
} elseif ($imageFile && $imageFile['error'] === UPLOAD_ERR_OK) {
    $imageToUse = $imageFile; // Nouvelle image
}

try {
    $eventC->updateEvent($event, $imageToUse);
    header("Location: afficherEvent.php");
    exit();
} catch (Exception $e) {
    $errors['general'] = "Erreur lors de la modification: " . $e->getMessage();
}
    }
}
?>

<?php include('include/header.php') ?>

<body>
    <?php include('include/spinner.php') ?>
    <?php include('include/navbar.php') ?>

    <style>
        .form-container {
            background-color: #f0fafc;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .form-label {
            font-weight: 600;
            color: #2c3e50;
        }
        .form-control {
            border: 1px solid #ced4da;
            border-radius: 6px;
            padding: 10px 15px;
        }
        .form-control:focus {
            border-color: #13a7cd;
            box-shadow: 0 0 0 0.2rem rgba(19, 167, 205, 0.25);
        }
        .error-message {
            color: #e74c3c;
            font-size: 0.9em;
            margin-top: 5px;
        }
        .btn-startlink {
            background-color: #13a7cd;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            color: white;
        }
        .btn-startlink:hover {
            background-color: #0d8bb7;
        }
        .image-preview {
            max-width: 200px;
            max-height: 150px;
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
        }
        .image-preview-container {
            margin-bottom: 15px;
        }
        .price-container {
            position: relative;
        }
        .price-container::after {
            content: "€";
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }
    </style>

    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h6 class="section-title bg-white text-center text-primary px-3">Événements</h6>
                <h1 class="mb-5">Modifier l'Événement</h1>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="form-container wow fadeInUp" data-wow-delay="0.3s">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-4">
                                <label for="titre" class="form-label">Titre</label>
                                <input type="text" class="form-control" id="titre" name="titre" 
                                       value="<?= htmlspecialchars($event->getTitre()) ?>">
                                <?php if (isset($errors['titre'])): ?>
                                    <div class="error-message"><?= $errors['titre'] ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-4">
                                <label for="dateEvent" class="form-label">Date</label>
                                <input type="date" class="form-control" id="dateEvent" name="dateEvent" 
                                       value="<?= htmlspecialchars($event->getDateEvent()) ?>">
                                <?php if (isset($errors['dateEvent'])): ?>
                                    <div class="error-message"><?= $errors['dateEvent'] ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-4">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" 
                                          rows="5"><?= htmlspecialchars($event->getDescription()) ?></textarea>
                                <?php if (isset($errors['description'])): ?>
                                    <div class="error-message"><?= $errors['description'] ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-4">
                                <label for="organisateur" class="form-label">Organisateur</label>
                                <input type="text" class="form-control" id="organisateur" name="organisateur" 
                                       value="<?= htmlspecialchars($event->getOrganisateur()) ?>">
                                <?php if (isset($errors['organisateur'])): ?>
                                    <div class="error-message"><?= $errors['organisateur'] ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-4">
                                <label for="prix" class="form-label">Prix (laisser vide pour gratuit)</label>
                                <div class="price-container">
                                    <input type="number" class="form-control" id="prix" name="prix" 
                                           step="0.01" min="0" max="10000"
                                           value="<?= $event->getPrix() !== null ? htmlspecialchars($event->getPrix()) : '' ?>">
                                </div>
                                <?php if (isset($errors['prix'])): ?>
                                    <div class="error-message"><?= $errors['prix'] ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Image actuelle</label>
                                <div class="image-preview-container">
                                    <?php if ($currentImage): ?>
                                        <img src="data:image/jpeg;base64,<?= base64_encode($currentImage) ?>" 
                                             class="image-preview" id="currentImagePreview">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" id="removeImage" name="removeImage">
                                            <label class="form-check-label" for="removeImage">
                                                Supprimer l'image actuelle
                                            </label>
                                        </div>
                                    <?php else: ?>
                                        <p>Aucune image actuellement</p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="image" class="form-label">Nouvelle image (laisser vide pour conserver l'actuelle)</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                <?php if (isset($errors['image'])): ?>
                                    <div class="error-message"><?= $errors['image'] ?></div>
                                <?php endif; ?>
                                <img id="newImagePreview" src="#" alt="Aperçu de la nouvelle image" class="image-preview mt-2" style="display: none;">
                            </div>

                            <?php if (isset($errors['general'])): ?>
                                <div class="alert alert-danger"><?= $errors['general'] ?></div>
                            <?php endif; ?>

                            <div class="text-center">
                                <button type="submit" class="btn btn-startlink me-3">
                                    <i class="fas fa-save me-2"></i>Enregistrer
                                </button>
                                <a href="afficherEvent.php" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>Annuler
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('include/footer.php') ?>
    <?php include('include/js.php') ?>

    <script>
        // Aperçu de la nouvelle image avant upload
        document.getElementById('image').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('newImagePreview');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
            }
        });

        // Validation du prix côté client
        
    </script>
</body>
</html>
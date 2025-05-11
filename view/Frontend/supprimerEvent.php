<?php
require_once __DIR__ . '/../../controller/EventC.php';
require_once __DIR__ . '/../../model/Event.php';

$eventC = new EventC();
$errors = [];

// Vérification CSRF (à implémenter selon votre système)
$tokenValide = true; // À remplacer par votre vérification réelle

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id']) && $tokenValide) {
    try {
        $idEvent = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        
        if (!$idEvent) {
            throw new Exception("ID d'événement invalide");
        }

        // Vérification supplémentaire
        $event = $eventC->getEventById($idEvent);
        if (!$event) {
            throw new Exception("Événement introuvable");
        }

        // Affichage de la page de confirmation
        ?>
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Confirmation de suppression - StartLink</title>
            <?php include('include/header.php') ?>
            
            <style>
                .confirmation-container {
                    background-color: #f0fafc;
                    border-radius: 8px;
                    padding: 30px;
                    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                    max-width: 600px;
                    margin: 0 auto;
                }
                .btn-confirm {
                    background-color: #e74c3c;
                    border: none;
                    padding: 10px 20px;
                    border-radius: 6px;
                    color: white;
                }
                .btn-confirm:hover {
                    background-color: #c0392b;
                }
                .event-details {
                    background-color: #fff;
                    border-radius: 6px;
                    padding: 15px;
                    margin: 20px 0;
                    border-left: 4px solid #13a7cd;
                }
                .event-detail-item {
                    margin-bottom: 8px;
                }
            </style>
        </head>
        <body>
            <?php include('include/spinner.php') ?>
            <?php include('include/navbar.php') ?>

            <div class="container-xxl py-5">
                <div class="container">
                    <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                        <h6 class="section-title bg-white text-center text-primary px-3">Événements</h6>
                        <h1 class="mb-5">Supprimer un Événement</h1>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="confirmation-container wow fadeInUp" data-wow-delay="0.3s">
                                <div class="text-center">
                                    <div class="mb-4 text-danger">
                                        <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                                        <h4>Confirmer la suppression</h4>
                                    </div>
                                    <p class="mb-4">Êtes-vous sûr de vouloir supprimer définitivement cet événement ?</p>
                                    
                                    <div class="event-details text-start">
                                        <div class="event-detail-item">
                                            <strong>Titre:</strong> <?= htmlspecialchars($event->getTitre()) ?>
                                        </div>
                                        <div class="event-detail-item">
                                            <strong>Date:</strong> <?= date('d/m/Y', strtotime($event->getDateEvent())) ?>
                                        </div>
                                        <div class="event-detail-item">
                                            <strong>Organisateur:</strong> <?= htmlspecialchars($event->getOrganisateur()) ?>
                                        </div>
                                        <div class="event-detail-item">
                                            <strong>Description:</strong> <?= htmlspecialchars(substr($event->getDescription(), 0, 100)) ?>...
                                        </div>
                                    </div>

                                    <form method="post" action="supprimerEvent.php" class="mt-4">
                                        <input type="hidden" name="id" value="<?= $idEvent ?>">
                                        <input type="hidden" name="confirm" value="1">
                                        <!-- Ajouter un token CSRF ici -->
                                        
                                        <button type="submit" class="btn btn-confirm me-3">
                                            <i class="fas fa-trash-alt me-2"></i>Confirmer la suppression
                                        </button>
                                        <a href="afficherEvent.php" class="btn btn-secondary">
                                            <i class="fas fa-times me-2"></i>Annuler
                                        </a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php include('include/footer.php') ?>
            <?php include('include/js.php') ?>
        </body>
        </html>
        <?php
        exit();

    } catch (Exception $e) {
        $errors['general'] = $e->getMessage();
        header("Location: afficherEvent.php?error=" . urlencode($e->getMessage()));
        exit();
    }
}

// Traitement de la confirmation POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm']) && $tokenValide) {
    try {
        $idEvent = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        
        if (!$idEvent) {
            throw new Exception("ID d'événement invalide");
        }

        // Suppression de l'événement
        $success = $eventC->deleteEvent($idEvent);
        
        if (!$success) {
            throw new Exception("Échec de la suppression de l'événement");
        }
        
        // Redirection avec message de succès
        header("Location: afficherEvent.php?success=1");
        exit();

    } catch (Exception $e) {
        header("Location: afficherEvent.php?error=" . urlencode($e->getMessage()));
        exit();
    }
}

// Redirection par défaut si accès incorrect
header("Location: afficherEvent.php");
exit();
?>
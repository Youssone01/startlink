<?php
session_start();
require_once __DIR__ . '/../../controller/EventC.php';
require_once __DIR__ . '/../../model/Event.php';

// Fonction d'envoi d'email
function sendEventEmail($organisateur, $prix, $titre, $dateEvent) {
    require 'C:/xampp/htdocs/startlink/startlink/PHPMailer/src/PHPMailer.php';
    require 'C:/xampp/htdocs/startlink/startlink/PHPMailer/src/SMTP.php';
    require 'C:/xampp/htdocs/startlink/startlink/PHPMailer/src/Exception.php';
    
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'amouryBendhieb@gmail.com';
        $mail->Password = 'yaoa wwif xqpq gyed';
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('amouryBendhieb@gmail.com', 'Équipe Événements');
        $mail->addAddress('Omar.bendhieb@esprit.tn', 'Administrateur');

        $mail->isHTML(true);
        $mail->Subject = 'Nouvel événement: ' . htmlspecialchars($titre);
        $mail->Body = '
            <h1>Nouvel evenement cree</h1>
            <p><strong>Titre:</strong> ' . htmlspecialchars($titre) . '</p>
            <p><strong>Date:</strong> ' . htmlspecialchars($dateEvent) . '</p>
            <p><strong>Organisateur:</strong> ' . htmlspecialchars($organisateur) . '</p>
            <p><strong>Prix:</strong> ' . ($prix !== null ? htmlspecialchars($prix) . ' €' : 'Gratuit') . '</p>
        ';
        $mail->AltBody = "Nouvel événement: $titre\nDate: $dateEvent\nOrganisateur: $organisateur\nPrix: " . ($prix !== null ? "$prix €" : "Gratuit");

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Erreur d'envoi d'email: " . $e->getMessage());
        return false;
    }
}

// Fonction d'envoi de SMS via Twilio
function sendEventSMS($to, $titre, $dateEvent, $organisateur, $prix) {
    $accountSid = 'AC35e5220b2cf616a0c9fdfc8ceb883b52'; // Votre Account SID
    $authToken = '8a97ddd722e6682ea4c50f641f626db1'; // Votre Auth Token
    $twilioNumber = '+19109710205'; // Votre numéro Twilio

    $url = "https://api.twilio.com/2010-04-01/Accounts/$accountSid/Messages.json";

    // Préparer le corps du message SMS
    $messageBody = "Nouvel événement: $titre\nDate: $dateEvent\nOrganisateur: $organisateur\nPrix: " . ($prix !== null ? "$prix €" : "Gratuit");

    $data = [
        'From' => $twilioNumber,
        'To' => $to,
        'Body' => $messageBody,
    ];

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_USERPWD, "$accountSid:$authToken");
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    if ($httpCode == 201) {
        return true;
    } else {
        $error = json_decode($response, true);
        error_log("Erreur d'envoi de SMS: " . ($error['message'] ?? 'Erreur inconnue'));
        return false;
    }
}

$eventC = new EventC();
$errors = [];

$titre = $dateEvent = $description = $organisateur = "";
$prix = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titre = trim($_POST["titre"] ?? '');
    $dateEvent = $_POST["dateEvent"] ?? '';
    $description = trim($_POST["description"] ?? '');
    $organisateur = trim($_POST["organisateur"] ?? '');
    $prix = isset($_POST["prix"]) && $_POST["prix"] !== '' ? (float)$_POST["prix"] : null;
    $imageFile = $_FILES['image'] ?? null;

    if (empty($titre)) {
        $errors['titre'] = "Le champ Titre est obligatoire.";
    } elseif (strlen($titre) < 3) {
        $errors['titre'] = "Le titre doit contenir au moins 3 caractères.";
    }

    if (empty($dateEvent)) {
        $errors['dateEvent'] = "La date de l'événement est obligatoire.";
    } elseif (strtotime($dateEvent) < strtotime(date('Y-m-d'))) {
        $errors['dateEvent'] = "La date doit être dans le futur.";
    }

    if (empty($description)) {
        $errors['description'] = "La description est obligatoire.";
    } elseif (strlen($description) < 10) {
        $errors['description'] = "La description doit contenir au moins 10 caractères.";
    }

    if (empty($organisateur)) {
        $errors['organisateur'] = "L'organisateur est obligatoire.";
    } elseif (strlen($organisateur) < 3) {
        $errors['organisateur'] = "Le nom de l'organisateur doit contenir au moins 3 caractères.";
    }

    if (isset($_POST['prix']) && $_POST['prix'] !== '') {
        if (!is_numeric($_POST['prix'])) {
            $errors['prix'] = "Le prix doit être un nombre valide.";
        } else {
            $prix = (float)$_POST['prix'];
            if ($prix < 0) {
                $errors['prix'] = "Le prix ne peut pas être négatif.";
            } elseif ($prix > 10000) {
                $errors['prix'] = "Le prix ne peut pas dépasser 10 000 €.";
            }
        }
    }

    if ($imageFile && $imageFile['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($imageFile['error'] !== UPLOAD_ERR_OK) {
            $errors['image'] = "Erreur lors de l'upload de l'image.";
        } else {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $imageFile['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mime, $allowedTypes)) {
                $errors['image'] = "Seuls les formats JPEG, PNG et GIF sont autorisés.";
            } elseif ($imageFile['size'] > 2 * 1024 * 1024) {
                $errors['image'] = "L'image ne doit pas dépasser 2MB.";
            }
        }
    }

    if (empty($errors)) {
        $event = new Event(null, $titre, $dateEvent, $description, $organisateur, $prix);
        
        try {
            $eventC->addEvent($event, $imageFile);
            
            // Envoi de l'email
            $emailSent = sendEventEmail($organisateur, $prix, $titre, $dateEvent);
            
            // Envoi du SMS
            $smsSent = sendEventSMS('+21699118219', $titre, $dateEvent, $organisateur, $prix); // Numéro fixe pour cet exemple
            
            if ($emailSent && $smsSent) {
                $_SESSION['success_message'] = "Événement ajouté avec succès, notification email et SMS envoyés !";
            } elseif ($emailSent) {
                $_SESSION['warning_message'] = "Événement ajouté et email envoyé, mais le SMS a échoué.";
            } elseif ($smsSent) {
                $_SESSION['warning_message'] = "Événement ajouté et SMS envoyé, mais l'email a échoué.";
            } else {
                $_SESSION['warning_message'] = "Événement ajouté, mais les notifications email et SMS ont échoué.";
            }
            
            header("Location: afficherEvent.php");
            exit();
        } catch (Exception $e) {
            $errors['general'] = "Erreur lors de l'ajout de l'événement: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Événement</title>
    <?php include('include/header.php') ?>
    <style>
        .form-container {
            background-color: #f0fafc;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
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
            display: none;
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
        .is-invalid {
            border-color: #dc3545;
        }
        .invalid-feedback {
            color: #dc3545;
            font-size: 0.875em;
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
                <h1 class="mb-5">Ajouter un Nouvel Événement</h1>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="form-container wow fadeInUp" data-wow-delay="0.3s">
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="mb-4">
                                <label for="titre" class="form-label">Titre de l'événement</label>
                                <input type="text" class="form-control <?= isset($errors['titre']) ? 'is-invalid' : '' ?>" 
                                       id="titre" name="titre" value="<?= htmlspecialchars($titre) ?>">
                                <?php if (isset($errors['titre'])): ?>
                                    <div class="invalid-feedback"><?= $errors['titre'] ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-4">
                                <label for="dateEvent" class="form-label">Date de l'événement</label>
                                <input type="date" class="form-control <?= isset($errors['dateEvent']) ? 'is-invalid' : '' ?>" 
                                       id="dateEvent" name="dateEvent" value="<?= htmlspecialchars($dateEvent) ?>">
                                <?php if (isset($errors['dateEvent'])): ?>
                                    <div class="invalid-feedback"><?= $errors['dateEvent'] ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-4">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control <?= isset($errors['description']) ? 'is-invalid' : '' ?>" 
                                          id="description" name="description" rows="5"><?= htmlspecialchars($description) ?></textarea>
                                <?php if (isset($errors['description'])): ?>
                                    <div class="invalid-feedback"><?= $errors['description'] ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-4">
                                <label for="organisateur" class="form-label">Organisateur</label>
                                <input type="text" class="form-control <?= isset($errors['organisateur']) ? 'is-invalid' : '' ?>" 
                                       id="organisateur" name="organisateur" value="<?= htmlspecialchars($organisateur) ?>">
                                <?php if (isset($errors['organisateur'])): ?>
                                    <div class="invalid-feedback"><?= $errors['organisateur'] ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-4">
                                <label for="prix" class="form-label">Prix (laisser vide pour gratuit)</label>
                                <div class="price-container">
                                    <input type="number" step="0.01" class="form-control <?= isset($errors['prix']) ? 'is-invalid' : '' ?>" 
                                           id="prix" name="prix" value="<?= $prix !== null ? htmlspecialchars($prix) : '' ?>">
                                </div>
                                <?php if (isset($errors['prix'])): ?>
                                    <div class="invalid-feedback"><?= $errors['prix'] ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-4">
                                <label for="image" class="form-label">Image de l'événement</label>
                                <input type="file" class="form-control <?= isset($errors['image']) ? 'is-invalid' : '' ?>" 
                                       id="image" name="image" accept="image/*">
                                <?php if (isset($errors['image'])): ?>
                                    <div class="invalid-feedback"><?= $errors['image'] ?></div>
                                <?php endif; ?>
                                <img id="imagePreview" src="#" alt="Aperçu de l'image" class="image-preview mt-2">
                            </div>

                            <?php if (isset($errors['general'])): ?>
                                <div class="alert alert-danger"><?= $errors['general'] ?></div>
                            <?php endif; ?>

                            <div class="text-center">
                                <button type="submit" class="btn btn-startlink me-3">
                                    <i class="fas fa-save me-2"></i>Enregistrer
                                </button>
                                <a href="afficherEvent.php" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Annuler
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
        document.getElementById('image').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('imagePreview');
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        });

        document.getElementById('dateEvent').addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (selectedDate < today) {
                this.setCustomValidity('La date doit être dans le futur');
            } else {
                this.setCustomValidity('');
            }
        });
    </script>
</body>
</html>
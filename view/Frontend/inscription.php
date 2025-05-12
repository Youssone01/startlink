<?php     
// Connexion à la base de données
$host = 'localhost';
$db   = 'gestion'; 
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Initialisation
$error = '';
$success = '';

// Récupérer l'ID de la formation depuis l'URL
if (!isset($_GET['idFormation'])) {
    die('Aucune formation sélectionnée.');
}

$idFormation = (int) $_GET['idFormation'];

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomParticipant = htmlspecialchars(trim($_POST['nomParticipant']));
    $emailParticipant = htmlspecialchars(trim($_POST['emailParticipant']));
    $emailConfirm = htmlspecialchars(trim($_POST['emailConfirm']));

    // Validation
    if (empty($nomParticipant) || empty($emailParticipant) || empty($emailConfirm)) {
        $error = "Veuillez remplir tous les champs obligatoires.";
    } elseif ($emailParticipant !== $emailConfirm) {
        $error = "Les adresses email ne correspondent pas.";
    } elseif (!filter_var($emailParticipant, FILTER_VALIDATE_EMAIL)) {
        $error = "L'adresse email est invalide.";
    }

    if (empty($error)) {
        // Recharger les données de la formation (places à jour)
        $stmt = $pdo->prepare("SELECT * FROM formations WHERE idFormation = ?");
        $stmt->execute([$idFormation]);
        $formation = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$formation) {
            $error = "Formation introuvable.";
        } elseif ($formation['places_disponibles'] <= 0) {
            $error = "Aucune place disponible pour cette formation.";
        } else {
            // Vérifier si l'email est déjà inscrit
            $emailCheck = $pdo->prepare("SELECT COUNT(*) FROM inscriptionformation WHERE emailParticipant = ? AND idFormation = ?");
            $emailCheck->execute([$emailParticipant, $idFormation]);
            if ($emailCheck->fetchColumn() > 0) {
                $error = "Vous êtes déjà inscrit à cette formation.";
            } else {
                // Inscription
                $insert = $pdo->prepare("INSERT INTO inscriptionformation (nomParticipant, emailParticipant, idFormation, dateInscription) VALUES (?, ?, ?, NOW())");
                if ($insert->execute([$nomParticipant, $emailParticipant, $idFormation])) {
                    // Mise à jour des places
                    $update = $pdo->prepare("UPDATE formations SET places_disponibles = places_disponibles - 1 WHERE idFormation = ?");
                    if ($update->execute([$idFormation])) {
                        $success = "✅ Inscription réussie !";
                    } else {
                        $error = "Erreur lors de la mise à jour du nombre de places.";
                    }
                } else {
                    $error = "Erreur lors de l'enregistrement de l'inscription.";
                }
            }
        }
    }
} else {
    // Récupération initiale de la formation
    $stmt = $pdo->prepare("SELECT * FROM formations WHERE idFormation = ?");
    $stmt->execute([$idFormation]);
    $formation = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$formation) {
        die("Formation introuvable.");
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription à la Formation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #e3f2fd, #bbdefb);
            font-family: 'Segoe UI', sans-serif;
        }

        .container {
            max-width: 850px;
            margin-top: 40px;
        }

        .card {
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(to right, #2196f3, #0d47a1);
            color: white;
            padding: 35px;
            text-align: center;
            font-size: 1.6rem;
            font-weight: 600;
        }

        .list-group-item {
            background-color: #f7fbff;
            border: none;
            border-left: 5px solid #2196f3;
            margin-bottom: 10px;
        }

        .form-label {
            font-weight: 600;
            color: #0d47a1;
        }

        .form-control {
            border-radius: 12px;
            border: 2px solid #cce0ff;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #0d47a1;
            box-shadow: 0 0 8px rgba(13, 71, 161, 0.3);
        }

        .btn-primary {
            background-color: #2196f3;
            border: none;
            padding: 12px;
            font-weight: 600;
            border-radius: 10px;
        }

        .btn-primary:hover {
            background-color: #0d47a1;
        }

        .btn-outline-secondary {
            border: 2px solid #2196f3;
            color: #2196f3;
            border-radius: 30px;
            padding: 10px 24px;
        }

        .btn-outline-secondary:hover {
            background-color: #2196f3;
            color: white;
        }

        .alert {
            border-radius: 10px;
        }

        .text-center a {
            color: #0d47a1;
            text-decoration: none;
            font-weight: 500;
        }

        .text-center a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="card-header">
            Inscription à : <?= htmlspecialchars($formation['nomFormation']) ?>
        </div>
        <div class="card-body">
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php elseif ($success): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>

            <h5 class="mb-3">Détails de la formation</h5>
            <ul class="list-group mb-4">
                <li class="list-group-item"><strong>Description :</strong> <?= nl2br(htmlspecialchars($formation['description'])) ?></li>
                <li class="list-group-item"><strong>Durée :</strong> <?= htmlspecialchars($formation['duree']) ?> heures</li>
                <li class="list-group-item"><strong>Niveau :</strong> <?= ucfirst(htmlspecialchars($formation['niveau'])) ?></li>
                <li class="list-group-item"><strong>Date de début :</strong> <?= !empty($formation['date_debut']) ? date('d/m/Y', strtotime($formation['date_debut'])) : 'Non définie' ?></li>
                <li class="list-group-item"><strong>Date de fin :</strong> <?= !empty($formation['date_fin']) ? date('d/m/Y', strtotime($formation['date_fin'])) : 'Non définie' ?></li>
                <li class="list-group-item"><strong>Places disponibles :</strong> <?= htmlspecialchars($formation['places_disponibles']) ?></li>
            </ul>

            <form method="POST">
                <div class="mb-3">
                    <label for="nomParticipant" class="form-label">Nom complet *</label>
                    <input type="text" class="form-control" id="nomParticipant" name="nomParticipant" required>
                </div>
                <div class="mb-3">
                    <label for="emailParticipant" class="form-label">Adresse email *</label>
                    <input type="email" class="form-control" id="emailParticipant" name="emailParticipant" required>
                </div>
                <div class="mb-3">
                    <label for="emailConfirm" class="form-label">Confirmer l'email *</label>
                    <input type="email" class="form-control" id="emailConfirm" name="emailConfirm" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Confirmer l'inscription</button>
            </form>
        </div>
    </div>

    <div class="text-center mt-4">
        <a href="formations.php" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour au catalogue
        </a>
    </div>
</div>
</body>



<script>
    // Fonction pour valider le formulaire avant l'envoi
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form');
        const nomParticipant = document.getElementById('nomParticipant');
        const emailParticipant = document.getElementById('emailParticipant');
        const emailConfirm = document.getElementById('emailConfirm');
        const errorDiv = document.createElement('div');
        errorDiv.classList.add('alert', 'alert-danger', 'd-none');
        form.insertBefore(errorDiv, form.firstChild);

        form.addEventListener('submit', function (event) {
            let errors = [];

            // Validation du nom
            if (nomParticipant.value.trim() === '') {
                errors.push("Le nom complet est requis.");
            } else if (nomParticipant.value.trim().length < 3) {
                errors.push("Le nom complet doit contenir au moins 3 caractères.");
            }

            // Validation de l'email
            if (emailParticipant.value.trim() === '') {
                errors.push("L'adresse email est requise.");
            } else if (!validateEmail(emailParticipant.value.trim())) {
                errors.push("L'adresse email est invalide.");
            }

            // Validation de la confirmation de l'email
            if (emailConfirm.value.trim() === '') {
                errors.push("La confirmation de l'email est requise.");
            } else if (emailParticipant.value.trim() !== emailConfirm.value.trim()) {
                errors.push("Les adresses email ne correspondent pas.");
            }

            if (errors.length > 0) {
                event.preventDefault();
                errorDiv.classList.remove('d-none');
                errorDiv.innerHTML = errors.join('<br>');
            }
        });

        // Fonction de validation d'email
        function validateEmail(email) {
            const regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            return regex.test(email);
        }

    });
</script>
</html>
</body>
</html>

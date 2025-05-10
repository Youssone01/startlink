<?php 
session_start();
require_once '../../Controllers/config.php';
$db = config::getConnexion();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];
$query = "SELECT fullname, email, adresse, date_n, role FROM users WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $userId);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$message = isset($_GET['updated']) ? "✅ Profil mis à jour avec succès." : "";

$profileImage = "uploads/" . ($_SESSION['photo'] ?? "defaut.jpg");
if (!file_exists($profileImage)) {
    $profileImage = "uploads/defaut.jpg";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: #f4f8fc;
            font-family: 'Segoe UI', sans-serif;
            padding-top: 100px;
        }
        .profile-container {
            background: white;
            max-width: 600px;
            margin: 100px auto 0 auto;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h2 {
            color: #0a1b89;
            font-weight: bold;
            margin-bottom: 30px;
        }
        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(10, 27, 137, 0.25);
        }
        .form-label {
            font-weight: 500;
            color: #0a1b89;
        }
        .profile-img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #0a1b89;
            margin-bottom: 20px;
        }
        .btn-primary {
            background-color: rgb(9, 27, 119);
            border: none;
        }
        .btn-primary:hover {
            background-color: #061061;
        }
        .alert {
            text-align: center;
            margin-bottom: 20px;
        }
        .btn-back {
            margin-top: 15px;
            background-color: #1b90c2;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
            width: 100%;
            text-align: center;
        }
        .btn-back:hover {
            background-color: #15bdd4;
        }
        .text-danger {
            font-size: 0.9em;
            text-align: left;
        }
    </style>
</head>
<body>
<?php include('include/header.php'); ?>
<?php include('include/navbar.php'); ?>

<div class="profile-container">
    <h2><i class="fa-solid fa-user"></i> Mon Profil</h2>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= $message ?></div>
    <?php endif; ?>

    <form id="profileForm" action="updateprofile.php" method="POST" enctype="multipart/form-data" novalidate>
        <img src="<?= htmlspecialchars($profileImage) ?>" alt="Photo de profil" class="profile-img">

        <div class="mb-3">
            <label for="photo" class="form-label">Changer la photo de profil</label>
            <input type="file" class="form-control" name="photo" id="photo" accept="image/*">
        </div>

        <div class="mb-3 text-start">
            <label class="form-label">Adresse</label>
            <input type="text" class="form-control" name="adresse" id="adresse"
                   value="<?= htmlspecialchars($user['adresse']) ?>">
            <div id="adresseError" class="text-danger"></div>
        </div>

        <div class="mb-3 text-start">
            <label class="form-label">Date de naissance</label>
            <input type="date" class="form-control" name="date_n" id="date_n"
                   value="<?= htmlspecialchars($user['date_n']) ?>">
            <div id="dateError" class="text-danger"></div>
        </div>

        <div class="mb-3 text-start">
            <label class="form-label">Rôle</label>
            <select class="form-select" name="role" id="role">
                <option value="">-- Sélectionner --</option>
                <option value="entrepreneur" <?= $user['role'] === 'entrepreneur' ? 'selected' : '' ?>>Entrepreneur</option>
                <option value="investisseur" <?= $user['role'] === 'investisseur' ? 'selected' : '' ?>>Investisseur</option>
            </select>
            <div id="roleError" class="text-danger"></div>
        </div>

        <button type="submit" class="btn btn-primary w-100 mb-2">💾 Mettre à jour</button>
    </form>

    <a href="index.php" class="btn-back">⬅ Retour</a>
</div>

<script>
document.getElementById("profileForm").addEventListener("submit", function (e) {
    let valid = true;

    const adresseField = document.getElementById("adresse");
    const dateField = document.getElementById("date_n");
    const roleField = document.getElementById("role");

    const adresse = adresseField.value.trim();
    const dateN = dateField.value;
    const role = roleField.value;

    const today = new Date().toISOString().split('T')[0];

    const adresseError = document.getElementById("adresseError");
    const dateError = document.getElementById("dateError");
    const roleError = document.getElementById("roleError");

    // Réinitialiser les messages et classes
    adresseError.textContent = "";
    dateError.textContent = "";
    roleError.textContent = "";

    adresseField.classList.remove("is-invalid");
    dateField.classList.remove("is-invalid");
    roleField.classList.remove("is-invalid");

    if (adresse.length < 3) {
        adresseError.textContent = "L'adresse doit contenir au moins 3 caractères.";
        adresseField.classList.add("is-invalid");
        valid = false;
    }

    if (!dateN || dateN >= today) {
        dateError.textContent = "La date de naissance doit être antérieure à aujourd'hui.";
        dateField.classList.add("is-invalid");
        valid = false;
    }

    if (role !== "entrepreneur" && role !== "investisseur") {
        roleError.textContent = "Veuillez sélectionner un rôle.";
        roleField.classList.add("is-invalid");
        valid = false;
    }

    if (!valid) {
        e.preventDefault(); // Empêche l’envoi du formulaire
    }
});
</script>


</body>
</html>

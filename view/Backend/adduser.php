<?php
require_once __DIR__ . '/../../Controllers/config.php';
require_once __DIR__ . '/../../Model/user.php';
require_once __DIR__ . '/../../controller/userController.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['fullname'];
    $date_n = $_POST['date_n'];
    $adresse = $_POST['adresse'];
    $bio = $_POST['bio'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $role = $_POST['role'] ?? 'user';

    $user = new User($fullname, $date_n, $adresse, $bio, $password, $email, $role);
    $controller = new StartlinkUserController();

    if ($controller->addUser($user)) {
        $message = "✅ Utilisateur ajouté avec succès.";
        // Redirection optionnelle après succès :
        // header("Location: liste_utilisateurs.php"); exit;
    } else {
        $message = "❌ Une erreur est survenue lors de l'ajout.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Utilisateur - StartLink</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f8fc;
            padding: 40px;
            display: flex;
            justify-content: center;
        }

        .form-container {
            background-color: #fff;
            padding: 35px 45px;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
            width: 520px;
        }

        h2 {
            text-align: center;
            color: #0a1b89;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #333;
        }

        input[type="text"],
        input[type="email"],
        input[type="date"],
        input[type="password"],
        textarea,
        select {
            width: 100%;
            padding: 10px 12px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        textarea {
            resize: vertical;
        }

        .btn-container {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 25px;
        }

        .btn-container button {
            background-color: #0a1b89;
            color: #fff;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-container button:hover {
            background-color: #061061;
        }

        .message {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }

    </style>
</head>
<body>
    <div class="form-container">
        <h2>Ajouter un utilisateur</h2>

        <?php if (!empty($message)) echo "<p class='message'>$message</p>"; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="fullname">Nom complet</label>
                <input type="text" name="fullname" id="fullname" required>
            </div>

            <div class="form-group">
                <label for="date_n">Date de naissance</label>
                <input type="date" name="date_n" id="date_n" required>
            </div>

            <div class="form-group">
                <label for="adresse">Adresse</label>
                <input type="text" name="adresse" id="adresse" required>
            </div>

            <div class="form-group">
                <label for="bio">Bio</label>
                <textarea name="bio" id="bio" rows="3" placeholder="Quelques mots sur vous..."></textarea>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" required>
            </div>

            <div class="form-group">
                <label for="email">Adresse Email</label>
                <input type="email" name="email" id="email" required>
            </div>

            <div class="form-group">
    <label for="role">Rôle</label>
    <select name="role" id="role" required>
        <option value="entrepreneur" selected>Entrepreneur</option>
        <option value="investisseur">Investisseur</option>
    </select>
</div>

<div style="display: flex; gap: 15px; margin-top: 20px;">
    <button type="submit" style="flex: 1; padding: 12px; background-color: #0a1b89; color: white; border: none; border-radius: 8px; font-weight: bold;">
        Ajouter l'utilisateur
    </button>

    <a href="dashboard.php" style="flex: 1; text-align: center; text-decoration: none;">
        <button type="button" style="width: 100%; padding: 12px; background-color: #e74c3c; color: white; border: none; border-radius: 8px; font-weight: bold;">
            Retour au tableau de bord
        </button>
    </a>
</div>

        </form>
    </div>
</body>
</html>

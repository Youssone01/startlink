<?php
require_once "../../Controllers/config.php";
require_once "../../Model/user.php";
require_once '../../controller/userController.php';

$controller = new StartlinkUserController();

if (!isset($_GET['id'])) {
    die("❌ ID d'utilisateur non spécifié !");
}

$id = $_GET['id'];

try {
    $user = $controller->getUserById($id);
    if (!$user) {
        die("❌ Utilisateur introuvable !");
    }
} catch (PDOException $e) {
    die("Erreur de base de données : " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullname = $_POST['fullname'];
    $date_n = $_POST['date_n'];
    $adresse = $_POST['adresse'];
    $bio = $_POST['bio'];
    $password = !empty($_POST['password']) ? $_POST['password'] : $user['password'];
    $email = $_POST['email'];
    $role = $_POST['role'] ?? 'user';

    $updatedUser = new User($fullname, $date_n, $adresse, $bio, $password, $email, $role);

    if ($controller->updateUser($id, $updatedUser)) {
        header("Location: dashboard.php?success=1");
        exit;
    } else {
        echo "<p style='color:red;'>❌ Erreur lors de la mise à jour.</p>";
    }
}
?>

<style>
    body {
        background-color: #f4f8ff;
        font-family: 'Segoe UI', sans-serif;
        padding: 40px;
        display: flex;
        justify-content: center;
    }

    form {
        background: white;
        padding: 30px 40px;
        border-radius: 12px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
        width: 600px;
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
        font-weight: bold;
        margin-bottom: 6px;
        color: #0a1b89;
    }

    input[type="text"],
    input[type="email"],
    input[type="date"],
    input[type="password"],
    textarea,
    select {
        width: 100%;
        padding: 10px;
        border-radius: 6px;
        border: 1px solid #ccc;
        background-color: #f9fbff;
    }

    input:focus, textarea:focus, select:focus {
        border-color: #0dbbd6;
        box-shadow: 0 0 6px rgba(140, 82, 255, 0.4);
        outline: none;
    }

    small {
        font-style: italic;
        color: #666;
    }

    .btn-container {
        display: flex;
        justify-content: space-between;
        margin-top: 25px;
    }

    .btn-primary {
        background-color: #0a1b89;
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        font-weight: bold;
        cursor: pointer;
        width: 48%;
    }

    .btn-primary:hover {
        background-color: #061061;
    }

    .btn-secondary {
        background-color: #0dbbd6;
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        font-weight: bold;
        cursor: pointer;
        width: 48%;
    }

    .btn-secondary:hover {
        background-color: #0dbbd6;
    }
</style>

<form method="POST">
    <h2>Modifier l'utilisateur</h2>

    <div class="form-group">
        <label>Nom complet :</label>
        <input type="text" name="fullname" value="<?= htmlspecialchars($user['fullname']) ?>" required>
    </div>

    <div class="form-group">
        <label>Date de naissance :</label>
        <input type="date" name="date_n" value="<?= htmlspecialchars($user['date_n']) ?>" required>
    </div>

    <div class="form-group">
        <label>Adresse :</label>
        <input type="text" name="adresse" value="<?= htmlspecialchars($user['adresse']) ?>" required>
    </div>

    <div class="form-group">
        <label>Bio :</label>
        <textarea name="bio" rows="3"><?= htmlspecialchars($user['bio']) ?></textarea>
    </div>

    <div class="form-group">
        <label>Email :</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
    </div>

    <div class="form-group">
        <label>Mot de passe :</label>
        <input type="password" name="password">
        <small>Laisser vide pour garder l'ancien mot de passe</small>
    </div>

    <div class="form-group">
        <label>Rôle :</label>
        <select name="role" required>
            <option value="entrepreneur" <?= $user['role'] === 'entrepreneur' ? 'selected' : '' ?>>Entrepreneur</option>
            <option value="investisseur" <?= $user['role'] === 'investisseur' ? 'selected' : '' ?>>Investisseur</option>
            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Administrateur</option>
        </select>
    </div>

    <div class="btn-container">
        <button type="submit" class="btn-primary">Mettre à jour</button>
        <button type="button" onclick="window.history.back()" class="btn-secondary">Annuler</button>
    </div>
</form>

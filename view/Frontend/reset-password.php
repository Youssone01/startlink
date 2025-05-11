<?php
require_once '../../Controllers/config.php'; // Inclure la connexion à la base de données

// Vérifier si le token est présent dans l'URL et s'il est valide
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['token'])) {
    $token = $_GET['token'];

    try {
        $pdo = config::getConnexion();

        // Vérifie si le token est valide et non expiré
        $stmt = $pdo->prepare("SELECT email, token, expired FROM users WHERE token = :token");
        $stmt->execute(['token' => $token]);

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC); // Récupère l'email et autres informations de l'utilisateur
            
            // Vérifier si le token a expiré
            if (strtotime($user['expired']) < time()) {
                echo "Le token est expiré.";
                exit();
            }
        } else {
            echo "Le token est invalide.";
            exit();
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}

// Traitement de la réinitialisation du mot de passe via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['token'])) {
    $token = $_POST['token'];
    $newPassword = $_POST['password'] ?? '';
    $passwordConfirmation = $_POST['password_confirmation'] ?? '';

    // Validation de base
    if (empty($newPassword) || empty($passwordConfirmation)) {
        die("Veuillez fournir un mot de passe et une confirmation.");
    }

    if ($newPassword !== $passwordConfirmation) {
        die("Les mots de passe ne correspondent pas.");
    }

    try {
        $pdo = config::getConnexion();

        // Mise à jour du mot de passe dans la base de données sans hashage
        $stmt = $pdo->prepare("UPDATE users SET password = :password, token = NULL, expired = NULL WHERE token = :token");
        $stmt->execute([
            'password' => $newPassword,  // Aucune modification du mot de passe
            'token' => $token
        ]);

        echo "Votre mot de passe a été réinitialisé avec succès. <a href='login.php'>Cliquez ici pour vous connecter</a>";
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>

<!-- Formulaire de réinitialisation -->
<?php if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($user)): ?>
    <form method="POST" action="" class="reset-password-form" onsubmit="return validateForm()">
        <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token']) ?>">

        <label for="password">Nouveau mot de passe</label>
        <input type="password" name="password" id="password" placeholder="Nouveau mot de passe" >
        <div id="password-error" class="error-message"></div> <!-- Message d'erreur -->

        <label for="password_confirmation">Confirmer le mot de passe</label>
        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirmer le mot de passe" >
        <div id="password-confirmation-error" class="error-message"></div> <!-- Message d'erreur -->

        <button type="submit" class="submit-button">Réinitialiser le mot de passe</button>
    </form>
<?php endif; ?>

<!-- CSS intégré pour la page -->
<style>
/* Style global pour la page */
body {
    font-family: Arial, sans-serif;
    background-color: #f5f5f5;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

.reset-password-form {
    background-color: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 400px;
}

.reset-password-form h1 {
    font-size: 24px;
    color: #333;
    text-align: center;
    margin-bottom: 20px;
}

.reset-password-form label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    color: #333;
}

.reset-password-form input {
    width: 100%;
    padding: 12px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 16px;
}

.reset-password-form input:focus {
    border-color: #0a1b89;
    outline: none;
    box-shadow: 0 0 5px rgba(10, 27, 137, 0.2);
}

.reset-password-form .submit-button {
    background-color: #0a1b89;
    color: white;
    padding: 15px;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    width: 100%;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.reset-password-form .submit-button:hover {
    background-color: #025bb5;
}

.reset-password-form .error-message {
    color: red;
    font-size: 14px;
    margin-top: -15px;
    margin-bottom: 15px;
}
</style>

<!-- Script JavaScript pour validation de saisie -->
<script>
function validateForm() {
    var password = document.getElementById("password").value;
    var passwordConfirmation = document.getElementById("password_confirmation").value;
    var passwordError = document.getElementById("password-error");
    var passwordConfirmationError = document.getElementById("password-confirmation-error");
    var isValid = true;

    // Réinitialiser les messages d'erreur
    passwordError.textContent = "";
    passwordConfirmationError.textContent = "";

    // Vérification de la longueur minimale du mot de passe
    if (password.length < 6) {
        passwordError.textContent = "Le mot de passe doit comporter au moins 6 caractères.";
        isValid = false;
    }

    // Vérification de la correspondance des mots de passe
    if (password !== passwordConfirmation) {
        passwordConfirmationError.textContent = "Les mots de passe ne correspondent pas.";
        isValid = false;
    }

    return isValid;
}
</script>
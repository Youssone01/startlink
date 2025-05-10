<?php
require_once '../../Controllers/config.php'; // Inclure la connexion à la base de données

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['token'])) {
    $token = $_POST['token'];
    $newPassword = $_POST['password'] ?? '';
    $passwordConfirmation = $_POST['password'] ?? '';

    // Validation de base
    if (empty($newPassword) || empty($passwordConfirmation)) {
        die("Veuillez fournir un mot de passe et une confirmation.");
    }

    if ($newPassword !== $passwordConfirmation) {
        die("Les mots de passe ne correspondent pas.");
    }

    try {
        $pdo = config::getConnexion();

        // Vérification du token et récupération de l'email associé
        $stmt = $pdo->prepare("SELECT email FROM users WHERE token = :token AND expired > NOW()");
        $stmt->execute(['token' => $token]);

        if ($stmt->rowCount() > 0) {
            $email = $stmt->fetchColumn();

            // Pas de hachage, on stocke directement le mot de passe
            $Password = $newPassword;

            // Mise à jour du mot de passe dans la base de données
            $updateStmt = $pdo->prepare("UPDATE users SET password = :password, token = NULL, expired = NULL WHERE email = :email");
            $updateStmt->execute([
                'password' => $Password,
                'email' => $email
            ]);

            echo "Votre mot de passe a été réinitialisé avec succès. <a href='login.php'>Cliquez ici pour vous connecter</a>";
        } else {
            echo "Token invalide ou expiré.";
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>
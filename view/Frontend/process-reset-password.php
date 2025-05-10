
<?php
require_once '../../Controllers/config.php'; // Inclure la connexion à la base de données

// Vérifier si le token est présent dans l'URL
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['token'])) {
    $token = $_GET['token'];

    try {
        $pdo = config::getConnexion();

        // Vérifier si le token est valide et non expiré
        $stmt = $pdo->prepare("SELECT email FROM users WHERE token = :token AND expired > NOW()");
        $stmt->execute(['token' => $token]);

        if ($stmt->rowCount() > 0) {
            $email = $stmt->fetchColumn();
            ?>
            <!-- Formulaire de réinitialisation du mot de passe -->
            <form method="POST" action="send-password-reset.php">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                <input type="password" name="password" placeholder="Nouveau mot de passe" required><br>
                <input type="password" name="password_confirmation" placeholder="Confirmer le mot de passe" required><br>
                <button type="submit">Réinitialiser le mot de passe</button>
            </form>
            <?php
        } else {
            echo "Le token est invalide ou a expiré.";
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>

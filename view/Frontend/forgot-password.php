<?php
$root = dirname(__DIR__, 2);

// PHPMailer
require_once $root . '/PHPMailer-master/src/Exception.php';
require_once $root . '/PHPMailer-master/src/PHPMailer.php';
require_once $root . '/PHPMailer-master/src/SMTP.php';

// BDD
require_once $root . '/Controllers/config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Traitement
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (empty($email)) {
        $error = "Veuillez saisir un email.";
    } else {
        try {
            $db         = new Config();
            $connection = $db->getConnexion();

            $stmt = $connection->prepare("SELECT id, fullname FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($userData) {
                $resetToken = bin2hex(random_bytes(32));
                $expiredAt  = date('Y-m-d H:i:s', strtotime('+1 hour'));

                $updateStmt = $connection->prepare(
                    "UPDATE users SET token = :token, expired = :expired WHERE id = :id"
                );
                $updateStmt->execute([
                    'token'   => $resetToken,
                    'expired' => $expiredAt,
                    'id' => $userData['id']
                ]);

                // Configuration PHPMailer
                $mail = new PHPMailer(true);
                $mail->isSMTP();  // Utiliser SMTP
                $mail->SMTPAuth = true;  // Authentification SMTP
                $mail->Host       = 'smtp.gmail.com';  // Serveur SMTP de Gmail
                $mail->Username   = 'spouz2003@gmail.com';  // Votre adresse email Gmail
                $mail->Password   = 'fdbx olhy sjgg wdwr';  // Votre mot de passe d'application (ou votre mot de passe normal si l'authentification à deux facteurs n'est pas activée)
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Sécurisation avec STARTTLS
                $mail->Port       = 587;  // Port SMTP avec STARTTLS
                $mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);
                // Définir l'expéditeur et le destinataire
                $mail->setFrom('spouz2003@gmail.com', 'StartLink Support');
                $mail->addAddress($email, $userData['fullname']);  // Ajouter un destinataire

                // Lien de réinitialisation
                $resetLink = "http://localhost/gestion_certif/user-startlink/startlink/view/Frontend/reset-password.php?token=" . urlencode($resetToken);

                // Contenu du mail
                $mail->isHTML(true);
                $mail->Subject = 'Réinitialisation de votre mot de passe StartLink';
                $mail->Body    = "
                    Bonjour {$userData['fullname']},<br><br>
                    Cliquez <a href=\"$resetLink\">ici</a> pour réinitialiser votre mot de passe.<br>
                    Ce lien expire dans 1 heure.<br><br>
                    Si vous n’avez pas demandé cette réinitialisation, ignorez cet email.
                ";
                $mail->AltBody = "Lien de réinitialisation : $resetLink";
                
                // Envoi de l'email
                $mail->send();

                $success = "Un lien de réinitialisation a été envoyé à votre email.";
            } else {
                $error = "Aucun compte StartLink n’est associé à cet email.";
            }
        } catch (Exception $e) {
            $error = "Erreur serveur : " . $e->getMessage();
        }
    }
}
?>

<?php include __DIR__ . '/include/header.php'; ?>
<?php include __DIR__ . '/include/navbar.php'; ?>
<style>
  .page-wrapper {
    min-height: calc(100vh - 300px); /* pour éviter que le footer colle */
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f0f4fb;
    padding: 3rem 1rem;
  }

  .form-container {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 8px 24px rgba(10,27,137,0.1);
    width: 100%;
    max-width: 420px;
    padding: 2rem;
    text-align: center;
  }

  .form-container h1 {
    color: #0a1b89;
    margin-bottom: 1.5rem;
    font-weight: 600;
  }

  .form-container label {
    display: block;
    text-align: left;
    margin-bottom: .5rem;
    font-weight: 500;
  }

  .form-container input,
  .form-container button {
    width: 100%;
    padding: .75rem 1rem;
    margin-bottom: 1rem;
    border-radius: 4px;
    border: 1px solid #ccc;
    font-size: 1rem;
  }

  .form-container input:focus {
    outline: none;
    border-color: #0a1b89;
    box-shadow: 0 0 0 2px rgba(10,27,137,0.2);
  }

  .form-container button {
    background: rgb(21, 198, 225);
    border: none;
    color: #fff;
    font-weight: 600;
    cursor: pointer;
  }

  .form-container button:hover {
    background: rgb(15, 132, 186);
  }

  .message {
    margin-bottom: 1rem;
    font-size: .95rem;
  }

  .message.error { color: #d32f2f; }
  .message.success { color: #388e3c; }

  .footer-text {
    font-size: .85rem;
    color: #777;
  }

  .footer-text a {
    color: #0a1b89;
    text-decoration: none;
  }
</style>

<div class="page-wrapper">
  <div class="form-container">
    <h1>Mot de passe oublié</h1>

    <?php if (!empty($error)): ?>
      <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php elseif (!empty($success)): ?>
      <div class="message success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="post" id="resetForm">
      <label for="email">Adresse email StartLink</label>
      <input type="text" id="email" name="email" placeholder="votre.email@exemple.com">
      <div id="jsError" class="message error" style="display:none;"></div>
      <button type="submit">Envoyer le lien</button>
    </form>

    <div class="footer-text">
      Retour à la <a href="login.php">page de connexion</a><br>
      &copy; <?= date('Y') ?> StartLink – Connectez entrepreneurs & investisseurs
    </div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('resetForm');
  const emailInput = document.getElementById('email');
  const errorDiv = document.getElementById('jsError');

  form.addEventListener('submit', function (e) {
    const email = emailInput.value.trim();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    // Check if the email is empty
    if (email === '') {
      errorDiv.textContent = 'Veuillez saisir votre adresse email.';
      errorDiv.style.display = 'block';
      e.preventDefault();  // Prevent form submission
    }
    // Check if the email format is valid
    else if (!emailRegex.test(email)) {
      errorDiv.textContent = 'Adresse email invalide.';
      errorDiv.style.display = 'block';
      e.preventDefault();  // Prevent form submission
    } 
    else {
      errorDiv.textContent = '';  // Clear any previous error messages
      errorDiv.style.display = 'none';  // Hide the error message box
    }
  });

  // Hide the error message as the user types in the email field
  emailInput.addEventListener('input', function () {
    errorDiv.style.display = 'none';
  });
});
</script>

<?php include __DIR__ . '/include/footer.php'; ?>

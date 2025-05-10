<?php
session_start();

require_once __DIR__ . '/../../Controllers/config.php';
require_once __DIR__ . '/../../Model/user.php';
require_once __DIR__ . '/../../controller/userController.php';

$message = "";
$fullname = $date_n = $adresse = $bio = $email = $role = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname         = trim($_POST['fullname']);
    $date_n           = $_POST['date_n'];
    $adresse          = trim($_POST['adresse']);
    $bio              = trim($_POST['bio']);
    $email            = trim($_POST['email']);
    $password         = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role             = $_POST['role'] ?? 'user';

    if (empty($fullname) || empty($date_n) || empty($adresse) || empty($email) || empty($password) || empty($confirm_password)) {
        $message = "❌ Tous les champs obligatoires doivent être remplis.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "❌ Email invalide.";
    } elseif (strlen($password) < 6) {
        $message = "❌ Le mot de passe doit contenir au moins 6 caractères.";
    } elseif ($password !== $confirm_password) {
        $message = "❌ Les mots de passe ne correspondent pas.";
    } else {
        // Sans hashing, on passe directement le mot de passe
        $user = new User($fullname, $date_n, $adresse, $bio, $password, $email, $role);
        $controller = new StartlinkUserController();

        if ($controller->addUser($user)) {
            header("Location: login.php");
            exit;
        } else {
            $message = "❌ Une erreur est survenue lors de l'inscription.";
        }
    }
}
?>
<?php include('include/navbar.php'); ?>
<?php include('include/header.php'); ?>
<link rel="stylesheet" href="assets/css/register.css">

<body>
    <?php include('include/spinner.php'); ?>

    <div class="form-container" style="max-width: 600px; margin: auto; padding: 20px;">
        <div style="text-align: center; margin-bottom: 30px;">
            <img src="assets/img/logo.PNG" alt="Logo StartLink" style="height: 100px;">
            <h3 style="color: #004AAD; margin-top: 10px;">Démarrez votre parcours professionnel</h3>
        </div>

        <h2 style="text-align: center; color: #0a1b89;">Inscription</h2>
        <?php if ($message): ?>
            <div class="message" style="color: red; text-align: center; margin-bottom: 15px;"><?= $message ?></div>
        <?php endif; ?>

        <form id="registerForm" action="" method="POST" novalidate>
            <!-- Nom complet -->
            <div class="form-group">
                <label for="fullname">Nom complet</label>
                <input type="text" name="fullname" id="fullname" class="form-control"
                       value="<?= htmlspecialchars($fullname) ?>" >
                <span id="fullnameError" class="error-message"></span>
            </div>

            <!-- Date de naissance -->
            <div class="form-group">
                <label for="date_n">Date de naissance</label>
                <input type="date" name="date_n" id="date_n" class="form-control"
                       value="<?= htmlspecialchars($date_n) ?>">
                <span id="date_nError" class="error-message"></span>
            </div>

            <!-- Adresse -->
            <div class="form-group">
                <label for="adresse">Adresse</label>
                <input type="text" name="adresse" id="adresse" class="form-control"
                       value="<?= htmlspecialchars($adresse) ?>" >
                <span id="adresseError" class="error-message"></span>
            </div>

            <!-- Bio -->
            <div class="form-group">
                <label for="bio">Bio</label>
                <textarea name="bio" id="bio" rows="3" class="form-control"><?= htmlspecialchars($bio) ?></textarea>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control"
                       value="<?= htmlspecialchars($email) ?>" >
                <span id="emailError" class="error-message"></span>
            </div>

            <!-- Mot de passe -->
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" class="form-control" >
                <span id="passwordError" class="error-message"></span>
            </div>

            <!-- Confirmation mot de passe -->
            <div class="form-group">
                <label for="confirm_password">Confirmer le mot de passe</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" >
                <span id="confirm_passwordError" class="error-message"></span>
            </div>

            <!-- Rôle -->
            <div class="form-group">
                <label for="role">Rôle</label>
                <select name="role" id="role" class="form-control" >
                    <option value="">-- Choisissez un rôle --</option>
                    <option value="entrepreneur" <?= ($role === 'entrepreneur') ? 'selected' : '' ?>>Entrepreneur</option>
                    <option value="investisseur" <?= ($role === 'investisseur') ? 'selected' : '' ?>>Investisseur</option>
                </select>
                <span id="roleError" class="error-message"></span>
            </div>

            <div class="form-group" style="text-align: center; margin-top: 20px;">
                <button type="submit" class="btn btn-primary"
                        style="padding: 10px 30px; border-radius: 8px; background-color: #0a1b89; border: none; color: white;">
                    S'inscrire
                </button>
            </div>

            <div class="text" style="text-align: center; margin-top: 15px;">
                <h3>Vous avez déjà un compte ? <a href="login.php">Se connecter</a></h3>
            </div>
        </form>
    </div>

    <?php include('include/footer.php'); ?>
    <?php include('include/js.php'); ?>

    <script>
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        e.preventDefault();
        let valid = true;
        ['fullname','date_n','adresse','email','password','confirm_password','role']
            .forEach(id => document.getElementById(id + 'Error').textContent = '');

        if (!this.fullname.value.trim()) {
            document.getElementById('fullnameError').textContent = '❌ Nom complet requis.';
            valid = false;
        }
        if (!this.date_n.value) {
            document.getElementById('date_nError').textContent = '❌ Date de naissance requise.';
            valid = false;
        }
        if (!this.adresse.value.trim()) {
            document.getElementById('adresseError').textContent = '❌ Adresse requise.';
            valid = false;
        }
        if (!this.email.value.includes('@')) {
            document.getElementById('emailError').textContent = '❌ Email invalide.';
            valid = false;
        }
        if (this.password.value.length < 6) {
            document.getElementById('passwordError').textContent = '❌ Au moins 6 caractères.';
            valid = false;
        }
        if (this.confirm_password.value !== this.password.value) {
            document.getElementById('confirm_passwordError').textContent = '❌ Les mots de passe diffèrent.';
            valid = false;
        }
        if (!this.role.value) {
            document.getElementById('roleError').textContent = '❌ Choisir un rôle.';
            valid = false;
        }

        if (valid) this.submit();
    });
    </script>
</body>
</html>

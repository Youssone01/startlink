<?php
require_once '../../controller/userController.php';
require_once '../../model/user.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['username']);
    $dateOfBirth = $_POST['dateOfBirth'];
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    $passwordConfirm = htmlspecialchars($_POST['passwordConfirm']);
    if ($password !== $passwordConfirm) {
        die('Les mots de passe ne correspondent pas.');
    }
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $user = new User($name, $dateOfBirth, "default.jpg", "Bio par défaut", $hashedPassword, $email);
    $userc = new userc();
    if ($userc->addUser($user)) {
        header("Location: index.php");
        exit();
    } else {
        echo "Une erreur est survenue lors de l'inscription.";
    }
}
?>

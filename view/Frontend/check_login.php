<?php
ob_clean();
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/../../Controllers/config.php';
require_once __DIR__ . '/../../Model/user.php';
require_once __DIR__ . '/../../controller/userController.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = ($_POST["password"]);

    $db = config::getConnexion();

    $query = $db->prepare("SELECT * FROM users WHERE email = :email AND password = :password");
    $query->execute([
        'email' => $email,
        'password' => $password
    ]);
    $user = $query->fetch();

    if ($user) {
        if ($user['email'] === 'admin@startlink.com' ) {
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_name'] = $user['fullname'];
            echo json_encode(["status" => "success", "redirect" => "../../View/Backend/dashboard.php"]);
        } else {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['fullname'];
            echo json_encode(["status" => "success", "redirect" => "index.php"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Email ou mot de passe incorrect"]);
    }
    exit;
}

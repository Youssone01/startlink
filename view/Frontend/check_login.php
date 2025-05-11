<?php
// Clean the output buffer and set correct headers for JSON response
ob_clean();
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__. '/../../Controllers/config.php';
require_once __DIR__ . '/../../model/user.php';
require_once __DIR__ . '/../../controller/userController.php';

// Initialize the response array with an error message
$response = [
    'status' => 'error',
    'message' => 'Invalid login details',
    'redirect' => ''
];

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = ($_POST["password"]);

    try {
        $db = config::getConnexion();
        
        // Prepare and execute the SQL query to check if the user exists
        $query = $db->prepare("SELECT * FROM users WHERE email = :email AND password = :password");
        $query->execute([
            'email' => $email,
            'password' => $password
        ]);
        $user = $query->fetch();

        if ($user) {
            // Check if the user is an admin
            if ($user['email'] === 'admin@startlink.com') {
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_name'] = $user['fullname'];
                $response['status'] = 'success';
                $response['redirect'] = '../../View/Backend/dashboard.php';
            } else {
                // For normal users, set the session and redirect based on the role
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['fullname'];
                $_SESSION['role'] = $user['role']; // Save the role in the session

                if ($_SESSION['role'] === 'investisseur') {
                    $response['status'] = 'success';
                    $response['redirect'] = 'mes_offres.php'; // Redirect to 'mes_offres.php' for investors
                } else if ($_SESSION['role'] === 'entrepreneur') {
                    $response['status'] = 'success';
                    $response['redirect'] = 'offres.php'; // Redirect to 'offres.php' for entrepreneurs
                } else {
                    // Default redirect for other roles if needed
                    $response['status'] = 'success';
                    $response['redirect'] = 'index.php';
                }
            }
        } else {
            // If the user is not found in the database, return an error message
            $response['status'] = 'error';
            $response['message'] = 'Email ou mot de passe incorrect';
        }
    } catch (Exception $e) {
        // Handle any exceptions that occur during the database query
        $response['status'] = 'error';
        $response['message'] = 'Une erreur s\'est produite : ' . $e->getMessage();
    }
}

// Output the JSON response
echo json_encode($response);
exit;
?>
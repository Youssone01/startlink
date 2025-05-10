<?php
require_once __DIR__ . '/../../Controllers/config.php';
require_once __DIR__ . '/../../Model/user.php';
require_once __DIR__ . '/../../controller/userController.php';

$controller = new StartlinkUserController();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $success = $controller->deleteUser($id);

    if ($success) {
        header("Location: dashboard.php?message=success");
        exit();
    } else {
        header("Location: dashboard.php?message=error");
        exit();
    }
} else {
    header("Location: dashboard.php?message=missing_id");
    exit();
}

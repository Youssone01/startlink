<?php
require_once __DIR__ . '/../controllers/config.php';
require_once __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $fileTmp = $_FILES['file']['tmp_name'];
    $fileName = $_FILES['file']['name'];
    $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    $pdo = config::getConnexion();

    try {
        if ($extension === 'csv') {
            $file = fopen($fileTmp, 'r');
            $header = fgetcsv($file); // ignore the first row

            while ($row = fgetcsv($file)) {
                [$fullname, $email, $role] = $row;

                // Vérifie si l'email existe déjà
                $check = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
                $check->execute([$email]);
                if ($check->fetchColumn() == 0) {
                    $stmt = $pdo->prepare("INSERT INTO users (fullname, email, role) VALUES (?, ?, ?)");
                    $stmt->execute([$fullname, $email, $role]);
                }
            }

            fclose($file);

        } elseif ($extension === 'xlsx') {
            $spreadsheet = IOFactory::load($fileTmp);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            // Ignore the header
            for ($i = 1; $i < count($rows); $i++) {
                [$fullname, $email, $role] = $rows[$i];

                $check = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
                $check->execute([$email]);
                if ($check->fetchColumn() == 0) {
                    $stmt = $pdo->prepare("INSERT INTO users (fullname, email, role) VALUES (?, ?, ?)");
                    $stmt->execute([$fullname, $email, $role]);
                }
            }

        } else {
            throw new Exception("Format de fichier non pris en charge.");
        }

        echo "<script>alert('✅ Importation réussie !'); window.location.href = '../view/Backend/user_tools.php';</script>";
        exit;

    } catch (Exception $e) {
        echo "<script>alert('❌ Erreur lors de l’importation : " . $e->getMessage() . "'); window.history.back();</script>";
        exit;
    }

} else {
    echo "<script>alert('Aucun fichier fourni.'); window.history.back();</script>";
}
?>

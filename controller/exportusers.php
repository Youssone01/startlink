<?php
// Chemins
require_once __DIR__ . '/../controllers/config.php';
require_once __DIR__ . '/../vendor/autoload.php';
// Librairies
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;

// Paramètres
$format = $_GET['format'] ?? 'csv';
$role = $_GET['role'] ?? '';

// Connexion BDD
$pdo = config::getConnexion();

// Requête SQL selon filtre
$query = "SELECT fullname AS Nom, email AS Email, role AS Rôle FROM users";
if (!empty($role) && $role !== 'all') {
    $query .= " WHERE role = :role";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':role', $role);
    $stmt->execute();
} else {
    $stmt = $pdo->query($query);
}

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Sécurité : rien à exporter
if (!$data) {
    echo "Aucune donnée à exporter.";
    exit;
}

// 📤 Export CSV
if ($format === 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment;filename="utilisateurs.csv"');
    $output = fopen('php://output', 'w');
    fputcsv($output, array_keys($data[0]));
    foreach ($data as $row) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit;
}

// 📤 Export Excel
if ($format === 'xlsx' || $format === 'excel') {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->fromArray(array_keys($data[0]), NULL, 'A1');
    $sheet->fromArray($data, NULL, 'A2');

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="utilisateurs.xlsx"');
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}

// 📤 Export PDF
if ($format === 'pdf') {
    $html = "<h2 style='color:#0a1b89;font-family:Arial'>Liste des utilisateurs</h2>";
    $html .= "<table border='1' cellpadding='8' cellspacing='0' style='border-collapse:collapse;width:100%;'>";
    $html .= "<tr>";
    foreach (array_keys($data[0]) as $col) {
        $html .= "<th style='background:#f0f0f0;'>$col</th>";
    }
    $html .= "</tr>";
    foreach ($data as $row) {
        $html .= "<tr>";
        foreach ($row as $cell) {
            $html .= "<td>$cell</td>";
        }
        $html .= "</tr>";
    }
    $html .= "</table>";

    $pdf = new Dompdf();
    $pdf->loadHtml($html);
    $pdf->setPaper('A4', 'portrait');
    $pdf->render();
    $pdf->stream("utilisateurs.pdf", ["Attachment" => true]);
    exit;
}

// Format invalide
echo "Format d'exportation non supporté.";
?>

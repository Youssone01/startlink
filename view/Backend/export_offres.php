<?php
require_once __DIR__ . '/../../Controllers/config.php';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=offres.csv');

// Ouvre la sortie standard pour écrire le CSV
$output = fopen('php://output', 'w');

// Écrit l'en-tête CSV
fputcsv($output, [
    'ID',
    'Titre',
    'Description',
    'Compétences Requises',
    'Type de Contrat',
    'Salaire',
    'Lieu',
    'Date de Publication',
    'Entreprise'
]);

$db = config::getConnexion();

// Requête avec jointure pour obtenir les infos de l'entreprise
$query = $db->query("
    SELECT o.*, u.fullname as entreprise 
    FROM offres o
    LEFT JOIN users u ON o.user_id = u.id
    ORDER BY o.date_publication DESC
");

$offres = $query->fetchAll(PDO::FETCH_ASSOC);

// Boucle sur chaque offre et écrit une ligne dans le CSV
foreach ($offres as $offre) {
    fputcsv($output, [
        $offre['id'],
        $offre['titre'],
        $offre['description'],
        $offre['competences_requises'],
        $offre['type_contrat'],
        number_format($offre['salaire'], 2),
        $offre['lieu'],
        date('d/m/Y H:i', strtotime($offre['date_publication'])),
        $offre['entreprise']
    ]);
}

fclose($output);
exit;
?>
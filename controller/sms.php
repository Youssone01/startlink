<?php
// Définir les identifiants Twilio
$accountSid = 'AC35e5220b2cf616a0c9fdfc8ceb883b52'; // Votre Account SID
$authToken = '8a97ddd722e6682ea4c50f641f626db1'; // Remplacez par votre Auth Token
$twilioNumber = '+19109710205'; // Remplacez par votre numéro Twilio

// Récupérer les données du formulaire (simulé ici, à adapter selon votre contexte)
$titre = isset($_POST['titre']) ? trim($_POST['titre']) : 'Événement inconnu';
$dateEvent = isset($_POST['dateEvent']) ? trim($_POST['dateEvent']) : date('Y-m-d');
$organisateur = isset($_POST['organisateur']) ? trim($_POST['organisateur']) : 'Inconnu';
$prix = isset($_POST['prix']) && $_POST['prix'] !== '' ? (float)$_POST['prix'] : null;

// Numéro de destination (à vérifier dans Verified Caller IDs)
$to = isset($_POST['to']) ? trim($_POST['to']) : '+21699118219';

// Construire le corps du message avec les données de l'événement
$messageBody = "Nouvel événement créé :\n";
$messageBody .= "Titre: $titre\n";
$messageBody .= "Date: $dateEvent\n";
$messageBody .= "Organisateur: $organisateur\n";
$messageBody .= "Prix: " . ($prix !== null ? "$prix €" : "Gratuit");

// URL de l'API Twilio
$url = "https://api.twilio.com/2010-04-01/Accounts/$accountSid/Messages.json";

// Préparer les données pour la requête POST
$data = [
    'From' => $twilioNumber,
    'To' => $to,
    'Body' => $messageBody,
];

// Initialiser cURL
$ch = curl_init($url);

// Configurer les options cURL
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_USERPWD, "$accountSid:$authToken"); // Authentification
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); // Données POST

// Exécuter la requête
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Vérifier les erreurs cURL
if ($response === false) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Erreur cURL : ' . curl_error($ch)
    ]);
    curl_close($ch);
    exit;
}

// Fermer la session cURL
curl_close($ch);

// Analyser la réponse
if ($httpCode == 201) {
    echo json_encode([
        'status' => 'success',
        'message' => 'SMS envoyé avec succès avec les détails de l\'événement !'
    ]);
} else {
    $error = json_decode($response, true);
    echo json_encode([
        'status' => 'error',
        'message' => 'Erreur lors de l\'envoi du SMS : ' . ($error['message'] ?? 'Erreur inconnue')
    ]);
}
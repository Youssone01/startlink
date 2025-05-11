<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Charger Composer

function sendEventEmail($organisateur, $prix) {
    $mail = new PHPMailer(true); // Active les exceptions

    try {
        // Configuration du serveur SMTP (exemple pour Gmail)
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Serveur SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'amouryBendhieb@gmail.com'; // Votre email
        $mail->Password = 'yaoa wwif xqpq gyed'; // Mot de passe ou "App Password"
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // TLS
        $mail->Port = 587; // Port SMTP

        // Expéditeur et destinataire
        $mail->setFrom('amouryBendhieb@gmail.com', 'Nom de l\'expéditeur');
        $mail->addAddress('Omar.bendhieb@esprit.tn', 'Nom du destinataire');

        // Contenu de l'email
        $mail->isHTML(true); // Format HTML
        $mail->Subject = 'Nouvel événement enregistré';
        $mail->Body = '
            <h1>Nouvel événement créé</h1>
            <p>Organisateur: ' . htmlspecialchars($organisateur) . '</p>
            <p>Prix: ' . htmlspecialchars($prix) . '</p>
        ';
        $mail->AltBody = 'Organisateur: ' . htmlspecialchars($organisateur) . ', Prix: ' . htmlspecialchars($prix); // Version texte brut

        // Envoyer l'email
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Erreur lors de l'envoi : {$mail->ErrorInfo}");
        return false;
    }
}
?>
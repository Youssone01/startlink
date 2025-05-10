<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    // Activer le débogage pour afficher les messages d'erreur
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;  // Affiche le débogage SMTP
    $mail->isSMTP();  // Utiliser SMTP
    $mail->Host = 'smtp.gmail.com';  // Serveur SMTP de Gmail
    $mail->SMTPAuth = true;  // Authentification SMTP
    $mail->Username = 'spouz2003@gmail.com';  // Votre adresse email Gmail
    $mail->Password = 'fdbx olhy sjgg wdwr';  // Votre mot de passe d'application
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Sécurisation avec STARTTLS
    $mail->Port = 587;  // Port SMTP avec STARTTLS
$mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);
    // Définir l'expéditeur et le destinataire
    $mail->setFrom('spouz2003@gmail.com', 'Support');
    $mail->addAddress('destinataire@example.com');  // Ajouter un destinataire

    // Contenu du mail
    $mail->isHTML(true);
    $mail->Subject = 'Test de connexion SMTP';
    $mail->Body    = 'Ceci est un test de connexion SMTP avec PHPMailer.';

    $mail->send();
    echo 'Message envoyé avec succès.';
} catch (Exception $e) {
    echo "Le message n'a pas pu être envoyé. Mailer Error: {$mail->ErrorInfo}";
}

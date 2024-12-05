<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

$mail->isSMTP(); // Utilisation du protocole SMTP
$mail->Host = 'smtp.gmail.com'; // Serveur SMTP de Gmail
$mail->SMTPAuth = true; // Activation de l'authentification SMTP
$mail->Username = 'afatouma873@gmail.com'; // Adresse email de l'expéditeur
$mail->Password = 'jgfn aazx qxrq wbeb'; // Mot de passe de l'adresse email
$mail->SMTPSecure = 'tls'; // Type de cryptage
$mail->Port = 587; // Port SMTP pour TLS

$mail->CharSet = 'utf-8'; // Jeu de caractères
$mail->setFrom('afatouma873@gmail.com', 'Fatouma Ali'); // Adresse email et nom de l'expéditeur
$mail->addAddress('aichabouh118@gmail.com', 'Fatouma Ali'); // Adresse email et nom du destinataire

$mail->isHTML(true); // Activation de l'envoi de contenu HTML
$mail->Subject = 'Confirmation d\'email'; // Sujet de l'email
$mail->Body = 'Bonjour, vous venez de recevoir un mail de test !'; // Contenu du mail

$mail->SMTPDebug = 0; // Désactiver les messages de débogage

// Envoi de l'email
if (!$mail->send()) {
    $message = "Mail non envoyé"; // Correction : ajout de l'égalité et de la syntaxe correcte
    echo 'Erreur : ' . $mail->ErrorInfo; // Correction : fin de la chaîne avec une guillemet simple
} else {
    $message = "Un email vous a été envoyé !"; // Correction de la phrase
    echo $message;
}
?>
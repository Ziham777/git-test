
<?php
// Inclure le fichier d'autoload de Composer
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // Configuration du serveur SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Utiliser Gmail
    $mail->SMTPAuth = true;
    $mail->Username = 'votre_email@gmail.com'; // Remplacez par votre adresse Gmail
    $mail->Password = 'votre_mot_de_passe'; // Remplacez par votre mot de passe Gmail ou mot de passe d'application
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Chiffrement TLS
    $mail->Port = 587;

    // Expéditeur
    $mail->setFrom('votre_email@gmail.com', 'Nom de votre site');

    // Destinataire (l'entreprise)
    $mail->addAddress('email_entreprise@example.com', 'Nom de l’entreprise');

    // Contenu de l'e-mail
    $mail->isHTML(true);
    $mail->Subject = 'Nouvelle candidature reçue';
    $mail->Body    = 'Bonjour,<br><br>Un candidat a postulé pour l\'offre <b>"Nom de l\'offre"</b>. Veuillez vérifier votre tableau de bord pour plus de détails.<br><br>Cordialement,<br>Votre équipe.';
    $mail->AltBody = 'Un candidat a postulé pour l\'offre "Nom de l\'offre". Veuillez vérifier votre tableau de bord pour plus de détails.';

    // Envoyer l'e-mail
    $mail->send();
    echo 'E-mail envoyé avec succès.';
} catch (Exception $e) {
    echo "Erreur lors de l'envoi de l'e-mail : {$mail->ErrorInfo}";
}


---

3. Configurer Gmail pour autoriser les e-mails

Pour envoyer des e-mails via Gmail :

Activer les mots de passe d'application : Si votre compte utilise l'authentification à deux facteurs, vous devez générer un mot de passe d'application pour Composer.

1. Allez dans https://myaccount.google.com/security.


2. Activez l'authentification à deux facteurs (si ce n'est pas déjà fait).


3. Générez un mot de passe d'application pour "Mail".



Si vous n'avez pas d'authentification à deux facteurs, activez l'option "Accès moins sécurisé" dans les paramètres de votre compte Gmail :

1. Connectez-vous à Gmail.


2. Accédez à https://myaccount.google.com/lesssecureapps.


3. Activez l'option.





---

4. Intégrer l'envoi dans votre système

1. Récupérez les données de la candidature : Après que le candidat soumet son formulaire, récupérez ses informations (par exemple, nom, e-mail, CV, etc.) ainsi que l'e-mail de l'entreprise depuis votre base de données.


2. Automatisez l'envoi : Appelez le script send_email.php dans le traitement du formulaire après avoir inséré les données dans la table candidature.



Exemple de traitement du formulaire :

<?php
// Connexion à la base de données
$conn = new mysqli('localhost', 'root', '', 'votre_base_de_donnees');

// Récupération des données du formulaire
$nom = $_POST['nom'];
$email = $_POST['email'];
$id_offre = $_POST['id_offre'];

// Insérer la candidature dans la base de données
$sql = "INSERT INTO candidature (nom, email, id_offre) VALUES ('$nom', '$email', '$id_offre')";
if ($conn->query($sql) === TRUE) {
    // Inclure le script d'envoi d'e-mails
    include 'send_email.php';
    echo "Candidature soumise et e-mail envoyé.";
} else {
    echo "Erreur : " . $conn->error;
}

$conn->close();


---

<?php


  // Utilisation de PHPMailer pour envoyer l'email
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;
  use PHPMailer\PHPMailer\SMTP;

  require 'PHPMailer/src/Exception.php';
  require 'PHPMailer/src/PHPMailer.php';
  require 'PHPMailer/src/SMTP.php';

// Connexion à la base de données
$connect = mysqli_connect("localhost", "root", "", "demande_emploi");

if (!$connect) {
    die("Erreur de connexion : " . mysqli_connect_error());
}

// Récupérer les informations du formulaire
$id_entreprise = $_POST['id_entreprise'];
$id_offre = $_POST['id_offre'];
$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$Nom_offre = $_POST['Nom_offre'];
$adresse = $_POST['adresse'];
$email = $_POST['email'];
$tel = $_POST['tel'];
$diplome = $_POST['diplome'];
$cv = $_FILES['cv']['name'];
$lettre = $_POST['lettre'];
$date = $_POST['date'];

// Validation de l'email
if (!$email) {
    echo "Adresse email invalide.<br>";
    exit;
}


// Récupérer l'email de l'entreprise depuis la base de données
$sql_email = "SELECT email FROM entreprise WHERE Numentrep = $id_entreprise";
$result_email = mysqli_query($connect, $sql_email);

if ($result_email && mysqli_num_rows($result_email) > 0) {
    $entreprise_data = mysqli_fetch_assoc($result_email);
    $email_entreprise = $entreprise_data['email'];

    // Déplacer le fichier téléchargé (CV) vers un dossier de stockage
    $cv_path = 'uploads/' . basename($cv);
    if (move_uploaded_file($_FILES['cv']['tmp_name'], $cv_path)) {
        // Insertion des données dans la table candidature
        $sql_insert = "INSERT INTO candidature (Numoffre, Numentrep, Nom, prenom, Nom_offre, adresse, Email, telephone, diplome, cv, lettremotivation, datecandidature) 
                       VALUES ('$id_offre', '$id_entreprise', '$nom', '$prenom', '$Nom_offre', '$adresse', '$email', $tel, '$diplome', '$cv', '$lettre', '$date')";
        if (mysqli_query($connect, $sql_insert)) {
            // Si l'insertion est réussie, on envoie l'email à l'entreprise
          
            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'afatouma873@gmail.com'; // Votre email
                $mail->Password = 'jgfn aazx qxrq wbeb';   // Votre mot de passe
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('fatouma873@gmail.com', 'JobLinker');
                $mail->addAddress($email_entreprise); // L'email de l'entreprise

                $mail->isHTML(true);
                $mail->Subject = 'Nouvelle candidature pour l\'offre ' . $id_offre;
                $mail->Body = "
                    <h2>Nouvelle Candidature</h2>
                    <p><strong>Nom :</strong> $nom</p>
                    <p><strong>Prénom :</strong> $prenom</p>
                    <p><strong>Nom_offre :</strong> $Nom_offre</p>
                    <p><strong>Adresse :</strong> $adresse</p>
                    <p><strong>Email :</strong> $email</p>
                    <p><strong>tel :</strong> $tel</p>
                    <p><strong>Diplome :</strong>$diplome</p>
                    <p><strong>Lettre de motivation :</strong> $lettre</p>
                    <p><strong>CV :</strong> <a href='uploads/$cv'>Télécharger le CV</a></p>
                    <p><strong>Date de Candidature :</strong> $date</p>
                ";

                if ($mail->send()) {
                    echo 'Candidature envoyée avec succès!';
                } else {
                    echo 'Erreur : ' . $mail->ErrorInfo;
                }
            } catch (Exception $e) {
                echo "Erreur d'envoi de l'email: {$mail->ErrorInfo}";
            }
        } else {
            echo "Erreur lors de l'insertion dans la base de données: " . mysqli_error($connect);
        }
    } else {
        echo 'Erreur lors du téléchargement du CV.';
    }
} else {
    echo "Entreprise introuvable.";
}

mysqli_close($connect);
?>
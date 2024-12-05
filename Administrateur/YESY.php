<?php
$server="localhost";
$user="root";
$pass="";
$base="demande_emploi";
$connect=mysqli_connect($server,$user,$pass,$base);
$Nom=$_POST['nom'];
$email=$_POST['email'];
$Adress=$_POST['Adress'];
$secteur=$_POST['secteur'];
$pass=$_POST['motpasse'];
$resultat=mysqli_query($connect, "insert into enseignant value('$Nom','$email''$Adress','$secteur','$pass')");
if ($resultat==0) {
	echo "les donnees ne sont pas enregistrer";
}
else{
	echo "les donnees sont enregistrer";
	echo'<a href="menugen.html"> Retour au Menu Generale';
}
?>
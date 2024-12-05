<?php
$serveur="localhost";
$utilisateur="root";
$mot="";
$base="demande_emploi";
$connect=mysqli_connect($serveur,$utilisateur,$mot,$base);
$nom=$_POST["nom"];
$email=$_POST["email"];
$phone=$_POST["phone"];
$message=$_POST["message"];
$res=mysqli_query($connect,"insert into contact(nomcontact,emailcontact,phonecontact,message) 
values('$nom','$email','$phone','$message')");
if($res==true){
    
    echo"Merci de nous contacter.";
}

?>
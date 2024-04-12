<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';
//Create an instance; passing `true` enables exceptions

// On se connecte à là base de données
//include 'connect.php';

//Listes des utilisateurs et leurs emails
$sql = 'SELECT * FROM `utilisateur` ORDER BY `id`';

// On prépare la requête
$query = $db->prepare($sql);

// On exécute
$query->execute();

// On récupère les valeurs dans un tableau associatif
$articlMails = $query->fetchAll(PDO::FETCH_ASSOC);

/*foreach($articlMails as $article){
    echo $article['email'] ;
}*/

function envoie_mail($from_name,$from_email,$subject,$message){
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->SMTPDebug = 0;
    $mail->SMTPSecure = 'ssl';
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;  
    $mail->Username   = 'mouhamadoulbachir2@gmail.com';                     //SMTP username
    $mail->Password   = 'nbsjptwdhczvpuid';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;
    $mail->setFrom($from_email, $from_name);
    $mail->addAddress($from_email);
    $mail->isHTML(true);     
    $mail->CharSet = "UTF-8";                            
    $mail->Subject = $subject;
    $mail->Body    = $message;
    $mail->setLanguage('fr', '/optional/path/to/language/directory/');
    if (!$mail->Send()) {
        return false;
    }
    else {
        return true;
    }

}/*
if (envoie_mail('mouhamadoulbachir','mouhamadoulbachir2@gmail.com','Test','testMes')) {
    echo 'OK';
}
else {
    echo "Une erreur s'est produite";
}*/

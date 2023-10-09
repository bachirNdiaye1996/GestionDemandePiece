<?php
    #$servername = "mysql-boulangerie.alwaysdata.net";
    $username = "root";
    $password = "";
    $db_name = "maintenance";  

    $db = new PDO("mysql:host=localhost;dbname=$db_name;charset=utf8", $username, $password);
    if(isset($_POST['valideArticle'])){
        if(!empty($_POST['quantites'])){
            $quantites=htmlspecialchars($_POST['quantites']);
            $designations=htmlspecialchars($_POST['designations']);
            $references=htmlspecialchars($_POST['references']);
            $priorites=htmlspecialchars($_POST['priorites']);
            $status=htmlspecialchars($_POST['status']);
            $user=htmlspecialchars($_POST['user']);
            $livraison=htmlspecialchars($_POST['livraison']);
            $insertUser=$db->prepare("INSERT INTO `articles` (`id`, `quantites`, `designations`, `references`, `priorites`, `status`, `datecreation`, `livraison`,`user`) VALUES (NULL, ?, ?, ?, ?, ?, current_timestamp(),?,?);')");
            $insertUser->execute(array($quantites,$designations,$references,$priorites,$status,$livraison,$user));
            header('Location: acueilAdmin.php');
        }       
    }

?>
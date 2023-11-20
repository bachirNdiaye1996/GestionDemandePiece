<head>
    <!-- Sweet Alert -->
    <link href="./libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css"/>
        <script src="./libs/sweetalert2/sweetalert2.min.js"></script>

        
        <!--  Jquery-ui -->
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
        <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
        <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
</head>

<?php

    //session_start(); 

    #$servername = "mysql-boulangerie.alwaysdata.net";
    $username = "root";
    $password = "";
    $db_name = "maintenance";  

    $db = new PDO("mysql:host=localhost;dbname=$db_name;charset=utf8", $username, $password);
    $user = $_SESSION['nomcomplet'];


    $insertUser=$db->prepare("INSERT INTO `da` (`id`,`user`,`datecreation`) VALUES (NULL, ?, current_timestamp());");
    $insertUser->execute(array($user));

    //sleep(3);
    header('location:acueilAdmin.php');
    //sleep(3);
    //exit;       
?>
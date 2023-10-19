<?php   

    session_start();

    $username = "root";
    $password = "";
    $db_name = "maintenance";  

    $db = new PDO("mysql:host=localhost;dbname=$db_name;charset=utf8", $username, $password);
    if(isset($_POST['valide'])){
        if(!empty($_POST['message'])){
            $message=htmlspecialchars($_POST['message']);
            $nomcomplet=$_SESSION['nomcomplet'] ;
            $insertUser=$db->prepare("INSERT into `reclamations`(`id`,`message`,`user`,`datecreation`) value(NULL,?,?,current_timestamp())");
            $insertUser->execute(array($message,$nomcomplet));
            header('location:acueilAdmin.php');
            exit;
        }     
    }

?>



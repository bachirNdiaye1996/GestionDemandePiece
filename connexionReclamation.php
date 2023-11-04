<?php   

    session_start();

    $username = "root";
    $password = "";
    $db_name = "maintenance";  

    $db = new PDO("mysql:host=localhost;dbname=$db_name;charset=utf8", $username, $password);
    if(isset($_POST['valide'])){
        if(!empty($_POST['message'])){
            $message=htmlspecialchars($_POST['message']);
            $nomcomplet=$_SESSION['nomcomplet'];
            $nomDA=htmlspecialchars($_POST['nomDA']);
            if($_SESSION['niveau'] == 'kemc'){
                $insertUser=$db->prepare("INSERT into `reclamations`(`id`,`message`,`user`,`datecreation`,`nomda`,`actifkemb`) value(NULL,?,?,current_timestamp(),?,1)");
                $insertUser->execute(array($message,$nomcomplet,$nomDA));
                header('location:acueilAdmin.php');
                exit;
            }elseif($_SESSION['niveau'] == 'mang'){
                $insertUser=$db->prepare("INSERT into `reclamations`(`id`,`message`,`user`,`datecreation`,`nomda`,`actifmang`) value(NULL,?,?,current_timestamp(),?,1)");
                $insertUser->execute(array($message,$nomcomplet,$nomDA));
                header('location:acueilAdmin.php');
                exit;
            }
        }     
    }

?>



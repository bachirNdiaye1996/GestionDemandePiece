<?php   

    session_start();   

    #$servername = "mysql-boulangerie.alwaysdata.net";
    $username = "root";
    $password = "";
    $db_name = "maintenance";  

    $db = new PDO("mysql:host=localhost;dbname=$db_name;charset=utf8", $username, $password);
    if(isset($_POST['valide'])){
        if(!empty($_POST['username']) && !empty($_POST['password'])){
            $user=htmlspecialchars($_POST['username']);
            $password=sha1($_POST['password']);
            #$password=$_POST['password'];
            $recupeUser=$db->prepare('select * from utilisateur where matricule=? and password=?');
            $recupeUser->execute(array($user,$password));
            
            //print_r($recupeUser);
            if($recupeUser->rowCount() > 0){
                $Utilisateur = $recupeUser->fetch();
                //On définit des variables de session
                $_SESSION['matricule'] = $Utilisateur['matricule'];
                $_SESSION['nomcomplet'] = $Utilisateur['nomcomplet'];
                $_SESSION['niveau'] = $Utilisateur['niveau'];
                $_SESSION['email'] = $Utilisateur['email'];
                $_SESSION['user'] = $Utilisateur['user'];
                if($Utilisateur['niveau'] == 'admin'){
                    header('Location: acueilAdmin.php');
                    exit;
                }elseif($Utilisateur['niveau'] == 'kemc' || $Utilisateur['niveau'] == 'mang'){
                    header('Location: acueilAdmin.php');
                    exit;
                }else{
                    header('Location: acueil.php');
                    exit;
                }
                #header('Location: acueil.php');
                #exit;
            }
        }
    }

    /*
    $User1 = $db->prepare("select*from utilisateur");
    $User1->execute();
    $Utilisateur = $User1->fetchAll();
    echo $Utilisateur['username'];*/
?>



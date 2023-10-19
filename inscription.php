<?php 

    $username = "root";
    $password = "";
    $db_name = "maintenance";  
    $mess="";
    
    $db = new PDO("mysql:host=localhost;dbname=$db_name;charset=utf8", $username, $password);
    if(isset($_POST['valide'])){
        if(!empty($_POST['matricule']) && !empty($_POST['password']) && !empty($_POST['inlineRadioOptions'])  && !empty($_POST['email']) && !empty($_POST['nomcomplet'])){
            $matricule=htmlspecialchars($_POST['matricule']);
            $nomcomplet=htmlspecialchars($_POST['nomcomplet']);
            $niveau=htmlspecialchars($_POST['inlineRadioOptions']);
            $password=sha1($_POST['password']);
            $email=htmlspecialchars($_POST['email']);
            $insertUser=$db->prepare('insert into utilisateur(matricule,nomcomplet,niveau,password,email) value(?,?,?,?,?)');
            $insertUser->execute(array($matricule,$nomcomplet,$niveau,$password,$email));
            header('Location: utilisateur.php');
        }else {
            $mess = "error";
        }    
    }
?>


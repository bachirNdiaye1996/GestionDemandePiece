<?php 

    $username = "root";
    $password = "";
    $db_name = "maintenance";  
    $mess="";

    
    $db = new PDO("mysql:host=localhost;dbname=$db_name;charset=utf8", $username, $password);

    include 'mail.php';

    if(isset($_POST['valide'])){
        if(!empty($_POST['matricule']) && !empty($_POST['password']) && !empty($_POST['inlineRadioOptions'])  && !empty($_POST['email']) && !empty($_POST['nomcomplet'])){
            $matricule=htmlspecialchars($_POST['matricule']);
            $nomcomplet=htmlspecialchars($_POST['nomcomplet']);
            $niveau=htmlspecialchars($_POST['inlineRadioOptions']);
            $password=sha1($_POST['password']);
            $email=htmlspecialchars($_POST['email']);
            $insertUser=$db->prepare('insert into utilisateur(matricule,nomcomplet,niveau,password,email) value(?,?,?,?,?)');
            $insertUser->execute(array($matricule,$nomcomplet,$niveau,$password,$email));

            //$messageD=$_SESSION['nomcomplet']." vient de creer votre compte pour l'application de gestion des demandes de piéces avec comme nom d'utilisateur : $matricule et mot de passe : $_POST[password] ".' Merci! <a href="http://localhost/GestionDemandePiece">Cliquez ici pour y acceder.</a>';
            $messageD = "
            <html>
            <head>
            <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
                <title>Nouveau compte</title>
            </head>
            <body>
                <div id='email-wrap' style='background: #33ECFF;color: #FFF; border-radius: 10px;'>
                    <p align='center'>
                    <img src='https://bootstrapemail.com/img/icons/logo.png' alt='' width=72 height=72>
                
                    <h3 align='center'>METAL AFRIQUE EMAIL</h3>
                
                    <p align='center'>$_SESSION[nomcomplet] vient de creer votre compte pour l'application de gestion des demandes de piéces avec comme :</p>
                    <p align='center'>Nom d'utilisateur : <strong> $matricule </strong></p>
                    <p align='center'>Mot de passe : <strong>$_POST[password]</strong></p>
                    <p align='center'>Vous pouvez modifier ces parametres.</p>
                    <p align='center'><a href='http://localhost/GestionDemandePiece'>Cliquez ici pour y acceder.</a></p>
                    </p>
                    <br>
                </div>
            </body>
            </html>
                ";
            //foreach($articlMails as $article){
                //if(($article['niveau'] != 'kemc')){
            envoie_mail($_SESSION['nomcomplet'],$_POST['email'],'Nouveau compte',$messageD);
                //}
            //}

            header('Location: utilisateur.php');
        }else {
            $mess = "error";
        }    
    }
?>


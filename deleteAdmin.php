<?php
    include "connexion.php";
    include 'mail.php';

    if(isset($_GET['id']) && $_SESSION['niveau']=="mang"){
        $id = $_GET['id'];
        $sql = "UPDATE `articles` SET `actifmang`=1 where id=$id";
        $db->query($sql);

        header("location:acueilAdmin1.php?id=$id");
        exit;
    }elseif(isset($_GET['id']) && $_SESSION['niveau']=="kemc"){
        unset($_GET['mess']); 
        if(isset($_GET['id'])){
            $id = $_GET['id'];
            $idda = $_GET['idda'];
            
            $sql = "UPDATE `articles` SET `actifkemb`=1 where id=$id";
            $db->query($sql);

            //Pour suprimer l'incrementation dans les DA
            $sql = "UPDATE `da` SET  `statusattente` = statusattente - 1 where id=$idda";
            $db->query($sql);
        
            header("location:acueilAdmin1.php?id=$idda");
            exit;
        }
    }
    //print_r($_GET['idr']);
    if(isset($_GET['idr']) && isset($_GET['quantites']) && isset($_GET['partielle']) && isset($_GET['livraisonPart']) && $_SESSION['niveau']=="kemc"){
        $id = $_GET['idr'];
        $idda = $_GET['idda'];
        $livraison = $_GET['livraisonPart'];
        $status = "livraison partielle";
        $reslt = $_GET['quantites'] - $_GET['livraisonPart'];
        if($reslt>0){
            $id1 = $_GET['idr'];
            $idda1 = $_GET['idda'];

            
            $sql2 = "SELECT * FROM `articles` where `id`=$id1;";
        
            // On prépare la requête
            $query2 = $db->prepare($sql2);

            // On exécute
            $query2->execute();

            // On récupère le nombre d'articles
            $result2 = $query2->fetch();
            

            if($result2['livraison'] == 0){
                $insertUser=$db->prepare("UPDATE da SET  statuspartielle = statuspartielle + 1 where id=?");
                //$reqtitre = $db->prepare($sql);
                $insertUser->execute(array($idda1));
            }

            $sql = "UPDATE `articles` SET `actifmang`=0, `datelivapprouv`=current_timestamp(),`status`=?, `quantites`=?,`livraison`=? where id=?";
            //$db->query($sql);
            $reqtitre = $db->prepare($sql);
            $reqtitre->execute(array($status,$reslt,$livraison,$id));

            $insertUser=$db->prepare("UPDATE da SET  datelivraison=current_timestamp() where id=?");
            //$reqtitre = $db->prepare($sql);
            $insertUser->execute(array($_GET['idda']));

            /*//$idd=$_GET['idda'];
            $insertUser1=$db->prepare("UPDATE da SET  statusattente = statusattente - 1 where id=?");
            //$reqtitre = $db->prepare($sql);
            $insertUser1->execute(array($idda));*/
        }elseif($reslt == 0){
            $id1 = $_GET['idr'];
            $idda1 = $_GET['idda'];

            
            $sql2 = "SELECT * FROM `articles` where `id`=$id1;";
        
            // On prépare la requête
            $query2 = $db->prepare($sql2);

            // On exécute
            $query2->execute();

            // On récupère le nombre d'articles
            $result2 = $query2->fetch();
            

            if($result2['livraison'] != 0){
                //$idd=$_GET['idda'];
                $insertUser=$db->prepare("UPDATE da SET  statuspartielle = statuspartielle - 1 where id=?");
                //$reqtitre = $db->prepare($sql);
                $insertUser->execute(array($idda));
            }
            //$status="Terminé";
            $sql = "UPDATE `articles` SET `actifmang`=0, `datelivapprouv`=current_timestamp(), `quantites`=?,  `status`='Terminé',`livraison`=? where id=?";
            //$db->query($sql);
            $reqtitre = $db->prepare($sql);
            $reqtitre->execute(array($reslt,$livraison,$id));

            $insertUser=$db->prepare("UPDATE da SET  statustermine = statustermine + 1 where id=?");
            //$reqtitre = $db->prepare($sql);
            $insertUser->execute(array($idda));

            //$idd=$_GET['idda'];
            $insertUser=$db->prepare("UPDATE da SET  statusattente = statusattente - 1 where id=?");
            //$reqtitre = $db->prepare($sql);
            $insertUser->execute(array($idda));

            $insertUser=$db->prepare("UPDATE da SET  datelivraison=current_timestamp() where id=?");
            //$reqtitre = $db->prepare($sql);
            $insertUser->execute(array($_GET['idda']));
        }
        
        //$messageD=$_SESSION['nomcomplet']." vient d'accepter la livraison de piéces de la DA00".$idda. ` Merci! <a href="http://localhost/GestionDemandePiece">Acceder ici.</a> `;
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
                
                    <p align='center'>$_SESSION[nomcomplet] vient d'accepter la livraison de piéces dans la DA00$idda.</p>
                    <p align='center'><a href='http://localhost/GestionDemandePiece'>Cliquez ici pour y acceder.</a></p>
                    </p>
                    <br>
                </div>
            </body>
            </html>
                ";
        foreach($articlMails as $article){
            if(($article['niveau'] != 'kemc')){
                envoie_mail($article['nomcomplet'],$article['email'],'Livraison acceptee',$messageD);
            }
        }

        header("location:commandeAapprouver.php");
        exit;
    }
    if(isset($_GET['idr'])  && isset($_GET['quantites']) && isset($_GET['livraisonPart']) && $_SESSION['niveau']=="kemc"){
        $id = $_GET['idr'];
        $idda = $_GET['idda'];
        $livraison = $_GET['livraisonPart'];
        $status = "livraison partielle";
        $reslt = $_GET['quantites'] - $_GET['livraisonPart'];
        if($reslt>0){
            $id1 = $_GET['idr'];
            $idda1 = $_GET['idda'];

            
            $sql2 = "SELECT * FROM `articles` where `id`=$id1;";
        
            // On prépare la requête
            $query2 = $db->prepare($sql2);

            // On exécute
            $query2->execute();

            // On récupère le nombre d'articles
            $result2 = $query2->fetch();
            

            if($result2['livraison'] == 0){
                $insertUser=$db->prepare("UPDATE da SET  statuspartielle = statuspartielle + 1 where id=?");
                //$reqtitre = $db->prepare($sql);
                $insertUser->execute(array($idda1));
            }

            $sql = "UPDATE `articles` SET `actifmang`=0, `datelivapprouv`=current_timestamp(),`status`=?, `quantites`=?,`livraison`=? where id=?";
            //$db->query($sql);
            $reqtitre = $db->prepare($sql);
            $reqtitre->execute(array($status,$reslt,$livraison,$id));

            /*//$idd=$_GET['idda'];
            $insertUser1=$db->prepare("UPDATE da SET  statusattente = statusattente - 1 where id=?");
            //$reqtitre = $db->prepare($sql);
            $insertUser1->execute(array($idda));*/

            $insertUser=$db->prepare("UPDATE da SET  datelivraison=current_timestamp() where id=?");
            //$reqtitre = $db->prepare($sql);
            $insertUser->execute(array($_GET['idda']));
        }elseif($reslt == 0){
            $id1 = $_GET['idr'];
            $idda1 = $_GET['idda'];

            
            $sql2 = "SELECT * FROM `articles` where `id`=$id1;";
        
            // On prépare la requête
            $query2 = $db->prepare($sql2);

            // On exécute
            $query2->execute();

            // On récupère le nombre d'articles
            $result2 = $query2->fetch();
            

            if($result2['livraison'] != 0){
                //$idd=$_GET['idda'];
                $insertUser=$db->prepare("UPDATE da SET  statuspartielle = statuspartielle - 1 where id=?");
                //$reqtitre = $db->prepare($sql);
                $insertUser->execute(array($idda));
            }
            //$status="Terminé";
            $sql = "UPDATE `articles` SET `actifmang`=0, `datelivapprouv`=current_timestamp(), `quantites`=?,  `status`='Terminé',`livraison`=? where id=?";
            //$db->query($sql);
            $reqtitre = $db->prepare($sql);
            $reqtitre->execute(array($reslt,$livraison,$id));

            $insertUser=$db->prepare("UPDATE da SET  statustermine = statustermine + 1 where id=?");
            //$reqtitre = $db->prepare($sql);
            $insertUser->execute(array($idda));

            //$idd=$_GET['idda'];
            $insertUser=$db->prepare("UPDATE da SET  statusattente = statusattente - 1 where id=?");
            //$reqtitre = $db->prepare($sql);
            $insertUser->execute(array($idda));

            $insertUser=$db->prepare("UPDATE da SET  datelivraison=current_timestamp() where id=?");
            //$reqtitre = $db->prepare($sql);
            $insertUser->execute(array($_GET['idda']));

        }
        //$messageD=$_SESSION['nomcomplet']." vient d'accepter la livraison de frais et services de la DA00".$idda. ' Merci! <a href="http://localhost/GestionDemandePiece">Acceder ici.</a>';
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
                
                    <p align='center'>$_SESSION[nomcomplet] vient d'accepter la livraison de frais et services dans la DA00$idda.</p>
                    <p align='center'><a href='http://localhost/GestionDemandePiece'>Cliquez ici pour y acceder.</a></p>
                    </p>
                    <br>
                </div>
            </body>
            </html>
                ";
        foreach($articlMails as $article){
            if(($article['niveau'] != 'kemc')){
                envoie_mail($article['nomcomplet'],$article['email'],'Livraison acceptee',$messageD);
            }
        }

        header("location:commandeAapprouver1.php");
        exit;
    }
    if(isset($_GET['idreg']) && $_SESSION['niveau']=="kemc"){
        $id = $_GET['idreg'];
        $idda = $_GET['idda'];
        $sql = "UPDATE `articles` SET `rege`=1, `actifmang`=0 where id=$id";
        $db->query($sql);

        //$messageD=$_SESSION['nomcomplet']." vient de rejeter la livraison de la DA00".$idda. ' Merci! <a href="http://localhost/GestionDemandePiece">Acceder ici.</a>';
        $messageD = "
            <html>
            <head>
            <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
                <title>Livraison rejetee</title>
            </head>
            <body>
                <div id='email-wrap' style='background: #33ECFF;color: #FFF; border-radius: 10px;'>
                    <p align='center'>
                    <img src='https://bootstrapemail.com/img/icons/logo.png' alt='' width=72 height=72>
                
                    <h3 align='center'>METAL AFRIQUE EMAIL</h3>
                
                    <p align='center'>$_SESSION[nomcomplet] vient de rejeter la livraison dans la DA00$idda.</p>
                    <p align='center'><a href='http://localhost/GestionDemandePiece'>Cliquez ici pour y acceder.</a></p>
                    </p>
                    <br>
                </div>
            </body>
            </html>
                ";
        foreach($articlMails as $article){
            if(($article['niveau'] != 'kemc')){
                envoie_mail($article['nomcomplet'],$article['email'],'Livraison rejetee',$messageD);
            }
        }

        header("location:commandeAapprouver.php");
        exit;
    }
?>
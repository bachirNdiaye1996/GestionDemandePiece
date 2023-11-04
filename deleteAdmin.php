<?php
    include "connexion.php";
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
    
            header("location:acueilAdmin1.php?id=$idda");
            exit;
        }
    }
    //print_r($_GET['idr']);
    if(isset($_GET['idr']) && isset($_GET['quantites']) && isset($_GET['livraisonPart']) && $_SESSION['niveau']=="kemc"){
        $id = $_GET['idr'];
        $livraison = $_GET['livraisonPart'];
        $status = $_GET['status'];
        $reslt = $_GET['quantites'] - $_GET['livraisonPart'];
        if($reslt>0){
            $sql = "UPDATE `articles` SET `actifmang`=0, datelivraison=current_timestamp(),`status`=?, `quantites`=?,`livraison`=? where id=?";
            //$db->query($sql);
            $reqtitre = $db->prepare($sql);
            $reqtitre->execute(array($status,$reslt,$livraison,$id));
        }elseif($reslt == 0){
            //$status="Terminé";
            $sql = "UPDATE `articles` SET `actifmang`=0, datelivraison=current_timestamp(), `quantites`=$reslt,  `status`='Terminé',`livraison`=$livraison where id=$id";
            //$db->query($sql);
            $reqtitre = $db->prepare($sql);
            $reqtitre->execute(array($reslt,$livraison,$id));
        }

        header("location:commandeAapprouver.php");
        exit;
    }
    if(isset($_GET['idr']) && $_SESSION['niveau']=="kemc"){
        $id = $_GET['idr'];
        $status = $_GET['status'];
        $sql = "UPDATE `articles` SET `actifmang`=0, datelivraison=current_timestamp(),`status`=? where id=?";
        //$db->query($sql);
        $reqtitre = $db->prepare($sql);
        $reqtitre->execute(array($status,$id));

        header("location:commandeAapprouver1.php");
        exit;
    }
    if(isset($_GET['idreg']) && $_SESSION['niveau']=="kemc"){
        $id = $_GET['idreg'];
        $sql = "UPDATE `articles` SET `rege`=1, `actifmang`=0 where id=$id";
        $db->query($sql);

        header("location:commandeAapprouver.php");
        exit;
    }
?>
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
        $reslt = $_GET['quantites'] - $_GET['livraisonPart'];
        if($reslt>0){
            $sql = "UPDATE `articles` SET `actifmang`=0, `quantites`=$reslt,`livraison`=$livraison where id=$id";
            $db->query($sql);
        }else{
            //$status="Terminé";
            $sql = "UPDATE `articles` SET `actifmang`=0, `quantites`=$reslt,  `status`='Terminé',`livraison`=$livraison where id=$id";
            $db->query($sql);
        }

        header("location:commandeAapprouver.php");
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
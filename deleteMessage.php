<?php
    include "connexion.php";
    if(isset($_GET['id']) && $_SESSION['niveau'] == 'admin'){
        $id = $_GET['id'];
        $sql = "DELETE from `reclamations` where id=$id";
        $db->query($sql);
    }
    if(isset($_GET['id']) && $_SESSION['niveau'] == 'kemc'){
        echo $_GET['idkemb'];
        $id = $_GET['id'];
        $sql = "UPDATE `reclamations` set actifmang=0 where id=$id";
        $db->query($sql);
    }
    if(isset($_GET['id']) && $_SESSION['niveau'] == 'mang'){
        $id = $_GET['id'];
        $sql = "UPDATE `reclamations` set actifkemb=0 where id=$id";
        $db->query($sql);
    }
    header('location:reclamation.php');
    exit;
?>
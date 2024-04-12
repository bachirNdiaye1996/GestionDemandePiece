<?php
    include "connexion.php";
    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $idda = $_GET['idda'];

        $sql = "UPDATE `articles` set `actif`=0 where id=$id";
        $db->query($sql);
    }
    header('location:acueilAdmin1.php');
    exit;
?>
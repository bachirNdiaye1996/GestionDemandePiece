<?php
    include "connexion.php";
    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $sql = "DELETE from `demandeur` where id=$id";
        $db->query($sql);
    }
    header('location:listeDemandeur.php');
    exit;
?>
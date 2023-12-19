<?php
    include "connexion.php";
    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $sql = "DELETE from `transporteur` where id=$id";
        $db->query($sql);
    }
    header('location:listeTransporteur.php');
    exit;
?>
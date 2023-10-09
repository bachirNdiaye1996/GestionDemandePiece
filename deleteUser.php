<?php
    include "connexion.php";
    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $sql = "DELETE from `utilisateur` where id=$id";
        $db->query($sql);
    }
    header('location:utilisateur.php');
    exit;
?>
<?php
    include "connexion.php";
    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $sql = "UPDATE `articles` set `status`='termine' where id=$id";
        $db->query($sql);
    }
    header('location:commandeCours.php');
    exit;
?>
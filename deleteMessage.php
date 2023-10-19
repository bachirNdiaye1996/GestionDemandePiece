<?php
    include "connexion.php";
    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $sql = "DELETE from `reclamations` where id=$id";
        $db->query($sql);
    }
    header('location:reclamation.php');
    exit;
?>
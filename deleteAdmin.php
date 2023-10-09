<?php
    include "connexion.php";
    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $sql = "DELETE from `articles` where id=$id";
        $db->query($sql);
    }
    header('location:acueilAdmin.php');
    exit;
?>
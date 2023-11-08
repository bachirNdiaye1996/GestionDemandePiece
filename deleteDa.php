<!Doctype html>
<html>
    <head>
    <link href="./libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css"/>
    <script src="./libs/sweetalert2/sweetalert2.min.js"></script>
    </head>
    <body>
        <?php
        include "connexion.php";
        if((isset($_GET['idda']) && $_SESSION['niveau']=="kemc") || (isset($_GET['idda']) && $_SESSION['niveau']=="admin")){
            $id = $_GET['idda'];
            $sql = "UPDATE `da` set `actif`=0, datelivraison=current_timestamp() where id=$id";
            $db->query($sql);
            $sql2 = "UPDATE `articles` SET `actif`=0 where idda=$id";
            $db->query($sql2);
            $sql2 = "UPDATE `articles` SET `actif`=0 where idda=$id";
            $db->query($sql2);
            header("location:acueilAdmin.php?id=$id");
            exit;
        }elseif(isset($_GET['idda']) && $_SESSION['niveau']=="mang"){
            $id = $_GET['idda'];
            $sql = "UPDATE `da` SET `actifmang`=1 where id=$id";
            $db->query($sql);

            header("location:acueilAdmin.php?id=$id");
            exit;
        }
        ?>
    </body>
</html>
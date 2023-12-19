<?php
  include "connexion.php";
  $matricule="";
  $nomcomplet="";
  $email="";
  $niveau="";
  $password="";
  




  $error="";
  $success="";

  if($_SERVER["REQUEST_METHOD"]=='GET' && ($_SESSION['niveau'] == 'admin') ){
    if(!isset($_GET['id']) && !isset($_GET['matricule'])){
      header("location:acueilAdmin.php");
      exit;
    }
    if(isset($_GET['id'])){
      $id = $_GET['id'];
      $sql = "select * from utilisateur where id=$id";
      $result = $db->query($sql);
      $row = $result->fetch();
      while(!$row){
        header("location: acueilAdmin.php");
        exit;
      }
      $matricule=$row['matricule'];
      $nomcomplet=$row["nomcomplet"];
      $niveau=$row["niveau"];
      $email=$row["email"];
      $password="";
    }elseif(isset($_GET['matricule'])){
      $id = $_GET['matricule'];
      $sql = "select * from utilisateur where matricule=$id";
      $result = $db->query($sql);
      $row = $result->fetch();
      while(!$row){
        header("location: acueilAdmin.php");
        exit;
      }
      $matricule=$row['matricule'];
      $nomcomplet=$row["nomcomplet"];
      $niveau=$row["niveau"];
      $email=$row["email"];
      $password="";
    }

  }
  elseif($_SERVER["REQUEST_METHOD"] !='GET' && ($_SESSION['niveau'] == 'admin')){
    $id = $_POST["id"];
    $matricule=$_POST['matricule'];
    $nomcomplet=$_POST["nomcomplet"];
    $niveau=$_POST["inlineRadioOptions"];
    $email=$_POST["email"];
    $password=sha1($_POST['password']);

    $sql = "update utilisateur set matricule='$matricule', nomcomplet='$nomcomplet', password='$password', niveau='$niveau', email='$email' where id='$id'";
    $result = $db->query($sql);  
    header("location: utilisateur.php");
    exit;  
  }

  //Pour un autre user different de l'admin

  if($_SERVER["REQUEST_METHOD"]=='GET' && ($_SESSION['niveau'] != 'admin')){
    if(!isset($_GET['matricule'])){
      header("location:acueilAdmin.php");
      exit;
    }
    $matricule = $_GET['matricule'];
    $sql = "select * from utilisateur where matricule=$matricule";
    $result = $db->query($sql);
    $row = $result->fetch();
    while(!$row){
      header("location: acueilAdmin.php");
      exit;
    }
    $matricule=$row['matricule'];
    $nomcomplet=$row["nomcomplet"];
    //$niveau=$row["niveau"];
    $email=$row["email"];
    $password="";

  }
  elseif($_SERVER["REQUEST_METHOD"] !='GET' && ($_SESSION['niveau'] != 'admin')){
    $id = $_POST["id"];
    $matricule=$_POST['matricule'];
    $nomcomplet=$_POST["nomcomplet"];
    //$niveau=$_POST["inlineRadioOptions"];
    $email=$_POST["email"];
    $password=sha1($_POST['password']);

    $sql = "update utilisateur set matricule='$matricule', nomcomplet='$nomcomplet', password='$password', email='$email' where matricule='$matricule'";
    $result = $db->query($sql);  
    header("location: acueilAdmin.php");
    exit;  
  }
  
?>

<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="images/favicon.ico">

            <!-- plugin css -->
            <link href="css/jsvectormap/jsvectormap.min.css" rel="stylesheet" type="text/css" />

            <!-- gridjs css -->
            <link  href="libs/gridjs/theme/mermaid.min.css" rel="stylesheet">

            <!-- Bootstrap Css -->
            <link href="css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
            <!-- Icons Css -->
            <link href="css/icons.min.css" rel="stylesheet" type="text/css" />
            <!-- App Css-->
            <link href="css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
            
            <!-- Style Css-->
            <link href="css/style.css" rel="stylesheet" type="text/css" />

            <!-- App favicon -->
            <link rel="shortcut icon" href="image/iconOnglet.png" />
          <style>
            /* Compte utilisateur */
            @import url("https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap");

            body {
              margin: 0px;
              padding: 0px;
              background-color: #fef1df;
              position: absolute;
              top: 50%;
              left: 50%;
              transform: translate(-50%, -50%);
              font-family: "Roboto", sans-serif;
              font-weight: bold;
            }

            form {
              padding: 30px;
              background-color: white;
              border-radius: 10px;
            }
            form h1 {
              font-size: 20px;
            }
            form .separation {
              width: 100%;
              height: 1px;
              background-color: #747cdf;
            }
            form .corps-formulaire {
              display: flex;
              flex-wrap: wrap;
              margin-bottom: 30px;
            }
            form .corps-formulaire .groupe {
              position: relative; /* Pour mettre positionner l’élément dans le flux normal de la page */
              margin-top: 20px;
              display: flex;
              flex-direction: column;
            }
            form .corps-formulaire .gauche .groupe input {
              margin-top: 5px;
              padding: 10px 5px 10px 30px;
              border: 1px solid #c9c9c9;
              outline-color: #747cdf;
              border-radius: 5px;
            }
            form .corps-formulaire .gauche .groupe i {
              position: absolute; /* positionné par rapport à son parent le plus proche positionné */
              left: 0;
              top: 25px;
              padding: 9px 8px;
              color: #747cdf;
            }
            form .corps-formulaire .droite {
              margin-left: 40px;
            }
            form .corps-formulaire .droite .groupe {
              height: 100%;
            }
            form .corps-formulaire .droite .groupe textarea {
              margin-top: 5px;
              padding: 10px;
              background-color: #f1f1f1;
              border: 2px solid #747cdf;
              outline: none;
              border-radius: 5px;
              resize: none;
              height: 72%;
            }
            form .pied-formulaire button {
              margin-top: 10px;
              background-color: #747cdf;
              color: white;
              font-size: 15px;
              border: none;
              padding: 10px 20px;
              border-radius: 5px;
              outline: none;
              cursor: pointer;
              transition: transform 0.5s;
            }
            form .pied-formulaire button:hover {
              transform: scale(1.05);
            }

            @media screen and (max-width: 920px) {
              form .corps-formulaire .droite {
                margin-left: 0px;
              }
            }
          </style>
        <title>METAL AFRIQUE</title>
    </head>
  <body>
    <form action="#" method="POST">
      <?php 
        if(($_SESSION['niveau'] == 'admin') && !isset($_GET['id']) && !isset($_GET['matricule'])){
      ?>
      <h1>Ajouter un utilisateur</h1>
      <?php 
        }elseif(isset($_GET['matricule']) || isset($_GET['id'])){
      ?>
      <h1>Modifier mon compte</h1>
      <?php 
        }
      ?>
      <div class="separation"></div>
      <div class="corps-formulaire">
        <div class="gauche">
        <input type="hidden" name="id" value="<?php echo $id; ?>" class="form-control"> <br>
          <div class="groupe">
            <label>Nom complet :</label>
            <input type="text" style="width:400px;" autocomplete="off" value="<?php echo $nomcomplet; ?>" placeholder="Entrer le nom complet de l'utilisateur" name="nomcomplet"/>
            <i class="fas fa-user"></i>
          </div>
          <div class="groupe">
            <label>Matricule :</label>
            <input type="text" autocomplete="off" value="<?php echo $matricule; ?>" placeholder="Entrer son matricule" name="matricule"/>
            <i class="fas fa-mobile"></i>
          </div>
          <div class="groupe">
            <label>Mot de passe :</label>
            <input type="text" autocomplete="off" placeholder="definir le mot de passe par défaut" name="password"/>
            <i class="fas fa-mobile"></i>
          </div>
          <?php 
            if($_SESSION['niveau'] == 'admin'){
          ?>
          <div class="groupe1">
            <label>Status de l'utilisateur :</label></br>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="maint">
                  <label class="form-check-label" for="inlineRadio1">Maintenance</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="mang">
                  <label class="form-check-label" for="inlineRadio2">Mangasin</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio3" value="kemc">
                  <label class="form-check-label" for="inlineRadio3">Kemba Cisse</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio4" value="admin">
                  <label class="form-check-label" for="inlineRadio4">Administrateur</label>
                </div>
          </div>
          <?php 
            }
          ?>
          <div class="groupe">
            <label>E-mail :</label>
            <input type="text" autocomplete="off" value="<?php echo $email; ?>" placeholder="Entrer son adresse e-mail" name="email"/>
            <i class="fas fa-envelope"></i>
          </div>
        </div>
        <?php 
            if($_SESSION['niveau'] == 'admin'){
        ?>
        <div class="droite">
          <div class="groupe">
            <label>Message pour l'utilisateur</label>
            <textarea placeholder="Taper un message." name="message"></textarea>
          </div>
        </div>
        <?php 
            }
          ?>
      </div>
        <div class="col-md-8 align-items-center">
            <div class="d-flex gap-2 pt-4">
              <?php 
                if($_SESSION['niveau'] == 'admin'){
              ?>
                <a href="utilisateur.php"><input class="btn btn-danger  w-lg" name="" value="Annuler"></a>
              <?php 
                }else{
              ?>
                <a href="acueilAdmin.php"><input class="btn btn-danger  w-lg" name="" value="Annuler"></a>
              <?php 
                }
              ?>
              <input class="btn btn-success  w-lg bouton" name="valide" type="submit" value="Enregistrer">
            </div>
        </div> 
    </form>
  </body>
</html>
<?php
    session_start();
    $username = "root";
    $password = "";
    $db_name = "maintenance";  
    $mess="";

    
    $db = new PDO("mysql:host=localhost;dbname=$db_name;charset=utf8", $username, $password);

    include 'mail.php';

    if(isset($_POST['valide'])){
        if(!empty($_POST['matricule']) && !empty($_POST['nomcomplet'])){
            $matricule=htmlspecialchars($_POST['matricule']);
            $nomcomplet=htmlspecialchars($_POST['nomcomplet']);
            //$email=htmlspecialchars($_POST['email']);
            $insertUser=$db->prepare('insert into transporteur(matricule,nomcomplet) value(?,?)');
            $insertUser->execute(array($matricule,$nomcomplet));

            header('Location: listeTransporteur.php');
        }else {
            $mess = "error";
        }    
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
          <!-- Style Css-->
          <link href="./style.css" id="app-style" rel="stylesheet" type="text/css" />
        <title>METAL AFRIQUE</title>
    </head>
  <body>
    <form action="#" method="POST">
      <h1>Ajouter un transporteur</h1>
      <div class="separation"></div>
      <div class="corps-formulaire">
        <div class="gauche">
          <div class="groupe">
            <label>Nom complet :</label>
            <input style="width:400px;" type="text" autocomplete="off" placeholder="Entrer nom complet du transporteur" name="nomcomplet"/>
            <i class="fas fa-user"></i>
          </div>
          <div class="groupe">
            <label>Matricule voiture :</label>
            <input type="text" style="width:400px;" autocomplete="off" placeholder="Entrer matricule de la voiture" name="matricule"/>
            <i class="fas fa-mobile"></i>
          </div>
        </div>

        <div class="droite">
          <div class="groupe">
            <label>Message pour l'utilisateur</label>
            <textarea placeholder="Taper un message." name="message"></textarea>
          </div>
        </div>
      </div>
        <div class="col-md-8 align-items-center">
           <?php if($mess == "error"){ ?> <h4 style="color:red; margin-buttom:50px;" id="rejet">Erreur remplir tous les champs svp</h4><?php } ?>
            <div class="d-flex gap-2 pt-4">
              <a href="listeTransporteur.php"><input class="btn btn-danger  w-lg" name="" value="Annuler"></a>
              <input class="btn btn-success  w-lg bouton" name="valide" type="submit" value="Enregistrer">
            </div>
        </div> 
    </form>
  </body>
</html>
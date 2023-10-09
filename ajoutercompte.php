<?php
    session_start();
    include 'inscription.php';
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    require './PHPMailer/src/Exception.php';
    require './PHPMailer/src/PHPMailer.php';
    require './PHPMailer/src/SMTP.php';
    //Create an instance; passing `true` enables exceptions
    if(isset($_POST['valide'])){
      $dest=htmlspecialchars($_POST['email']);
      $objet="Les indentifications d'acces.";
      $message=htmlspecialchars($_POST['message']."\n"."Se connecter avec les identifiants suivants :\n"."Username : ".$_POST['matricule']."\nPassword : ".$_POST['password']);
      $entetes="From : ".$_SESSION['email'];
      $entetes.="Cc: http://localhost/GestionDemandePiece/";
      $entetes.="Content-Type: text/html; charset=iso-8859-1";
      
      if(mail($dest,$objet,$message,$entetes))
         echo "Mail envoyé avec succès.";
      else
         echo "Un problème est survenu.";
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
      <h1>Ajouter un utilisateur</h1>
      <div class="separation"></div>
      <div class="corps-formulaire">
        <div class="gauche">
          <div class="groupe">
            <label>Nom complet :</label>
            <input type="text" autocomplete="off" placeholder="Entrer le nom complet de l'utilisateur" name="nomcomplet"/>
            <i class="fas fa-user"></i>
          </div>
          <div class="groupe">
            <label>Matricule :</label>
            <input type="text" autocomplete="off" placeholder="Entrer son matricule" name="matricule"/>
            <i class="fas fa-mobile"></i>
          </div>
          <div class="groupe">
            <label>Mot de passe :</label>
            <input type="text" autocomplete="off" placeholder="definir le mot de passe par défaut" name="password"/>
            <i class="fas fa-mobile"></i>
          </div>
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
          <div class="groupe">
            <label>E-mail :</label>
            <input type="email" autocomplete="off" placeholder="Entrer son adresse e-mail" name="email"/>
            <i class="fas fa-envelope"></i>
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
            <div class="d-flex gap-2 pt-4">
              <a href="utilisateur.php"><input class="btn btn-danger  w-lg" name="" value="Annuler"></a>
              <input class="btn btn-success  w-lg bouton" name="valide" type="submit" value="Enregistrer">
            </div>
        </div> 
    </form>
  </body>
</html>
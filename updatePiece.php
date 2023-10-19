<?php

include "connexion.php";
$quantites="";
$designations="";
$reference="";
$priorites="";
$namefile="";





$error="";
$success="";
$idda="";

if($_SERVER["REQUEST_METHOD"]=='GET' && ($_SESSION['niveau'] == 'kemc') ){
  if(!isset($_GET['id'])){
    header("location:acueilAdmin.php");
    exit;
  }
    $id = $_GET['id'];
    $idda = $_GET['idda'];
    $sql = "select * from articles where id=$id";
    $result = $db->query($sql);
    $row = $result->fetch();
    while(!$row){
      header("location: acueilAdmin.php");
      exit;
    }
    $quantites=$row['quantites'];
    $designations=$row['designations'];
    $reference=$row['references'];
    $priorites=$row['priorites'];
    $namefile=$row['namefile'];
}else{
    $idda = $_GET['idda'];
    $id = $_GET['id'];
    $quantites=$_POST['quantites'];
    $designations=$_POST['designations'];
    $reference=$_POST['references'];
    $priorites=$_POST['priorites'];
    
    
    //-------Gestion fichier
                $uploaddir = 'fichiers/';
                $uploadfile = $uploaddir . basename($_FILES['file']['name']);
        
                echo '<pre>';
                    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
                        //echo "File is valid, and was successfully uploaded.\n";
                    } else {
                        //echo "Possible file upload attack!\n";
                    }
                print "</pre>";                            
    //-------Gestion fichier
    $namefile=htmlspecialchars($_FILES['file']['name']);



  $sql = "UPDATE `articles` SET `quantites`='$quantites', `designations`='$designations', `references`='$reference', `priorites`='$priorites', `namefile`='$namefile' where id=$id;";
  $result = $db->query($sql);  
  header("location: acueilAdmin1.php?id=$idda");
  exit;  
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="images/favicon.ico">

        <!-- plugin css -->
        <link href="css/jsvectormap/jsvectormap.min.css" rel="stylesheet" type="text/css" />

        <!-- Sweet Alert -->
        <link href="./libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css"/>
        <script src="./libs/sweetalert2/sweetalert2.min.js"></script>

        <!--  Jquery-ui -->
        <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="/resources/demos/style.css">
        <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
        <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>


        <!-- gridjs css -->
        <link  href="libs/gridjs/theme/mermaid.min.css" rel="stylesheet">

        <!-- Bootstrap Css -->
        <link href="css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="css/icons.min.css" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
        
        <!-- Style Css-->

        <link href="./style.css" id="app-style" rel="stylesheet" type="text/css" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="./image/iconOnglet.png" />
    <title>METAL AFRIQUE</title>
    <script>
    $( function() {
        var availableTags = [
        "roulement 6305",
        "roulement 6206",
        "pompe injection fourchette N°1 a reparer",
        "roulement 6210",
        "roulement NU-209",
        "impedeur Ø18",
        "pince a souder",
        "metre cube de bois poutrelle 6x8 de 4m",
        "chalumeau soudeur",
        "vis BTR 12x50",
        "vis tete fraisée BTR 5x20",
        "dynamometre portée max 6t",
        "dynamometre portée max 3,2t",
        "fut de graisse MULTIS EP2",
        "comparateur",
        "palier UCP-205",
        "raccord union droit de 12M-20",
        "metre rond inox Ø12",
        ];
        $( ".designa" ).autocomplete({
        source: availableTags
        });
    } );
    </script>
    <script>
    $( function() {
        var availableTag = [
        "roulement 6305",
        "roulement 6206",
        "pompe injection fourchette N°1 a reparer",
        "roulement 6210",
        "roulement NU-209",
        "impedeur Ø18",
        "pince a souder",
        "metre cube de bois poutrelle 6x8 de 4m",
        "chalumeau soudeur",
        "vis BTR 12x50",
        "vis tete fraisée BTR 5x20",
        "dynamometre portée max 6t",
        "dynamometre portée max 3,2t",
        "fut de graisse MULTIS EP2",
        "comparateur",
        "palier UCP-205",
        "raccord union droit de 12M-20",
        "metre rond inox Ø12",
        ];
        $( ".ref" ).autocomplete({
        source: availableTag
        });
    } );
    </script>
    </head>
    <body style="background-color: #fef1df;">
                        <div class="modal-dialog modal-xl modal-dialog-centered" style="margin-top:170px">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="myExtraLargeModalLabel">Modifier cette commande</h5>
                                </div>
                                <div class="modal-body">
                                    <form action="#" method="POST" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-6 text-start">
                                                    <label class="form-label fw-bold" for="nom">Quantités</label>
                                                    <input class="form-control" type="number" name="quantites" value="<?php echo $quantites; ?>" id="example-date-in" placeholder="Donner la quantité">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3 text-start">
                                                    <label class="form-label fw-bold" for="prenom" >Désignations</label>
                                                    <input class="form-control designa" type="text" value="<?php echo $designations; ?>" name="designations" id="example-dat"  placeholder="Taper la designation">
                                                </div>

                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3 text-start">
                                                    <label class="form-label fw-bold" for="reference" >Références</label>
                                                    <input class="form-control" type="text" value="<?php echo $quantites; ?>" name="references" id="example-da"  placeholder="Taper la reference">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3 text-start">
                                                    <label class="form-label fw-bold" for="priorites" >Priorités</label>
                                                    <select class="form-control" name="priorites" value="<?php echo $priorites; ?>">
                                                        <option>E-Planifié</option>
                                                        <option>D-Dés que possible</option>
                                                        <option>C-Normal</option>
                                                        <option>B-Urgent</option>
                                                        <option>A-Hyper-Urgent</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6 visually-hidden">
                                                <div class="mb-3 text-start">
                                                    <label class="form-label fw-bold" for="priorites">Status</label>
                                                    <select class="form-control" name="status" value="Attente livraison">
                                                        <option>Attente approbation</option>
                                                        <option>Attente livraison</option>
                                                        <option>livraison partielle</option>
                                                        <option>Terminé</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6 visually-hidden">
                                                <div class="mb-3 text-start">
                                                    <label class="form-label fw-bold" for="user" ></label>
                                                    <input class="form-control " type="text" value="<?php
                                                        $array = explode(' ', $_SESSION['nomcomplet']);
                                                        echo $array[0]; 
                                                    ?>" name="user" id="example-date-inpu">
                                                </div>
                                            </div>
                                            <div class="col-md-6 visually-hidden">
                                                <div class="mb-3 text-start">
                                                    <label class="form-label fw-bold" for="idda" ></label>
                                                    <input class="form-control " type="text" value="<?php
                                                        echo $id;
                                                    ?>" name="idda" id="examp">
                                                </div>
                                            </div>
                                            <div class="col-md-6 visually-hidden">
                                                <div class="mb-3 text-start">
                                                    <label class="form-label fw-bold" for="livraison">Livraison</label>
                                                    <input class="form-control" type="text" value="0" name="livraison" id="example-d" placeholder="Noter la livraison">
                                                </div>
                                            </div>   
                                    </div>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <h6 class="text-start">Photo de la piéce</h6>
                                                <div class="table-responsive">
                                                        <table class="table table-nowrap align-middle">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th scope="col" class="text-start fw-bold">Type de pièce</th>
                                                                    <th scope="col" class="text-start fw-bold">Fichier à joindre</th>
                                                                    <th scope="col" class="text-start fw-bold">Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr class="text-start">
                                                                    <td>JPEG</td>
                                                                    <td>PHOTO</td>
                                                                    <td>
                                                                        <ul class="list-inline mb-0">
                                                                            <li class="list-inline-item">
                                                                                <a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete" class="px-2 text-danger"><i class="bx bx-trash-alt font-size-18"></i></a>
                                                                            </li>
                                                                        </ul>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    <div class="col-md-12 text-start">
                                                        <!--<label for="file-upload" class="btn btn-danger boutons-ajout me-1">Ajouter fichier</label>!-->
                                                        <input name="file" type="file" value="<?php echo $namefile; ?>"  id="formFileDisabled" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-12 text-end">
                                                <div class="col-md-8 align-items-center col-md-12 text-end">
                                                    <div class="d-flex gap-2 pt-4">                           
                                                        <a href="<?php echo "acueilAdmin1.php?id=$idda"; ?>"><input class="btn btn-danger  w-lg bouton" value="Annuler"></a>
                                                        <input class="btn btn-success  w-lg bouton" name="valideArticle" type="submit" value="Enregistrer">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>  
                                </div>
                            </div><!-- /.modal-content -->
                    </div><!-- /.modal -->
        
    </body>
    
</html>
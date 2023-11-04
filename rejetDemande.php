<?php

    // On se connecte à là base de données
    include 'connexionReclamation.php';

    if(isset($_GET['page']) && !empty($_GET['page'])){
        $currentPage = (int) strip_tags($_GET['page']);
    }else{
        $currentPage = 1;
    }

    // On se connecte à là base de données
    include 'connect.php';

    $mess1="";

    if(isset($_POST['valideLivraison'])){
        if(!empty($_POST['status'])){
            //echo $_POST['quantites'];
              $status=htmlspecialchars($_POST['status']);
              $id=htmlspecialchars($_POST['id']);
              $user=htmlspecialchars($_POST['userLivrer']);
              $req ="UPDATE articles SET statuspart=?, actifmang=2, rege=0, userlivrer=? WHERE id=$id;"; 
              //$db->query($req); 
              $reqtitre = $db->prepare($req);
              $reqtitre->execute(array($status,$user));
              
              if(isset($_GET['id'])){
                  $id = $_GET['id'];
                  header("location:acueilAdmin2.php?id=$id");
                  exit;
              }
          }elseif($_POST['livraison'] > ($_POST['quantites'])){
            $mess1 = "error";
          }      
    }

    // On détermine le nombre total d'articles
    $sql = "SELECT COUNT(*) AS nb_articles FROM `reclamations`;";
    // On prépare la requête
    $query = $db->prepare($sql);

    // On exécute
    $query->execute();
    
    // On récupère le nombre d'articles
    $result = $query->fetch();

    
    $nbReclamation = (int) $result['nb_articles'];

    // On détermine le nombre total d'articles
    $sql = "SELECT COUNT(*) AS nb_articles FROM `articles` where `rege`=1 and `actifkemb`=0;";
    // On prépare la requête
    $query = $db->prepare($sql);

    // On exécute
    $query->execute();
    
    // On récupère le nombre d'articles
    $result = $query->fetch();
  
    $nbRege = (int) $result['nb_articles'];




    // On détermine le nombre total d'articles
    $sql1 = 'SELECT COUNT(*) AS nb_utilisateur FROM `utilisateur`;';

    // On prépare la requête
    $query1 = $db->prepare($sql1);

    // On exécute
    $query1->execute();

    // On récupère le nombre d'articles
    $result1 = $query1->fetch();

    $nbutilisateur = (int) $result1['nb_utilisateur'];

    // On détermine le nombre total de demande
    $sql8 = "SELECT COUNT(*) AS nb_articles FROM `articles` where `rege`=1 and `actifkemb`=0 and `actifmang`=0 and `description`!='0';";
    // On prépare la requête
    $query8 = $db->prepare($sql8);

    // On exécute
    $query8->execute();
    
    // On récupère le nombre d'articles
    $result8 = $query8->fetch();
  
    $nbRegeDemande = (int) $result8['nb_articles']; 

    if($_SESSION['niveau'] =='mang' || $_SESSION['niveau'] =='admin'){

        // ---------------On détermine le nombre total d'articles
        $sql = "SELECT COUNT(*) AS nb_articles FROM `articles` where `actif`= 1 and `actifkemb`=0 and `actifmang`=0 and `description`!='0' and `rege`=1;";
        
        // On prépare la requête
        $query = $db->prepare($sql);

        // On exécute
        $query->execute();

        // On récupère le nombre d'articles
        $result = $query->fetch();

        $nbArticles = (int) $result['nb_articles'];

        // On détermine le nombre d'articles par page
        $parPage = 7;

        // On calcule le nombre de pages total
        $pages = ceil($nbArticles / $parPage);

        // Calcul du 1er article de la page
        $premier = ($currentPage * $parPage) - $parPage;

        //-------------------
        $sql = "SELECT * FROM `articles` where `actif`= 1 and `actifkemb`=0 and `description`!='0' and `actifmang`=0 and `rege`=1 ORDER BY `id` DESC LIMIT :premier, :parpage;";

        // On prépare la requête
        $query = $db->prepare($sql);

        $query->bindValue(':premier', $premier, PDO::PARAM_INT);
        $query->bindValue(':parpage', $parPage, PDO::PARAM_INT);

        // On exécute
        $query->execute();

        // On récupère les valeurs dans un tableau associatif
        $articles = $query->fetchAll(PDO::FETCH_ASSOC);

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

        <!-- gridjs css -->
        <link  href="libs/gridjs/theme/mermaid.min.css" rel="stylesheet">

        <!-- Bootstrap Css -->
        <link href="css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="css/icons.min.css" rel="stylesheet" type="text/css" />

        <!-- Sweet Alert -->
        <link href="./libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css"/>
        <script src="./libs/sweetalert2/sweetalert2.min.js"></script>

        
        <!--  Jquery-ui -->
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
        <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
        <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

        <!-- App Css-->
        <link href="css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
        
          <!-- Style Css-->
          <link href="./style.css" id="app-style" rel="stylesheet" type="text/css" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="image/iconOnglet.png" />
    <title>METAL AFRIQUE</title>
</head>
<body>
    <!-- Begin page -->
    <div id="layout-wrapper">            
    <header id="page-topbar" class="">
        <div class="d-flex mr-5 float-lg-end">
                <div class="dropdown d-inline-block">
                    <button type="button" class="btn header-item user text-start d-flex align-items-center" id="page-header-user-dropdown-v"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img class="rounded-circle header-profile-user" src="image/avatar-3.jpg"
                        alt="Header Avatar">
                        <span class="d-none d-xl-inline-block ms-2 fw-medium font-size-15"><?php echo $_SESSION['nomcomplet'] ?></span>
                    </button>
                    <?php 
                        if($_SESSION['niveau']=='admin'){
                        ?>
                        <div class="dropdown-menu dropdown-menu-end pt-0">
                            <div class="p-3 border-bottom">
                                <h6 class="mb-0">Administrateur</h6>
                            </div>
                            <a class="dropdown-item d-flex align-items-center" href="modifiercompte.php"><i class="mdi mdi-cog-outline text-muted font-size-16 align-middle me-2"></i> <span class="align-middle me-3">Paramètres</span></a>
                            <a class="dropdown-item d-flex align-items-center" href="ajoutercompte.php"><i class="mdi mdi mdi-account-plus text-muted font-size-16 align-middle me-2"></i> <span class="align-middle me-3">Ajouter utilisateur</span></a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="index.php"><i class="mdi mdi-logout text-muted font-size-16 align-middle me-2"></i> <span class="align-middle">Déconnexion</span></a>
                        </div>
                        <?php
                        }
                    ?> 
                    <?php 
                        if($_SESSION['niveau']=='kemc' || $_SESSION['niveau']=='mang'){
                        ?>
                        <div class="dropdown-menu dropdown-menu-end pt-0">
                            <div class="p-3 border-bottom">
                                <h6 class="mb-0">Maintenance</h6>
                            </div>
                            <a class="dropdown-item d-flex align-items-center" data-bs-toggle="modal" data-bs-target=".add-new5" href=""><i class="mdi mdi mdi-bell-sleep text-muted font-size-16 align-middle me-2"></i> <span class="align-middle me-3">Signaler probléme</span></a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="index.php"><i class="mdi mdi-logout text-muted font-size-16 align-middle me-2"></i> <span class="align-middle">Déconnexion</span></a>
                        </div>
                        <?php
                        }
                    ?>                   
                </div>
                
                <div class="modal fade add-new5" id="add-new5" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="myExtraLargeModalLabel">Faire une réclamation</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="#" method="POST">
                                    <div class="row">
                                        
                                        <div class="col-md-6">
                                            <div class="mb-6 text-start">
                                                <label class="form-label fw-bold" for="nom">Message</label>
                                                <textarea class="form-control" placeholder="Taper votre réclamation destinée à l'administrateur svp!" name="message" id="example-date-input" rows="8"></textarea>
                                            </div>
                                        </div>  
                                        <div class="col-md-6">
                                            <div class="mb-3 text-start">
                                                <label class="form-label fw-bold" for="user" >Nom de la DA</label>
                                                <input class="form-control " type="text" value="" name="nomDA" id="example-date-input" placeholder="Donner le nom de la DA exp: DA0012">
                                            </div>
                                        </div>                                     
                                        <div class="col-md-6 visually-hidden">
                                            <div class="mb-3 text-start">
                                                <label class="form-label fw-bold" for="user" ></label>
                                                <input class="form-control " type="text" value="<?php
                                                    $array = explode(' ', $_SESSION['nomcomplet']);
                                                    echo $array[0]; 
                                                ?>" name="user" id="example-date-input">
                                            </div>
                                        </div>   
                                </div>
                                    <div class="row mt-2">
                                        <div class="col-md-12 text-end">
                                            <div class="col-md-8 align-items-center col-md-12 text-end">
                                                <div class="d-flex gap-2 pt-4">                           
                                                    <a href=""><input class="btn btn-danger  w-lg bouton" name="" type="submit" value="Annuler"></a>
                                                    <input class="btn btn-success  w-lg bouton" name="valide" type="submit" value="Envoyer">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>                             
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->

            </div>
        <!-- Content Row -->
                <div class="row header-item user text-start d-flex align-items-center w-75 p-3">
                    <?php 
                        if(($_SESSION['niveau'] !='kemc')){
                        ?>
                        <div class="col-xl-3 col-md-6">
                            <a href="rejet.php">
                                <div class="card border-left-warning shadow h-100 py-2 bg-danger bg-gradient">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                    Demandes rejetées</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">Nombres : <?php echo $nbRegeDemande;?></div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-comments fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div> 
                    <?php
                        }
                    ?>
                </div>

                <!-- Content Row -->
            <div class="navbar-header">
            
            <div class="d-flex">
                <!-- LOGO -->
                <div class="navbar-brand-box">
                    <a href="accueil.html" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="images/logo-sirhae-1.png" alt="" height="26">
                        </span>
                        <span class="logo-lg">
                            <img src="images/logo-sirhae-sm.png" alt="" height="26">
                        </span>
                    </a>

                    <a href="accueil.html" class="logo logo-light">
                        <span class="logo-lg">
                            <img src="images/logo-sirhae-1.png" alt="" height="30">
                        </span>
                        <span class="logo-sm">
                            <img src="images/logo-sirhae-sm.png" alt="" height="26">
                        </span>
                    </a>
                </div>


                <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect vertical-menu-btn">
                    <i class="bx bx-menu align-middle"></i>
                </button>

            </div>           
        </div>
    </header>

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3">
                        <!-- start page title -->
                        <div class="page-title-box align-self-center d-none d-md-block">
                             <h4 class="page-title mb-0 pl-5 text-center mb-3">Profil Utilisateur</h4>
                        </div>
                        <!-- end page title -->
                        <div class="card">
                            <div class="card-body p-0">
                                <div class="user-profile-img">
                                    <img src="image/avatar-3.jpg"
                                    class="profile-img profile-foreground-img rounded-top" style="height: 120px;"
                                    alt="">
                                    <div class="overlay-content rounded-top">
                                            <div>
                                                <div class="user-nav p-3">
                                                    <div class="d-flex justify-content-end">
                                                            
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                                <!-- end user-profile-img -->
                                <div class="p-4 pt-0">
                                <div class="mt-n5 position-relative text-center border-bottom pb-3">
                                    <div class="profile-container">
                                            <img id="profile-photo" src="image/avatar-3.jpg" alt="profile-photo" class="avatar-xl rounded-circle img-thumbnail profile-photo">
                                                <div class="avatar-overlay">
                                                    <input type="file" id="avatar-input" style="display: none;">
                                                        <span class="profile-button" onclick="changeProfilePhoto()"><i class="bx bx-camera rounded-circle"></i></span>
                                                    </div>
                                                </div>                                           
                                                <div class="mt-3">
                                                   <h5 class="mb-1"><?php echo $_SESSION['nomcomplet'] ?></h5>
                                                   <span class="badge badge-soft-success mb-0">L'administrateur</span>
                                                </div>
                                    </div>
                                    <div class="table-responsive mt-3 border-bottom pb-3" style="font-family: montserrat;">
                                            <table class="table align-middle table-sm table-nowrap table-borderless table-centered mb-0">
                                                <tbody>
                                                    <tr>
                                                        <th class="text-start fw-bold">Matricule :</th>
                                                        <td class="text-start fw-bold"><?php echo  $_SESSION['matricule'] ?></td><br>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-start fw-bold">Nom Complet :</th>
                                                        <td class="text-start fw-bold"><?php echo $_SESSION['nomcomplet'] ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-start fw-bold">Email :</th>
                                                        <td class="text-start fw-bold"><?php echo $_SESSION['email'] ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-start fw-bold">Niveau d'acces :</th>
                                                        <td class="text-start fw-bold"><?php echo $_SESSION['niveau'] ?></td>
                                                    </tr>
                                                </tbody><!-- end tbody -->
                                            </table>
                                    </div> 
                                </div>
                            </div>
                        </div>
                    </div>      
                    <div class="col-md-9">
                            <div class="card">
                                <div class="card-body">
                                    <!-- Nav tabs -->
                                    <ul class="nav nav-pills nav-justified card-header-pills" role="tablist">  
                                        <li class="nav-item">
                                            <a class="nav-link w-50 p-3 active" data-bs-toggle="tab" href="detail-agent.html#termine" role="tab">
                                                <span class="fw-bold font-size-15">Commandes rejetées</span> 
                                            </a>
                                        </li>                              
                                    </ul>
                                </div>
                            </div>
                <!-- Tab content -->
                <div class="tab-content">
                                    <!-- end message -->
                                    <div class="tab-pane active" id="termine" role="tabpanel">
                                        <div class="card">
                                           <div class="card-body">
                                             <div class="accordion accordion-flush" id="accordionFlushExample">
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header" id="flush-headingOne">
                                                                    <button class="accordion-button fw-medium" type="button" data-bs-toggle="collapse"
                                                                        data-bs-target="#flush-collapseOne" aria-expanded="true" aria-controls="flush-collapseOne">
                                                                        Liste des commandes rejetées 
                                                                    </button>
                                                                </h2>
                                                                <div id="flush-collapseOne" class="accordion-collapse collapse show" aria-labelledby="flush-headingOne"
                                                                    data-bs-parent="#accordionFlushExample">
                                                                    <div class="accordion-body text-muted">
                                                                        <div class="table-responsive">
                                                                            <table class="table table-nowrap align-middle">
                                                                                <thead class="table-light">
                                                                                    <tr>   
                                                                                    <th scope="col" class="fw-bold text-start">Nom DA<button tabindex="-1" aria-label="Sort column ascending" title="Sort column ascending" class="gridjs-sort gridjs-sort-neutral"></button></th>                                                                     
                                                                                        <th scope="col" class="fw-bold text-start">Demande<button tabindex="-1" aria-label="Sort column ascending" title="Sort column ascending" class="gridjs-sort gridjs-sort-neutral"></button></th>
                                                                                        <th scope="col" class="fw-bold text-start">Priorités<button tabindex="-1" aria-label="Sort column ascending" title="Sort column ascending" class="gridjs-sort gridjs-sort-neutral"></button></th>
                                                                                        <th scope="col" class="fw-bold text-start">Status<button tabindex="-1" aria-label="Sort column ascending" title="Sort column ascending" class="gridjs-sort gridjs-sort-neutral"></button></th>
                                                                                        <th scope="col" class="fw-bold text-start">Livré Par<button tabindex="-1" aria-label="Sort column ascending" title="Sort column ascending" class="gridjs-sort gridjs-sort-neutral"></button></th>
                                                                                        <th scope="col" class="fw-bold text-start">Date creation<button tabindex="-1" aria-label="Sort column ascending" title="Sort column ascending" class="gridjs-sort gridjs-sort-neutral"></button></th>
                                                                                        <?php 
                                                                                        if(($_SESSION['niveau']=='mang' && $nbRege)){
                                                                                        ?>
                                                                                            <th scope="col" class="fw-bold text-start">Options<button tabindex="-1" aria-label="Sort column ascending" title="Sort column ascending" class="gridjs-sort gridjs-sort-neutral"></button></th>
                                                                                        <?php 
                                                                                        }
                                                                                        ?>
                                                                                    </tr>
                                                                                </thead>
    
                                                                                <tbody>
                                                                                <?php require_once 'fonctions.php'; ?>
                                                                                    <?php
                                                                                        $i=0;
                                                                                        foreach($articles as $article){
                                                                                            $i++;
                                                                                            //if($article['status'] == 'termine'){
                                                                                    ?>                                                                              
                                                                                    <tr class="text-start">
                                                                                    <td><?php echo "DA00".$article['idda']; ?></td>
                                                                                        <td><?php trunkString($article['description'], 20); ?></td>
                                                                                        <td><?= $article['priorites'] ?></td>
                                                                                        <td><span class="<?php if($article['statuspart'] != "Terminé"){echo "badge badge-soft-success mb-0";}else{echo "badge badge-soft-danger mb-0";}?>"><?= $article['statuspart'] ?></span></td>
                                                                                        <td><?= $article['userLivrer'] ?></td>
                                                                                        <td><?= $article['datecreation'] ?></td>
                                                                                        <td>
                                                                                            <?php 
                                                                                                if($_SESSION['niveau']=='mang'){
                                                                                            ?>
                                                                                                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#fileModal1<?php echo $i; ?>" data-bs-url="images/test.pdf" data-bs-placement="top" title="Afficher Demande" class="px-2 text-primary" data-bs-original-title="Afficher Demande" aria-label="Afficher Demande"><i class="bx bx-show-alt font-size-18"></i></a>
                                                                                                <a data-bs-toggle="modal" data-bs-target=".add-newlivrer" class="btn btn-success  w-lg "><i class=""></i>Modifier</a>
                                                                                            <?php
                                                                                            }
                                                                                            ?>
                                                                                        </td> 
                                                                                    </tr>
                                                                                    <div class="modal fade" id="fileModal1<?php echo $i; ?>" tabindex="-1" aria-labelledby="fileModalLabel" aria-hidden="true">
                                                                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                                                                            <div class="modal-content">
                                                                                                <div class="modal-header">
                                                                                                    <h5 class="modal-title" id="fileModalLabel">Description de la demande</h5>
                                                                                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                                                                        <span aria-hidden="true">&times;</span>
                                                                                                    </button>
                                                                                                </div>  
                                                                                                <h6 class="form-label fw-bold" for="nom" style="margin-left:25px; margin-top:10px;">Message :</h6>
                                                                                                <div class="modal-body border border-warning" style="margin:70px; border-radius: 15px 30px; margin-top:20px; background-color: #fef1df;">
                                                                                                    <h4><?php echo $article['description']; ?></h4>                                                                                                
                                                                                                </div>                                                                                             
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="modal fade add-newlivrer" id="add-newlivrer" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="myExtraLargeModalLabel">Modifier la livraison de la commande</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="#" method="POST">
                                    <div class="row">
                                        <div class="col-md-6 visually-hidden">
                                            <div class="mb-3 text-start">
                                                <label class="form-label fw-bold" for="livraison">id</label>
                                                <input class="form-control" type="text" value="<?= $article['id'] ?>" name="id" id="example-date-input" placeholder="Noter le nombre de piéces à livrer">
                                            </div>
                                        </div>
                                        <div class="col-md-6 visually-hidden">
                                            <div class="mb-3 text-start">
                                                <label class="form-label fw-bold" for="quantites">id</label>
                                                <input class="form-control" type="text" value="<?= $article['quantites'] ?>" name="quantites" id="example-date-input" placeholder="Noter le nombre de piéces à livrer">
                                            </div>
                                        </div> 
                                        <div class="col-md-6">
                                            <div class="mb-3 text-start">
                                                <label class="form-label fw-bold" for="priorites">Status</label>
                                                <select class="form-control" name="status" value="Attente livraison">
                                                    <option>Attente approbation</option>
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
                                                ?>" name="userLivrer" id="example-date-input">
                                            </div>
                                        </div>  
                                </div>
                                    <div class="row mt-2">
                                        <div class="col-md-12 text-end">
                                            <div class="col-md-8 align-items-center col-md-12 text-end">
                                                <div class="d-flex gap-2 pt-4"> 
                                                    <?php if($mess1 == "error"){ ?> 
                                                            <script>    
                                                                Swal.fire({
                                                                    text: 'La quantité à livrer est supérieure à la quantité demandée!',
                                                                    icon: 'error',
                                                                    timer: 3500,
                                                                    showConfirmButton: false,
                                                                });
                                                            </script> 
                                                        <?php } 
                                                    ?>                          
                                                    <a href="#"><input class="btn btn-danger  w-lg bouton" name="" type="submit" value="Annuler"></a>
                                                    <input class="btn btn-success  w-lg bouton" name="valideLivraison" type="submit" value="Enregistrer">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>                             
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
                
                                                                                    <?php
                                                                                            }
                                                                                        //}
                                                                                    ?>
                                                                                </tbody>
                                                                            </table>
                                                                            <!-- Bouton et pagnination--> 
                                                                            <div class="col-md-8 align-items-center">
                                                                                <div class="d-flex gap-2 pt-4">
                                                                                    <?php 
                                                                                    if($_SESSION['niveau']=='kemc'){
                                                                                    ?>
                                                                                        <a data-bs-toggle="modal" data-bs-target=".add-new" class="btn btn-success  w-lg bouton"><i class="bx bx-plus me-1"></i> Ajouter Commande</a>
                                                                                    <?php
                                                                                    }
                                                                                    ?>
                                                                                        <a href="rejet.php" class="btn btn-danger  w-lg "><ion-icon name="arrow-undo-outline"></ion-icon>Retour</a>
                                                                                </div>
                                                                                <div class="d-flex gap-2 pt-4">
                                                                                </div>
                                                                            </div>
                                                                            <div class="row g-0 align-items-center pb-4">
                                                                                <div class="col-sm-12">
                                                                                    <div class="float-sm-end">
                                                                                        <ul class="pagination mb-sm-0">
                                                                                            <li class="page-item <?= ($currentPage == 1) ? "disabled" : "" ?>">
                                                                                                <a href="?page=<?= $currentPage - 1 ?>" class="page-link">Précédente</a>
                                                                                            </li>
                                                                                            <?php for($page = 1; $page <= $pages; $page++): ?>
                                                                                            <!-- Lien vers chacune des pages (activé si on se trouve sur la page correspondante) -->
                                                                                            <li class="page-item <?= ($currentPage == $page) ? "active" : "" ?>">
                                                                                                    <a href="?page=<?= $page ?>" class="page-link"><?= $page ?></a>
                                                                                            </li>
                                                                                            <?php endfor ?>
                                                                                            <!-- Lien vers la page suivante (désactivé si on se trouve sur la dernière page) -->
                                                                                            <li class="page-item <?= ($currentPage == $pages) ? "disabled" : "" ?>">
                                                                                                <a href="?page=<?= $currentPage + 1 ?>" class="page-link">Suivante</a>
                                                                                            </li>
                                                                                        </ul>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                           <!-- Bouton et pagnination--> 
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div> 
                                                        </div><!-- end accordion -->
                                                        <!--End !-->
                                           </div>
                                        </div>
                                    </div>
                   
                                    <div class="modal fade add-new" id="add-new" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="myExtraLargeModalLabel">Ajouter une commande</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="#" method="POST">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-6 text-start">
                                                <label class="form-label fw-bold" for="nom">Quantités</label>
                                                <input class="form-control" type="number" name="quantites" value="" id="example-date-input" placeholder="Donner la quantité">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 text-start">
                                                <label class="form-label fw-bold" for="prenom" >Désignations</label>
                                                <input class="form-control" type="text" value="" name="designations" id="example-date-input"  placeholder="Taper la designation">
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 text-start">
                                                <label class="form-label fw-bold" for="reference" >Références</label>
                                                <input class="form-control" type="text" value="" name="references" id="example-date-input"  placeholder="Taper la reference">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 text-start">
                                                <label class="form-label fw-bold" for="priorites" >Priorités</label>
                                                <input class="form-control" type="text" value="" name="priorites" id="example-date-input" placeholder="Taper la propriété">
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
                                                ?>" name="user" id="example-date-input">
                                            </div>
                                        </div>
                                        <div class="col-md-6 visually-hidden">
                                            <div class="mb-3 text-start">
                                                <label class="form-label fw-bold" for="idda" ></label>
                                                <input class="form-control " type="text" value="<?php
                                                    echo $id;
                                                ?>" name="idda" id="example-date-input">
                                            </div>
                                        </div>
                                        <div class="col-md-6 visually-hidden">
                                            <div class="mb-3 text-start">
                                                <label class="form-label fw-bold" for="livraison">Livraison</label>
                                                <input class="form-control" type="text" value="0" name="livraison" id="example-date-input" placeholder="Noter la livraison">
                                            </div>
                                        </div>   
                                </div>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h6 class="text-start">Pièces justificatives</h6>
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
                                                                <td>PDF</td>
                                                                <td>Fichier</td>
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
                                                    <label for="file-upload" class="btn btn-danger boutons-ajout me-1">Ajouter fichiers</label>
                                                    <input id="file-upload" type="file" multiple style="display: none;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-12 text-end">
                                            <div class="col-md-8 align-items-center col-md-12 text-end">
                                                <?php if($mess == "error"){ ?> <h4 style="color:red; margin-buttom:50px;" id="rejet">Erreur remplir tous les champs svp</h4><?php } ?>
                                                <div class="d-flex gap-2 pt-4">                           
                                                    <a href="#"><input class="btn btn-danger  w-lg bouton" name="" type="submit" value="Annuler"></a>
                                                    <input class="btn btn-success  w-lg bouton" name="valideArticle" type="submit" value="Enregistrer">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>                             
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
                </div> 
                </div>
                </div>                                  
        </div> 
    </div>
    <?php include 'footer.php'; ?>                                                                                            


    <!-- JAVASCRIPT -->
    <script src="libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="libs/metismenujs/metismenujs.min.js"></script>
    <script src="libs/simplebar/simplebar.min.js"></script>
    <script src="libs/eva-icons/eva.min.js"></script>

    <script src="js/app.js"></script>

        <!-- boxicons -->
        <script src="https://cdn.jsdelivr.net/npm/boxicons@2.0.9/js/boxicons.min.js"></script>
        <script>
          
          function changeProfilePhoto() {
                var fileInput = document.createElement('input');
                fileInput.type = 'file';
                fileInput.accept = 'image/*';
                fileInput.addEventListener('change', function(event) {
                  var file = event.target.files[0];
                  var reader = new FileReader();
                  reader.onload = function(e) {
                    var newPhoto = e.target.result;
                    var profilePhoto = document.getElementById('profile-photo');
                    profilePhoto.src = newPhoto;
                  };
                  reader.readAsDataURL(file);
                });
                fileInput.click();
              }


          var fileModal = document.getElementById("fileModal");
              var fileViewer = document.getElementById("fileViewer");

              var fileLinks = document.querySelectorAll("[data-bs-target='#fileModal']");

              fileLinks.forEach(function(fileLink) {
                fileLink.addEventListener("click", openFileModal);
              });

              function openFileModal(event) {
                var url = event.target.getAttribute("data-bs-url");

                // Supprimer l'ancien contenu du visualiseur de fichier
                fileViewer.innerHTML = "";

                // Vérifier le type de fichier
                var fileType = url.split('.').pop().toLowerCase();

                if (fileType === "pdf") {
                  // Si le fichier est un PDF, afficher avec un <embed>
                  var embed = document.createElement("embed");
                  embed.setAttribute("src", url);
                  embed.setAttribute("type", "application/pdf");
                  embed.style.width = "100%";
                  embed.style.height = "500px";
                  fileViewer.appendChild(embed);
                } else if (fileType === "png" || fileType === "jpg" || fileType === "jpeg") {
                  // Si le fichier est une image (PNG, JPG, JPEG), afficher avec une balise <img>
                  var img = document.createElement("img");
                  img.setAttribute("src", url);
                  img.style.maxWidth = "100%";
                  fileViewer.appendChild(img);
                }
              }


                // Récupérer tous les éléments <th>
                var headers = document.querySelectorAll("#affectations th");

                // Parcourir chaque élément <th>
                headers.forEach(function(th) {
                  // Récupérer le texte existant dans l'élément <th>
                  var texteOriginal = th.textContent.trim();

                  // Supprimer le contenu textuel de l'élément <th>
                  th.textContent = "";

                  // Créer un nouvel élément de conteneur <div>
                  var conteneur = document.createElement("div");
                  conteneur.classList.add("d-flex", "justify-content-between", "align-items-center");

                  // Créer un élément de texte à gauche
                  var texteElement = document.createElement("span");
                  texteElement.textContent = texteOriginal;

                  // Créer un élément bouton à droite
                  var bouton = document.createElement("button");
                  bouton.setAttribute("tabindex", "-1");
                  bouton.setAttribute("aria-label", "Sort column ascending");
                  bouton.setAttribute("title", "Sort column ascending");
                  bouton.classList.add("gridjs-sort", "gridjs-sort-neutral");

                  // Ajouter le texte et le bouton dans le conteneur
                  conteneur.appendChild(texteElement);
                  conteneur.appendChild(bouton);

                  // Ajouter le conteneur à l'élément <th>
                  th.appendChild(conteneur);
        });
    </script>
</body>
</html>
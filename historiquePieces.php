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

    if((isset($_GET['id']) && $_SESSION['niveau']=='admin') || (isset($_GET['id']) && $_SESSION['niveau']=='kemc')){
        $id = $_GET['id'];

        // ---------------On détermine le nombre total d'articles
        $sql = "SELECT COUNT(*) AS nb_articles FROM `articles` where `idda`=$id and `description`='0';";
        
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
        $sql = "SELECT * FROM `articles` where `idda`='$id' and `description`='0' ORDER BY `id` DESC LIMIT :premier, :parpage;";

        // On prépare la requête
        $query = $db->prepare($sql);

        $query->bindValue(':premier', $premier, PDO::PARAM_INT);
        $query->bindValue(':parpage', $parPage, PDO::PARAM_INT);

        // On exécute
        $query->execute();

        // On récupère les valeurs dans un tableau associatif
        $articles = $query->fetchAll(PDO::FETCH_ASSOC);

        
        // ----------- On definie le nombre de demande
        
        $sqld = "SELECT COUNT(*) AS nb_articles FROM `articles` where `actif`= 1 and `status`!='Terminé' and `actifkemb`= 0 and `idda`=$id and `description`!='0' and `description`!='';";
            
        // On prépare la requête
        $queryd = $db->prepare($sqld);

        // On exécute
        $queryd->execute();

        // On récupère le nombre d'articles
        $resultd = $queryd->fetch();

        $nbDemande = (int) $resultd['nb_articles'];

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

        <!-- Sweet Alert -->
        <link href="./libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css"/>
        <script src="./libs/sweetalert2/sweetalert2.min.js"></script>
        <script src="./libs/sweetalert2/jquery-1.12.4.js"></script>

        <!-- Bootstrap Css -->
        <link href="css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="css/icons.min.css" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
        
          <!-- Style Css-->
          <link href="./style.css" id="app-style" rel="stylesheet" type="text/css" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="image/iconOnglet.png" />
    <title>METAL AFRIQUE</title>
</head>
<body>
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
                            <a class="dropdown-item d-flex align-items-center" href="<?php echo "updateUser.php?matricule=$_SESSION[matricule]";?>"><i class="mdi mdi-cog-outline text-muted font-size-16 align-middle me-2"></i> <span class="align-middle me-3">Paramètres</span></a>
                            <div class="dropdown-divider"></div>
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
                            <a class="dropdown-item d-flex align-items-center" href="<?php echo "updateUser.php?matricule=$_SESSION[matricule]";?>"><i class="mdi mdi-cog-outline text-muted font-size-16 align-middle me-2"></i> <span class="align-middle me-3">Paramètres</span></a>
                            <div class="dropdown-divider"></div>
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
                                                    <a href="acueilKemC.php"><input class="btn btn-danger  w-lg bouton" name="" type="submit" value="Annuler"></a>
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
                    
                    <!-- Earnings (Monthly) Card Example -->                   
                        <div class="col-xl-3 col-md-6t">
                            <a href="acueiladmin.php">
                                <div class="card border-left-primary shadow h-100 py-2 bg-success bg-gradient">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                    Commandes (Piéces)</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">Nombres : <?php echo $nbArticles;?></div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                               </div>
                            </a>
                        </div>

                        <?php 
                    if($_SESSION['niveau']=='admin' && $nbDemande){
                        ?>
                    <div class="col-xl-3 col-md-6t">
                        <a href="historiqueDemandes.php?id=<?php echo $_GET['id'];?>">
                            <div class="card border-left-primary shadow h-100 py-2 bg-success bg-gradient">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Demandes (En cours)</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">Nbre (Demandes) : <?php echo $nbDemande;?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
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
                                                <span class="fw-bold font-size-15">Liste toutes les commandes <?php echo "DA00".$id; ?> </span> 
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
                                                                        Liste de toutes les commandes 
                                                                    </button>
                                                                </h2>
                                                                <div id="flush-collapseOne" class="accordion-collapse collapse show" aria-labelledby="flush-headingOne"
                                                                    data-bs-parent="#accordionFlushExample">
                                                                    <div class="accordion-body text-muted">
                                                                        <div class="table-responsive">
                                                                            <table class="table table-nowrap align-middle">
                                                                                <thead class="table-light">
                                                                                    <tr>                                                                                       
                                                                                        <th scope="col" class="fw-bold text-start">Quantités<button tabindex="-1" aria-label="Sort column ascending" title="Sort column ascending" class="gridjs-sort gridjs-sort-neutral"></button></th>
                                                                                        <th scope="col" class="fw-bold text-start">Designations<button tabindex="-1" aria-label="Sort column ascending" title="Sort column ascending" class="gridjs-sort gridjs-sort-neutral"></button></th>
                                                                                        <th scope="col" class="fw-bold text-start">References<button tabindex="-1" aria-label="Sort column ascending" title="Sort column ascending" class="gridjs-sort gridjs-sort-neutral"></button></th>
                                                                                        <th scope="col" class="fw-bold text-start">Priorités<button tabindex="-1" aria-label="Sort column ascending" title="Sort column ascending" class="gridjs-sort gridjs-sort-neutral"></button></th>
                                                                                        <th scope="col" class="fw-bold text-start">Status<button tabindex="-1" aria-label="Sort column ascending" title="Sort column ascending" class="gridjs-sort gridjs-sort-neutral"></button></th>
                                                                                        <th scope="col" class="fw-bold text-start">Livraison<button tabindex="-1" aria-label="Sort column ascending" title="Sort column ascending" class="gridjs-sort gridjs-sort-neutral"></button></th>
                                                                                        <th scope="col" class="fw-bold text-start">Restant<button tabindex="-1" aria-label="Sort column ascending" title="Sort column ascending" class="gridjs-sort gridjs-sort-neutral"></button></th>
                                                                                        <th scope="col" class="fw-bold text-start">Créée Par<button tabindex="-1" aria-label="Sort column ascending" title="Sort column ascending" class="gridjs-sort gridjs-sort-neutral"></button></th>
                                                                                        <th scope="col" class="fw-bold text-start">Date creation<button tabindex="-1" aria-label="Sort column ascending" title="Sort column ascending" class="gridjs-sort gridjs-sort-neutral"></button></th>
                                                                                        <th scope="col" class="fw-bold text-start">Date livraison<button tabindex="-1" aria-label="Sort column ascending" title="Sort column ascending" class="gridjs-sort gridjs-sort-neutral"></button></th>
                                                                                        <th scope="col" class="fw-bold text-start">Durée livraison<button tabindex="-1" aria-label="Sort column ascending" title="Sort column ascending" class="gridjs-sort gridjs-sort-neutral"></button></th>
                                                                                        <th scope="col" class="fw-bold text-start">Options<button tabindex="-1" aria-label="Sort column ascending" title="Sort column ascending" class="gridjs-sort gridjs-sort-neutral"></button></th>
                                                                                    </tr>
                                                                                </thead>
    
                                                                                <tbody>
                                                                                    <?php
                                                                                        $i=0;
                                                                                        foreach($articles as $article){
                                                                                            $i++;
                                                                                            //if($article['status'] == 'termine'){
                                                                                    ?>                                                                              
                                                                                    <tr class="text-start">
                                                                                        <td><?= $article['quantites'] ?></td>
                                                                                        <td><?= $article['designations'] ?></td>
                                                                                        <td><?= $article['references'] ?></td>
                                                                                        <td><?= $article['priorites'] ?></td>
                                                                                        <td><span class="<?php if($article['status'] != "Terminé"){echo "badge badge-soft-success mb-0";}else{echo "badge badge-soft-danger mb-0";}?>"><?= $article['status'] ?></span></td>                                                                                       
                                                                                        <td><?= $article['livraison'] ?></td>
                                                                                        <td><?php echo $article['quantites']; ?></td>
                                                                                        <td><?= $article['user'] ?></td>
                                                                                        <td><?= $article['datecreation'] ?></td>
                                                                                        <td><?= $article['datelivraison'] ?></td>
                                                                                        <td><?php
                                                                                            //$now = time();
                                                                                            $dateCreation = strtotime($article['datecreation']);
                                                                                            $diff = strtotime($article['datelivraison']) - $dateCreation;
                                                                                            if(floor($diff/(60*60*24)) == 0){echo "Moins de 24H";}else{echo floor($diff/(60*60*24))."  (JOURS)";}
                                                                                        ?></td>
                                                                                        <td>
                                                                                            <a data-bs-toggle="modal" data-bs-target="#fileModal<?php echo $i; ?>" data-bs-url="" data-bs-placement="top" title="Afficher photo" class="px-2 text-primary" data-bs-original-title="Afficher photo" aria-label="Afficher photo"><i class="bx bx-file-blank font-size-18"></i></a>
                                                                                        </td> 
                                                                                    </tr>
                                                                                     <!-- Modal pour afficher le fichier -->
                                                                                    <div class="modal fade" id="fileModal<?php echo $i; ?>" tabindex="-1" aria-labelledby="fileModalLabel" aria-hidden="true">
                                                                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                                                                            <div class="modal-content">
                                                                                                <div class="modal-header">
                                                                                                    <h5 class="modal-title" id="fileModalLabel">Photo de la piéce <?php $temp2 = $article['namefile']; echo $temp2; ?></h5>
                                                                                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                                                                        <span aria-hidden="true">&times;</span>
                                                                                                    </button>
                                                                                                </div>
                                                                                                <?php 
                                                                                                    if($article['namefile'] != null){
                                                                                                ?>
                                                                                                    <div class="modal-body" style="max-height: 90%">
                                                                                                    <iframe
                                                                                                        src="./fichiers/<?php echo $article['namefile'];?>"
                                                                                                        width="98%"
                                                                                                        height="650"
                                                                                                        frameborder="0" allow="autoplay; encrypted-media" allowfullscreen
                                                                                                    ></iframe>
                                                                                                    </div>
                                                                                                <?php 
                                                                                                    }else{
                                                                                                ?>                
                                                                                                    <h2 style="margin-left:150px; margin-top:20px; color:red;">Il y'a pas d'image à cette commande!</h2>
                                                                                                <?php 
                                                                                                    }
                                                                                                ?>
                                                                                                
                                                                                                <div class="modal-body">
                                                                                                    <div id="fileViewer"></div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!-- Fin modal PDF--> 
                                                                                    <div class="modal fade add-newlivrer" id="add-newlivrer" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                                                                                        <div class="modal-dialog modal-xl modal-dialog-centered">
                                                                                            <div class="modal-content">
                                                                                                <div class="modal-header">
                                                                                                    <h5 class="modal-title" id="myExtraLargeModalLabel">Livraison de la commande</h5>
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
                                                                                                            <div class="col-md-6">
                                                                                                                <div class="mb-3 text-start">
                                                                                                                    <label class="form-label fw-bold" for="livraison">Livraison</label>
                                                                                                                    <input class="form-control" type="text" value="" name="livraison" id="example-date-input" placeholder="Noter le nombre de piéces à livrer">
                                                                                                                </div>
                                                                                                            </div>   
                                                                                                    </div>
                                                                                                        <div class="row mt-2">
                                                                                                            <div class="col-md-12 text-end">
                                                                                                                <div class="col-md-8 align-items-center col-md-12 text-end">
                                                                                                                    <div class="d-flex gap-2 pt-4">                           
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
                                                                                    <a href="<?php echo "historique.php";?>" class="btn btn-danger  w-lg "><ion-icon name="arrow-undo-outline"></ion-icon>Retour</a>
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
                                <form action="#" method="POST" enctype="multipart/form-data">
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
                                                <select class="form-control" name="priorites" value="">
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
                                                    <input name="file" type="file"  id="formFileDisabled" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-12 text-end">
                                            <div class="col-md-8 align-items-center col-md-12 text-end">
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
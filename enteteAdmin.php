<?php

    // On se connecte à là base de données
    include 'connexionReclamation.php';

    //On verifie qui se connecte
    if($_SESSION['niveau']=='mang'){
        if(isset($_GET['page']) && !empty($_GET['page'])){
            $currentPage = (int) strip_tags($_GET['page']);
        }else{
            $currentPage = 1;
        }
    
        // On se connecte à là base de données
        include 'connect.php';
    
        
        // On détermine le nombre total d'articles
        $sql = "SELECT COUNT(*) AS nb_articles FROM `reclamations`";
        // On prépare la requête
        $query = $db->prepare($sql);
    
        // On exécute
        $query->execute();
        
        // On récupère le nombre d'articles
        $result = $query->fetch();
        
        $nbReclamation = (int) $result['nb_articles'];
    
        //$_SESSION['da'] = $nbReclamation;
    
        // On détermine le nombre total d'articles
        $sql1 = 'SELECT COUNT(*) AS nb_utilisateur FROM `utilisateur`;';
    
        // On prépare la requête
        $query1 = $db->prepare($sql1);
    
        // On exécute
        $query1->execute();
    
        // On récupère le nombre d'articles
        $result1 = $query1->fetch();
    
        $nbutilisateur = (int) $result1['nb_utilisateur'];
    
    
        // On détermine le nombre total d'articles
        $sql = "SELECT COUNT(*) AS nb_articles FROM `articles` where `actif`= 1;";
    
    
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
    
        $sql = "SELECT * FROM `da` where `actif`=1 ORDER BY `id` DESC LIMIT :premier, :parpage;";
    
        // On prépare la requête
        $query = $db->prepare($sql);
    
        $query->bindValue(':premier', $premier, PDO::PARAM_INT);
        $query->bindValue(':parpage', $parPage, PDO::PARAM_INT);
    
        // On exécute
        $query->execute();
    
        // On récupère les valeurs dans un tableau associatif
        $articles = $query->fetchAll(PDO::FETCH_ASSOC);
    
        // On definie le nombre de DA
            $sqlda = "SELECT COUNT(*) AS nb_articles FROM `da` where `actif`=1 ";
            // On prépare la requête
            $queryda = $db->prepare($sqlda);
        
            // On exécute
            $queryda->execute();
            
            // On récupère le nombre d'articles
            $resultda = $queryda->fetch();
            
            $nbda = (int) $resultda['nb_articles'];
    
    
        // ----------- On definie le nombre de commande a approuvées
        $sqlartA = "SELECT COUNT(*) AS nb_articles FROM `articles` where `actif`=1 and `actifmang`=1";
        // On prépare la requête
        $queryartA = $db->prepare($sqlartA);
    
        // On exécute
        $queryartA->execute();
        
        // On récupère le nombre d'articles
        $resultartA = $queryartA->fetch();
        
        $nbartA = (int) $resultartA['nb_articles'];
    }elseif ($_SESSION['niveau']=='kemc') {
        if(isset($_GET['page']) && !empty($_GET['page'])){
            $currentPage = (int) strip_tags($_GET['page']);
        }else{
            $currentPage = 1;
        }
    
        // On se connecte à là base de données
        include 'connect.php';
    
        
        // On détermine le nombre total d'articles
        $sql = "SELECT COUNT(*) AS nb_articles FROM `reclamations` where ``";
        // On prépare la requête
        $query = $db->prepare($sql);
    
        // On exécute
        $query->execute();
        
        // On récupère le nombre d'articles
        $result = $query->fetch();
        
        $nbReclamation = (int) $result['nb_articles'];
    
        //$_SESSION['da'] = $nbReclamation;
    
        // On détermine le nombre total d'articles
        $sql1 = 'SELECT COUNT(*) AS nb_utilisateur FROM `utilisateur`;';
    
        // On prépare la requête
        $query1 = $db->prepare($sql1);
    
        // On exécute
        $query1->execute();
    
        // On récupère le nombre d'articles
        $result1 = $query1->fetch();
    
        $nbutilisateur = (int) $result1['nb_utilisateur'];
    
    
        // On détermine le nombre total d'articles
        $sql = "SELECT COUNT(*) AS nb_articles FROM `articles` where `actif`= 1;";
    
    
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
    
        $sql = "SELECT * FROM `da` where `actif`=1 ORDER BY `id` DESC LIMIT :premier, :parpage;";
    
        // On prépare la requête
        $query = $db->prepare($sql);
    
        $query->bindValue(':premier', $premier, PDO::PARAM_INT);
        $query->bindValue(':parpage', $parPage, PDO::PARAM_INT);
    
        // On exécute
        $query->execute();
    
        // On récupère les valeurs dans un tableau associatif
        $articles = $query->fetchAll(PDO::FETCH_ASSOC);
    
        // On definie le nombre de DA
            $sqlda = "SELECT COUNT(*) AS nb_articles FROM `da` where `actif`=1 ";
            // On prépare la requête
            $queryda = $db->prepare($sqlda);
        
            // On exécute
            $queryda->execute();
            
            // On récupère le nombre d'articles
            $resultda = $queryda->fetch();
            
            $nbda = (int) $resultda['nb_articles'];
    
    
        // ----------- On definie le nombre de commande a approuvées
        $sqlartA = "SELECT COUNT(*) AS nb_articles FROM `articles` where `actif`=1 and `actifmang`=1";
        // On prépare la requête
        $queryartA = $db->prepare($sqlartA);
    
        // On exécute
        $queryartA->execute();
        
        // On récupère le nombre d'articles
        $resultartA = $queryartA->fetch();
        
        $nbartA = (int) $resultartA['nb_articles'];
    }elseif ($_SESSION['niveau']=='admin') {
        if(isset($_GET['page']) && !empty($_GET['page'])){
            $currentPage = (int) strip_tags($_GET['page']);
        }else{
            $currentPage = 1;
        }
    
        // On se connecte à là base de données
        include 'connect.php';
    
        
        // On détermine le nombre total d'articles
        $sql = "SELECT COUNT(*) AS nb_articles FROM `reclamations`";
        // On prépare la requête
        $query = $db->prepare($sql);
    
        // On exécute
        $query->execute();
        
        // On récupère le nombre d'articles
        $result = $query->fetch();
        
        $nbReclamation = (int) $result['nb_articles'];
    
        //$_SESSION['da'] = $nbReclamation;
    
        // On détermine le nombre total d'articles
        $sql1 = 'SELECT COUNT(*) AS nb_utilisateur FROM `utilisateur`;';
    
        // On prépare la requête
        $query1 = $db->prepare($sql1);
    
        // On exécute
        $query1->execute();
    
        // On récupère le nombre d'articles
        $result1 = $query1->fetch();
    
        $nbutilisateur = (int) $result1['nb_utilisateur'];
    
    
        // On détermine le nombre total d'articles
        $sql = "SELECT COUNT(*) AS nb_articles FROM `articles` where `actif`= 1;";
    
    
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
    
        $sql = "SELECT * FROM `da` where `actif`=1 ORDER BY `id` DESC LIMIT :premier, :parpage;";
    
        // On prépare la requête
        $query = $db->prepare($sql);
    
        $query->bindValue(':premier', $premier, PDO::PARAM_INT);
        $query->bindValue(':parpage', $parPage, PDO::PARAM_INT);
    
        // On exécute
        $query->execute();
    
        // On récupère les valeurs dans un tableau associatif
        $articles = $query->fetchAll(PDO::FETCH_ASSOC);
    
        // On definie le nombre de DA
            $sqlda = "SELECT COUNT(*) AS nb_articles FROM `da` where `actif`=1 ";
            // On prépare la requête
            $queryda = $db->prepare($sqlda);
        
            // On exécute
            $queryda->execute();
            
            // On récupère le nombre d'articles
            $resultda = $queryda->fetch();
            
            $nbda = (int) $resultda['nb_articles'];
    
    
        // ----------- On definie le nombre de commande a approuvées
        $sqlartA = "SELECT COUNT(*) AS nb_articles FROM `articles` where `actif`=1 and `actifmang`=1";
        // On prépare la requête
        $queryartA = $db->prepare($sqlartA);
    
        // On exécute
        $queryartA->execute();
        
        // On récupère le nombre d'articles
        $resultartA = $queryartA->fetch();
        
        $nbartA = (int) $resultartA['nb_articles'];
    }

?>
<?php

    // On se connecte à là base de données
    include 'connexionReclamation.php';

    require 'fpdf/fpdf.php';

    if(!$_SESSION['niveau']){
        echo "<div style='background-color: lightblue; width:700px; height:400px; margin-left:300px'>";
        echo "<h1 style='color:red; text-align: center'>Error :</h1>";
        echo "<h2 style='color:red; margin-left:10px'>Sessions expurées!</h2>";
        echo "<h2 style='color:red; margin-left:10px'>Assurez vous que les fenetres ne sont pas ouvertes plusieures fois!</h2>";
        echo "<h2 style='color:red; margin-left:10px'>Veillez vous reconnecter svp! <a style='color:black;' href='http://localhost/GestionDemandePiece'>Acceder ici.</a></h2>";
        echo "</div>";
        return 0;
    }

    // ---------------On détermine le nombre total d'articles
    $sql = "SELECT COUNT(*) AS nb_articles FROM `articles` where `actifkemb`= 0 and `quantites`>=0 and `idda`=$_GET[id] and `references`!='';";
    
    // On prépare la requête
    $query = $db->prepare($sql);

    // On exécute
    $query->execute();

    // On récupère le nombre d'articles
    $result = $query->fetch();

    $nb_articlesP = (int) $result['nb_articles'];


    // ---------------On détermine le nombre total d'articles
    $sql = "SELECT COUNT(*) AS nb_articles FROM `articles` where `actifkemb`= 0 and `quantites`>=0 and `idda`=$_GET[id] and `references`='';";
    
    // On prépare la requête
    $query = $db->prepare($sql);

    // On exécute
    $query->execute();

    // On récupère le nombre d'articles
    $result = $query->fetch();

    $nb_articlesFS = (int) $result['nb_articles'];

$sql = "SELECT * FROM `articles` where `actifkemb`= 0 and `idda`= '$_GET[id]' and `quantites`>=0 and `references`!='' ORDER BY `id` DESC;";

// On prépare la requête
$query = $db->prepare($sql);


// On exécute
$query->execute();

// On récupère les valeurs dans un tableau associatif
$articles = $query->fetchAll();


//Pour les frais et services
$sqlFS = "SELECT * FROM `articles` where `actifkemb`= 0 and `idda`= '$_GET[id]' and `quantites`>=0 and `references`='' ORDER BY `id` DESC;";

// On prépare la requête
$queryFS = $db->prepare($sqlFS);


// On exécute
$queryFS->execute();

// On récupère les valeurs dans un tableau associatif
$articlesFS = $queryFS->fetchAll();

//print_r($articles);

$sqlDA = "SELECT * FROM `da` where id=$_GET[id] ORDER BY `id` DESC;";

// On prépare la requête
$queryDA = $db->prepare($sqlDA);
// On exécute
$queryDA->execute();

// On récupère les valeurs dans un tableau associatif
$articleDA = $queryDA->fetch();

$pdf = new FPDF('P','mm','A4');


$pdf->AddPage();
$pdf->SetFont('Arial','B',18);

/* Pour l'entete */

$pdf->SetTextColor(50,60,100);
$pdf->Cell(55,29,'METAL AFRIQUE',1,0);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(139,8,'             SYSTEME DE GESTION DES DEMANDES DE PIECES                  '.utf8_decode('Création : ').$articleDA['datecreation'],0,1);
$pdf->SetFont('Arial','B',19);
$pdf->Cell(55,28,'           * * *',0,0);
$pdf->SetFont('Arial','B',8);    
if(empty($articleDA['datelivraison'])){
    $pdf->Cell(139,8,utf8_decode('                                                                                                                         '.utf8_decode('Livraison : ')."En attente livraison"/*.$_POST['date']*/),0,1);
}else{
    $pdf->Cell(139,8,utf8_decode('                                                                                                                         '.utf8_decode('Livraison : ').$articleDA['datelivraison']/*.$_POST['date']*/),0,1);
}
$pdf->SetFont('Arial','B',12);
$pdf->Cell(148,13,"                                              DEMANDE D'APPROVISIONNEMENT",0,0,'C');
$pdf->Line(160,39,19,39);
$pdf->Line(160,26,65,26);
$pdf->Line(204,18,158,18);
$pdf->Line(204,10,204,27);
$pdf->Line(204,10,65,10);
$pdf->SetFont('Arial','B',10);
if($articleDA['statuspartielle'] > $articleDA['statustermine'] && $articleDA['statuspartielle'] > $articleDA['statusattente'] )
{$pdf->Cell(46,13,utf8_decode('Status : '.'Livraison partielle'),1,1);}
elseif($articleDA['statusattente'] > $articleDA['statustermine'] && $articleDA['statusattente'] > $articleDA['statuspartielle'] && $articleDA['statustermine']==0 && $articleDA['statuspartielle']==0){$pdf->Cell(46,13,utf8_decode('Status : '.'Attente livraison'),1,1);}
elseif($articleDA['statustermine'] == 0 && $articleDA['statuspartielle'] == 0){$pdf->Cell(46,13,utf8_decode('Status : '.'Attente livraison'),1,1);}
elseif($articleDA['statustermine'] > $articleDA['statusattente'] && $articleDA['statustermine'] > $articleDA['statuspartielle'] && $articleDA['statusattente']==0){$pdf->Cell(46,13,utf8_decode('Status : '.'Terminée'),1,1);}
else{$pdf->Cell(46,13,utf8_decode('Status : '.'Livraison partielle'),1,1);}
$pdf->Line(158,10,158,27);

$pdf->SetTextColor(0,0,0);
/* Pour Identification poste */
$pdf->SetFont('Arial','B',14);
$pdf->Cell(194,3,'',0,1);
$pdf->SetTextColor(50,60,100);
$pdf->Cell(194,10,'DA NUMERO '.$_GET['id'],1,1,'C');
$pdf->SetTextColor(0,0,0);

$pdf->Cell(194,2,'',0,1);
$pdf->SetTextColor(50,60,100);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(194,6,utf8_decode("DEMANDE DES PIECES (".$nb_articlesP.")"),0,1,'C');
$pdf->SetTextColor(0,0,0);



$i=0;
foreach($articles as $article){
    $i++;

/* Pour Identification */
$pdf->SetFont('Arial','B',7);
$pdf->Cell(194,1,'',0,1);
$pdf->SetTextColor(50,60,100);
$pdf->Cell(194,4,utf8_decode("Demande N° ".$i),1,1,'C');
$pdf->SetTextColor(0,0,0);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(70,5,utf8_decode('Quantités'),1,0);
$pdf->Cell(124,5,$article['quantites'],1,1,'C');
$pdf->Cell(70,5,utf8_decode('Désignations'),1,0);
$pdf->Cell(124,5,utf8_decode($article['designations']),1,1,'C');
$pdf->Cell(70,5,utf8_decode('Références'),1,0);
$pdf->Cell(124,5,utf8_decode($article['references'] ),1,1,'C');
$pdf->Cell(70,5,utf8_decode('Priorités'),1,0);
$pdf->Cell(124,5,utf8_decode($article['priorites']),1,1,'C');
$pdf->Cell(70,5,utf8_decode('Nombre de piéces livré'),1,0);
$pdf->Cell(124,5,utf8_decode($article['livraison']),1,1,'C');
$pdf->Cell(70,5,'Status',1,0);
$pdf->Cell(124,5,utf8_decode($article['status']),1,1,'C');
$pdf->Cell(70,5,utf8_decode('Créée par'),1,0);
$pdf->Cell(124,5,utf8_decode($article['user']),1,1,'C');
$idDemandeur1 = $article['idtransporteur'];
$sql3 = "SELECT * FROM `transporteur` where id=$idDemandeur1;";

// On prépare la requête
$query3 = $db->prepare($sql3);

// On exécute
$query3->execute();

// On récupère le nombre de demandeur
$result3 = $query3->fetch();
$pdf->Cell(70,5,'Transporteur',1,0);
if(empty($result3)){
    $pdf->Cell(124,5,utf8_decode('Pas encore livrée'),1,1,'C');
}else{
    $pdf->Cell(124,5,utf8_decode($result3['nomComplet']." (Matricule voiture :  ".$result3['matricule']." )"),1,1,'C');
}

$idDemandeur = $article['iddemandeur'];
$sql2 = "SELECT * FROM `demandeur` where id=$idDemandeur;";

// On prépare la requête
$query2 = $db->prepare($sql2);

// On exécute
$query2->execute();

// On récupère le nombre de demandeur
$result2 = $query2->fetch();
$pdf->Cell(70,5,'Demandeur',1,0);
$pdf->Cell(124,5,utf8_decode($result2['nomcomplet']." (Matricule :  ".$result2['matricule']." )"),1,1,'C');
$pdf->Cell(70,5,utf8_decode('Date création'),1,0);
$pdf->Cell(124,5,utf8_decode($article['datecreation']),1,1,'C');
$pdf->Cell(70,5,'Date livraison (Magasin)',1,0);
if(empty($article['datelivraison'])){
    $pdf->Cell(124,5,utf8_decode("Pas encore livrée"),1,1,'C');
}else{
    $pdf->Cell(124,5,utf8_decode($article['datelivraison']),1,1,'C');
}
$pdf->Cell(70,5,utf8_decode('Date livraison approuvée'),1,0);
if(empty($article['datelivapprouv'])){
    $pdf->Cell(124,5,utf8_decode("Pas encore approuvée"),1,1,'C');
}else{
    $pdf->Cell(124,5,utf8_decode($article['datelivapprouv']),1,1,'C');
}
$pdf->Cell(70,5,utf8_decode('Durée de la commande'),1,0);
$now = time();
$dateCreation = strtotime($article['datecreation']);
$diff = $now - $dateCreation;
if(floor($diff/(60*60*24)) == 0 && $article['datelivraison'] != NULL){$pdf->Cell(124,5,utf8_decode("Moins de 24H"),1,1,'C');}else{$pdf->Cell(124,5,utf8_decode(floor($diff/(60*60*24))."  (JOURS)"),1,1,'C');}

}



//Pour les frais et services
$pdf->Cell(194,2,'',0,1);
$pdf->SetTextColor(50,60,100);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(194,6,utf8_decode("DEMANDE DES FRAIS ET SERVICES (".$nb_articlesFS.")"),0,1,'C');
$pdf->SetTextColor(0,0,0);

$i=0;
foreach($articlesFS as $article){
    $i++;

/* Pour Identification */
$pdf->SetFont('Arial','B',7);
$pdf->Cell(194,1,'',0,1);
$pdf->SetTextColor(50,60,100);
$pdf->Cell(194,4,utf8_decode("Demande N° ".$i),1,1,'C');
$pdf->SetTextColor(0,0,0);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(70,5,utf8_decode('Quantités'),1,0);
$pdf->Cell(124,5,$article['quantites'],1,1,'C');
$pdf->Cell(70,5,utf8_decode('Désignations'),1,0);
$pdf->Cell(124,5,utf8_decode($article['designations']),1,1,'C');
$pdf->Cell(70,5,utf8_decode('Priorités'),1,0);
$pdf->Cell(124,5,utf8_decode($article['priorites']),1,1,'C');
$pdf->Cell(70,5,utf8_decode('Nombre de piéces livré'),1,0);
$pdf->Cell(124,5,utf8_decode($article['livraison']),1,1,'C');
$pdf->Cell(70,5,'Status',1,0);
$pdf->Cell(124,5,utf8_decode($article['status']),1,1,'C');
$pdf->Cell(70,5,utf8_decode('Créée par'),1,0);
$pdf->Cell(124,5,utf8_decode($article['user']),1,1,'C');
$idDemandeur1 = $article['idtransporteur'];
$sql3 = "SELECT * FROM `transporteur` where id=$idDemandeur1;";

// On prépare la requête
$query3 = $db->prepare($sql3);

// On exécute
$query3->execute();

// On récupère le nombre de demandeur
$result3 = $query3->fetch();
$pdf->Cell(70,5,'Transporteur',1,0);
if(empty($result3)){
    $pdf->Cell(124,5,utf8_decode('Pas encore livrée'),1,1,'C');
}else{
    $pdf->Cell(124,5,utf8_decode($result3['nomComplet']." (Matricule voiture :  ".$result3['matricule']." )"),1,1,'C');
}

$idDemandeur = $article['iddemandeur'];
$sql2 = "SELECT * FROM `demandeur` where id=$idDemandeur;";

// On prépare la requête
$query2 = $db->prepare($sql2);

// On exécute
$query2->execute();

// On récupère le nombre de demandeur
$result2 = $query2->fetch();
$pdf->Cell(70,5,'Demandeur',1,0);
$pdf->Cell(124,5,utf8_decode($result2['nomcomplet']." (Matricule :  ".$result2['matricule']." )"),1,1,'C');
$pdf->Cell(70,5,utf8_decode('Date création'),1,0);
$pdf->Cell(124,5,utf8_decode($article['datecreation']),1,1,'C');
$pdf->Cell(70,5,'Date livraison (Magasin)',1,0);
if(empty($article['datelivraison'])){
    $pdf->Cell(124,5,utf8_decode("Pas encore livrée"),1,1,'C');
}else{
    $pdf->Cell(124,5,utf8_decode($article['datelivraison']),1,1,'C');
}
$pdf->Cell(70,5,utf8_decode('Date livraison approuvée'),1,0);
if(empty($article['datelivapprouv'])){
    $pdf->Cell(124,5,utf8_decode("Pas encore approuvée"),1,1,'C');
}else{
    $pdf->Cell(124,5,utf8_decode($article['datelivapprouv']),1,1,'C');
}
$pdf->Cell(70,5,utf8_decode('Durée de la commande'),1,0);
$now = time();
$dateCreation = strtotime($article['datecreation']);
$diff = $now - $dateCreation;
if(floor($diff/(60*60*24)) == 0 && $article['datelivraison'] != NULL){$pdf->Cell(124,5,utf8_decode("Moins de 24H"),1,1,'C');}else{$pdf->Cell(124,5,utf8_decode(floor($diff/(60*60*24))."  (JOURS)"),1,1,'C');}

}

$pdf->Output();


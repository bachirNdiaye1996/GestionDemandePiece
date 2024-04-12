<?php

// On se connecte à là base de données
include 'connexionReclamation.php';


// On se connecte à là base de données
include 'connect.php';
include 'mail.php';



// Prendre toutes les pieces
    // On détermine le nombre total d'articles
    $sql = "SELECT COUNT(*) AS nb_articles FROM `articles` where `actif`=1 and `status`='Attente livraison';";
    // On prépare la requête
    $query = $db->prepare($sql);

    // On exécute
    $query->execute();

    // On récupère le nombre d'articles
    $result = $query->fetch();

    $NbPieceEnAttente = (int) $result['nb_articles'];
//

// Debut select
    $sql = "SELECT * FROM `articles` where `actifkemb`= 0 and `quantites`>=0 and `actif`=1 and `status`='Attente livraison' ORDER BY `idda` DESC;";

    // On prépare la requête
    $query = $db->prepare($sql);

    // On exécute
    $query->execute();

    // On récupère les valeurs dans un tableau associatif
    $PieceEnAttente = $query->fetchAll();
// Debut select

// ******* Pour les pieces livrés partiellement
    // Prendre toutes les pieces
        // On détermine le nombre total d'articles
        $sql = "SELECT COUNT(*) AS nb_articles FROM `articles` where `actif`=1 and `status`='livraison partielle';";
        // On prépare la requête
        $query = $db->prepare($sql);

        // On exécute
        $query->execute();

        // On récupère le nombre d'articles
        $result = $query->fetch();

        $NbPieceEnPartielle = (int) $result['nb_articles'];
    //

    // Debut select
        $sql = "SELECT * FROM `articles` where `actifkemb`= 0 and `quantites`>=0 and `actif`=1 and `status`='livraison partielle' ORDER BY `idda` DESC;";

        // On prépare la requête
        $query = $db->prepare($sql);

        // On exécute
        $query->execute();

        // On récupère les valeurs dans un tableau associatif
        $PieceEnPartielle = $query->fetchAll();
    // Debut select
// ******* Fin pour les pieces livrés partiellement


//Pour inserer une DA avec mail de notification 
               
                                     
    //$messageD=$_SESSION['nomcomplet'].' vient de creer une nouvelle DA de demande de piece.';
    $messageD = "
        <html>
        <head>
        <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
            <title>Rapport des pièces</title>
        </head>
        <body>
            <div id='email-wrap' style='background: #33ECFF;color: #FFF; border-radius: 10px;'>
                <br>
                <p align='center'>
                <img src='https://bootstrapemail.com/img/icons/logo.png' alt='' width=72 height=72>
            
                <h3 align='center' style='color:#0000FF;'>METAL AFRIQUE</h3>
            
                <p align='center'>Rapport des pièces.</p>
                <h4 align='center'>Voici la liste des pieces qui sont toujours en attente de livraison ( Nombres : $NbPieceEnAttente  ).</h4>
                </p>
                <br>
                <table style='border-collapse: separate;  margin-bottom: 50px; border: 1px solid white; border-spacing : 7px;  margin-left: auto; margin-right: auto;'>
                    <thead>
                        <tr>  
                            <th scope='col' style=' margin:6px;'>Nom DA</th>                                                                                     
                            <th scope='col' style=' margin:6px;'>Quantites</th>
                            <th scope='col' style=' margin:6px;'>Designations</th>
                            <th scope='col' style=' margin:6px;'>References</th>
                            <th scope='col' style=' margin:6px;'>Priorites</th>
                            <th scope='col' style=' margin:6px;'>Date planification</th>
                            <th scope='col' style=' margin:6px;'>Date creation</th>
                        </tr>
                    </thead>
                    <tbody>";
                        foreach($PieceEnAttente as $article){
                            $messageD.="
                            <tr>
                                <td style=' color:black; text-align: center;'>DA00.$article[idda]</td>
                                <td style=' color:black; text-align: center;'>$article[quantites]</td>
                                <td style=' color:black;text-align: center;'>$article[designations]</td>
                                <td style=' color:black;text-align: center;'>$article[references]</td>
                                <td style=' color:black; text-align: center;'>$article[priorites]</td>
                                <td style=' color:black; text-align: center;'>
                                    $article[dateplanifie]
                                </td>
                                <td style=' color:black; text-align: center;'>$article[datecreation]</td>
                            </tr>";
                                }
                            $messageD.="
                    </tbody>
                </table>
                <br>
                <br>
                <h4 align='center'>Voici la liste des pieces qui sont toujours en attente de livraison ( Nombres : $NbPieceEnPartielle  ).</h4>
                </p>
                <br>
                <table style='border-collapse: separate;  margin-bottom: 50px; border: 1px solid white; border-spacing : 7px;  margin-left: auto; margin-right: auto;'>
                    <thead>
                        <tr>  
                            <th scope='col' style=' margin:6px;'>Nom DA</th>                                                                                     
                            <th scope='col' style=' margin:6px;'>Quantites</th>
                            <th scope='col' style=' margin:6px;'>Designations</th>
                            <th scope='col' style=' margin:6px;'>References</th>
                            <th scope='col' style=' margin:6px;'>Priorites</th>
                            <th scope='col' style=' margin:6px;'>Date planification</th>
                            <th scope='col' style=' margin:6px;'>Date creation</th>
                        </tr>
                    </thead>
                    <tbody>";
                        foreach($PieceEnPartielle as $article){
                            $messageD.="
                            <tr>
                                <td style=' color:black; text-align: center;'>DA00.$article[idda]</td>
                                <td style=' color:black; text-align: center;'>$article[quantites]</td>
                                <td style=' color:black;text-align: center;'>$article[designations]</td>
                                <td style=' color:black;text-align: center;'>$article[references]</td>
                                <td style=' color:black; text-align: center;'>$article[priorites]</td>
                                <td style=' color:black; text-align: center;'>
                                    $article[dateplanifie]
                                </td>
                                <td style=' color:black; text-align: center;'>$article[datecreation]</td>
                            </tr>";
                                }
                            $messageD.="
                    </tbody>
                </table>
                <br>
                <br>
                <p align='center'>GESTIONDEMANDEPIECES@METALAFRIQUE.</p>
                <br>
                <br>
            </div>
        </body>
        </html>
            ";

    envoie_mail('Bachir','mouhamadoulbachir2@gmail.com','Rapport des pièces',$messageD);

?>
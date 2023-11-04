<?php   

    session_start();   

    #$servername = "mysql-boulangerie.alwaysdata.net";
    $username = "root";
    $password = "";
    $db_name = "maintenance";  

    $db = new PDO("mysql:host=localhost;dbname=$db_name;charset=utf8", $username, $password);
    if(isset($_POST['valide'])){
        if(!empty($_POST['username']) && !empty($_POST['password'])){
            $user=htmlspecialchars($_POST['username']);
            $password=sha1($_POST['password']);
            #$password=$_POST['password'];
            $recupeUser=$db->prepare('select * from utilisateur where matricule=? and password=?');
            $recupeUser->execute(array($user,$password));
            
            //print_r($recupeUser);
            if($recupeUser->rowCount() > 0){
                $Utilisateur = $recupeUser->fetch();
                if($Utilisateur['niveau'] == 'kemc'){
                    $_SESSION['ProduitRef']=[];
                    $_SESSION['ProduitDesign']=[];

                    $serverName = "SERVINTERAL\SQL2012";
                    $connectionOptions = array("Database"=>"INTERAL",
                        "Uid"=>"mbachir", "PWD"=>"ndiabass19");
                    $conn = sqlsrv_connect($serverName, $connectionOptions);

                    if( $conn ) {
                        // echo "Connexion établie.<br />";
                    }else{
                        echo "La connexion n'a pu être établie (Probleme de connexion).<br />";
                        die( print_r( sqlsrv_errors(), true));
                    }

                    try
                    {
                        //$conn = OpenConnection();
                        $tsql = "SELECT * FROM INTERAL.dbo.PART_PART_V";
                        $getProducts = sqlsrv_query($conn, $tsql);
                        if ($getProducts == FALSE)
                            die(FormatErrors(sqlsrv_errors()));
                        $productCount = 0;
                        while($row = sqlsrv_fetch_array($getProducts, SQLSRV_FETCH_ASSOC))
                        {
                            //echo($row['ID_PRODUCT']."-----------".$row['NO_PRODUCT']."-----------".$row['DESCRIPTION']."-----------");
                            //echo("<br/>");
                            //$ProduitRef[$productCount]=$row['NO_PRODUCT'];
                            //$ProduitDesign[$productCount]=utf8_decode($row['DESCRIPTION']);
                            //$_SESSION['ProduitRef'][$productCount]=$row['NO_PRODUCT'];
                            $_SESSION['ProduitDesign'][$productCount]="DESI : ".utf8_encode($row['DESCRIPTION'])." REFE : ".$row['NO_PRODUCT'];
                            //$_SESSION['ProduitDesign'][$productCount]=mb_detect_encoding($row['DESCRIPTION'], 'UTF-8'); 
                            $productCount++;
                        }
                        sqlsrv_free_stmt($getProducts);
                        sqlsrv_close($conn);
                    }
                    catch(Exception $e)
                    {
                        echo("Error!");
                    }
                }
                //On définit des variables de session
                $_SESSION['matricule'] = $Utilisateur['matricule'];
                $_SESSION['nomcomplet'] = $Utilisateur['nomcomplet'];
                $_SESSION['niveau'] = $Utilisateur['niveau'];
                $_SESSION['email'] = $Utilisateur['email'];
                $_SESSION['user'] = $Utilisateur['user'];
                if($Utilisateur['niveau'] == 'admin'){
                    header('Location: acueilAdmin.php');
                    exit;
                }elseif($Utilisateur['niveau'] == 'kemc' || $Utilisateur['niveau'] == 'mang'){
                    header('Location: acueilAdmin.php');
                    exit;
                }else{
                    header('Location: acueil.php');
                    exit;
                }
                #header('Location: acueil.php');
                #exit;
            }
        }
    }

    /*
    $User1 = $db->prepare("select*from utilisateur");
    $User1->execute();
    $Utilisateur = $User1->fetchAll();
    echo $Utilisateur['username'];*/
?>



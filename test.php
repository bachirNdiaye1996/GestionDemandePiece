<?php

    session_start();   

    #$servername = "mysql-boulangerie.alwaysdata.net";
    $username = "root";
    $password = "";
    $db_name = "maintenance";
    $db = new PDO("mysql:host=localhost;dbname=$db_name;charset=utf8", $username, $password);
 

    if (isset($_POST['rechercheDA'])) {

        echo $_POST['typeChoix'];

        if(!empty($_POST['typeChoix'])){

            

            // The prepared statement
            $sql = "SELECT * FROM fruit WHERE name LIKE ?";

            // Prepare, bind, and execute the query
            $choix = $_POST['valRechercheDA'];

            $recupeUser=$db->prepare('select * from da where id=?');
            $recupeUser->execute(array($choix));

            if ($recupeUser->rowCount() === 0) {
                // No match found
                echo "No match found";
                // Kill the script
                exit();

            } else {
                // Process the result(s)
                while ($row = $result->fetch_assoc()) {
                    echo "<b>Fruit Name</b>: ". $row['name'] . "<br />";
                    echo "<b>Fruit Color</b>: ". $row['color'] . "<br />";

                } // end of while loop
            } // end of if($result->num_rows)
            
        }

    } else { // The user accessed the script directly

        // Tell them nicely and kill the script.
        echo "That is not allowed!";
        exit();
    }
?>
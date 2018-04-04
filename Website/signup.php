<?php
//header ("Location:http://markuspi.bulme.at/admin/panel.html"); 

error_reporting(E_ALL);

//including config file with login information
//_______________________________________________________________________________________
$config = include('config.php');
//_______________________________________________________________________________________



// connecting and logging in to the database
//_______________________________________________________________________________________
$connect = mysqli_connect ($config['host'], 
                           $config['username'], 
                           $config['password'], 
                           $config['database']);
//_______________________________________________________________________________________




//define variables
//_______________________________________________________________________________________
        $email = $_POST["email"];
        $password_master = '83b6cca508f07790ba2407730c24d5869eeb41790740216f0baf40bc99180d5f'; //Adminadmin#31#
        $algo = sha256;
        $password_hash = hash ( $algo , $_POST["masterpassword"] , false );
        $password = hash ( $algo , $_POST["password"] , false );
        $master = 0;
//_______________________________________________________________________________________





// read id from db
//_______________________________________________________________________________________
//the id is 0 when $email does not exist in the database

$sql = "SELECT id FROM login WHERE username = '". $email ."'";//read id from database
    //write the id from the database into a variable 
    $db_erg = mysqli_query( $connect, $sql );
        if ( ! $db_erg )
        {
          die('Ungültige Abfrage: ' . mysqli_error());
        }

        while ($zeile = mysqli_fetch_array( $db_erg, MYSQL_ASSOC))
        {
            $id_db = $zeile['id'] ;
        }

    mysqli_free_result( $db_erg );

//_______________________________________________________________________________________




//check master password
//_______________________________________________________________________________________

if (strcmp($password_hash, $password_master) !== 0) 
{
    //master-password false
    header('Location: http://markuspi.bulme.at/admin/signupfalse.html');
}
else
{
    //master-password right
            
    //check if email is already signed up
    //_______________________________________________________________________________________

                if ($id_db == 0) 
                {
                //Email is not signed up
                
                //insert email and password into the database
                //_______________________________________________________________________________________

                                        $sql = "INSERT INTO login
                                            (
                                                username,
                                                password,
                                                userdeleted
                                            )
                                            VALUES
                                            (
                                                '". $email ."',
                                                '". $password ."',
                                                '0'
                                            );
                                        ";

                                        $result = mysqli_query($connect, $sql) 
                                               OR die("'".$sql."':".mysqli_error());
                                        header('Location: http://markuspi.bulme.at/admin/login.html');
                //_______________________________________________________________________________________
                
                
                
                    }
                    else
                    {
                        //email already signed up
                        header('Location: http://markuspi.bulme.at/admin/signupfalse2.html');
                    }

    //_______________________________________________________________________________________
            
            
            
            
        }
//_______________________________________________________________________________________



mysqli_close($link);

?>
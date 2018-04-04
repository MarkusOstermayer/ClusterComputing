<?php

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
 
    
    
    
    
//read password
//_______________________________________________________________________________________
session_start();
$userid = $_SESSION['userid'];
    
$sql = "SELECT id FROM login WHERE user_id = '". $userid ."'";//read id from database
    //write the id from the database into a variable 
    $db_erg = mysqli_query( $connect, $sql );
    
    if ( ! $db_erg )
    {
      die('invalid: ' . mysqli_error());
    }

    while ($zeile = mysqli_fetch_array( $db_erg, MYSQL_ASSOC))
    {
        $id_db = $zeile['id'] ;
    }

    mysqli_free_result( $db_erg );
//_______________________________________________________________________________________

    
    
    
    
    
//defining variables
//_______________________________________________________________________________________
    $name = $_POST["Vorname"];
    $surname = $_POST["Nachname"];
    $street = $_POST["Strasse"];
    $housenumber = $_POST["Hausnummer"];
    $place = $_POST["Ort"];
    $postcode = $_POST["PLZ"];
    $email = $_POST["Email"];
    $master = 0;
//_______________________________________________________________________________________
    
    
    
    

    
//read if infomation is already entered
//_______________________________________________________________________________________
$sql = "SELECT login_id FROM user_info WHERE Email = '". $email ."'";//read login_id from database
    //write the login id from the database into a variable 
    $db_erg = mysqli_query( $connect, $sql );
    
    if ( ! $db_erg )
    {
      die('invalid: ' . mysqli_error());
    }

    while ($zeile = mysqli_fetch_array( $db_erg, MYSQL_ASSOC))
    {
        $login_id_db = $zeile['login_id'] ;
    }

    mysqli_free_result( $db_erg );
//_______________________________________________________________________________________
    

   

    

    
    

//_______________________________________________________________________________________
    if ($login_id_db == 0) 
    {
        $sql = "INSERT INTO user_info
        (
            Email,
            Vorname,
            Nachname,
            Strasse,
            Hausnummer,
            Ort,
            PLZ,
            login_id
        )
        VALUES
        (
            '". $email       ."',
            '". $name        ."',
            '". $surname     ."',
            '". $street      ."',
            '". $housenumber ."',
            '". $place       ."',
            '". $postcode    ."',
            '". $id_db       ."'
            );
        ";
 
        $result = mysqli_query($connect, $sql) 
           OR die("'".$sql."':".mysqli_error());
    }
    else
    {
        $sql = "UPDATE user_info SET 
                Vorname = '". $name ."',
                Nachname = '". $surname ."',
                Strasse = '". $street ."',
                Hausnummer = '". $housenumber ."',
                Ort = '". $place ."',
                PLZ = '". $postcode ."',
                login_id = '". $id_db ."'
                WHERE Email = '". $email ."';
            ";

        $result = mysqli_query($connect, $sql) 
               OR die("'".$sql."':".mysqli_error());
    }
//_______________________________________________________________________________________
?>
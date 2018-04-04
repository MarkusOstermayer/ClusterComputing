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




//defining variables
//_______________________________________________________________________________________
    $email = $_POST['email']; //save email in variable
    $password = $_POST['password'];//save password in variable
    $algo = sha256;//define hashing alorithym
    $password_hash = hash ( $algo , $_POST["password"] , false );//hash the in login.html entered password
    $no_password = 'e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855';//hash of a blank
    $timestamp = time();
    $date = date("Y-m-d  H:i:s", $timestamp);
//_______________________________________________________________________________________

 



//read password
//_______________________________________________________________________________________
    $sql = "SELECT password FROM login WHERE username = '". $email ."'";//read the saved password from database
    //write the password from the database into a variable 
    $db_erg = mysqli_query( $connect, $sql );
        if ( ! $db_erg )
        {
          die('Ungültige Abfrage: ' . mysqli_error());
        }

        while ($zeile = mysqli_fetch_array( $db_erg, MYSQL_ASSOC))
        {
            $password_db = $zeile['password'] ;
        }

    mysqli_free_result( $db_erg );
//_______________________________________________________________________________________





//read userdeleted
//_______________________________________________________________________________________
    $sql = "SELECT userdeleted FROM login WHERE username = '". $email ."'";//read if acc. is activ from database
    //write the userdelted from the database into a variable 
    $db_erg = mysqli_query( $connect, $sql );
        if ( ! $db_erg )
        {
          die('Ungültige Abfrage: ' . mysqli_error());
        }

        while ($zeile = mysqli_fetch_array( $db_erg, MYSQL_ASSOC))
        {
            $userdeleted_db = $zeile['userdeleted'] ;
        }

    mysqli_free_result( $db_erg );
//_______________________________________________________________________________________





//read id
//_______________________________________________________________________________________
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



//start cookie session
//_______________________________________________________________________________________
session_start();
$pdo = new PDO('mysql:host='.$config['host'].';dbname='.$config['database'].'', ''.$config['username'].'', ''.$config['password'].'');
//_______________________________________________________________________________________
 





//creat a radom string
//_______________________________________________________________________________________
 function random_string() {
        
        if(function_exists('random_bytes')) {
             $bytes = random_bytes(16);
             $str = bin2hex($bytes); 
        } 
        else if(function_exists('openssl_random_pseudo_bytes')) {
             $bytes = openssl_random_pseudo_bytes(16);
             $str = bin2hex($bytes); 
        } 
        else if(function_exists('mcrypt_create_iv')) {
             $bytes = mcrypt_create_iv(16, MCRYPT_DEV_URANDOM);
             $str = bin2hex($bytes); 
        } 
        else {
            //Bitte euer_geheim_string durch einen zufälligen String mit >12 Zeichen austauschen
            $str = md5(uniqid('theveryspecialstring1029384756', true));
        } 
        return $str;
    }
//_______________________________________________________________________________________





//define cookie variables
//_______________________________________________________________________________________
 $user['email'] = $email;
 $user['password_db'] = $password_db;
 $user['id'] = $id_db;

//check if account is activ
if($userdeleted_db == 0){
//account is activ
    
    //check password
    if ($user !== false && strcmp($password_hash, $password_db) == 0) {
    $_SESSION['userid'] = $user['id'];

    $identifier = random_string();
    $securitytoken = random_string();
//_______________________________________________________________________________________

     
  
     
     
//insert cookie variables into database
//_______________________________________________________________________________________
     
     $sql = "UPDATE login SET 
                user_id =       '". $user['id'] ."', 
                identifier =    '". $identifier ."', 
                securitytoken = '". sha1($securitytoken) ."',
                created_at =    '". $date . "'
                WHERE id =      '". $id_db ."';
            ";

        $result = mysqli_query($connect, $sql) 
               OR die("'".$sql."':".mysqli_error());
//_______________________________________________________________________________________
     
     
     
     
        
//create a cookie
//_______________________________________________________________________________________
     setcookie("identifier",$identifier,time()+(60), "/", "http://markuspi.bulme.at/admin/panel.php", 0, 1);
     setcookie("securitytoken",$securitytoken,time()+(60), "/", "http://markuspi.bulme.at/admin/panel.php", 0, 1);


     die(header('Location: http://markuspi.bulme.at/admin/panel.php'));
 } 
//_______________________________________________________________________________________



//_______________________________________________________________________________________
    //end of check password
    else {
        //password false
        header('Location: http://markuspi.bulme.at/admin/loginfalse.html');
     }
    
}//end of userdeleted
else{
    //account is deleted
    header('Location: http://markuspi.bulme.at/admin/logindeleted.html');
}
//_______________________________________________________________________________________

mysqli_close($link);

?>

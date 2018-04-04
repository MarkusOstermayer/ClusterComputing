<!DOCTYPE html>
<html>

<php>
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
$sql = "SELECT password FROM login WHERE username = '". $_POST["email"] ."'";//read password from database
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
    
    


//check master password
//_______________________________________________________________________________________
        
        $algo = sha256;
        $email =             $_POST["email"];
        $password_old =      $_POST["password_old"];
        $passwordcheck_old = $_POST["passwordcheck_old"];
        $password_master =   '83b6cca508f07790ba2407730c24d5869eeb41790740216f0baf40bc99180d5f'; //Adminadmin#31#
        $password_hash =     hash ( $algo , $_POST["masterpassword"] , false );
        $password =          hash ( $algo , $_POST["password_old"] , false );
        $master = 0;

        if (strcmp($password_old, $passwordcheck_old) !== 0) 
        {
            //old password was entered wrong
            $master= 0;
            header('Location: http://markuspi.bulme.at/admin/passwordnewcheck.php');
        }
        if (strcmp($password, $password_db) !== 0) 
        {
            //old password is not equal to the entered password
            $master= 0;
            header('Location: http://markuspi.bulme.at/admin/passwordnewcheck.php');
        }
        else
        {
            //entered password is the same as the new one
            $master = 1;
        }
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
    
   
    

    

//insert email and password into the database
//_______________________________________________________________________________________

if($master == 1)
{
    $sql = "UPDATE login SET 
                password = '0'
                WHERE id = '". $id_db ."';
            ";

        $result = mysqli_query($connect, $sql) 
               OR die("'".$sql."':".mysqli_error());
}

//_______________________________________________________________________________________
?>
</php>
    
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Cluster | Log in</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="plugins/iCheck/square/blue.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="login.html">RCNP</a>
        </div>
        <!-- /.login-logo -->
        
        <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-warning"></i> Attention</h4>
                Please enter your email address and your new password.
        </div>
        
        <div class="login-box-body">
            <!--<p class="login-box-msg">Sign in to start your session</p>-->
            
            <!-- Database Button -->
            <form action="passwordrenew.php" method="post">
                <div class="form-group has-feedback">
                    <input name="email" type="email" class="form-control" placeholder="Email">
                    <span class="fa fa-envelope form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input name="password_new" type="password" class="form-control" placeholder="new Password">
                    <span class="fa fa-lock form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input name="passwordcheck_new" type="password" class="form-control" placeholder="new Password check">
                    <span class="fa fa-lock form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input name="masterpassword" type="password" class="form-control" placeholder="Master-Password">
                    <span class="fa fa-lock form-control-feedback"></span>
                </div>
            <div class="user-footer">
                <div class="pull-right">
                    <div class="col-xs-32">
                        <button name="database" class="btn btn-primary btn-block btn-flat">Save new password</button>
                    </div>
                </div>
                <div class="row">
                </div>
            </div>
                
            </form>
            
        </div>
        <!-- /.login-box-body -->
    </div>
    <!-- /.login-box -->

    <!-- jQuery 2.2.3 -->
    <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
    <!-- Bootstrap 3.3.6 -->
    <script src="/bootstrap/js/bootstrap.min.js"></script>
    <!-- iCheck -->
    <script src="plugins/iCheck/icheck.min.js"></script>
    <script>
        $(function() {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });

    </script>
</body>

</html>
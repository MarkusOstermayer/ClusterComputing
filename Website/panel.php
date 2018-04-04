<!DOCTYPE html>
<!--

This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Raspberry| Cluster</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Morris charts -->
    <link rel="stylesheet" href="plugins/morris/morris.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
    
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
    
    
<php>
<?php
    
//including config file with login information
//_______________________________________________________________________________________
$config = include('config.php');
//_______________________________________________________________________________________

    
    
//Start Session and DB connection
//-----------------------------------------------------------------------------------------
    session_start();
    $pdo = new PDO('mysql:host='.$config['host'].';dbname='.$config['database'].'', ''.$config['username'].'', ''.$config['password'].'');


// connecting and logging in to the database
//_______________________________________________________________________________________
$connect = mysqli_connect ($config['host'], 
                           $config['username'], 
                           $config['password'], 
                           $config['database']);
//_______________________________________________________________________________________
    
    
//Connect to DB and read Temperature Data
//-------------------------------------------------------------------------------------------------
    $query = "SELECT data, time_stamp FROM HardwareDataTest WHERE identifier = 'P' ORDER BY 'time_stamp'";
    $result = mysqli_query($connect, $query);
    $chart_data = '';
    $i = 0;
//fill process data into the variable which will be use to display the fata 
    while($row = mysqli_fetch_array($result))
    {
        if(($i) % 3 === 0){
            $chart_data .= "{ time_stamp:'".date('G:i:s ', $row["time_stamp"])."', MasterNode:".$row["data"]."}, ";
        }
        if(($i+2) % 3 === 0){
            $chart_data .= "{ time_stamp:'".date('G:i:s ', $row["time_stamp"])."', Node1:".$row["data"]."}, ";
        }
        if(($i+1) % 3 === 0){
            $chart_data .= "{ time_stamp:'".date('G:i:s ', $row["time_stamp"])."', Node2:".$row["data"]."}, ";
        }
        $i++;
    }

    $process_data = substr($chart_data, 0, -2);
//-------------------------------------------------------------------------------------------------
    

    
//Connect to DB and read Temperature Data
//------------------------------------------------------------------------------------------------- 
    $query = "SELECT data, time_stamp FROM HardwareDataTest WHERE identifier = 'T' ORDER BY 'time_stamp'";
    $result = mysqli_query($connect, $query);
    $chart_data = '';
    $i = 0;
//fill temperture data into the variable which will be use to display the fata
    while($row = mysqli_fetch_array($result))
    {
        if(($i) % 3 === 0){
            $chart_data .= "{ time_stamp:'".date('G:i:s ', $row["time_stamp"])."', MasterNode:".$row["data"]."}, ";
        }
        if(($i+2) % 3 === 0){
            $chart_data .= "{ time_stamp:'".date('G:i:s ', $row["time_stamp"])."', Node1:".$row["data"]."}, ";
        }
        if(($i+1) % 3 === 0){
            $chart_data .= "{ time_stamp:'".date('G:i:s ', $row["time_stamp"])."', Node2:".$row["data"]."}, ";
        }
        $i++;
    } 
    
    
    $temperatur_data = substr($chart_data, 0, -2); 
//-------------------------------------------------------------------------------------------------
    
    
    
//Generte and check Cookies for login
//-------------------------------------------------------------------------------------------------
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
//-----------------------------------------------------------------------------------------
    //check for 'signed in' - Cookie
    if(!isset($_SESSION['userid']) && isset($_COOKIE['identifier']) && isset($_COOKIE['securitytoken'])) {
        
        $identifier = $_COOKIE['identifier'];
        $securitytoken = $_COOKIE['securitytoken'];
        $statement = $pdo->prepare("SELECT * FROM login WHERE identifier = '". $identifier ."'");
        $result = $statement->execute(array($identifier));
        $securitytoken_row = $statement->fetch();

        if(sha1($securitytoken) !== $securitytoken_row['securitytoken']) {
            die('stolen security token');
        } 
        else {//token correct
            //set new token
            $new_securitytoken = random_string(); 
            $insert = $pdo->prepare("UPDATE login SET securitytoken = :securitytoken WHERE identifier = :identifier");
            $insert->execute(array('securitytoken' => sha1($new_securitytoken), 'identifier' => $identifier));
            setcookie("identifier",$identifier,time()+(60)); 
            setcookie("securitytoken",$new_securitytoken,time()+(60));

            //log user in
            $_SESSION['userid'] = $securitytoken_row['user_id'];
        }
    }
//-----------------------------------------------------------------------------------------
    if(!isset($_SESSION['userid'])) {
        die(header('Location: http://markuspi.bulme.at/admin/login.html'));
    }

    $userid = $_SESSION['userid'];
  
//-------------------------------------------------------------------------------------------------
    
    
    
    
    
//_________________________________________________________________________________________________

$query = "SELECT id, node, time_stamp, status, IPaddress FROM ClientData WHERE node = 'Node 1'";
    $result = mysqli_query($connect, $query);

    while ($row = mysqli_fetch_array($result))
    {
        $id_1 =        $row['id'] ;
        $name_1 =      $row['node'] ;
        $timestamp =   $row['time_stamp'] ;
        $state =       $row['status'] ;
        $type_1 =      $row['IPaddress'] ;
    }
    $online_since_1 = date('d.m.Y   G:i:s', $timestamp);
    
    if($state == 1){
        $state_color_1 =  "label label-success";
        $state_text_1 =   "online"; 
    }else{
        $state_color_1 =  "label label-danger";
        $state_text_1 =   "offline"; 
    }
    
    mysqli_free_result( $result );
//_________________________________________________________________________________________________

    
  
    
//_________________________________________________________________________________________________

$query = "SELECT id, node, time_stamp, status, IPaddress FROM ClientData WHERE node = 'Node 2'";
    $result = mysqli_query($connect, $query);

    while ($row = mysqli_fetch_array($result))
    {
        $id_2 =        $row['id'] ;
        $name_2 =      $row['node'] ;
        $timestamp =   $row['time_stamp'] ;
        $state =       $row['status'] ;
        $type_2 =      $row['IPaddress'] ;
    }
    $online_since_2 = date('d.m.Y   G:i:s', $timestamp);
    
    if($state == 1){
        $state_color_2 =  "label label-success";
        $state_text_2 =   "online"; 
    }else{
        $state_color_2 =  "label label-danger";
        $state_text_2 =   "offline"; 
    }
    
    mysqli_free_result( $result );
//_________________________________________________________________________________________________
   
    
  
//_________________________________________________________________________________________________

$query = "SELECT id, node, time_stamp, status, IPaddress FROM ClientData WHERE node = 'Masternode'";
    $result = mysqli_query($connect, $query);

    while ($row = mysqli_fetch_array($result))
    {
        $id_3 =        $row['id'] ;
        $name_3 =      $row['node'] ;
        $timestamp =   $row['time_stamp'] ;
        $state =       $row['status'] ;
        $type_3 =      $row['IPaddress'] ;
    }
    
    $online_since_3 = date('d.m.Y   G:i:s', $timestamp);
    
    if($state == 1){
        $state_color_3 =  "label label-success";
        $state_text_3 =   "online"; 
    }else{
        $state_color_3 =  "label label-danger";
        $state_text_3 =   "offline"; 
    }
    
    mysqli_free_result( $result );
//_________________________________________________________________________________________________
    
    $storage_1 =      "16 GB";
    $storage_2 =      "16 GB";
    $storage_3 =      "16 GB";
    
//-------------------------------------------------------------------------------------------------

    $userid = $_SESSION['userid'];
    
    $query = "SELECT username, created_at FROM login WHERE user_id = '". $userid ."'";
    
    $result = mysqli_query( $connect, $query );
    
    while ($row = mysqli_fetch_array( $result))
    {
        $Name =        $row['username'] ;
        $MemberSince = $row['created_at'];
    }

    mysqli_free_result( $result );
    
//------------------------------------------------------------------------------------------------- 
    
    
?>
</php>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

        <!-- Main Header -->
        <header class="main-header">

            <!-- Logo -->
            <a href="panel.php" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><b>R</b>CL</span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><b>Raspberry</b>Cluster</span>
            </a>

            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                <!-- Navbar Right Menu -->
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- Messages: style can be found in dropdown.less-->
                        <li class="dropdown messages-menu">




                            <!-- User Account Menu -->
                            <li class="dropdown user user-menu">
                                <!-- Menu Toggle Button -->
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <!-- The user image in the navbar-->
                                    <img src="dist/img/noAvatar.png" class="user-image" alt="User Image">
                                    <!-- hidden-xs hides the username on small devices so only the image appears. -->
                                    <span class="hidden-xs">Admin Account</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <!-- The user image in the menu -->
                                    <li class="user-header">
                                        <img src="dist/img/noAvatar.png" class="img-circle" alt="User Image">

                                        <p>
                                            Admin Account - <?php echo $Name; ?>
                                            <small>Member since <?php echo $MemberSince; ?></small>
                                        </p>
                                    </li>



                                    <!-- Menu Footer-->
                                    <li class="user-footer">
                                        <div class="pull-left">
                                            <a href="profil.php" class="btn btn-default btn-flat">Profile</a>
                                        </div>
                                        <div class="pull-right">
                                            <a href="logout.php" class="btn btn-default btn-flat">Sign out</a>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <!-- Control Sidebar Toggle Button -->
                            <li>
                                <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                            </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">

            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">

                <!-- Sidebar user panel (optional) -->
                <div class="user-panel">
                    <div class="pull-left image">
                        <img src="dist/img/noAvatar.png" class="img-circle" alt="User Image">
                    </div>
                    <div class="pull-left info">
                        <p>Admin Account</p>
                        <!-- Status -->
                        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                    </div>
                </div>

                <!-- search form (Optional) -->
                <form action="#" method="get" class="sidebar-form">
                    <div class="input-group">
                        <input type="text" name="q" class="form-control" placeholder="Search...">
                        <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
                    </div>
                </form>
                <!-- /.search form -->

                <!-- Sidebar Menu -->
                <ul class="sidebar-menu">
                    <li class="header">Cluster</li>
                    <!-- Optionally, you can add icons to the links -->
                    <li class="treeview">
                        <a href="#"><i class="fa fa-link"></i> <span>Master-Node</span>
                            <span class="pull-right-container">
                              <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="Info.php"><i class="fa fa-info-circle"></i>Informationen</a></li>
                        </ul>
                    </li>

                    <li class="treeview">
                        <a href="#"><i class="fa fa-link"></i> <span>Verfügbare Nodes</span>
                            <span class="pull-right-container">
                              <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="treeview">
                                <a href="#"><i class="fa fa-desktop"></i> <span>Node 1</span>
					               <span class="pull-right-container">
                                       <i class="fa fa-angle-left pull-right"></i>
					               </span>
				                </a>
                                <ul class="treeview-menu">
                                    <li><a href="Info1.php"><i class="fa fa-info-circle"></i>Informationen</a></li>
                                </ul>
                            </li>
                            <li class="treeview">
                                <a href="#"><i class="fa fa-desktop"></i> <span>Node 2</span>
                                    <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="Info2.php"><i class="fa fa-info-circle"></i>Informationen</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
                <!-- /.sidebar-menu -->
            </section>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->

            <!--<section class="content-header">
      <h1>
        Controllpanel
        <small>RPNC</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
      </ol>
    </section>-->

            <!-- Main content -->
            <section class="content">



                <!-- Your Page Content Here -->
                
                <div class="row">
                    <div class="col-xs-12">
                        <div class="box">
                            <div class="box-header">
                                <span class="fa fa-cloud"></span>
                                <h3 class="box-title">Nodes im Netzwerk</h3>


                                <div class="box-tools">
                                    <div class="input-group input-group-sm" style="width: 150px;">
                                        <!-- <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

                                            <div class="input-group-btn">
                                            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                        </div>-->
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body table-responsive no-padding">
                                <table class="table table-hover">
                                    <tbody>
                                        <tr>
                                            <th>ID</th>
                                            <th>Hostname</th>
                                            <th>Online seit</th>
                                            <th>Status</th>
                                            <th>IP Adresse</th>
                                            <th>Angeschlossene Module</th>
                                        </tr>
                                        <tr>
                                            <td><?php echo $id_1; ?></td>
                                            <td><?php echo $name_1; ?></td>
                                            <td><?php echo $online_since_1; ?></td>
                                            <td><span class="<?php echo $state_color_1; ?>"><?php echo $state_text_1; ?></span></td>
                                            <td><?php echo $type_1; ?></td>
                                            <td><?php echo $storage_1; ?></td>
                                        </tr>
                                        <tr>
                                            <td><?php echo $id_2; ?></td>
                                            <td><?php echo $name_2; ?></td>
                                            <td><?php echo $online_since_2; ?></td>
                                            <td><span class="<?php echo $state_color_2; ?>"><?php echo $state_text_2; ?></span></td>
                                            <td><?php echo $type_2; ?></td>
                                            <td><?php echo $storage_2; ?></td>
                                        </tr>
                                        <tr>
                                            <td><?php echo $id_3; ?></td>
                                            <td><?php echo $name_3; ?></td>
                                            <td><?php echo $online_since_3; ?></td>
                                            <td><span class="<?php echo $state_color_3; ?>"><?php echo $state_text_3; ?></span></td>
                                            <td><?php echo $type_3; ?></td>
                                            <td><?php echo $storage_3; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <!-- /.box -->
                    </div>
                </div>

                
                
                <div class="row"> </div>
                <div class="row">
                    <!-- Thingiverse MQTT Iframe, hier wird die Temperatur angezeigt (Beispiel) -->

                    <div class="col-md-6">
                        <!-- AREA CHART -->
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Temperaturverlauf</h3>

                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                            <div class="box-body chart-responsive">
                                <div class="chart" id="temperatur-chart" style="height: 300px;"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- /.box -->
                    <div class="col-md-6">
                      <!-- LINE CHART -->
                       <div class="box box-info">
                        <div class="box-header with-border">
                          <h3 class="box-title">Prozessorauslastung</h3>

                          <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                          </div>
                           </div>
                        <div class="box-body chart-responsive">
                         <div class="chart" id="process-chart" style="height: 300px;"></div>
                         <!--/.box-body -->
                      </div>
                </div>
                </div>
                
                <!-- Leercontainer -->
                <div class="row"> </div>


                <div class="row">

                </div>
                </div>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="pull-right hidden-xs">
                Ostermayer Markus und Scheschko Markus
            </div>
            <!-- Default to the left -->
            <strong>Copyright &copy; 2017 <a href="#">Bulme Diplomarbeit</a>.</strong> All rights reserved.
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">

            <!-- Create the tabs -->
            <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
                <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
                <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <!-- Home tab content -->
                <div class="tab-pane active" id="control-sidebar-home-tab">
                    <h3 class="control-sidebar-heading">Recent Activity</h3>
                    <ul class="control-sidebar-menu">
                        <li>


                            <a href="javascript::;">
                                <i class="menu-icon fa fa-users bg-red"></i>
                                <div class="menu-info">
                                    <h4 class="control-sidebar-subheading">Weboberfläche</h4>

                                    <p>17.2.2017 erster Launch der Weboberfläche</p>
                                </div>
                            </a>

                            <a href="javascript::;">
                                <i class="menu-icon fa fa-users bg-red"></i>
                                <div class="menu-info">
                                    <h4 class="control-sidebar-subheading">GitHub</h4>

                                    <p>5.3.17 GitHub Test</p>
                                </div>
                            </a>

                        </li>
                    </ul>
                    <!-- /.control-sidebar-menu -->

                    <h3 class="control-sidebar-heading">Tasks Progress</h3>
                    <ul class="control-sidebar-menu">
                        <li>
                            <a href="javascript::;">
                                <h4 class="control-sidebar-subheading">
                Projektstatus
                <span class="pull-right-container">
                  <span class="label label-danger pull-right">95%</span>
                </span>
              </h4>

                                <div class="progress progress-xxs">
                                    <div class="progress-bar progress-bar-danger" style="width: 95%"></div>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <!-- /.control-sidebar-menu -->

                </div>
                <!-- /.tab-pane -->
                <!-- Stats tab content 
                <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
                <!-- /.tab-pane -->
                <!-- Settings tab content -->
                <div class="tab-pane" id="control-sidebar-settings-tab">
                    <form method="post">
                        <h3 class="control-sidebar-heading">Settings</h3>

                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                                Under Development
                                <!-- <input type="checkbox" class="pull-right" checked> -->
                            </label>

                            <p>
                                Under development
                            </p>
                        </div>
                        <!-- /.form-group -->
                    </form>
                </div>
                <!-- /.tab-pane -->
            </div>
        </aside>

        <!-- /.control-sidebar -->
        <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
        <div class="control-sidebar-bg"></div>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 2.2.3 -->
    <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
    <!-- Bootstrap 3.3.6 -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <!-- Morris.js charts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="plugins/morris/morris.min.js"></script>
    <!-- FastClick -->
    <script src="plugins/fastclick/fastclick.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/app.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
    <!-- page script -->
<script>
 
    Morris.Line({
     element : 'process-chart',
     resize: true,
     data:[<?php echo $process_data; ?>],
     xkey:'time_stamp',
     ykeys:['MasterNode', 'Node1', 'Node2'],
     labels:['MasterNode', 'Node1', 'Node2'],
     hideHover:'auto',
     stacked:true,
     parseTime: false
    });
    
    Morris.Line({
     element : 'temperatur-chart',
     resize: true,
     data:[<?php echo $temperatur_data; ?>],
     xkey:'time_stamp',
     ykeys:['MasterNode', 'Node1', 'Node2'],
     labels:['MasterNode', 'Node1', 'Node2'],
     hideHover:'auto',
     stacked:true,
     parseTime: false
    });
    
    </script>

</body>
    
    
</html>
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Clusternetwork | Adminpanel</title>
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
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect.
  -->
    <link rel="stylesheet" href="dist/css/skins/skin-blue.min.css">

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

    
    session_start();
    $pdo = new PDO('mysql:host='.$config['host'].';dbname='.$config['database'].'', ''.$config['username'].'', ''.$config['password'].'');


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
   



// connecting and logging in to the database
//_______________________________________________________________________________________
$connect = mysqli_connect ($config['host'], 
                           $config['username'], 
                           $config['password'], 
                           $config['database']);
//_______________________________________________________________________________________
 
    
    $query = "SELECT id, node, time_stamp, status, IPaddress FROM ClientData WHERE node = 'Node 2'";
    $result = mysqli_query($connect, $query);

    while ($row = mysqli_fetch_array($result))
    {
        $id =          $row['id'];
        $name_1 =      $row['node'] ;
        $timestamp =   $row['time_stamp'] ;
        $state =       $row['status'] ;
        $type_1 =      $row['IPaddress'] ;
    }
    
    $online_since_1 = date('d.m.Y   G:i:s', $timestamp); 
    
    
    mysqli_free_result( $result );
    
    
    
    $query = "SELECT data, time_stamp FROM HardwareDataTest WHERE identifier = 'P' and node = 'Node 2' ORDER BY 'time_stamp'";
    $result = mysqli_query($connect, $query);
    $chart_data = '';
    //fill process data into the variable which will be use to display the fata 
    while($row = mysqli_fetch_array($result))
    {
            $chart_data .= "{ time_stamp:'".date('G:i:s ', $row["time_stamp"])."', Node2:".$row["data"]."}, ";
    }

    $process_data = substr($chart_data, 0, -2);
    
    
    $query = "SELECT data, time_stamp FROM HardwareDataTest WHERE identifier = 'T' and node = 'Node 2' ORDER BY 'time_stamp'";
    $result = mysqli_query($connect, $query);
    $chart_data = '';
    //fill temperature data into the variable which will be use to display the fata 
    while($row = mysqli_fetch_array($result))
    {
            $chart_data .= "{ time_stamp:'".date('G:i:s ', $row["time_stamp"])."', Node2:".$row["data"]."}, ";
    }

    $temperatur_data = substr($chart_data, 0, -2);
    
    
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
                                            Admin Account - Web Master
                                            <small>Member since Februar 2017</small>
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
                        <a href="#"><i class="fa fa-link"></i> <span>Masternode</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
                        <ul class="treeview-menu">
                            <li><a href="Info.php"><i class="fa fa-info-circle"></i>Informationen</a></li>
                            <li><a href="fehler.html"><i class="fa fa-database"></i>SSH & Data</a></li>
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
                                <a href="#"><i class="fa fa-desktop"></i> <span>Node1</span>
					<span class="pull-right-container">
					<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
                                <ul class="treeview-menu">
                                    <li><a href="fehler.html"><i class="fa fa-info-circle"></i>Informationen</a></li>
                                    <li><a href="fehler.html"><i class="fa fa-database"></i>SSH & Data</a></li>
                                </ul>
                            </li>
                            <li class="treeview">
                                <a href="#"><i class="fa fa-desktop"></i> <span>Node2</span>
					<span class="pull-right-container">
					<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
                                <ul class="treeview-menu">
                                    <li><a href="fehler.html"><i class="fa fa-info-circle"></i>Informationen</a></li>
                                    <li><a href="fehler.html"><i class="fa fa-database"></i>SSH & Data</a></li>
                                </ul>
                            </li>
                            <li class="treeview">
                                <a href="#"><i class="fa fa-desktop"></i> <span>Node3</span>
					<span class="pull-right-container">
					<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
                                <ul class="treeview-menu">
                                    <li><a href="fehler.html"><i class="fa fa-info-circle"></i>Informationen</a></li>
                                    <li><a href="fehler.html"><i class="fa fa-database"></i>SSH & Data</a></li>
                                </ul>
                            </li>
                            <li class="treeview">
                                <a href="#"><i class="fa fa-desktop"></i> <span>Node4</span>
					<span class="pull-right-container">
					<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
                                <ul class="treeview-menu">
                                    <li><a href="fehler.html"><i class="fa fa-info-circle"></i>Informationen</a></li>
                                    <li><a href="fehler.html"><i class="fa fa-database"></i>SSH & Data</a></li>
                                </ul>
                            </li>
                            <li class="treeview">
                                <a href="#"><i class="fa fa-desktop"></i> <span>Node5</span>
					<span class="pull-right-container">
					<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
                                <ul class="treeview-menu">
                                    <li><a href="fehler.html"><i class="fa fa-info-circle"></i>Informationen</a></li>
                                    <li><a href="fehler.html"><i class="fa fa-database"></i>SSH & Data</a></li>
                                </ul>
                            </li>
                            <li class="treeview">
                                <a href="#"><i class="fa fa-desktop"></i> <span>Node6</span>
					<span class="pull-right-container">
					<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
                                <ul class="treeview-menu">
                                    <li><a href="fehler.html"><i class="fa fa-info-circle"></i>Informationen</a></li>
                                    <li><a href="fehler.html"><i class="fa fa-database"></i>SSH & Data</a></li>
                                </ul>
                            </li>
                            <li class="treeview">
                                <a href="#"><i class="fa fa-desktop"></i> <span>Node7</span>
					<span class="pull-right-container">
					<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
                                <ul class="treeview-menu">
                                    <li><a href="fehler.html"><i class="fa fa-info-circle"></i>Informationen</a></li>
                                    <li><a href="fehler.html"><i class="fa fa-database"></i>SSH & Data</a></li>
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
		    <div class="row"></div>
        	<div class="col-lg-4 col-xs-6">
          	<!-- small box -->
          		<div class="small-box bg-gray">
            			<div class="inner">
              				<h1>Allgemeine</h1>
                            <h1>Informtaion</h1>
					
					<h2 class="fa fa-user">  Hostname: <?php echo $name_1; ?></h2>
					<p class="row"></p>
					<h2 class="fa fa-list-ol">	Nummer:  ID <?php echo $id; ?></h2> 
					<p class="row"></p>
                    <h2 class="fa fa-cogs">	Type: Raspberry Pi 2 B+</h2> 
					<p class="row"></p>
					<h2 class="fa fa-floppy-o">	 Speicher: 16 GB</h2>
					
            			</div>
            			<div class="icon">
              				<i class="fa fa-file-text"></i>
            			</div>
          			</div>
        	</div>
                
            <div class="col-lg-4 col-xs-6">
          	<!-- small box -->
          		<div class="small-box bg-yellow">
            			<div class="inner">
              				<h1>Hardware</h1>
                            <h1>daten</h1>
					
					<h2 class="fa fa-signal">  Prozessor:  BCM2836 Cortex-A7 ARMv7</h2>
					<p class="row"></p>
					<h2 class="fa fa-circle-o-notch">	 Taktrate: 900 MHz</h2> 
					<p class="row"></p>
					<h2 class="fa fa-cubes">	 Kern: Quad core</h2>
					<p class="row"></p>
					<h2 class="fa fa-floppy-o">  RAM: 1 GB</h2>
					
            			</div>
            			<div class="icon">
              				<i class="fa fa-file-text"></i>
            			</div>
          			</div>
        	</div>
                
            <div class="col-lg-4 col-xs-6">
          	<!-- small box -->
          		<div class="small-box bg-light-blue">
            			<div class="inner">
              				<h1>Netzwerk</h1>
                            <h1>daten</h1>
					
					<h2 class="fa  fa-plug">  Netzwerkanschluss: LAN</h2>
					<p class="row"></p>
					<h2 class="fa fa-dashboard">  Bitrate: 100 Mbit</h2>
					<p class="row"></p>
                    <h2 class="fa fa-list-alt">	 IP Adresse: <?php echo $type_1; ?></h2> 
					<p class="row"></p>
					<h2 class="fa fa-hourglass-half">  Online since: <?php echo $online_since_1; ?></h2>  
					
            			</div>
            			<div class="icon">
              				<i class="fa fa-file-text"></i>
            			</div>
          			</div>
        	</div>
                <!-- Leercontainer -->
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
                  <span class="label label-danger pull-right">7%</span>
                </span>
              </h4>

                                <div class="progress progress-xxs">
                                    <div class="progress-bar progress-bar-danger" style="width: 7%"></div>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <!-- /.control-sidebar-menu -->

                </div>
                <!-- /.tab-pane -->
                <!-- Stats tab content -->
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
    <!-- AdminLTE App -->
    <script src="dist/js/app.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>

    <!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. Slimscroll is required when using the
     fixed layout. -->

</body>
    
    <script>
 
    Morris.Line({
     element : 'process-chart',
     resize: true,
     data:[<?php echo $process_data; ?>],
     xkey:'time_stamp',
     ykeys:['Node2'],
     labels:['Node2'],
     hideHover:'auto',
     stacked:true,
     parseTime: false
    });
    
    Morris.Line({
     element : 'temperatur-chart',
     resize: true,
     data:[<?php echo $temperatur_data; ?>],
     xkey:'time_stamp',
     ykeys:['Node2'],
     labels:['Node2'],
     hideHover:'auto',
     stacked:true,
     parseTime: false
    });
    
    </script>

</html>

<?php   
    
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


//Delete old Data
//-------------------------------------------------------------------------------------------------
    $query = "DELETE FROM HardwareDataTest";
    $result = mysqli_query($connect, $query);
    mysqli_free_result( $result );
//-------------------------------------------------------------------------------------------------


//for Temperature Data
//-------------------------------------------------------------------------------------------------


//MasterNode Data   
//------------------------------------------------------------------------------------------------- 
    $query = "SELECT node, time_stamp, identifier, data FROM HardwareData WHERE node = 'MasterNode' and identifier = 'T' ORDER BY 'time_stamp'";
    $result = mysqli_query($connect, $query);
    $MasterNode_Node = '';
    $MasterNode_TimeStamp = '';
    $MasterNode_Identifier = '';
    $MasterNode_Data = '';
    $i = 0;

    while($row = mysqli_fetch_array($result))
    {
            $MasterNode_Node[$i] .= $row["node"];
            $MasterNode_TimeStamp[$i] .= $row["time_stamp"];
            $MasterNode_Identifier[$i] .= $row["identifier"];
            $MasterNode_Data[$i] .= $row["data"];  
            $i++;
    }

//Node1 Data
//-------------------------------------------------------------------------------------------------

    $query = "SELECT node, time_stamp, identifier, data FROM HardwareData WHERE node = 'Node 1' and identifier = 'T' ORDER BY 'time_stamp'";
    $result = mysqli_query($connect, $query);
    $Node1_Node = '';
    $Node1_TimeStamp = '';
    $Node1_Identifier = '';
    $Node1_Data = '';
    $j = 0;

    while($row = mysqli_fetch_array($result))
    {
            $Node1_Node[$j] .= $row["node"];
            $Node1_TimeStamp[$j] .= $row["time_stamp"];
            $Node1_Identifier[$j] .= $row["identifier"];
            $Node1_Data[$j] .= $row["data"];  
            $j++;
    }

//Node2 Data
//------------------------------------------------------------------------------------------------

    $query = "SELECT node, time_stamp, identifier, data FROM HardwareData WHERE node = 'Node 2' and identifier = 'T'ORDER BY 'time_stamp'";
    $result = mysqli_query($connect, $query);
    $Node2_Node = '';
    $Node2_TimeStamp = '';
    $Node2_Identifier = '';
    $Node2_Data = '';
    $k = 0;

    while($row = mysqli_fetch_array($result))
    {
            $Node2_Node[$k] .= $row["node"];
            $Node2_TimeStamp[$k] .= $row["time_stamp"];
            $Node2_Identifier[$k] .= $row["identifier"];
            $Node2_Data[$k] .= $row["data"];  
            $k++;
    }

$m = 0;
$n = 0;
$o = 0;
echo $min1 = 3 * min($i, $j, $k), "<br>";
for ($l = 0; $l < $min1; $l++) {
        if(($l) % 3 == 0){
            $query = "INSERT INTO HardwareDataTest(node, time_stamp, identifier, data)
                      VALUES('".$MasterNode_Node[$m]      ."','".$MasterNode_TimeStamp[$m]."',
                             '".$MasterNode_Identifier[$m]."','".$MasterNode_Data[$m]     ."')";
            $result = mysqli_query($connect, $query);
            mysqli_free_result( $result );
            $m++;
        }
        if(($l+2) % 3 == 0){
            $query = "INSERT INTO HardwareDataTest(node, time_stamp, identifier, data)
                      VALUES('".$Node1_Node[$n]      ."','".$Node1_TimeStamp[$n]."',
                             '".$Node1_Identifier[$n]."','".$Node1_Data[$n]     ."')";
            $result = mysqli_query($connect, $query);
            mysqli_free_result( $result );
            $n++;
        }
        if(($l+1) % 3 == 0){
            $query = "INSERT INTO HardwareDataTest(node, time_stamp, identifier, data)
                      VALUES('".$Node2_Node[$o]      ."','".$Node2_TimeStamp[$o]."',
                             '".$Node2_Identifier[$o]."','".$Node2_Data[$o]     ."')";
            $result = mysqli_query($connect, $query);
            mysqli_free_result( $result );
            $o++;
        }
}
//-------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------









//for Process Data
//-------------------------------------------------------------------------------------------------

//MasterNode Data   
//------------------------------------------------------------------------------------------------- 
    $query = "SELECT node, time_stamp, identifier, data FROM HardwareData WHERE node = 'MasterNode' and identifier = 'P' ORDER BY 'time_stamp'";
    $result = mysqli_query($connect, $query);
    $MasterNode_Node = '';
    $MasterNode_TimeStamp = '';
    $MasterNode_Identifier = '';
    $MasterNode_Data = '';
    $i = 0;

    while($row = mysqli_fetch_array($result))
    {
            $MasterNode_Node[$i] .= $row["node"];
            $MasterNode_TimeStamp[$i] .= $row["time_stamp"];
            $MasterNode_Identifier[$i] .= $row["identifier"];
            $MasterNode_Data[$i] .= $row["data"];  
            $i++;
    }

//Node1 Data
//-------------------------------------------------------------------------------------------------

    $query = "SELECT node, time_stamp, identifier, data FROM HardwareData WHERE node = 'Node 1' and identifier = 'P' ORDER BY 'time_stamp'";
    $result = mysqli_query($connect, $query);
    $Node1_Node = '';
    $Node1_TimeStamp = '';
    $Node1_Identifier = '';
    $Node1_Data = '';
    $j = 0;

    while($row = mysqli_fetch_array($result))
    {
            $Node1_Node[$j] .= $row["node"];
            $Node1_TimeStamp[$j] .= $row["time_stamp"];
            $Node1_Identifier[$j] .= $row["identifier"];
            $Node1_Data[$j] .= $row["data"];  
            $j++;
    }

//Node2 Data
//------------------------------------------------------------------------------------------------

    $query = "SELECT node, time_stamp, identifier, data FROM HardwareData WHERE node = 'Node 2' and identifier = 'P'ORDER BY 'time_stamp'";
    $result = mysqli_query($connect, $query);
    $Node2_Node = '';
    $Node2_TimeStamp = '';
    $Node2_Identifier = '';
    $Node2_Data = '';
    $k = 0;

    while($row = mysqli_fetch_array($result))
    {
            $Node2_Node[$k] .= $row["node"];
            $Node2_TimeStamp[$k] .= $row["time_stamp"];
            $Node2_Identifier[$k] .= $row["identifier"];
            $Node2_Data[$k] .= $row["data"];  
            $k++;
    }

$m = 0;
$n = 0;
$o = 0;
echo $min2 = 3 * min($i, $j, $k), "<br>";
for ($l = 0; $l < $min2; $l++) {
        if(($l) % 3 == 0){
            $query = "INSERT INTO HardwareDataTest(node, time_stamp, identifier, data)
                      VALUES('".$MasterNode_Node[$m]      ."','".$MasterNode_TimeStamp[$m]."',
                             '".$MasterNode_Identifier[$m]."','".$MasterNode_Data[$m]     ."')";
            $result = mysqli_query($connect, $query);
            mysqli_free_result( $result );
            $m++;
        }
        if(($l+2) % 3 == 0){
            $query = "INSERT INTO HardwareDataTest(node, time_stamp, identifier, data)
                      VALUES('".$Node1_Node[$n]      ."','".$Node1_TimeStamp[$n]."',
                             '".$Node1_Identifier[$n]."','".$Node1_Data[$n]     ."')";
            $result = mysqli_query($connect, $query);
            mysqli_free_result( $result );
            $n++;
        }
        if(($l+1) % 3 == 0){
            $query = "INSERT INTO HardwareDataTest(node, time_stamp, identifier, data)
                      VALUES('".$Node2_Node[$o]      ."','".$Node2_TimeStamp[$o]."',
                             '".$Node2_Identifier[$o]."','".$Node2_Data[$o]     ."')";
            $result = mysqli_query($connect, $query);
            mysqli_free_result( $result );
            $o++;
        }
}


//-------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------

?>

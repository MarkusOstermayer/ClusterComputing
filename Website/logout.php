<?php
    session_start();
    session_destroy();

    //Cookies entfernen
    setcookie("identifier","",time()-(60)); 
    setcookie("securitytoken","",time()-(60)); 

    header('Location: http://markuspi.bulme.at/admin/login.html')
?>
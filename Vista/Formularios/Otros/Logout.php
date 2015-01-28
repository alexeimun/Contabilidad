<?php


    session_start();
    session_unset();
    session_destroy();


    echo '<script language = javascript>self.location = "Login.php";</script> ';
    exit();
?>
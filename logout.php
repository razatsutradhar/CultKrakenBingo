<?php
    // include(dirname(__DIR__).'/userlogin.php');
    session_start();
    session_destroy();
    header("location: ./userlogin.php");
?>
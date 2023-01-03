<?php
session_start();
echo $_SESSION['user'];
echo time()-$_SESSION['time'];
if(!isset($_SESSION['user']) || time()-$_SESSION['time']>3600){
    $_SESSION=array(); 
    session_destroy();
    header('Location:login.php');
}


?>
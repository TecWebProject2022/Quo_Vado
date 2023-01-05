<?php 
session_start();
$_SESSION=array();
unset($_SESSION['user']); 
unset($_SESSION['time']);
header('Location:index.html');
?>
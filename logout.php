<?php 
session_start();
unset($_SESSION['user']); 
unset($_SESSION['time']);
header('Location:index.html');
?>
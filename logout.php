<?php 
session_start();
unset($_SESSION['user']); 
    unset($_SESSION['time']);
    unset($_SESSION['info']);
    unset($_SESSION['errorf']);
    unset($_SESSION['LAUREA']);
    unset($_SESSION['data']);
    unset($_SESSION['errori1']);
    unset($_SESSION['commenti']);
    unset($_SESSION['errorf']);
    unset($_SESSION['query7']);
    unset($_SESSION['nuova']);
    unset($_SESSION['vecchia']);
    unset($_SESSION['error']);
    unset( $_SESSION['commento']);
header('Location:index.html');
?>
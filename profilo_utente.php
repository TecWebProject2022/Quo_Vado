<?php
session_start();
echo $_SESSION['user'];
echo time()-$_SESSION['time'];
// Se non hai fatto il login o la tua sessione (durata max 1 h di inattività) è scaduta
if(!isset($_SESSION['user']) || time()-$_SESSION['time']>3600){
    unset($_SESSION['user']); 
    unset($_SESSION['time']);
    $_SESSION['sessione']='<p>Sessione Scaduta</p>';
    header('Location:login.php');
}
?>
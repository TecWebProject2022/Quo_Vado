<?php
require_once 'utilita.php';
require_once 'database.php';
session_start(); 
if(check()){
    if($_SESSION['user']!='admin')
        header("Location:area_utente.php");
    else
        header("Location:area_admin.php");
}
else if(isset($_SESSION['user']) &&  isset($_SESSION['time'])  && time()-$_SESSION['time']>3600){
    unset($_SESSION['user']); 
    unset($_SESSION['time']);
    $_SESSION['sessione']='<p class="error">Sessione Scaduta</p>';
}

$content='';
$username='';
$password='';
$errori='';
$sessione_tag='';
if(isset($_POST['submit'])){
    $username=PulisciInput($_POST['username']);
    $password=PulisciInput($_POST['password']);
    if(!strlen($username)){
        $errori.='<p class="error">Il campo username non può essere vuoto</p>';
    }
    else if (!preg_match('/^[@a-zA-Z0-9._-]{4,40}$/',$username)){
        $errori.='<p class="error">Il campo username non può contenere spazi e deve contenere da 4 a 40 caratteri alfanumerici (sono ammessi i seguenti caratteri: @ . _ - )</p>';
    }
    if(!strlen($password)){
        $errori.='<p class="error">Il campo password non può essere vuoto</p>';
    }
    else if (!preg_match('/^[@a-zA-Z0-9._-]{4,20}$/',$password)){
        $errori.='<p class="error">Il campo password non può contenere spazi e deve contenere da 4 a 20 caratteri alfanumerici (sono ammessi i seguenti caratteri: @ . _ - )</p>';
    }


if(!$errori){
    $db=new Connection();
    $dbOK=$db->Connect();
    if($dbOK){
        $query = "SELECT * FROM Utente inner join Credenziale on nome_utente=utente WHERE nome_utente=\"$username\" and pw=\"$password\" and attuale=1 ";
      if($db->Login($query)){
        $_SESSION['user']=$username;
        $_SESSION['time']=time();
        if($_SESSION['user']!='admin')
            header("Location:area_utente.php");
        else
            header("Location:area_admin.php");
      }
      else{
        $errori.='<p class="error">Username o password non corretti</p>';
      }
    $db->Disconnect();    
    }
    else{
        $errori.='<p class="error">Ci scusiamo, la connessione non &egrave; riuscita. Per favore, attendere e riprovare</p>';
    }   
}
}
$content=file_get_contents('login.html');

if(isset($_SESSION['sessione'])){
    $sessione_tag=$_SESSION['sessione'];
    unset($_SESSION['sessione']);
}
$content=str_replace('<sessione/>',$sessione_tag,$content);  
$content=str_replace('<errori />',$errori,$content);
$content=str_replace('<username/>',$username,$content);
$content=str_replace('<password/>',$password,$content);
echo $content;
?>
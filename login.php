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
        $errori.='<li class="error">Il campo username non può essere vuoto</li>';
    }
    else if (!preg_match('/^[@a-zA-Z0-9._-]{4,40}$/',$username)){
        $errori.='<li class="error">Il campo username non può contenere spazi e deve contenere da 4 a 40 caratteri alfanumerici (sono ammessi i seguenti caratteri: @ . _ - )</li>';
    }
    if(!strlen($password)){
        $errori.='<li class="error">Il campo password non può essere vuoto</li>';
    }
    else if (!preg_match('/^[@a-zA-Z0-9._-]{4,20}$/',$password)){
        $errori.='<li class="error">Il campo password non può contenere spazi e deve contenere da 4 a 20 caratteri alfanumerici (sono ammessi i seguenti caratteri: @ . _ - )</li>';
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
        $errori.='<li class="error">Username o password non corretti</li>';
      }
    $db->Disconnect();    
    }
    else{
        $errori.='<li class="error">Connessione non riuscita, attendere e riprovare</li>';
    }   
}
}
$content=file_get_contents('login.html');
if($errori){
    $errori='<ul>'.$errori.'</ul>';
}

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
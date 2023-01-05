<?php
session_start(); 
require_once 'database.php';
require_once 'utilita.php';
$content='';
$username='';
$password='';
$errori='';
$sessione_tag='';
if(isset($_POST['submit'])){
    $username=PulisciInput($_POST['username']);
    $password=PulisciInput($_POST['password']);
    if(!strlen($username)){
        $errori.='<li>Il campo username non può essere vuoto</li>';
    }
    else if (!preg_match('/^[@a-zA-Z0-9._-]{4,40}$/',$username)){
        $errori.='<li>Il campo username non può contenere spazzi e deve contenere da 4 a 40 caratteri alfanumerici (sono ammessi i seguenti caratteri: @ . _ - )</li>';
    }
    if(!strlen($password)){
        $errori.='<li>Il campo password non può essere vuoto</li>';
    }
    else if (!preg_match('/^[@a-zA-Z0-9._-]{4,20}$/',$password)){
        $errori.='<li>Il campo password non può contenere spazzi e deve contenere da 4 a 20 caratteri alfanumerici (sono ammessi i seguenti caratteri: @ . _ - )</li>';
    }


if(!$errori){
    $db=new Connection();
    $dbOK=$db->Connect();
    if($dbOK){
      if($db->Login($username,$password)){
        $_SESSION['user']=$username;
        $_SESSION['time']=time();
        if($_SESSION['user']!='admin')
            header("Location:profilo_utente.php");
        else
            header("Location:profilo_admin.php");
      }
      else{
        $errori.='<li>Username o password non correti</li>';
      }
    $db->Disconnect();    
    }
    else{
        $errori.='<li>Connection not succefultlly, please try againg</li>';
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
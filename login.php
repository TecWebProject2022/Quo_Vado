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
        $errori.='<li>Campo username vuoto</li>';
    }
    if(strlen($username)>40){
        $errori.='<li>Username non compatibile</li>';
    }
    if(!strlen($password)){
        $errori.='<li>Campo username vuoto</li>';
    }
    if(strlen($password)>20){
        $errori.='<li>Password non compatibile</li>';
    }


if(!$errori){
    $db=new Connection();
    $dbOK=$db->Connect();
    if($dbOK){
      if($db->Login($username,$password)){
        $_SESSION['user']=$username;
        $_SESSION['time']=time();
        header("Location:profilo_utente.php");
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
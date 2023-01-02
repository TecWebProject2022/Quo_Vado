<?php 
require_once 'database.php';
require_once 'utilita.php';
$content='';
$username='';
$password='';
$errori='';

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
        session_start();
        $_SESSION['user']=$username;
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
$content=str_replace('<errori/>',$errori,$content);
$content=str_replace('<username/>',$username,$content);
$content=str_replace('<password/>',$password,$content);
echo $content;
?>
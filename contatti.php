<?php 
require_once 'utilita.php';
require_once 'database.php';
$email='';
$commento='';
$info='';
$errori='';
if(isset($_POST['submit'])){
    $email=PulisciInput($_POST['email']);
    $commento=isset($_POST['commento']) ? PulisciInput($_POST['commento']) : "";
    if(!preg_match('/^([\w\-\+\.]+)\@([\w\-\+\.]+)\.([\w\-\+\.]+)$/',$email)){
        $errori.='<p class="error">Il campo email non corrisponde ad una email valida</p>';
    }
    if(!preg_match('/^[ !?@a-zA-Z0-9.,_-]{10,400}$/',$commento)){
        $errori.='<p class="error">Il campo commento/mesaggio può contenere da 10 a 400 caratteri (sono amessi i numeri da 0 a 9 e i seguenti simboli: .,_-!?@)</p>';
    }
    if($errori==""){
        $db=new Connection();
        $dbOK=$db->Connect();
        if($dbOK){
            $insert="INSERT INTO Domande( email, data, descrizione) VALUES(\"".$email."\",now(),\"".$commento."\");";
            $q=$db->Insert($insert);
            if($q){
                $info.="<p class=\"invito\">Inserimento avvenuto con successo, Grazie di cuore del tuo aiuto, a presto.</p>";
                $nome='';
                $cognome='';
                $email='';
                $commento='';
                $errori='';
            }
            else{
                $errori.="<<p class=\"error\">Inserimento non riuscito</p>";
            }
        }
        else{
            $errori.="<p class=\"error\">Connessione non riuscita</p>";
        }
    }
}
$content=file_get_contents('contatti.html');
$content=str_replace('<valoreEmail/>',$email,$content);
$content=str_replace('<valoreMessagio/>',$commento,$content);
$content=str_replace('<errori/>',$errori,$content);
$content=str_replace(' <info/>',$info,$content);
$info='';
echo $content;
?>
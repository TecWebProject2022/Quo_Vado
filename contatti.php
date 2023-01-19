<?php 
require_once 'utilita.php';
require_once 'database.php';
$email='';
$commento='';
$errori='<ul>';

if(isset($_POST['submit'])){
    $email=PulisciInput($_POST['email']);
    $commento=PulisciInput($_POST['commento']);
    if(!preg_match('/^([\w\-\+\.]+)\@([\w\-\+\.]+)\.([\w\-\+\.]+)$/',$email)){
        $errori.='<li>Il campo email non corrisponde ad una email valida</li>';
    }
    if(!preg_match('/^[!?@a-zA-Z .,_-]{10,400}$/',$commento)){
        $errori.='<li>Il campo commento/mesaggio può contenere da 10 a 500 caratteri (sono amessi i seguenti simboli: .,_-!?@)</li>';
    }
    if($errori=="<ul>"){
        $db=new Connection();
        $dbOK=$db->Connect();
        if($dbOK){
            $insert="INSERT INTO Domande( email, data, descrizione) VALUES(\"".$email."\",now(),\"".$commento."\");";
            $q=$db->Insert($insert);
            if($q){
                $errori.="<li>Inserimento con successo, Grazie di cuore del tuo aiuto, a presto.</li>";
                $nome='';
                $cognome='';
                $email='';
                $commento='';
            }
            else{
                $errori.="<li>Inserimento non riuscito</li>";
            }
        }
        else{
            $errori.="<li>Connessione non riuscita</li>";
        }
    }
}
$errori.='</ul>';
$content=file_get_contents('contatti.html');
$content=str_replace('<valoreEmail/>',$email,$content);
$content=str_replace('<valoreMessagio/>',$commento,$content);
$content=str_replace('<errori/>',$errori,$content);
echo $content;
?>
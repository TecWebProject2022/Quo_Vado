<?php 
require_once 'utilita.php';
require_once 'database.php';
$nome='';
$cognome='';
$email='';
$commento='';
$errori='<ul>';

if(isset($_POST['submit'])){
    $nome=PulisciInput($_POST['name']);
    $cognome=PulisciInput($_POST['last_name']);
    $email=PulisciInput($_POST['email']);
    $commento=PulisciInput($_POST['commento']);
    if(!preg_match('/^[a-zA-Z ]{2,20}$/',$nome)){
        $errori.='<li>Il campo nome non può essere vuoto e può contenere numeri o caratteri speciali, deve avere una lunghezza compresa da 2 a 20 caratteri</li>';
    }
    if(!preg_match('/^[a-zA-Z ]{3,30}$/',$cognome)){
        $errori.='<li>Il campo cognome non può essere vuoto e può contenere numeri o caratteri speciali, deve avere una lunghezza compresa da 2 a 40 caratteri</li>';
    }
    if(!$email){
        $errori.='<li>Il campo email non può essere vuoto</li>';
    }
    if(!preg_match('/^([\w\-\+\.]+)\@([\w\-\+\.]+)\.([\w\-\+\.]+)$/',$email)){
        $errori.='<li>Il campo email non corrisponde ad una email valida</li>';
    }
    if(!preg_match('/^[!?@a-zA-Z.,_-]{10,500}$/',$commento)){
        $errori.='<li>Il campo commento/mesaggio può contenere da 10 a 500 caratteri (sono amessi i seguenti simboli: .,_-!?@)</li>';
    }
    if($errori=="<ul>"){
        $db=new Connection();
        $dbOK=$db->Connect();
        if($dbOK){
            $insert="INSERT INTO Domande(nome, cognome, email, data, descrizione) VALUES(\"".$nome."\",\"".$cognome."\",\"".$email."\",curdate(),\"".$commento."\");";
            $q=$db->Insert($insert);
            if($q){
                $errori.="<li>Inserimento con successo</li>";
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
$content=str_replace('<valoreNome/>',$nome,$content);
$content=str_replace('<valoreCognome/>',$cognome,$content);
$content=str_replace('<valoreEmail/>',$email,$content);
$content=str_replace('<valoreMessagio/>',$commento,$content);
$content=str_replace('<errori/>',$errori,$content);

echo $content;
?>
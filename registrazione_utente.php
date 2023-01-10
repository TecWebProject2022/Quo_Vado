<?php
require_once 'utilita.php';
require_once 'database.php';
$nome='';
$cognome='';
$data='';
$genere='';
$scuola='';
$username='';
$password='';
$rippassword='';
$errori='<ul>';
$content=file_get_contents('registrazione_utente.html');

if(isset($_POST['submit'])){
    $nome=PulisciInput($_POST['name']);
    $cognome=PulisciInput($_POST['last_name']);
    $data=PulisciInput($_POST['birthday']);
    $genere=PulisciInput($_POST['gender']);
    $scuola=PulisciInput($_POST['school']);
    $username=PulisciInput($_POST['username']);
    $password=PulisciInput($_POST['password']);
    $rippassword=PulisciInput($_POST['repeat_password']);
    if(!preg_match('/^[a-zA-Z ]{2,20}$/',$nome)){
        $errori.='<li>Il campo nome non può essere vuoto e può contenere numeri o caratteri speciali, deve avere una lunghezza compresa da 2 a 20 caratteri</li>';
    }
    if(!preg_match('/^[a-zA-Z ]{2,40}$/',$cognome)){
        $errori.='<li>Il campo cognome non può essere vuoto e può contenere numeri o caratteri speciali, deve avere una lunghezza compresa da 2 a 40 caratteri</li>';
    }
    if(!preg_match('/\d{4}\-\d{2}\-\d{2}/',$data)){
        $errori.='<li>La data  inserita non rispetta il seguente schema: dd/mm/aaaa</li>';
    }
    if (!preg_match('/^[@a-zA-Z0-9._-]{4,40}$/',$username)){
        $errori.='<li>Il campo username vuoto e  non può contenere spazzi e deve contenere da 4 a 40 caratteri alfanumerici (sono ammessi i seguenti caratteri: @ . _ - )</li>';
    }
    if (!preg_match('/^[@a-zA-Z0-9._-]{4,20}$/',$password)){
        $errori.='<li>Il campo password vuoto e non può contenere spazzi e deve contenere da 4 a 20 caratteri alfanumerici (sono ammessi i seguenti caratteri: @ . _ - )</li>';
    }
    if($password!=$rippassword){
        $errori.='<li>Le due password inserite non corrispondono</li>';
    }
    if($errori=="<ul>"){
       
        $db=new Connection();
        $dbOK=$db->Connect();
        if($dbOK){
            $query="Select nome_utente from Utente where nome_utente=\"".$username."\"";
            if($r=$db->ExecQueryAssoc($query)){
                $errori.="Username già registrato";
            }
            
            else{
                $insert="INSERT INTO Utente(nome_utente, nome, cognome,data_nascita, genere, scuola_sup) VALUES(\"".$username."\",\"".$nome."\",\"".$cognome."\",\"".$data."\",\"".$genere."\",\"".$scuola."\");";
            
                $q=$db->Insert($insert);
                if($q){
                    $errori.="<li>Inserimento con successo</li>";
                }
                else{
                    $errori.="<li>Inserimento non riuscito</li>";
                }
                
            }
            $db->Disconnect();
        }
        else{
            $errori.="Connessione non riuscita";
        }
        
    }

}
$errori=$errori."</ul>";
$content=str_replace('<valoreNome/>',$nome,$content);
$content=str_replace('<valoreCognome/>',$cognome,$content);
$content=str_replace('<valoreDataNascita/>',$data,$content);
$content=str_replace('<valoreGenere/>',$genere,$content);
$content=str_replace('<valoreScuola/>',$scuola,$content);
$content=str_replace('<Username/>',$username,$content);
$content=str_replace('<valorePassword/>',$password,$content);
$content=str_replace('<errori/>',$errori,$content);
echo $content;

?>
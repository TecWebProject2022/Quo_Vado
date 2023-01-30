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
        $errori.='<li class="error">Il campo nome non può essere vuoto e può contenere numeri o caratteri speciali, deve avere una lunghezza compresa da 2 a 20 caratteri</li>';
    }
    if(!preg_match('/^[a-zA-Z ]{3,30}$/',$cognome)){
        $errori.='<li class="error">Il campo cognome non può essere vuoto e può contenere numeri o caratteri speciali, deve avere una lunghezza compresa da 2 a 40 caratteri</li>';
    }
    if(!preg_match('/\d{4}\-\d{2}\-\d{2}/',$data)){
        $errori.='<li class="error">La data  inserita non rispetta il seguente schema: gg/mm/aaaa</li>';
    }
    if (!preg_match('/^[@a-zA-Z0-9._-]{4,40}$/',$username)){
        $errori.='<li class="error">Il campo username  non può essere vuoto e  non può contenere spazzi e deve contenere da 4 a 40 caratteri alfanumerici (sono ammessi i seguenti caratteri: @ . _ - )</li>';
    }
    if (!preg_match('/^[@a-zA-Z0-9._-]{4,20}$/',$password)){
        $errori.='<li class="error">Il campo password non può essere vuoto e non può contenere spazzi e deve contenere da 4 a 20 caratteri alfanumerici (sono ammessi i seguenti caratteri: @ . _ - )</li>';
    }
    if($password!=$rippassword){
        $errori.='<li class="error">Le due password inserite non corrispondono</li>';
    }
    if($errori=="<ul>"){
       
        $db=new Connection();
        $dbOK=$db->Connect();
        if($dbOK){
            $query="Select nome_utente from Utente where nome_utente=\"".$username."\"";
            if($r=$db->ExecQueryAssoc($query)){
                $errori.="<li class='error'>Username già registrato</li>";
            }
            
            else{
                $insert="INSERT INTO Utente(nome_utente, nome, cognome,data_nascita, genere, scuola_sup) VALUES(\"".$username."\",\"".$nome."\",\"".$cognome."\",\"".$data."\",\"".$genere."\",\"".$scuola."\");";
                $insert.="INSERT INTO Credenziale(pw, data_inserimento, utente, attuale) VALUES('".$password."',curdate(),'".$username."',1); ";
            
                $q=$db->multiInsert($insert);
                if($q){
                    $errori.="<li class='invito'>Inserimento con successo</li>";
                    $nome='';
                    $cognome='';
                    $data='';
                    $genere='';
                    $scuola='';
                    $username='';
                    $password='';
                    $rippassword='';
                }
                else{
                    $errori.="<li class='error'>Inserimento non riuscito</li>";
                }
                
            }
            $db->Disconnect();
        }
        else{
            $errori.="<li class='error'>Connessione non riuscita</li>";
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

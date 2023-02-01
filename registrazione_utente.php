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
$errori='';
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
        $errori.='<p class="error">Il campo nome non può essere vuoto e può contenere numeri o caratteri speciali, deve avere una lunghezza compresa da 2 a 20 caratteri</p>';
    }
    if(!preg_match('/^[a-zA-Z ]{3,30}$/',$cognome)){
        $errori.='<p class="error">Il campo cognome non può essere vuoto e può contenere numeri o caratteri speciali, deve avere una lunghezza compresa da 2 a 40 caratteri</p>';
    }
    if(!preg_match('/\d{4}\-\d{2}\-\d{2}/',$data)){
        $errori.='<p class="error">La data  inserita non rispetta il seguente schema: gg/mm/aaaa</p>';
    }
    if (!preg_match('/^[@a-zA-Z0-9._-]{4,40}$/',$username)){
        $errori.='<p class="error">Il campo username  non può essere vuoto e  non può contenere spazzi e deve contenere da 4 a 40 caratteri alfanumerici (sono ammessi i seguenti caratteri: @ . _ - )</p>';
    }
    if (!preg_match('/^[@a-zA-Z0-9._-]{4,20}$/',$password)){
        $errori.='<p class="error">Il campo password non può essere vuoto e non può contenere spazzi e deve contenere da 4 a 20 caratteri alfanumerici (sono ammessi i seguenti caratteri: @ . _ - )</p>';
    }
    if($password!=$rippassword){
        $errori.='<p class="error">Le due password inserite non corrispondono</p>';
    }
    if($errori==""){
       
        $db=new Connection();
        $dbOK=$db->Connect();
        if($dbOK){
            $query="Select nome_utente from Utente where nome_utente=\"".$username."\"";
            if($r=$db->ExecQueryAssoc($query)){
                $errori.="<p class='error'>Username già registrato</p>";
            }
            
            else{
                $insert="INSERT INTO Utente(nome_utente, nome, cognome,data_nascita, genere, scuola_sup) VALUES(\"".$username."\",\"".$nome."\",\"".$cognome."\",\"".$data."\",\"".$genere."\",\"".$scuola."\");";
                $insert.="INSERT INTO Credenziale(pw, data_inserimento, utente, attuale) VALUES('".$password."',curdate(),'".$username."',1); ";
            
                $q=$db->multiInsert($insert);
                if($q){
                    $errori.="<p class='invito'>Registrazione avvenuta con successo</p>";
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
                    $errori.="<p class='error'>Registrazione non riuscito</p>";
                }
                
            }
            $db->Disconnect();
        }
        else{
            $errori.="<p class='error'>Connessione non riuscita</p>";
        }
        
    }

}
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

<?php
session_start();
echo time()-$_SESSION['time'];
require_once 'utilita.php';
require_once 'database.php';
// Se non hai fatto il login o la tua sessione (durata max 1 h di inattività) è scaduta
if(!isset($_SESSION['user']) || !isset($_SESSION['time']) || time()-$_SESSION['time']>3600){
    unset($_SESSION['user']); 
    unset($_SESSION['time']);
    $_SESSION['sessione']='<p>Sessione Scaduta</p>';
    header('Location:login.php');
}
$menu1='<nav id="visible-sottomenu" aria-label="sotto menù di area riservata">
<ul>
    <li><a href="#Commenti">Commenti rilasciti</a></li>
    <li><a href="#CambioPw">Cambia password</a></li>
    <li><a href="logout.php">Logout</a></li>
</ul>
</nav>';
$menu2='<nav id="visible-sottomenu" aria-label="sotto menù di area riservata">
<ul>
    <li><a href="#Commenti">Commenti rilasciti</a></li>
    <li><a href="logout.php">Logout</a></li>
</ul>
</nav>';
$vecchia='';
$nuova='';
$content=file_get_contents("area_riservata.html");
$user=$_SESSION['user'];
if($user!='user'){
    $content=str_replace('<sottomenu/>',$menu1,$content);
}
else{
    $content=str_replace('<sottomenu/>',$menu2,$content);
}

$errori1='<ul>';
$errori='';
$contenuto='';

$query1="Select  * from Utente where nome_utente=\"$user\";";
$db=new Connection();
$dbOK=$db->Connect();
if($dbOK){
    if($res1=$db->ExecQueryAssoc($query1)){
        $contenuto.="<h2>Dati Personali</h2>";
        $contenuto.="<dl>";
        $contenuto.="<dt>Nome Utente: </dt><dd>".$res1[0]['nome_utente']."</dd>";
        $contenuto.="<dt>Nome: </dt><dd>".$res1[0]['nome']."</dd>";
        $contenuto.="<dt>:Cognome: </dt><dd>".$res1[0]['cognome']."</dd>";
        $contenuto.="<dt>Data di nascita: </dt><dd>".$res1[0]['data_nascita']."</dd>";
        $contenuto.="<dt>Genere: </dt><dd>".$res1[0]['genere']."</dd>";
        $contenuto.="<dt>Scuola superiore frequentata: </dt><dd>".$res1[0]['scuola_sup']."</dd>";
        $contenuto.="</dl>";
        $query2="Select ateneo, classe,corso, datai, dataf,punteggio_scuola_provenienza  from Iscrizione where nome_utente=\"$user\"";
        if($res2=$db->ExecQueryAssoc($query2)){
            $contenuto.="<h2>Iscrizioni</h2> ";
            $contenuto.="<ul>";
            foreach($res2 as $i){
                $contenuto.="<li>";
                $contenuto.="<ul><li>Ateneo: ".$i['ateneo']."</li>";
                $contenuto.="<ul><li>Classe di Laurea: ".$i['classe']."</li>";
                $contenuto.="<ul><li>Corso di Studi: ".$i['corso']."</li>";
                $contenuto.="<ul><li>Data inizio studi: ".date("d/m/Y",strtotime($i['datai']))."</li>";
                $contenuto.="<ul><li>Data fine studi: ".date("d/m/Y",strtotime($i['dataf']))."</li>";
                $contenuto.="<ul><li>Punteggio di affinità con la scuoa superiore frequentata: ".$i['punteggio_scuola_provenienza']."</li></ul>";
                $contenuto.="</li>"; 
            }
            $contenuto.="</ul>";
            $contenuto.="<h2 id='Commenti'>Commenti rilasciati</h2>";
            $query3="";
        }
        else{
            $errori.="<p>Siamo spiacenti ma i dati non sono al momento dipsonibili</p>";
        }
    }
    else{
        $errori.="<p>Siamo spiacenti ma i dati non sono al momento dipsonibili</p>";
    }
    $db->Disconnect();
}
else{
    $errori.="<p>Siamo spiacenti ma i dati non sono al momento dipsonibili</p>";
}
if($user!='user'){

    $contenuto.='<h2 id="CambioPw">Cambia Password</h2><form action="area_utente.php" method="post" >
    <fieldset>Cambio password</fieldset>
    <label for="oldpassword"><span lang="en">Immetti la tua vecchia Password: </span></label>
    <span><input  value="<old>" type="password" id="oldpassword" name="Vecchiapassword" placeholder="Immetti la tua vecchia Password" maxlength="20"                      
        data-msg-invalid="Il campo password non può contenere spazzi e deve contenere da 4 a 20 caratteri alfanumerici (sono ammessi i seguenti caratteri: @ . _ - ), controlla e riprova"
        data-msg-empty="Il campo vecchia password non può essere vuoto" /></span>
    <label for="newpassword"><span lang="en">Immetti la tua nuova Password: </span></label>
    <span><input  value="<new>" type="password" id="newpassword" name="newpassword" placeholder="Immetti la tua nuova password" maxlength="20"                      
        data-msg-invalid="Il campo password non può contenere spazzi e deve contenere da 4 a 20 caratteri alfanumerici (sono ammessi i seguenti caratteri: @ . _ - ), controlla e riprova"
        data-msg-empty="Il campo nuova password non può essere vuoto" /></span>
    <label for="repeat"><span lang="en">Ripeti la Password: </span></label>
    <span><input  value="" type="password" id="repeat" name="repepassword" placeholder="Ripeti la password" maxlength="20"                      
        data-msg-empty="Il campo repeti password non può essere vuoto" /></span>   
    <input type="submit" id="submit" name="submit1" value="Salva"/>
    <input type="reset" id="reset" name="reset" value="Cancella tutto"/>
    </form>
    </err/>';
}
if(isset($_POST['submit1']) && check()){
    $vecchia=PulisciInput($_POST['Vecchiapassword']);
    $nuova=PulisciInput($_POST['newpassword']);
    $rep=PulisciInput($_POST['repepassword']);
    echo $vecchia;
    echo $nuova;
    echo $rep;
    $errori1='<ul>';
    if (!preg_match('/^[@a-zA-Z0-9._-]{4,20}$/',$vecchia)){
        $errori1.='<li>Il campo vecchia password non può essere vuoto e non può contenere spazzi e deve contenere da 4 a 20 caratteri alfanumerici (sono ammessi i seguenti caratteri: @ . _ - )</li>';
    }
    if (!preg_match('/^[@a-zA-Z0-9._-]{4,20}$/',$nuova)){
        $errori1.='<li>Il campo nuova password non può essere vuoto e non può contenere spazzi e deve contenere da 4 a 20 caratteri alfanumerici (sono ammessi i seguenti caratteri: @ . _ - )</li>';
    }
    if($nuova!=$rep){
        $errori1.='<li>Il campo nuova password e ripeti la password non corrispondono</li>';
    }
    if($errori1=='<ul>'){
        $db=new Connection();
        $dbOK=$db->Connect();
        if($dbOK){
            $query="Select * from Credenziale where utente=\"".$user."\" && pw=\"".$nuova."\";";
            if($r=$db->ExecQueryAssoc($query)){
                $errori1.="<li>Password già usata</li>";
            }
            else{
                $query3="Select * from Credenziale where utente=\"".$user."\" && pw=\"".$vecchia."\";";
                if($r=$db->ExecQueryAssoc($query3)){
                $query2="UPDATE Credenziale SET attuale=0 WHERE utente=\"".$user."\" and pw=\"".$vecchia."\";";
                $query2.="INSERT INTO Credenziale(pw, data_inserimento, utente, attuale) VALUES('".$nuova."',curdate(),'".$user."',1);";
                $q=$db->multiInsert($query2);
                if($q){
                    $errori1.="<li>Password modificata con successo</li>";
                }
                else{
                    $errori1.="<li>Cambiamento password non riuscito. I sistemi sono al momentamentamnete non disponibili</li>";
                } 
            }
            else{
                $errori1.="<li>la vecchia password inserita non corrisponde</li>";
            }
            
            }
        $db->Disconnect();
        }
    $errori1.="</ul>";   
    }
    
}
$contenuto=str_replace("<new>",$nuova,$contenuto);
$contenuto=str_replace("<old>",$vecchia,$contenuto);
$content=str_replace("<content/>",$contenuto,$content);
$content=str_replace("<errori/>",$errori,$content);
$content=str_replace("</err/>",$errori1,$content);
echo $content;
?>
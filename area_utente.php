<?php
session_start();
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
    <li><a href="#Aggiungi">Aggiungi un commento</a></li>
    <li><a href="#CambioPw">Cambia password</a></li>
    <li><a href="logout.php">Logout</a></li>
</ul>
</nav>';
$menu2='<nav id="visible-sottomenu" aria-label="sotto menù di area riservata">
<ul>
    <li><a href="#Commenti">Commenti rilasciti</a></li>
    <li><a href="#Aggiungi">Aggiungi un commento</a></li>
    <li><a href="logout.php">Logout</a></li>
</ul>
</nav>';
$vecchia='';
$res3='';
$nuova='';
$errorf='<ul>';
$content=file_get_contents("area_riservata.html");
$user=$_SESSION['user'];
if($user!='user'){
    $content=str_replace('<sottomenu/>',$menu1,$content);
}
else{
    $content=str_replace('<sottomenu/>',$menu2,$content);
}
$commento='';
$errori1='<ul>';
$errori='';
$commenti='<ul>';
$contenuto='';
$cancella='';
$query1="Select  * from Utente where nome_utente=\"$user\";";
$db=new Connection();
$dbOK=$db->Connect();
if($dbOK){
    if($res1=$db->ExecQueryAssoc($query1)){
        $contenuto.="<h2 class='titles_utente'>Dati Personali</h2>";
        $contenuto.="<dl id='info_utente'>";
        $contenuto.="<dt>Nome Utente: </dt><dd>".$res1[0]['nome_utente']."</dd>";
        $contenuto.="<dt>Nome: </dt><dd>".$res1[0]['nome']."</dd>";
        $contenuto.="<dt>Cognome: </dt><dd>".$res1[0]['cognome']."</dd>";
        $contenuto.="<dt>Data di nascita: </dt><dd>".date("d/m/Y",strtotime($res1[0]['data_nascita']))."</dd>";
        $contenuto.="<dt>Genere: </dt><dd>".$res1[0]['genere']."</dd>";
        $contenuto.="<dt>Scuola superiore frequentata: </dt><dd>".$res1[0]['scuola_sup']."</dd>";
        $contenuto.="</dl>";
        $query2="Select ateneo, classe,corso, datai, dataf,punteggio_scuola_provenienza  from Iscrizione where nome_utente=\"$user\"";
        if($res2=$db->ExecQueryAssoc($query2)){
            $contenuto.="<h2 class='titles_utente'>Iscrizioni</h2> ";
            $contenuto.="<ul>";
            foreach($res2 as $i){
                $contenuto.="<li>";
                $contenuto.="<ul><li>Ateneo: ".$i['ateneo']."</li>";
                $contenuto.="<ul><li>Classe di Laurea: ".$i['classe']."</li>";
                $contenuto.="<ul><li>Corso di Studi: ".$i['corso']."</li>";
                $contenuto.="<ul><li>Data inizio studi: ".date("d/m/Y",strtotime($i['datai']))."</li>";
                $contenuto.="<ul><li>Data fine studi: ".date("d/m/Y",strtotime($i['dataf']))."</li>";
                $contenuto.="<ul><li>Punteggio di affinità con la scuola superiore frequentata: ".$i['punteggio_scuola_provenienza']."</li></ul>";
                $contenuto.="</li>"; 
            }
            $contenuto.="</ul>";
            $contenuto.="<h2 id='Commenti'>Commenti rilasciati</h2>";
            $query3="Select classe_laurea,datav,commento,p_complessivo,p_acc_fisica,p_servizio_inclusione,tempestivita_burocratica,p_insegnamento,tag FROM Valutazione WHERE nome_utente=\"$user\"";
            if($res3=$db->ExecQueryNum($query3)){
                $contenuto.="<aside>
                <h2>Glossario</h2>
                <p>Ogni utente può esprime un giudizio con un valore da 1 a 5 su i seguenti ambiti riguardanti una classe di laurea</p>
                    <dl>
                        <dt>Valutazione complessiva:</dt>
                        <dd></dd>
                        <dt>Valutazione accessibilità fisica:</dt>
                        <dd></dd>
                        <dt>Valutazione sul servizio inclusione:</dt>
                        <dd></dd>
                        <dt>Valutazione sulla tempestività burocratica:</dt>
                        <dd></dd>
                        <dt>Valutazione sulla qualità di insegnamento:</dt>
                        <dd></dd>
                        <dt>Ambito di valutazione</dt>
                        <dd></dd>
                    </dl>
            </aside>";
                $contenuto.="<label id=\"cancellacomm\">Seleziona un commento e premi cancella per eliminarlo</label>";
                $contenuto.='<form aria-describedby="cancellacomm" action="area_utente.php"  method="post" >
                <fieldset><legend>Commenti</legend>';
                for($i=0;$i<count($res3);$i++){
                    
                        $contenuto.='<span><input type="checkbox" id="'.$i.'" name="commento[]" value="'.$i.'" /></span><label for="'.$i.'">
                        <ul><li>Data di emissione: '.date("d/m/Y",strtotime($res3[$i][1])).'</li>
                        <li>Classe di laurea: '.$res3[$i][0].'</li>
                        <li>Comemento: '.$res3[$i][2].'</li>
                        <li>Valutazione complessiva: '.$res3[$i][3].'</li>
                        <li>Valutazione accessibilità fisica: '.$res3[$i][4].'</li>
                        <li>Valutazione sul servizio inclusione: '.$res3[$i][5].'</li>
                        <li>Valutazione sulla tempestività burocratica: '.$res3[$i][6].'</li>
                        <li>Valutazione sulla qualità di insegnamento: '.$res3[$i][7].'</li>';
                        if($res3[$i][8]==1){
                            $contenuto.="<li>Valutazione riguardante l'ambito dell'inclusità</li></ul></label><br />";
                        }
                        else{
                            $contenuto.="<li>Valutazione riguardante l'ambito generale </li></ul></label><br />";
                        }      
                }
                $contenuto.='<input type="submit" id="submit2"  name="submit2" value="cancella"/></fieldset></form></commenterror>';
                
            }
            else{
                $contenuto.="<p>Siamo spiacenti non hai ancora inserito alcun commento</p>";
            }
        }
        else{
            $errori.="<p>Siamo spiacenti ma i dati non sono al momento disponibili</p>";
        }
    }
    else{
        $errori.="<p>Siamo spiacenti ma i dati non sono al momento disponibili</p>";
    }
}
else{
    $errori.="<p>Siamo spiacenti ma i dati non sono al momento disponibili</p>";
}
$query5="Select classe FROM Iscrizione where nome_utente=\"".$user."\";";

if($res5=$db->ExecQueryAssoc($query5)){
    $classi="<label for='classi'>Classi di Laurea:</label>

    <select id='classi' name='classel'>";
    foreach($res5 as $r){
       $classi.="<option value=\"".$r['classe']."\">".$r['classe']."</option>";
    }
    $classi.="</select>";
$contenuto.='<h2 id="Aggiungi">Aggiungi un commento</h2><label id="formdesc">Ti è consentito lasciare un solo commento per ogni classe di laurea e il contenuto testuale del commento dovrà contenere da 10 a 200 caraterri alfanumerici (sono ammessi i seguenti caratteri: @ . _ - )</label><form  aria-describedby="formdesc"action="area_utente.php"   method="post">
<fieldset>
<legend>Agguingi un commento</legend>'.$classi.'
<label for="commento"></label>
<span><textarea id="commento" name="insertcommento" maxlength="200"><areacom/></textarea></span>

<label for="p_complessivo">Punteggio complessivo:</label>
<span><input type="number" id="p_complessivo" name="p_complessivo" placeholder="1" value="1" min="1" max="5" 
    msg-data-empty="inserisci il punteggio complessivo del corso" msg-data-invalid="il punteggio deve essere compreso tra 1 e 5"/></span>
<label for="p_acc_fisica">Punteggio accessibilità fisica:</label>
<span><input type="number" id="p_acc_fisica" name="p_acc_fisica" placeholder="1" value="1" min="1" max="5" required
    msg-data-empty="inserisci il punteggio accessibilità fisica del corso" msg-data-invalid="il punteggio deve essere compreso tra 1 e 5"/></span>
<label for="p_inclusione">Punteggio servizio inclusione:</label>
<span><input type="number" id="p_inclusione" name="p_inclusione" placeholder="1" value="1" min="1" max="5" required
    msg-data-empty="inserisci il punteggio servizio inclusione del corso" msg-data-invalid="il punteggio deve essere compreso tra 1 e 5"/></span>
<label for="p_tempestivita">Punteggio tempestivita burocratica: </label>
<span><input type="number" id="p_tempestivita" name="p_tempestivita" placeholder="1" value="1" min="1" max="5" required
    msg-data-empty="inserisci il punteggio tempestivita burocratica del corso" msg-data-invalid="il punteggio deve essere compreso tra 1 e 5"/></span>
<label for="p_insegnamento">Punteggio insegnamento:</label>
<span><input type="number" id="p_insegnamento" name="p_insegnamento"placeholder="1" value="1" min="1" max="5" required
    msg-data-empty="inserisci il punteggio insegnamento del corso" msg-data-invalid="il punteggio deve essere compreso tra 1 e 5"/></span>   
<label for="tag">Il tuo commento riguarda:</label>
<span><select name="tag" id="tag" data-msg-empty="Per favore, aiutaci a capire di cosa parla il tuo commento">
    <option value="1">Inclusività</option>
    <option value="2">commento generale</option></select></span>

<input type="submit" id="submit"  name="submit3" value="pubblica"/>
<input type="reset"  name="cancella" value="cancella tutti i campi"/>
</fieldset>
</form>
</errorform>';
}

if($user!='user'){

    $contenuto.='<h2 id="CambioPw">Cambia Password</h2><form id="form_passw" action="area_utente.php" method="post" >
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
if(isset($_POST['submit2']) && check()){
    $cancella=isset($_POST['commento']) ? $_POST['commento']: '';
    
    if(!$cancella){
     $commenti.='<li>Selezionare un commento per cancellarlo</li>';
    }
    else{
        print_r($cancella);
        
        $db=new Connection();
        $dbOK=$db->Connect();
        if($dbOK){
            foreach($cancella as $i){
                echo $i;
                
            $query4="DELETE FROM Valutazione Where nome_utente=\"".$user."\" && classe_laurea=\"".$res3[$i][0]."\" && tag=\"".$res3[$i][8]."\";";
            if($r=$db->Insert($query4)){
                $commenti.='<li>Si è verificato un errori ai nostri servizi</li>';
            }
            }
            $cancella='';
            header('Location:area_utente.php');
        }
        
        
 }
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
        }
    $errori1.="</ul>";   
    
    }

    
}
if(isset($_POST['submit3']) && check()){
   $commento=PulisciInput($_POST['insertcommento']);
   $classlaurea=$_POST['classel'];
   $pc=$_POST['p_complessivo'];
   $pf=$_POST['p_acc_fisica'];
   $ps=$_POST['p_inclusione'];
   $tb=$_POST['p_tempestivita'];
   $pi=$_POST['p_insegnamento'];
   $tag=$_POST['tag'];
   if (!preg_match('/^[@a-zA-Z 0-9._-]{10,200}$/',$commento)){
    $errorf.='<li>Il campo commento non può essere vuoto e deve contenere da 10 a 200 caratteri alfanumerici (sono ammessi i seguenti caratteri: @ . _ - )</li>';
}
if($errorf=='<ul>'){
    $db=new Connection();
    $dbOK=$db->Connect();
    if($dbOK){
        $check="Select * from  Valutazione where nome_utente=\"".$user."\" && classe_laurea=\"".$classlaurea."\" && tag=\"".$tag."\";";
        if($r=$db->ExecQueryAssoc($check)){
            $errorf.="<li>Commento già risaliscato per questa calsse di laurea</li>";
        }
        else{
       $insert="INSERT INTO Valutazione(nome_utente, classe_laurea, datav, commento, tag, p_complessivo, p_acc_fisica, p_servizio_inclusione, tempestivita_burocratica, p_insegnamento) VALUES (\"".$user."\",\"".$classlaurea."\",curdate(),\"".$commento."\",\"".$tag."\",".$pc.",".$pf.",".$ps.",".$tb.",".$pi.");";
       $q=$db->Insert($insert);
       if($q){
           header('Location:area_utente.php');
           $errorf.="<li>Inserimento con successo</li>";
           
       }
       else{
           $errorf.="<li>Inserimento non riuscito</li>";
       }
    }
    
    }
    else{
        $errorf.="<li>Spiacenti ma i nostri servizi sono momentaneamente non disponibili</li>"; 
    }

}
}
$errorf.="</ul>";
$db->Disconnect();
$contenuto=str_replace("<areacom/>",$commento,$contenuto);
$contenuto=str_replace("</errorform>",$errorf,$contenuto);
$contenuto=str_replace("</commenterror>",$commenti,$contenuto);
$contenuto=str_replace("<new>",$nuova,$contenuto);
$contenuto=str_replace("<old>",$vecchia,$contenuto);
$content=str_replace("<content/>",$contenuto,$content);
$content=str_replace("<errori/>",$errori,$content);
$content=str_replace("</err/>",$errori1,$content);
echo $content;
?>
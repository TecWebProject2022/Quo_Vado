<?php
session_start();
require_once 'utilita.php';
require_once 'database.php';
// Se non hai fatto il login o la tua sessione (durata max 1 h di inattività) è scaduta
if(!isset($_SESSION['user']) || !isset($_SESSION['time']) || time()-$_SESSION['time']>3600){
    unset($_SESSION['user']); 
    unset($_SESSION['time']);
    $_SESSION['sessione']='<p class="error">Sessione Scaduta</p>';
    header('Location:login.php');
}
$menu1='<nav id="visible-sottomenu" aria-label="sotto menù di area riservata">
<ul>
    <li><a href="#iscrizione">Iscrizioni</a></li>
    <li><a href="#Commenti">Commenti rilasciati</a></li>
    <li><a href="#Aggiungi">Aggiungi un commento</a></li>
    <li><a href="#CambioPw">Cambia password</a></li>
    <li><a href="logout.php">Logout</a></li>
</ul>
</nav>';
$vecchia='';
$res3='';
$nuova='';
$errorf='<ul class="error">';
$content=file_get_contents("area_riservata.html");
$user=$_SESSION['user'];
$content=str_replace('<sottomenu/>',$menu1,$content);

if(isset($_SESSION['info'])){
    $content=str_replace('<info/>',$_SESSION['info'],$content);
    $_SESSION['info']='';
}
$commento='';
$errori1='<ul class="error">';
$errori='';
$commenti='<ul class="error">';
$contenuto='';
$cancella='';
$query1="Select  * from Utente where nome_utente=\"$user\";";
$db=new Connection();
$dbOK=$db->Connect();
if($dbOK){

    //DATI PERSONALI//
    if($res1=$db->ExecQueryAssoc($query1)){
        $contenuto.="<h2 class='titles_utente'>Dati personali</h2>";
        $contenuto.="<dl id='container_info'>";
        $contenuto.="<dt class='highlight'>Nome utente: </dt><dd class='highlight'>".$res1[0]['nome_utente']."</dd>";
        $contenuto.="<dt>Nome: </dt><dd>".$res1[0]['nome']."</dd>";
        $contenuto.="<dt class='highlight'>Cognome: </dt><dd class='highlight'>".$res1[0]['cognome']."</dd>";
        $contenuto.="<dt>Data di nascita: </dt><dd>".date("d/m/Y",strtotime($res1[0]['data_nascita']))."</dd>";
        $contenuto.="<dt class='highlight'>Genere: </dt><dd class='highlight'>".$res1[0]['genere']."</dd>";
        $contenuto.="<dt>Scuola superiore frequentata: </dt><dd>".$res1[0]['scuola_sup']."</dd>";
        $contenuto.="</dl>";
        $query2="Select ateneo, classe,corso, datai, dataf,punteggio_scuola_provenienza  from Iscrizione where nome_utente=\"$user\"";

    //ISCRIZIONI
        if($res2=$db->ExecQueryAssoc($query2)){
            $contenuto.="<h2 class='titles_utente'>Iscrizioni</h2> ";
            $contenuto.="<ul id='container_iscrizioni'>";
            foreach($res2 as $i){
                $contenuto.="<li><dl class='container_iscrizione'>";
                $contenuto.="<dt class='highlight'>Ateneo: </dt><dd class='highlight'>".$i['ateneo']."</dd>";
                $contenuto.="<dt>Classe di Laurea: </dt><dd>".$i['classe']."</dd>";
                $contenuto.="<dt class='highlight'>Corso di studi: </dt><dd class='highlight'>".$i['corso']."</dd>";
                $contenuto.="<dt>Data inizio studi: </dt><dd>".date("d/m/Y",strtotime($i['datai']))."</dd>";
                $contenuto.="<dt class='highlight'>Data fine studi: </dt><dd class='highlight'>".date("d/m/Y",strtotime($i['dataf']))."</dd>";
                $contenuto.="<dt>Punteggio affinità scuola superiore: </dt><dd>".$i['punteggio_scuola_provenienza']."</dd>";
                $contenuto.="</dl></li>";
            }
            $contenuto.="</ul>";

    //COMMENTI RILASCIATI
            $contenuto.="<h2 class='titles_utente'>Commenti rilasciati</h2>";
            $query3="Select classe_laurea,datav,commento,p_complessivo,p_acc_fisica,p_servizio_inclusione,tempestivita_burocratica,p_insegnamento,tag FROM Valutazione WHERE nome_utente=\"$user\"";
            if($res3=$db->ExecQueryNum($query3)){
                
                $contenuto.="<label class='container_iscrizione'>Seleziona un commento e clicca &quot;cancella commento selezionato&quot; per eliminarlo</label>";
                $contenuto.='<form aria-describedby="cancellacomm" id="no_border" class="container_iscrizione" action="area_utente.php" method="post" onsubmit="return OnDelete()" >
                <fieldset><legend>Commenti</legend>';
                /*flexbox esterna*/
                $contenuto.="<ul class=' no_disc container_iscrizioni'>";
                for($i=0;$i<count($res3);$i++){
                        /*flexbox interna*/
                        $contenuto.='<li ><span><input type="checkbox" id="'.$i.'" name="commento[]" value="'.$i.'" /></span><label for="'.$i.'">
                        <dl><dt class="highlight">Data di emissione:</dt><dd class="highlight">'.date("d/m/Y",strtotime($res3[$i][1])).'</dd>
                        <dt>Classe di laurea: </dt> <dd>'.$res3[$i][0].'</dd>
                        <dt class="highlight">Commento:</dt> <dd class="highlight">'.$res3[$i][2].'</dd>
                        <dt>Valutazione complessiva: </dt> <dd>'.$res3[$i][3].'</dd>
                        <dt class="highlight">Valutazione accessibilità fisica: </dt> <dd class="highlight">'.$res3[$i][4].'</dd>
                        <dt>Valutazione servizio inclusione: </dt> <dd> '.$res3[$i][5].'</dd>
                        <dt class="highlight">Valutazione tempestività burocratica: </dt> <dd class="highlight">'.$res3[$i][6].'</dd>
                        <dt>Valutazione qualità di insegnamento: </dt> <dd>'.$res3[$i][7].'</dd></dl>';
                        if($res3[$i][8]==1){
                            $contenuto.="<p class='tipo_valutazione'>Valutazione riguardante l'inclusività</p></label></li>";
                        }
                        else{
                            $contenuto.="<p class='tipo_valutazione'>Valutazione riguardante l'ambito generale </p></label></li>";
                        }      
                }

                $contenuto.="<ul/>";
                $contenuto.='<input type="submit"  class="submit" name="submit2" value="cancella"/></ul></fieldset></form></commenterror>';
                
            }
            else{
                $contenuto.="<p class='error'>Siamo spiacenti non hai ancora inserito alcun commento</p>";
            }
        }
        else{
            $errori.="<p class='error'>Siamo spiacenti ma i dati non sono al momento disponibili <a href='contatti.php'>Contattaci</a> per avere un suppoorto</p>";
        }
    }
    else{
        $errori.="<p class='error'>Siamo spiacenti ma i dati non sono al momento disponibili <a href='contatti.php'>Contattaci</a> per avere un suppoorto</p>";
    }
}
else{
    $errori.="<p class='error'>Siamo spiacenti ma i dati non sono al momento disponibili</p>";
}

$query5="Select classe FROM Iscrizione where nome_utente=\"".$user."\";";


    //AGGIUNGI COMMENTO
if($res5=$db->ExecQueryAssoc($query5)){
    $classi="<ul id='comm_list'><li><label for='classi'>Classi di Laurea:</label>

    <select id='classi' name='classel'>";
    foreach($res5 as $r){
       $classi.="<option value=\"".$r['classe']."\">".$r['classe']."</option>";
    }
    $classi.="</select></li>";
$contenuto.='<h2 class="titles_utente">Aggiungi un commento</h2>';
$contenuto.='<label class="formdesc">Ti è consentito lasciare un solo commento per ogni ambito delle classe di laurea per le quali ti sei dichiarato iscritto  e il contenuto testuale del 
commento dovrà contenere da 10 a 200 caratteri alfanumerici (sono ammessi i seguenti caratteri: @ . _ - )</label>';

$contenuto.='<form id="form_aggiungicomm" aria-describedby="formdesc" action="area_utente.php"  onsubmit="return OnInsert()" method="post">
<fieldset id="container_aggiungi">

<legend class="field_legend">Aggiungi un commento</legend>'.$classi.'


<li><label for="commento">Commento:</label><br/><span><textarea id="commento" name="insertcommento" maxlength="200"><areacom/></textarea></span></li>
<li><label for="tag">Il tuo commento riguarda:</label><span><select name="tag" id="tag" data-msg-empty="Per favore, aiutaci a capire di cosa parla il tuo commento">
    <option value="1">Inclusività</option>
    <option value="2">Commento generale</option></select></span></li>
</ul>


<ul id="val_list">
<li><label for="p_complessivo">Punteggio complessivo:</label><span><input type="number" id="p_complessivo" name="p_complessivo" placeholder="1" value="1" min="1" max="5" 
    msg-data-empty="inserisci il punteggio complessivo del corso" msg-data-invalid="il punteggio deve essere compreso tra 1 e 5"/></span></li>
    
<li><label for="p_acc_fisica">Punteggio accessibilità fisica:</label><span><input type="number" id="p_acc_fisica" name="p_acc_fisica" placeholder="1" value="1" min="1" max="5" required
    msg-data-empty="inserisci il punteggio accessibilità fisica del corso" msg-data-invalid="il punteggio deve essere compreso tra 1 e 5"/></span></li>
    
<li><label for="p_inclusione">Punteggio servizio inclusione:</label><span><input type="number" id="p_inclusione" name="p_inclusione" placeholder="1" value="1" min="1" max="5" required
    msg-data-empty="inserisci il punteggio servizio inclusione del corso" msg-data-invalid="il punteggio deve essere compreso tra 1 e 5"/></span></li>
    
<li><label for="p_tempestivita">Punteggio tempestivita burocratica: </label><span><input type="number" id="p_tempestivita" name="p_tempestivita" placeholder="1" value="1" min="1" max="5" required
    msg-data-empty="inserisci il punteggio tempestivita burocratica del corso" msg-data-invalid="il punteggio deve essere compreso tra 1 e 5"/></span></li>
    
<li><label for="p_insegnamento">Punteggio insegnamento:</label><span><input type="number" id="p_insegnamento" name="p_insegnamento"placeholder="1" value="1" min="1" max="5" required
    msg-data-empty="inserisci il punteggio insegnamento del corso" msg-data-invalid="il punteggio deve essere compreso tra 1 e 5"/></span></li>
</ul>


<input type="submit" class="submit"  name="submit3" value="pubblica"/>

</fieldset>
</form>
</errorform>';
}

    //CAMBIO PASSWORD
    $contenuto.='<h2 class="titles_utente">Password</h2>';
    $contenuto.='<form id="form_passw" action="area_utente.php" method="post" >';
    $contenuto.='<fieldset><legend class="field_legend">Cambia password</legend>

    <ul id="changePw">

    <li><label for="oldpassword"><span lang="en">Immetti la tua vecchia password: </span></label><span><input  value="<old>" type="password" id="oldpassword" name="Vecchiapassword" placeholder="Immetti la tua vecchia password" maxlength="20"                      
        data-msg-invalid="Il campo password non può contenere spazzi e deve contenere da 4 a 20 caratteri alfanumerici (sono ammessi i seguenti caratteri: @ . _ - ), controlla e riprova"
        data-msg-empty="Il campo vecchia password non può essere vuoto" /></span></li>

    <li><label for="newpassword"><span lang="en">Immetti la tua nuova password: </span></label><span><input  value="<new>" type="password" id="newpassword" name="newpassword" placeholder="Immetti la tua nuova password" maxlength="20"                      
        data-msg-invalid="Il campo password non può contenere spazzi e deve contenere da 4 a 20 caratteri alfanumerici (sono ammessi i seguenti caratteri: @ . _ - ), controlla e riprova"
        data-msg-empty="Il campo nuova password non può essere vuoto" /></span></li>

    <li><label for="repeat"><span lang="en">Ripeti la password: </span></label><span><input  value="" type="password" id="repeat" name="repepassword" placeholder="Ripeti la password" maxlength="20"                      
        data-msg-empty="Il campo repeti password non può essere vuoto" /></span></li>
        
    </ul>

    <input type="submit" class="submit" name="submit1" value="Salva"/>

    </fieldset>
    </form>
    </err/>';
if(isset($_POST['submit2']) && check()){
    $cancella=isset($_POST['commento']) ? $_POST['commento']: '';
    
    if(!$cancella){
     $commenti.='<li>Selezionare un commento o dei commenti per cancellarli</li>';
    }
    else{
        print_r($cancella);
        
        $db=new Connection();
        $dbOK=$db->Connect();
        $r='';
        if($dbOK){
            foreach($cancella as $i){
                
            $query4="DELETE FROM Valutazione Where nome_utente=\"".$user."\" && classe_laurea=\"".$res3[$i][0]."\" && tag=\"".$res3[$i][8]."\";";
            $r=$db->Insert($query4);
            if($r==null){
                break;
            }
        }
        if($r==true){
            $_SESSION['info']="<p class='verde'>Cancellazione con successo</p>";
            header('Location:area_utente.php');
        }
        else{
            $_SESSION['info']="<p class='error'>Cancellazione non riuscita</p>";
        } 
    }
} 
}
if(isset($_POST['submit1']) && check()){
    $vecchia=PulisciInput($_POST['Vecchiapassword']);
    $nuova=PulisciInput($_POST['newpassword']);
    $rep=PulisciInput($_POST['repepassword']);
    if (!preg_match('/^[@a-zA-Z0-9._-]{4,20}$/',$vecchia)){
        $errori1.='<li>Il campo vecchia password non può essere vuoto e non può contenere spazzi e deve contenere da 4 a 20 caratteri alfanumerici (sono ammessi i seguenti caratteri: @ . _ - )</li>';
        $_SESSION['info']="<p class='error'>Cambiamento non risucito</p>";
    }
    if (!preg_match('/^[@a-zA-Z0-9._-]{4,20}$/',$nuova)){
        $errori1.='<li>Il campo nuova password non può essere vuoto e non può contenere spazzi e deve contenere da 4 a 20 caratteri alfanumerici (sono ammessi i seguenti caratteri: @ . _ - )</li>';
        $_SESSION['info']="<p class='error'>Cambiamento non risucito</p>";
    }
    if($nuova!=$rep){
        $errori1.='<li>Il campo nuova password e ripeti la password non corrispondono</li>';
        $_SESSION['info']="<p class='error'>Cambiamento non risucito</p>";
    }
    if($errori1=='<ul class="error">'){
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
                    $_SESSION['info']="<p class='verde'>password modificata con successo</p>";
                    header('Location:area_utente.php');
                }
                else{
                    $_SESSION['info']="<p class='error'>Cambiamento non risucito</p>";
                } 
            }
            else{
                $_SESSION['info']="<p class='error'>La vecchia password inserita non corrisposnde</p>";
            }
            
            }
        }
    $errori1.="</ul>";   
    
    }

    
}
if(isset($_POST['submit3']) && check()){
    echo"hsj";
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
if($errorf=='<ul class="error">'){
    $db=new Connection();
    $dbOK=$db->Connect();
    if($dbOK){
        $check="Select * from  Valutazione where nome_utente=\"".$user."\" && classe_laurea=\"".$classlaurea."\" && tag=\"".$tag."\";";
        if($r=$db->ExecQueryAssoc($check)){
            $$_SESSION['info']="<p class='error'>Commento già rialasciato per quesat classe di laurea</p>";
        }
        else{
       $insert="INSERT INTO Valutazione(nome_utente, classe_laurea, datav, commento, tag, p_complessivo, p_acc_fisica, p_servizio_inclusione, tempestivita_burocratica, p_insegnamento) VALUES (\"".$user."\",\"".$classlaurea."\",curdate(),\"".$commento."\",\"".$tag."\",".$pc.",".$pf.",".$ps.",".$tb.",".$pi.");";
       $q=$db->Insert($insert);
       if($q){
            $_SESSION['info']="<p class='verde'>Inserimento avvenuto con successo</p>";
            header('Location:area_utente.php');
           
       }
       else{
        $_SESSION['info']="<p class='error'>inserimento non riuscito</p>";
       }
    }
    
    }
    else{
       $_SESSION['info']="<p class='error'>Siamo spiacebti i nostri sistemi sono al moemtno non accessibili. Per urgenze <a href='contatti.php'>Contattaci</a> per avere un suppoorto</p>";
    }

}
}
$commenti.="</ul>";
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

/*
$contenuto.="<aside>
                <h2 class='titles_utente'>Legenda valutazione</h2>
                <p>Ogni utente può esprime un giudizio con un valore da 1 a 5 sui seguenti ambiti riguardanti una classe di laurea</p>
                    <dl>
                        <dt>Complessiva: </dt>
                        <dd>valutazione che riguarda tutti gli ambiti universitari in generale</dd>
                        <dt>Accessibilità fisica: </dt>
                        <dd>valutazione che riguarda la possibilità da parte di chiunque di fruire dei servizi universitari da un punto di vista fisico</dd>
                        <dt>Servizio inclusione: </dt>
                        <dd>valutazione riguardante l'accoglienza e l'appartenenza ad un gruppo universitario</dd>
                        <dt>Tempestività burocratica: </dt>
                        <dd>valutazione attinente alla velocità di intervento e risposta da parte dei servizi amministrativi e burocratici universitari</dd>
                        <dt>Qualità di insegnamento: </dt>
                        <dd>valutazione riguardante la qualità di insegnamento ricevuto e le competenze acquisite in esso</dd>
                    </dl>
            </aside>";
*/
?>


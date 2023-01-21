<?php
session_start();
require_once 'utilita.php';
require_once 'database.php';
// Se non hai fatto il login o la tua sessione (durata max 1 h di inattività) è scaduta
if(!isset($_SESSION['user']) || !isset($_SESSION['time']) || time()-$_SESSION['time']>3600){
    unset($_SESSION['user']); 
    unset($_SESSION['time']);
    unset($_SESSION['info']);
    unset($_SESSION['errorf']);
    unset($_SESSION['LAUREA']);
    unset($_SESSION['data']);
    unset($_SESSION['errori1']);
    unset($_SESSION['commenti']);
    unset($_SESSION['errorf']);
    unset($_SESSION['query7']);
    unset($_SESSION['nuova']);
    unset($_SESSION['vecchia']);
    unset($_SESSION['error']);
    $_SESSION['sessione']='<p class="error">Sessione Scaduta</p>';
    header('Location:login.php');
}
$menu1='<nav id="visible-sottomenu" aria-label="sotto menù di area riservata">
<ul>
    <li><a href="#iscrizioni">Iscrizioni</a></li>
    <li><a href="#aggiscrizione">Aggiungi Iscrizione</a></li>
    <li><a href="#commenti">Commenti rilasciati</a></li>
    <li><a href="#aggiungi">Aggiungi un commento</a></li>
    <li><a href="#form_passw">Cambia password</a></li>
    <li><a href="logout.php">Logout</a></li>
</ul>
</nav>';
$content=file_get_contents("area_riservata.html");


$user=$_SESSION['user'];
$content=str_replace('<sottomenu/>',$menu1,$content);

if(isset($_SESSION['info'])){
    $content=str_replace('<span id="8"></span>',$_SESSION['info'],$content);
    $_SESSION['info']='';
}

$commento='';
if(!isset($errori1)){
    $errori1='<ul class="error">';
}
if(!isset($errori)){
    $errori='';
}

$contenuto='';
$cancella='';
$query1="Select  * from Utente where nome_utente=\"$user\";";
$db=new Connection();
$dbOK=$db->Connect();
if($dbOK){

    //DATI PERSONALI//
    if($res1=$db->ExecQueryAssoc($query1)){
        $contenuto.="<h2 class='titles_area_classi'>Dati personali</h2>";
        $contenuto.="<dl id='container_info'>";
        $contenuto.="<dt class='highlight'>Nome utente: </dt><dd class='highlight'>".$res1[0]['nome_utente']."</dd>";
        $contenuto.="<dt>Nome: </dt><dd>".$res1[0]['nome']."</dd>";
        $contenuto.="<dt class='highlight'>Cognome: </dt><dd class='highlight'>".$res1[0]['cognome']."</dd>";
        $contenuto.="<dt>Data di nascita: </dt><dd>".date("d/m/Y",strtotime($res1[0]['data_nascita']))."</dd>";
        $contenuto.="<dt class='highlight'>Genere: </dt><dd class='highlight'>".$res1[0]['genere']."</dd>";
        $contenuto.="<dt>Scuola superiore frequentata: </dt><dd>".$res1[0]['scuola_sup']."</dd>";
        $contenuto.="</dl>";
        
    }
    else{
        $errori.="<p class='error'>Siamo spiacenti, i dati non sono al momento disponibili <a href='contatti.php'>Contattaci</a> per avere un suppoorto</p>";
    }
    $query2="Select ateneo, classe,corso, datai, dataf,punteggio_scuola_provenienza  from Iscrizione where nome_utente=\"$user\"";
    //ISCRIZIONI
        if($res2=$db->ExecQueryAssoc($query2)){
            $contenuto.="<h2 id='iscrizioni' class='titles_area_classi'>Iscrizioni</h2> ";
            $contenuto.="<ul id='container_iscrizioni'>";
            foreach($res2 as $i){
                $contenuto.="<li><dl class=\"container_iscrizione\">";
                $contenuto.="<dt class=\"highlight\">Ateneo: </dt><dd class=\"highlight\">".$i['ateneo']."</dd>";
                $contenuto.="<dt>Classe di Laurea: </dt><dd>".$i['classe']."</dd>";
                $contenuto.="<dt class=\"highlight\">Corso di studi: </dt><dd class=\"highlight\">".$i['corso']."</dd>";
                $contenuto.="<dt>Data inizio studi: </dt><dd>".date("d/m/Y",strtotime($i['datai']))."</dd>";
                $contenuto.="<dt class=\"highlight\">Data fine studi: </dt><dd class=\"highlight\">".date("d/m/Y",strtotime($i['dataf']))."</dd>";
                $contenuto.="<dt>Punteggio affinità scuola superiore: </dt><dd>".$i['punteggio_scuola_provenienza']."</dd>";
                $contenuto.="</dl></li>";
            }
            $contenuto.="</ul>";

            $query3="Select classe_laurea,datav,commento,p_complessivo,p_acc_fisica,p_servizio_inclusione,tempestivita_burocratica,p_insegnamento,tag FROM Valutazione WHERE nome_utente=\"$user\"";
            
        }
        else{
            $errori.="<p class=\"error\">Siamo spiacenti, hai nessuna iscrizione nel tuo profilo</p>";
        }

    //NUOVA ISCRIZIONE
$query6="Select num_classe FROM ClassediLaurea";
if($res5=$db->ExecQueryAssoc($query6)){
    $contenuto.="<h2 id=\"aggiscrizione\" class='titles_area_classi'>Inserisci una nuova iscrizione</h2>";
    $contenuto.='<span id="error"></span>';
    $contenuto.="<label id=\"descseclect\" class=\"formdesc\">Qui ti è consentito inserire una nuova iscrizione, fai molta attenzione ai passaggi</label>";
    $contenuto.='<form  aria-describedby="descseclect" id="selectclass" action="area_utente.php#aggiscrizione" method="post" ><fieldset><legend class="field_legend">Seleziona classe</legend>';
    $contenuto.="<label for=\"classi\">Classi disponibili:</label>
    <select id=\"classi\" name=\"classe\">";
    foreach($res5 as $r){
        $contenuto.="<option value=\"".$r['num_classe']."\">".$r['num_classe']."</option>";
    }
    $contenuto.="</select>";
    $contenuto.=' <input type="submit" class="submit" name="submit4" value="Avanti"/> </fieldset>
    </form>';
    $contenuto.="<span id=\"ins\"></span>";
}
else{
    $contenuto.="<p class=\"error\">Siamo spaicenti ma non è  presente alcuna classe di laurea</p>";
}


    //COMMENTI RILASCIATI
$contenuto.="<h2 id=\"commenti\"  class='titles_area_classi'>Commenti rilasciati</h2>";
$query3="Select classe_laurea,datav,commento,p_complessivo,p_acc_fisica,p_servizio_inclusione,tempestivita_burocratica,p_insegnamento,tag FROM Valutazione WHERE nome_utente=\"$user\"";
             
if($res3=$db->ExecQueryNum($query3)){

    $contenuto.="<label id=\"cancellacomm\" class=\"formdesc\">Seleziona un commento e clicca &quot;cancella&quot; per eliminarlo</label>";
    $contenuto.='<form id="form_cancellacomm" aria-describedby="cancellacomm" action="area_utente.php" method="post" onsubmit="return OnDelete()">
    <fieldset><legend class="field_legend">Commenti</legend>';
    /*flexbox esterna*/
    $contenuto.="<ul id=\"commrilasc\">";
    for($i=0;$i<count($res3);$i++){
            /*flexbox interna*/
            $contenuto.='<li class="blocco_commento"><label><span><input type="checkbox" id="'.$i.'" name="commento[]" value="'.$i.'" /></span>
            <span class="highlight">Data di emissione: '.date("d/m/Y",strtotime($res3[$i][1])).'</span>
            <span>Classe di laurea: '.$res3[$i][0].'</span>
            <span class="highlight">Commento: '.$res3[$i][2].'</span>
            <span>Valutazione complessiva:  '.$res3[$i][3].'</span>
            <span class="highlight">Valutazione accessibilità fisica:  '.$res3[$i][4].'</span>
            <span>Valutazione servizio inclusione:   '.$res3[$i][5].'</span>
            <span class="highlight">Valutazione tempestività burocratica:  '.$res3[$i][6].'</span>
            <span>Valutazione qualità di insegnamento:  '.$res3[$i][7].'</span>';
            if($res3[$i][8]==1){
                $contenuto.="<span class='tipo_valutazione'>Valutazione riguardante l'inclusività</span></label></li>";
            }
            else{
                $contenuto.="<span class='tipo_valutazione'>Valutazione riguardante l'ambito generale </span></label></li>";
            }      
    }

    $contenuto.="</ul>";
    $contenuto.='<input type="submit"  class="submit" name="submit2" value="cancella"/></fieldset></form></commenterror>';
    
}
else{
    $contenuto.="<p class=\"invito\">Siamo spiacenti, non hai ancora inserito alcun commento</p>";
}
$query5="Select classe FROM Iscrizione where nome_utente=\"".$user."\";";


    //AGGIUNGI COMMENTO
if($res5=$db->ExecQueryAssoc($query5)){
    $classi="<ul id=\"comm_list\"><li><label for=\"classi2\">Classi di Laurea:</label>
    <select id=\"classi2\" name=\"classel\">";
    foreach($res5 as $r){
       $classi.="<option value=\"".$r['classe']."\">".$r['classe']."</option>";
    }
    $classi.="</select></li>";

$contenuto.='<h2 id="aggiungi" class="titles_area_classi">Aggiungi un commento</h2>';
$contenuto.='<label id="aggiungi_commento" class="formdesc">Ti è consentito lasciare un solo commento per ogni ambito delle classe di laurea per le quali ti sei dichiarato iscritto  e il contenuto testuale del 
commento dovrà contenere da 10 a 200 caratteri alfanumerici (sono ammessi i seguenti caratteri: @ . _ - )</label>';

$contenuto.='<form id="form_aggiungicomm" aria-describedby="aggiungi_commento" action="area_utente.php"  onsubmit="return OnInsert()" method="post">
<fieldset id="container_aggiungi">
<legend class="field_legend">Aggiungi un commento</legend>'.$classi.'
<li><label for="commento">Commento:</label><br/><span><textarea id="commento" name="insertcommento" maxlength="200"><areacom/></textarea></span></li>
<li><label for="tag">Il tuo commento riguarda:</label><span><select name="tag" id="tag" data-msg-empty="Per favore, aiutaci a capire di cosa parla il tuo commento">
    <option value="1">Inclusività</option>
    <option value="2">Commento generale</option></select></span></li>
</ul>
<ul id="val_list">
<li><label for="p_complessivo">Punteggio complessivo:</label><span><input type="number" id="p_complessivo" name="p_complessivo" placeholder="1" value="1" min="1" max="5" 
   /></span></li>
    
<li><label for="p_acc_fisica">Punteggio accessibilità fisica:</label><span><input type="number" id="p_acc_fisica" name="p_acc_fisica" placeholder="1" value="1" min="1" max="5" required
   /></span></li>
    
<li><label for="p_inclusione">Punteggio servizio inclusione:</label><span><input type="number" id="p_inclusione" name="p_inclusione" placeholder="1" value="1" min="1" max="5" required
    /></span></li>
    
<li><label for="p_tempestivita">Punteggio tempestivita burocratica: </label><span><input type="number" id="p_tempestivita" name="p_tempestivita" placeholder="1" value="1" min="1" max="5" required
    /></span></li>
    
<li><label for="p_insegnamento">Punteggio insegnamento:</label><span><input type="number" id="p_insegnamento" name="p_insegnamento" placeholder="1" value="1" min="1" max="5" required
    /></span></li>
</ul>
<input type="submit" class="submit"  name="submit3" value="pubblica"/>
</fieldset>
</form>
</errorform>';
}

else{
    $errori.="<p class=\"error\">Siamo spiacenti, hai nessun commento nel tuo profilo</p>";
}
$query5="Select classe FROM Iscrizione where nome_utente=\"".$user."\";";
if($res5=$db->ExecQueryAssoc($query5)){
    $classi="<ul id=\"comm_list\"><li><label for=\"classi\">Corsi di Studio disponibili:</label>
    <select id=\"corsi\" name=\"corsi\">";
    foreach($res5 as $r){
       $classi.="<option value=\"".$r['classe']."\">".$r['classe']."</option>";
    }
    $classi.="</select></li>";

   
}
}

    //CAMBIO PASSWORD
$contenuto.='<h2 class="titles_area_classi">Cambio password</h2>';
$contenuto.='<form id="form_passw" action="area_utente.php" method="post" >';
$contenuto.='<fieldset><legend class="field_legend">Cambio password</legend>
<ul class="none" id="changePw">
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


    //NUOVA ISCRIZIONE PT2
if(isset($_POST['submit4']) && check()){
    
    $_SESSION['LAUREA']=$_POST['classe'];
    $db=new Connection();
    $dbOK=$db->Connect();
    if($dbOK){
        $query7="Select ateneo, nome FROM CorsodiStudio where classe_laurea=\"".$_POST['classe']."\";";
        if($r=$db->ExecQueryNum($query7)){
           $_SESSION['data']=$r;
            $form='<p class="formdesc">Inserimento per la classe di laurea: '.$_SESSION['LAUREA'].'<form id="selectcorso" action="area_utente.php" method="post">';
            $form.="<fieldset><legend class='field_legend'>Seleziona corso di studi</legend>";
            $form.='<label for="corso">Seleziona il corso: </label>';
            $form.='<select id="corso" name="corso">';
            for($i=0; $i<count($r);$i++){
                $form.="<option value=\"".$i."\">".$r[$i][0]."|".$r[$i][1]."</option>";
            }
            $form.="</select>";
            $form.='<br/><label for="datai">Data di iscrizione: </label>
            <input type="date" id="datai" name="datai" value="1960-01-01" min="1960-01-01" max="2100-01-01"/>';
            $form.='<br/><label for="dataf">Data di fine studi: </label>
            <input type="date" id="dataf" name="dataf" value="1960-01-01" min="1960-01-01" max="2100-01-01"/>';
            $form.='<br/><label for="punteggio">Punteggio scuola di provenienza: </label>
            <input type="number" id="punteggio" name="punteggio" value="1" min="1" max="5"/>';
            $form.='<br/> <input type="submit" class="submit" name="submit5" value="Inserisci"/>';
            $form.="</fieldset></form>";
            $contenuto=str_replace("<span id=\"ins\"></span>",$form,$contenuto);
        }
        else{
            $contentuo.="<p  class=\"error\">Impossibile recuperare le informazioni richieste</p>";

        }
        
    }else{
        $contentuo.="<p  class=\"error\">Impossibile recuperare le informazioni richieste</p>";
       
    }
   
}
if(isset($_POST['submit5']) && check()){
   $r=$_SESSION['data'];
   $corso=$r[$_POST['corso']];
   $classe=$_SESSION['LAUREA'];
   $datai=$_POST['datai'];
   $dataf= $_POST['dataf'];
   $punteggio= $_POST['punteggio'];
    $_SESSION['user'];
    if($dataf>$datai){
        $db=new Connection();
        $dbOK=$db->Connect();
   if($dbOK){
        $check=" SELECT * FROM Iscrizione where  classe=\"".$classe."\" && nome_utente=\"".$_SESSION['user']."\";";
        if($resc=$db->ExecQueryNum($check)){
            $_SESSION['error']='<p class="error">Iscrizione già inserita</p>';
            header('Location:area_utente.php#aggiscrizione'); 
        }
        else{
        $query8="INSERT INTO Iscrizione (ateneo, classe, corso, nome_utente, datai, punteggio_scuola_provenienza, dataf) VALUES(\"".$corso[0]."\",\"".$classe."\",\"".$corso[1]."\",\"".$_SESSION['user']."\",'".$datai."',".$punteggio.",'".$dataf."');";
        echo $query8;
        $q=$db->Insert($query8);
       if($q){
            $_SESSION['info']="<p class=\"invito\">Inserimento avvenuto con successo</p>";
            unset($_SESSION['error']);
            header('Location:area_utente.php');
       }
       else{
        $_SESSION['error']='<p class="error">L\'inserimento non è andato a buon fine</p>';
        header('Location:area_utente.php#aggiscrizione');
       }
    
   
}
}
else{
    $_SESSION['error']='<p class="error">Inserimento non riuscito</p>';
    $errori.="<p  class=\"error\">Inserimento  non riuscito</p>";
   }
}
else{
    $_SESSION['error']='<p class="error">La data di fine studi deve essere successiva alla data di inizio studi</p>';
    header('Location:area_utente.php#aggiscrizione');
}
}
if(isset($_POST['submit2']) && check()){
    $commenti='<ul class="error">';
    $_SESSION['commenti']='';
    $cancella=isset($_POST['commento']) ? $_POST['commento']: '';
    
    if(!$cancella){
     $commenti.='<li>Selezionare un commento o dei commenti per cancellarli</li>';
     $_SESSION['commenti'].="<p class='error'>Cancellazione  non riuscita</p>";
    header('Location:area_utente.php#commenti');
    }
    else{
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
            $_SESSION['info'].="<p  class=\"invito\">Cancellazione avvenuta con successo</p>";
            unset($_SESSION['commenti']);
            header('Location:area_utente.php');
        }
        else{
           
            $_SESSION['commenti'].="<p class=\"error\">Cancellazione  non riuscita</p>";
            header('Location:area_utente.php#commenti');
        } 
    }
    else{
        $_SESSION['commenti'].="<p class=\"error\">Cancellazione  non riuscita</p>";
        header('Location:area_utente.php#commenti');
    } 
    }

} 

if(isset($_POST['submit1']) && check()){
   $errori1='<ul class="error">';
   $_SESSION['errori1']='';
    $vecchia=PulisciInput($_POST['Vecchiapassword']);
    $nuova=PulisciInput($_POST['newpassword']);
    $rep=PulisciInput($_POST['repepassword']);
    $_SESSION['vecchia']=$vecchia;
    $_SESSION['nuova']=$nuova;
    if (!preg_match('/^[@a-zA-Z0-9._-]{4,20}$/',$vecchia)){
        $errori1.='<li>Il campo vecchia password non può essere vuoto e non può contenere spazzi e deve contenere da 4 a 20 caratteri alfanumerici (sono ammessi i seguenti caratteri: @ . _ - )</li>';
        $_SESSION['errori1'].='<p class="error">Il campo vecchia password non può essere vuoto e non può contenere spazzi e deve contenere da 4 a 20 caratteri alfanumerici (sono ammessi i seguenti caratteri: @ . _ - )</p>';
        $errori.="<p class=\"error\">Errore nell'aggiornamento della password</p>";
        header('Location:area_utente.php#form_passw');
        
    }
    if (!preg_match('/^[@a-zA-Z0-9._-]{4,20}$/',$nuova)){
        $errori1='<li>Il campo vecchia password non può essere vuoto e non può contenere spazzi e deve contenere da 4 a 20 caratteri alfanumerici (sono ammessi i seguenti caratteri: @ . _ - )</li>';
        $_SESSION['errori1'].='<p class="error">Il campo nuova password non può essere vuoto e non può contenere spazzi e deve contenere da 4 a 20 caratteri alfanumerici (sono ammessi i seguenti caratteri: @ . _ - )</p>';
        $errori.="<p class=\"error\">Errore nell'aggiornamento della password</p>";
        header('Location:area_utente.php#form_passw');
        
    }
    if($nuova!=$rep){
        $errori1.="<p class=\"error\">Errore nell'aggiornamento della password</p>";
        $_SESSION['errori1'].='<p class="error">Il campo nuova password e ripeti la password non corrispondono</p>';
        $errori.="<p class=\"error\">Errore nell'aggiornamento della password</p>";
        header('Location:area_utente.php#form_passw');
        
    }
    if($errori1=='<ul class="error">'){
        $db=new Connection();
        $dbOK=$db->Connect();
        if($dbOK){
            $query="Select * from Credenziale where utente=\"".$user."\" && pw=\"".$nuova."\";";
            if($r=$db->ExecQueryAssoc($query)){
                $errori1.="<li>Password già usata</li>";
                $errori.="<p class=\"error\">Errore nell'aggiornamento della password</p>";
                $_SESSION['errori1'].='<p class="error">Password già usata</p>';
                header('Location:area_utente.php#form_passw');
            }
            else{
                $query3="Select * from Credenziale where utente=\"".$user."\" && pw=\"".$vecchia."\";";
                if($r=$db->ExecQueryAssoc($query3)){
                $query2="UPDATE Credenziale SET attuale=0 WHERE utente=\"".$user."\" and pw=\"".$vecchia."\";";
                $query2.="INSERT INTO Credenziale(pw, data_inserimento, utente, attuale) VALUES('".$nuova."',curdate(),'".$user."',1);";
                $q=$db->multiInsert($query2);
                if($q){
                    $_SESSION['info']="<p class=\"invito\">Password modificata con successo</p>";
                    unset($_SESSION['errori1']);
                    $_SESSION['nuova']='';
                    $_SESSION['vecchia']='';
                    header('Location:area_utente.php');
                    
                }
                else{
                    $errori."<p class=\"error\">Al momento non è possibile modificare la password</p>";
                    $_SESSION['errori1'].='<p class="error">Al momento non è possibile modificare la password</p>';
                    header('Location:area_utente.php#form_passw');
                } 
            }
            else{
                $errori.="<p class=\"error\">La vecchia password inserita non corrisponde</p>";
                $_SESSION['errori1'].='<p class="error">La vecchia password inserita non corrisponde</p>';
                header('Location:area_utente.php#form_passw');
            }
            
            }
        }
   
    
    }
 $errori1.="</ul>";      
}
if(isset($_POST['submit3']) && check()){
   $errorf='<ul class="error">';
   $_SESSION['errorf']='';
   $commento=PulisciInput($_POST['insertcommento']);
   $classlaurea=$_POST['classel'];
   $pc=$_POST['p_complessivo'];
   $pf=$_POST['p_acc_fisica'];
   $ps=$_POST['p_inclusione'];
   $tb=$_POST['p_tempestivita'];
   $pi=$_POST['p_insegnamento'];
   $tag=$_POST['tag'];
   if (!preg_match('/^[@a-zA-Z 0-9._-]{10,200}$/',$commento)){
    $_SESSION['errorf'].='<p class="error">Il campo commento non può essere vuoto e deve contenere da 10 a 200 caratteri alfanumerici (sono ammessi i seguenti caratteri: @ . _ - )</p>';
    $errorf.='<li>Il campo commento non può essere vuoto e deve contenere da 10 a 200 caratteri alfanumerici (sono ammessi i seguenti caratteri: @ . _ - )</li>';
    $errori.="<p class=\"error\">Errore nell'inserimento del commento</p>";
    header('Location:area_utente.php#aggiungi');
}
if($errorf=='<ul class="error">'){
    $db=new Connection();
    $dbOK=$db->Connect();
    if($dbOK){
        $check="Select * from  Valutazione where nome_utente=\"".$user."\" && classe_laurea=\"".$classlaurea."\" && tag=\"".$tag."\";";
        if($r=$db->ExecQueryAssoc($check)){
            $_SESSION['errorf'].='<p class="error">Commento già risaliscato per questa calsse di laurea</p>';
            $errorf.="<li>Commento già risaliscato per questa calsse di laurea</li>";
            $errori.="<p class=\"error\">Errore nell'inserimento del commento</p>";
            header('Location:area_utente.php#aggiungi');
        }
        else{
       $insert="INSERT INTO Valutazione(nome_utente, classe_laurea, datav, commento, tag, p_complessivo, p_acc_fisica, p_servizio_inclusione, tempestivita_burocratica, p_insegnamento) VALUES (\"".$user."\",\"".$classlaurea."\",curdate(),\"".$commento."\",\"".$tag."\",".$pc.",".$pf.",".$ps.",".$tb.",".$pi.");";
       $q=$db->Insert($insert);
       if($q){
            $_SESSION['info']="<p class=\"invito\">Inserimento avvenuto con successo</p>";
            unset($_SESSION['errorf']);
            header('Location:area_utente.php');
       }
       else{
        $_SESSION['errorf'].='<p class="error">Al momento non è possibile inserire commenti</p>';
        $errori.="<p class=\"error\">Al momento non è possibile inserire commenti</p>";
        header('Location:area_utente.php#aggiungi');
       }
    }
    
    }
    else{
        $_SESSION['errorf'].='<p class="error">Spiacenti ma i nostri servizi sono momentaneamente non disponibili</p>'; 
        $errorf.="<li>Spiacenti ma i nostri servizi sono momentaneamente non disponibili</li>"; 
        $errori.="<p class=\"error\">Al momento non è possibile inserire commenti</p>";
        header('Location:area_utente.php#aggiungi');
        
    }

}
}
if(!isset($errori1)){
    $errori1='';
}
if(!isset($_SESSION['errori1'])){
    $_SESSION['errori1']='';
}

if(!isset($_SESSION['commenti'])){
    $_SESSION['commenti']='';
}

if(!isset($_SESSION['errorf'])){
    $_SESSION['errorf']='';
}
if(!isset($_SESSION['vecchia'])){
    $_SESSION['vecchia']='';
}
if(!isset($_SESSION['nuova'])){
    $_SESSION['nuova']='';
}
if(!isset($_SESSION['error'])){
    $_SESSION['error']='';
}   
$db->Disconnect();
$contenuto=str_replace("<span id=\"ins\"></span>",$_SESSION['error'],$contenuto);
$contenuto=str_replace("<areacom/>",$commento,$contenuto);
$contenuto=str_replace("</errorform>",$_SESSION['errorf'],$contenuto);
$contenuto=str_replace("</commenterror>",$_SESSION['commenti'],$contenuto);
$contenuto=str_replace("<new>",$_SESSION['nuova'],$contenuto);
$contenuto=str_replace("<old>",$_SESSION['vecchia'],$contenuto);
$content=str_replace("<content/>",$contenuto,$content);
$content=str_replace("</err/>",$_SESSION['errori1'],$content);
echo $content;

/*
$contenuto.="<aside>
                <h2 class="titles_area_classi">Legenda valutazione</h2>
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

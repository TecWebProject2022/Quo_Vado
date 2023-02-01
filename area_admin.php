<?php
session_start();

// Se non hai fatto il login o la tua sessione (durata max 1 h di inattività) è scaduta
if(!isset($_SESSION['user']) || !isset($_SESSION['time']) || time()-$_SESSION['time']>3600){
    unset($_SESSION['user']); 
    unset($_SESSION['time']);
    unset($_SESSION['password']);
    unset($_SESSION['add']);
    unset($_SESSION['nome_admin']);
    unset($_SESSION['add_nome']);
    unset( $_SESSION['add_link']);
    unset($_SESSION['nuova']);
    unset($_SESSION['vecchia']);
    $_SESSION['sessione']='<p class="error">Sessione Scaduta</p>';
    header('Location:login.php');
}
else if($_SESSION['user']!='admin'){
    unset($_SESSION['user']); 
    unset($_SESSION['time']);
    $_SESSION['sessione']='<p class="error">Non hai i permessi di admin per accedere a quest\' area.</p>';
    header('Location:login.php');
}

require_once 'utilita.php';
require_once 'database.php';

$content=file_get_contents("area_admin.html");

if(isset($_SESSION['info'])){
    $content=str_replace('<span id="2"></span>',$_SESSION['info'],$content);
    $_SESSION['info']='';
}
#dichiarazioni
$msgCommenti='';
$msgCorso='';
$msgPassword='';
$msgCommenti_delete='';
$formCommenti='';
$tags=array(
    1=>"commento generale",
    2=>"commento riguardante l'inclusivita"
);
$formSelezioneCommenti='';
$formGestioneCorsi='';
$formCambioPw='';
$conn_error='';

#inizio
$db=new Connection();
$dbOK=$db->Connect();
if($dbOK){
    #preparazione elementi select classi e atenei
    $query_get_classi='SELECT num_classe FROM ClassediLaurea ;';
    $opzioni='';
    if($lista_classi=$db->ExecQueryAssoc($query_get_classi)){
        foreach($lista_classi as $l){
            $opzioni.='<option value="'.$l['num_classe'].'">'.$l['num_classe'].'</option>';
        }
    }
    if($opzioni){    
        $input_classi_commenti='<select id="com_classe" name="com_classe"  
        > <option value="" disabled="disabled" selected="selected">Seleziona una classe</option>'.$opzioni.'</select>';
        $input_classi_corsi='<select id="cor_classe" name="cor_classe"  
        >'.$opzioni.'</select>';
    }else{
        $input_classi_commenti='<input id="com_classe" name="com_classe" type="text" placeholder="L01" />';
        $input_classi_corsi='<input id="cor_classe" name="cor_classe" type="text" placeholder="L01"/>';
    }
    $query_get_atenei='SELECT nome FROM Ateneo;';
    $opzioni='';
    if($lista_atenei=$db->ExecQueryAssoc($query_get_atenei)){
        foreach($lista_atenei as $l){
            $opzioni.='<option value="'.$l['nome'].'">'.$l['nome'].'</option>';
        }
    }
    if($opzioni){ 
        $input_atenei='<select id="cor_ateneo" name="cor_ateneo">'.$opzioni.'</select>';
    }else{
        $input_atenei='<input id="cor_ateneo" name="cor_ateneo" type="text" placeholder="Politecnico di milano"/>';
    }
    $queryUtente=$db->ExecQueryAssoc("Select * From Utente where nome_utente!=\"admin\"");
    $formSelezioneCommenti='';
    $formSelezioneCommenti.='
    <h2 id="Cancella" class="titles_area_classi">Elimina un commento</h2>';
    # preparazione form commenti
    if(!$lista_classi || !$queryUtente){
        $formSelezioneCommenti.="<p class=\"invito\">Siamo spiacenti, non è presente nessun utente e nessuna classe di laurea di cui eliminare commenti</p>";
    }
    if($lista_classi && $queryUtente){  
    
   
    
    $formSelezioneCommenti.='
   
    <p id="formTrovaCommenti" class="formdesc">Inserisci nome utente o classe di laurea dei commenti che vuoi ricercare</p>
       <msgCommenti/>
        <form aria-describedby="formTrovaCommenti" id="formTrovaCommentiform" action="area_admin.php#formEliminaCommenti" method="post" onsubmit=" return Cancella()">
            <fieldset>
                <legend class="field_legend">Trova i commenti da eliminare</legend>
                <label for="com_utente">Utente: </label>
                <span><input value="<nome>" id="com_utente" name="com_utente" type="text" placeholder="Inserisci Utente" /></span>

                <label for="com_classe">Inserisci Classe di laurea: </label>
                <span>'.$input_classi_commenti.'</span><br/>

                <input type="submit" class="submit"  name="trova" onclick="Cancella()" value="trova"/>
            </fieldset>
        </form>';
    # sezione elimina commenti
    if(isset($_POST['trova'])){
        $formCommenti='';
        # in base ai campi inseriti imposto la query
        $user=isset($_POST['com_utente']) ? PulisciInput($_POST['com_utente']):'';
        $classe=isset($_POST['com_classe']) ? PulisciInput($_POST['com_classe']):'';
        $_SESSION['nome_admin']=$user;
        if($msgCommenti){
            $msgCommenti='<ul>'.$msgCommenti.'</ul>';
        }else{
            if($user ||  $classe ){
                $query_commenti='SELECT nome_utente,datav,classe_laurea,tag,commento,p_complessivo,p_acc_fisica, p_servizio_inclusione,tempestivita_burocratica, p_insegnamento FROM Valutazione WHERE ';
                if($user && $classe){
                    $query_commenti.="nome_utente=\"".$user."\" and classe_laurea=\"".$classe."\";";
                }
                else{
                    if($user){
                        $query_commenti.="nome_utente=\"".$user."\";";
                       
                    }
                    else{
                        $query_commenti.="classe_laurea=\"".$classe."\";";
                    }
                }
                
                
                

                if($commenti=$db->ExecQueryNum($query_commenti)){
                    $formCommenti='<p id="formEliminaCommenti" class="formdesc">Selezionare i commenti che vuoi eliminare e premere "Elimina commenti selezionati" per eliminarli</p><form aria-describedby="formEliminaCommenti" id="form_cancellacomm" action="area_admin.php" method="post" onsubmit="return OnDelete()"><fieldset><legend class="field_legend">Seleziona i commenti da eliminare</legend>';
                    $formCommenti.= "<ul id=\"commrilasc\">";
                    for($i=0;$i<count($commenti);$i++){
                        $commento='

                            <span class="highlight">utente: '.$commenti[$i][0].'</span><span> classe di laurea: '.$commenti[$i][2].'</span><span class="highlight">data commento:'.date("d-m-Y",strtotime($commenti[$i][1])).'</span>
                            <span >commento : '.$commenti[$i][4].'</span>
                            <span class="highlight">Punteggio complessivo: '.$commenti[$i][5].' </span>
				            <span>Punteggio accessibita fisica: '.$commenti[$i][6].' </span>
				            <span class="highlight">Punteggio servizio inclusione: '.$commenti[$i][7].' </span>
				            <span>Punteggio tempestivita burocratica: '.$commenti[$i][8].' </span>
				            <span class="highlight">Punteggio insegnamento: '.$commenti[$i][9].'</span>
				            <span class="tipo_valutazione">'.$tags[$commenti[$i][3]].'</span>';

                        $formCommenti.='
                        <li class="blocco_commento"> <span><input type="checkbox" id="C'.$i.'" name="commento[]" value="'.$commenti[$i][0].'-'.$commenti[$i][2].'-'.$commenti[$i][3].'"/></span>
                                <label for="C'.$i.'">'.$commento.'</label></li>
                           ';
                        
                    }
                    $formCommenti.="</ul>";
                    $formCommenti.='<input type="submit" class="submit" id="delete_commento" name="delete_commento"  value="elimina commenti selezionati"/></fieldset></form>';
                }else{
                    $msgCommenti.='<p class="error">E\' presente nessun commento in base al campo inserito</p>';
                }
            }else{
                #nessun valore inserito nel form perla ricerca
                $msgCommenti.='<p class="error">E\' necessario riempire almeno uno dei due campi</p>';
            } 
        }  
    }
}
$formGestioneCorsi.='
<h2 id="Corsi" class="titles_area_classi">Gestione corsi di studio</h2>';
if($lista_classi && $lista_atenei){
    #preparazione form corsi
    $formGestioneCorsi.='
        <p id="Aggicorso" class="formdesc">Per aggiungere un corso di studi è necessario riempire tutti i campi, per eliminarne uno bastano nome, classe di laurea e ateneo e premere il relativo pulsante</p>
        <form id="formCorsi" aria-describedby="Aggicorso" action="area_admin.php" method="post">
            <fieldset>
                <legend class="field_legend">Aggiungi o elimina un corso di studi</legend>
                
                <label for="cor_classe">Classe di laurea: </label>
                <span>'.$input_classi_corsi.'</span>
                <label for="cor_ateneo">Ateneo: </label>
                <span>'.$input_atenei.'</span>
                <label for="cor_nome">Nome corso di studio: </label>
                <span><input id="cor_nome" value="<nome>" name="cor_nome" type="text" placeholder="Inserisci corso di studio"/></span>
                <label for="cor_link">indirizzo  del sito web: </label>
                <span><input id="cor_link" value="<link>" name="cor_link" type="text" placeholder="inserisci link al sito web"/></span>
                <label for="cor_accesso">Tipo di accesso: </label>
                <span><select name="cor_accesso" id="cor_accesso">
                        <option value="Accesso programmato">Accesso programmato</option>
                        <option value="Accesso libero con prova">Accesso libero con prova</option>
                        <option value="Accesso a numero chiuso">Accesso a numero chiuso</option>
                        <option value="Accesso libero cronologico">Accesso libero cronologico</option>
                </select></span><br/>

                <input type="submit"  class="submit"  onclick="return Add()" id="add_corso" name="add_corso" value="Aggiungi" />
                <input type="submit"  class="submit" onclick="return Remove()" id="delete_corso" name="delete_corso" value="Elimina" />
            </fieldset>
        </form><msgCorsi/>';
    
        # controllo se nel form per la ricerca e' stato selezionato qualcosa
    if(isset($_POST['delete_commento'])){
        $_SESSION['add']='';
        #commenti da eliminare selezionati
       
        $commenti_selezionati=isset($_POST['commento']) ? $_POST['commento']: '';
        if($commenti_selezionati){
            foreach($commenti_selezionati as $i){  
                $userdata=explode("-",$i);
                $query_delete_commenti="DELETE FROM Valutazione Where nome_utente=\"".$userdata[0]."\" && classe_laurea=\"".$userdata[1]."\" && tag=\"".$userdata[2]."\";";
                if($db->Insert($query_delete_commenti)){
                    $_SESSION['info'].='<p id="ok" class="invito">Commento dell\'utente '.$userdata[0].' eliminato con successo</p>';
                    unset($_SESSION['nome_admin']);
                    unset($_SESSION['add']);
                    header("Location:area_admin.php#ok");
                }else{
                    $_SESSION['add']='<p class="error">Cancellazione non riuscita</p>';
                    header("Location:area_admin.php#formCorsi");
                }
            }
        }else{
          
            $_SESSION['add']='<p class="error">Selezionare almeno un commento</p>';
            header("Location:area_admin.php#formCorsi");
        }
       
    }

    #sezione gestione corsi
    if(isset($_POST['add_corso'])){
        $_SESSION['add']='';
        $classe=isset($_POST['cor_classe'])?PulisciInput($_POST['cor_classe']):'';
        $ateneo=isset($_POST['cor_ateneo'])?PulisciInput($_POST['cor_ateneo']):'';
        $nome=isset($_POST['cor_nome'])?PulisciInput($_POST['cor_nome']):'';
        $link=isset($_POST['cor_link'])?PulisciInput($_POST['cor_link']):'';
        $accesso=isset($_POST['cor_accesso'])? PulisciInput($_POST['cor_accesso']):'';
        $_SESSION['add_nome']=$nome;
        $_SESSION['add_link']=$link;
        if (!preg_match('/^(L|LM)[0-9]{2}$/',$classe)){
            $msgCorso.='<li class="error"></li>';
            $_SESSION['add'].='<p class="error">La classe di laurea non può essere vuoto o contenere spazi.Le classi di laurea vanno dalla L01 alla L43 e dalla LM01 alla LM94</p>';
            header("Location:area_admin.php#formCorsi");
        }
        if (!preg_match('/^(Accesso programmato|Accesso libero con prova|Accesso a numero chiuso|Accesso libero cronologico)$/',$accesso)){
            $msgCorso.='<li class="error"></li>';
            $_SESSION['add'].='<p class="error">Le modalita di accesso sono Accesso programmato,Accesso libero con prova,Accesso a numero chiuso,Accesso libero cronologico4</p>';
            header("Location:area_admin.php#formCorsi");
        }
        if (!preg_match('/^[a-zA-ZÀ-ÿ\s]{1,50}$/',$ateneo)){
            $msgCorso.='<li class="error"></li>';
            $_SESSION['add'].='<p class="error">Il nome dell\'ateneo non può essere vuoto o contenere numeri o caratteri speciali</p>';
            header("Location:area_admin.php#formCorsi");
        }
        if (!preg_match('/^[a-zA-ZÀ-ÿ\s]{1,50}$/',$nome)){
            $msgCorso.='<li class="error"></li>';
            $_SESSION['add'].='<p class="error">Il nome del corso di laurea non può essere vuoto o contenere numeri o caratteri speciali</p>';
            header("Location:area_admin.php#formCorsi");
        }
        if(!filter_var($link, FILTER_VALIDATE_URL)){
            $msgCorso.='<li class="error"></li>';
            $_SESSION['add'].='<p class="error">Indirizzo <span lang="en">web</span> non valido, inserire un <span lang="en">url</span> corretto</p>';
            header("Location:area_admin.php#formCorsi");
        }
        if(!$msgCorso){
            # tutte le variabili sono istanziate e valide
           
                $query_insert_corso="INSERT INTO CorsodiStudio(ateneo,classe_laurea,nome,accesso,link) VALUES ('".$ateneo."','".$classe."','".$nome."','".$accesso."','".$link."');";
                if($db->Insert($query_insert_corso)){
                    $_SESSION['info'].='<p class="invito">'.$nome.' aggiunto con successo</p>';
                    unset($_SESSION['add']);
                    unset( $_SESSION['add_link']);
                    unset( $_SESSION['add_nome']);
                    header("Location:area_admin.php");
                }else{
                   
                    $_SESSION['add'].='<p class="error">Corso  '.$nome.' già presente </p>';
                    header("Location:area_admin.php#formCorsi");
                }
            
        }else{
            $msgCorso='<ul>'.$msgCorso.'</ul>';
        }
    }else{
        if(isset($_POST['delete_corso'])){
            $_SESSION['add']='';
            $classe=isset($_POST['cor_classe']) ? PulisciInput($_POST['cor_classe']):'';
            $ateneo=isset($_POST['cor_ateneo']) ? PulisciInput($_POST['cor_ateneo']):'';
            $nome=isset($_POST['cor_nome']) ? PulisciInput($_POST['cor_nome']):'';
            $_SESSION['add_nome']=$nome;
            #controlli sulle variabili
            if (!preg_match('/^(L|LM)[0-9]{2}$/',$classe)){
                $msgCorso.='<li class="error"></li>';
                $_SESSION['add'].='<p class="error">La classe di laurea non può essere vuoto o contenere spazi.Le classi di laurea vanno dalla classe L01 alla L43 e dalla LM01 alla LM94</p>';
                header("Location:area_admin.php#formCorsi");
            }
            if (!preg_match('/^[a-zA-ZÀ-ÿ\s]{1,50}$/',$ateneo)){
                $msgCorso.='<li class="error"></li>';
                $_SESSION['add'].='<p class="error">Il nome dell\'ateneo non può essere vuoto o contenere numeri o caratteri speciali</p>';
                header("Location:area_admin.php#formCorsi");
            }
            if (!preg_match('/^[a-zA-ZÀ-ÿ\s]{1,80}$/',$nome)){
                $msgCorso.='<li class="error"></li>';
                $_SESSION['add'].='<p class="error">Il nome del corso di laurea non può essere vuoto o contenere numeri o caratteri speciali</p>';
                header("Location:area_admin.php#formCorsi");
            }

            if(!$msgCorso){
                # tutte le variabili sono istanziate e valide
                $query_delete_corso="DELETE FROM CorsodiStudio WHERE  ateneo='".$ateneo."' AND classe_laurea='".$classe."' AND nome='".$nome."';";
                if($db->Insert($query_delete_corso)){
                    $_SESSION['info'].='<p class="invito">'.$nome.' rimosso con successo</p>';
                    unset($_SESSION['add']);
                    unset( $_SESSION['add_nome']);
                    header("Location:area_admin.php");
                }else{
                    $_SESSION['add'].='<p class="error">Il corso risulta inesistente</p>';
                    header("Location:area_admin.php#formCorsi");
                }
            }
        }
    }
}
else{
   
        $formGestioneCorsi.="<p class=\"invito\">Siamo spiacenti, non è presente alcun ateneo o alcuna classe di laurea  di cui inserire un nuovo corso di studio</p>";
    
}
    #preparazione form cambio password
    $formCambioPw=' 
    <h2 id="CambioPw" class="titles_area_classi">Cambia <span lang="en">Password</span></h2>
    <p class="formdesc" id="form_passw">Per modificare la tua <span lang="en">password</span> compila i campi sottostanti e clicca "salva" per salvare la modifica</p>
    <form aria-describedby="form_passw" action="area_admin.php" method="post"  id="formpw" onsubmit="return OnPassword()" >
        <fieldset>
            <legend class="field_legend">Cambio <span lang="en">password</span></legend>
            <label for="oldpassword">Immetti la tua vecchia <span lang="en">Password</span>: </label>
            <span><input  value="<old>" type="password" id="oldpassword" name="Vecchiapassword" placeholder="Inserisci vecchia password" maxlength="20" /></span>
            <br/><label for="newpassword">Immetti la tua nuova <span lang="en">Password</span>: </label>
            <span><input  value="<new>" type="password" id="newpassword" name="newpassword" placeholder="Inserisci nuova password" maxlength="20" /></span>
            <br/><label for="repeat">Ripeti la <span lang="en">Password</span>:</label>   
            <span><input  value="" type="password" id="repeat" name="repepassword" placeholder="Ripeti la password" maxlength="20"  /></span>
            <br/><input type="submit"  class="submit" id="submit" name="salva" value="Salva"/>
        </fieldset>
    </form>';
    #sezione  cambio password da gestire 
    if(isset($_POST['salva']) && check()){
        $_SESSION['password']='';
        $vecchia=PulisciInput($_POST['Vecchiapassword']);
        $nuova=PulisciInput($_POST['newpassword']);
        $_SESSION['nuova']=$nuova;
        $_SESSION['vecchia']=$vecchia;
       


        $rep=PulisciInput($_POST['repepassword']);
        
        if (!preg_match('/^[@a-zA-Z0-9._-]{4,20}$/',$vecchia)){
            $msgPassword.='<li class="error"></li>';
            $_SESSION['password'].='<p class="error">Il campo vecchia <span lang="en">password</span> non può essere vuoto e non può contenere spazi e deve contenere da 4 a 20 caratteri alfanumerici (sono ammessi i seguenti caratteri: @ . _ - )</p>';
             header("Location:area_admin.php#form_passw");
        }
        if (!preg_match('/^[@a-zA-Z0-9._-]{4,20}$/',$nuova)){
            $msgPassword.='<li class="error"></li>';
            $_SESSION['password'].='<p class="error">Il campo nuova <span lang="en">password</span> non può essere vuoto e non può contenere spazzi e deve contenere da 4 a 20 caratteri alfanumerici (sono ammessi i seguenti caratteri: @ . _ - )</p>';
            header("Location:area_admin.php#form_passw");
        }
        if($nuova!=$rep){
            $msgPassword.='<li class="error"></li>';
            $_SESSION['password'].='<p class="error">Il campo nuova <span lang="en">password</span> e ripeti la <span lang="en">password</span> non corrispondono</p>';
            header("Location:area_admin.php#form_passw");
        }
        
        if(!$msgPassword){
            $query="Select * from Credenziale where utente='admin' && pw=\"".$nuova."\";";
            if($r=$db->ExecQueryAssoc($query)){
               
                $_SESSION['password'].='<p class="error"><span lang="en">Password</span> già usata</p>';
                header("Location:area_admin.php#form_passw");
            }else{
                $query_controllo_pw="Select * from Credenziale where utente='admin' && pw=\"".$vecchia."\";";
                if($db->ExecQueryAssoc($query_controllo_pw)){
                    $query_update_pw="UPDATE Credenziale SET attuale=0 WHERE utente='admin' and pw=\"".$vecchia."\";";
                    $query_update_pw.="INSERT INTO Credenziale(pw, data_inserimento, utente, attuale) VALUES('".$nuova."','".date('Y-m-d')."','admin',1);";
                    if($db->multiInsert($query_update_pw)){
                        $_SESSION['info'].='<p class="invito"><span lang="en">Password</span> modificata con successo</p>';
                        unset($_SESSION['password']);
                        $_SESSION['nuova']='';
                        $_SESSION['vecchia']='';
                        header("Location:area_admin.php");
                    }else{
                        $_SESSION['password'].='<p class="error">Cambiamento <span lang="en">password</span> non riuscito</p>';
                        header("Location:area_admin.php#form_passw");
                    } 
                }else{
                   
                    $_SESSION['password'].='<p class="error">La vecchia password non corrisponde</p>';
                    header("Location:area_admin.php#form_passw");
                }
            }
        
    }
    }
$db->Disconnect();
}else{
    $formSelezioneCommenti='<p class="invito">Spiacente non è stato possibile recuperarre le informazioni personali</p>'.$formSelezioneCommenti;
}
if(!isset($_SESSION['add'])){
    $_SESSION['add']='';
}
if(!isset($_SESSION['password'])){
    $_SESSION['password']='';
}
if(!isset($_SESSION['nuova'])){
    $_SESSION['nuova']='';
}
if(!isset($_SESSION['vecchia'])){
    $_SESSION['vecchia']='';
}
if(!isset($_SESSION['nome_admin']))
{
    $_SESSION['nome_admin']='';
}
if(!isset($_SESSION['add_nome'])){
    $_SESSION['add_nome']='';
}
if(! isset($_SESSION['add_link'])){
    $_SESSION['add_link']='';
}
#stampa sezione commenti
$formSelezioneCommenti=str_replace("<nome>",$_SESSION['nome_admin'],$formSelezioneCommenti);
$content=str_replace("<formSelezioneCommenti/>",$formSelezioneCommenti,$content);
$content=str_replace("<formCommenti/>",$formCommenti,$content);
$content=str_replace("<msgCommenti/>",$msgCommenti,$content);
$content=str_replace("<msgCommenti_delete/>",$msgCommenti_delete,$content);
$formGestioneCorsi=str_replace("<nome>", $_SESSION['add_nome'],$formGestioneCorsi);
$formGestioneCorsi=str_replace("<link>",  $_SESSION['add_link'],$formGestioneCorsi);
$content=str_replace("<formGestioneCorsi/>",$formGestioneCorsi,$content);
$content=str_replace("<msgCorsi/>",$_SESSION['add'],$content);
#stampa cambio pw
$formCambioPw=str_replace("<new>",$_SESSION['nuova'],$formCambioPw);
$formCambioPw=str_replace("<old>",$_SESSION['vecchia'],$formCambioPw);
$content=str_replace("<formCambioPw/>",$formCambioPw,$content);
$content=str_replace("<msgPassword/>",$_SESSION['password'],$content);
$content=str_replace("<msgConnError/>",$conn_error,$content);

echo $content;

?>
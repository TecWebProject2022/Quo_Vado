<?php
session_start();

// Se non hai fatto il login o la tua sessione (durata max 1 h di inattività) è scaduta
if(!isset($_SESSION['user']) || !isset($_SESSION['time']) || time()-$_SESSION['time']>3600){
    unset($_SESSION['user']); 
    unset($_SESSION['time']);
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
#dichiarazioni
$msgCommenti='';
$msgCorso='';
$msgPassword='';
$msgCommenti_delete='';
$formCommenti='';



#inizio
$db=new Connection();
$dbOK=$db->Connect();
if($dbOK){
    # sezione elimina commenti
    if(isset($_POST['trova'])){
        # in base ai campi inseriti imposto la query
        $user=isset($_POST['com_utente'])?pulisciInput($_POST['com_utente']):'';
        $classe=isset($_POST['com_classe'])?pulisciInput($_POST['com_classe']):'';

        if (($user?(!preg_match('/^[@a-zA-Z0-9._-]{4,40}$/',$user)): false)){
            $msgCommenti.='<li class="error">Il campo username non può contenere spazi e deve contenere da 4 a 40 caratteri alfanumerici (sono ammessi i seguenti caratteri: @ . _ - )</li>';
        }
        if (($classe?(!preg_match('/^(L|LM)[0-9]{2}$/',$classe)): false)){
            $msgCommenti.='<li class="error">La classe di laurea non puo\' contenere spazi.Le classi di laurea vanno dalla classe L01 alla L43 e dalla LM01 alla LM94</li>';
        }

        if($msgCommenti){
            $msgCommenti='<ul>'.$msgCommenti.'</ul>';
        }else{
            if($user || $classe ){
                $query_commenti='SELECT nome_utente,datav,classe_laurea,tag,commento,p_complessivo,p_acc_fisica, p_servizio_inclusione,tempestivita_burocratica, p_insegnamento FROM Valutazione WHERE ';
                $query_commenti.= $user? "nome_utente='".$user."'" : '';
                $query_commenti.= ($user && $classe)? ' AND ' : '';
                $query_commenti.= $classe? "classe_laurea='".$classe."'" : '';
                
                if (substr($query_commenti, -4) == " AND ") {
                    $query_commenti = substr($query, 0, -4);
                }
                $query_commenti.=';';

                if($commenti=$db->ExecQueryNum($query_commenti)){
                    $formCommenti='<form id="formEliminaCommenti" action="area_admin.php" method="post" onsubmit="return OnCommentDelete()"><fieldset><legend>Seleziona i commenti da eliminare</legend>';
                    for($i=0;$i<count($commenti);$i++){
                        $commento='<span> utente: '.$commenti[$i][0].'| classe di laurea: '.$commenti[$i][2].'| data commento:'.date("d-m-Y",strtotime($commenti[$i][1]));
                        $commento.='<p>commento :'.$commenti[$i][4].'</p>';
                        $commento.='<dl>
                            <dt>Punteggio complessivo:</dt><dd> '.$commenti[$i][5].' </dd>
                            <dt>Punteggio accessibilita fisica:</dt><dd> '.$commenti[$i][6].' </dd>
                            <dt>Punteggio servizio inclusione:</dt><dd> '.$commenti[$i][7].' </dd>
                            <dt>Punteggio tempestivita burocratica:</dt><dd> '.$commenti[$i][8].' </dd>
                            <dt>Punteggio insegnamento:</dt><dd> '.$commenti[$i][9].' </dd>
                        </dl></span>';

                        $formCommenti.='<label for="'.$i.'">'.$commento.'</label>';
                        $formCommenti.='<input type="checkbox" id="'.$i.'" name="commento[]" value="'.$commenti[$i][0].'-'.$commenti[$i][2].'-'.$commenti[$i][3].'"/>';
                    }
                    $formCommenti.='<input type="submit" id="delete_commento" name="delete_commento" value="elimina commenti selezionati"/></fieldset></form>';
                }else{
                    $msgCommenti.='<p class="error">nessun commento</p>';
                }
            }else{
                #nessun valore inserito nel form perla ricerca
                $msgCommenti.='<p class="error">riempire almeno uno dei due campi</p>';
            } 
        }  
    }
    # controllo se nel form per la ricerca e' stato selezionato qualcosa
    if(isset($_POST['delete_commento'])){
        #commenti da eliminare selezionati
        $msgCommenti_delete.='<ul>';
        $commenti_selezionati=isset($_POST['commento']) ? $_POST['commento']: '';
        if($commenti_selezionati){
            foreach($commenti_selezionati as $i){  
                $userdata=explode("-",$i);
                $query_delete_commenti="DELETE FROM Valutazione Where nome_utente=\"".$userdata[0]."\" && classe_laurea=\"".$userdata[1]."\" && tag=\"".$userdata[2]."\";";
                if(!$db->Insert($query_delete_commenti)){
                    $msgCommenti_delete.='<li class="error">Si è verificato un errori ai nostri servizi, commento dell\'utente '.$userdata[0].' non eliminato</li>';
                }else{
                    $msgCommenti_delete.='<li class="error">Commento dell\'utente '.$userdata[0].' eliminato con successo</li>';
                }
            }
        }else{
            $msgCommenti_delete.='<li class="error">Selezionare almeno un commento</li>';
        }
        $msgCommenti_delete.='</ul>';
    }

    #sezione gestione corsi
    if(isset($_POST['add_corso'])){
        $classe=isset($_POST['cor_classe'])?pulisciInput($_POST['cor_classe']):'';
        $ateneo=isset($_POST['cor_ateneo'])?pulisciInput($_POST['cor_ateneo']):'';
        $nome=isset($_POST['cor_nome'])?pulisciInput($_POST['cor_nome']):'';
        $link=isset($_POST['cor_link'])?pulisciInput($_POST['cor_link']):'';
        $accesso=isset($_POST['cor_accesso'])?pulisciInput($_POST['cor_accesso']):'';
        #controlli sulle variabili
        if (!preg_match('/^(L|LM)[0-9]{2}$/',$classe)){
            $msgCorso.='<li class="error">La classe di laurea non puo\' essere vuoto o contenere spazi.Le classi di laurea vanno dalla L01 alla L43 e dalla LM01 alla LM94</li>';
        }
        if (!preg_match('/^(Accesso programmato|Accesso libero con prova|Accesso a numero chiuso|Accesso libero cronologico)$/',$classe)){
            $msgCorso.='<li class="error">Le modalita di accesso sono Accesso programmato,Accesso libero con prova,Accesso a numero chiuso,Accesso libero cronologico</li>';
        }
        if (!preg_match('/^[a-zA-Z\s]+$/',$ateneo)){
            $msgCorso.='<li class="error">Il nome dell\'ateneo non puo\' essere vuoto o contenere numeri o caratteri speciali</li>';
        }
        if (!preg_match('/^[a-zA-Z\s]+$/',$nome)){
            $msgCorso.='<li class="error">Il nome del corso di laurea non puo\' essere vuoto o contenere numeri o caratteri speciali</li>';
        }
        if(!filter_var($link, FILTER_VALIDATE_URL)){
            $msgCorso.='<li class="error">Link non valido, inserire un url corretto</li>';
        }
        if(!$msgCorso){
            # tutte le variabili sono istanziate e valide
            $query_insert_corso="INSERT INTO CorsodiStudio(ateneo,classe_laurea,nome,accesso,link) VALUES ('".$ateneo."','".$classe."','".$nome."','".$accesso."','".$link."');";
            if($db->Insert($query_insert_corso)){
                $msgCorso.='<p class="error">'.$nome.' aggiunto con successo</p>';
            }else{
                $msgCorso.='<p class="error">Inserimento di '.$nome.' non riuscito, riprova</p>';
            }
        }else{
            $msgCorso='<ul>'.$msgCorso.'</ul>';
        }
    }else{
        if(isset($_POST['delete_corso'])){
            $classe=isset($_POST['cor_classe'])?pulisciInput($_POST['cor_classe']):'';
            $ateneo=isset($_POST['cor_ateneo'])?pulisciInput($_POST['cor_ateneo']):'';
            $nome=isset($_POST['cor_nome'])?pulisciInput($_POST['cor_nome']):'';
            #controlli sulle variabili
            if (!preg_match('/^(L|LM)[0-9]{2}$/',$classe)){
                $msgCorso.='<li class="error">La classe di laurea non puo\' essere vuoto o contenere spazi.Le classi di laurea vanno dalla classe L01 alla L43 e dalla LM01 alla LM94</li>';
            }
            if (!preg_match('/^[a-zA-Z\s]+$/',$ateneo)){
                $msgCorso.='<li class="error">Il nome dell\'ateneo non puo\' essere vuoto o contenere numeri o caratteri speciali</li>';
            }
            if (!preg_match('/^[a-zA-Z\s]+$/',$nome)){
                $msgCorso.='<li class="error">Il nome del corso di laurea non puo\' essere vuoto o contenere numeri o caratteri speciali</li>';
            }

            if(!$msgCorso){
                # tutte le variabili sono istanziate e valide
                $query_delete_corso="DELETE FROM CorsodiStudio WHERE  ateneo='".$ateneo."' AND classe_laurea='".$classe."' AND nome='".$nome."';";
                if($db->Insert($query_delete_corso)){
                    $msgCorso.='<p class="error">'.$nome.' rimosso con successo</p>';
                }else{
                    $msgCorso.='<p class="error">Cancellazione di '.$nome.' non riuscita, riprova</p>';
                }
            }else{
                $msgCorso='<ul>'.$msgCorso.'</ul>';
            }
        }
    }

        
    #sezione  cambio password da gestire 
    if(isset($_POST['salva']) && check()){
        $vecchia=PulisciInput($_POST['Vecchiapassword']);
        $nuova=PulisciInput($_POST['newpassword']);
        $rep=PulisciInput($_POST['repepassword']);
        
        if (!preg_match('/^[@a-zA-Z0-9._-]{4,20}$/',$vecchia)){
            $msgPassword.='<li class="error">Il campo vecchia password non può essere vuoto e non può contenere spazi e deve contenere da 4 a 20 caratteri alfanumerici (sono ammessi i seguenti caratteri: @ . _ - )</li>';
        }
        if (!preg_match('/^[@a-zA-Z0-9._-]{4,20}$/',$nuova)){
            $msgPassword.='<li class="error">Il campo nuova password non può essere vuoto e non può contenere spazzi e deve contenere da 4 a 20 caratteri alfanumerici (sono ammessi i seguenti caratteri: @ . _ - )</li>';
        }
        if($nuova!=$rep){
            $msgPassword.='<li class="error">Il campo nuova password e ripeti la password non corrispondono</li>';
        }
        
        if(!$msgPassword){
            $query="Select * from Credenziale where utente='admin' && pw=\"".$nuova."\";";
            if($r=$db->ExecQueryAssoc($query)){
                $msgPassword.='<p class="error">Password già usata</strong>';
            }else{
                $query_controllo_pw="Select * from Credenziale where utente='admin' && pw=\"".$vecchia."\";";
                if($db->ExecQueryAssoc($query_controllo_pw)){
                    $query_update_pw="UPDATE Credenziale SET attuale=0 WHERE utente='admin' and pw=\"".$vecchia."\";";
                    $query_update_pw.="INSERT INTO Credenziale(pw, data_inserimento, utente, attuale) VALUES('".$nuova."','".date('Y-m-d')."','admin',1);";
                    if($db->multiInsert($query_update_pw)){
                        $msgPassword.='<p class="error">Password modificata con successo</strong>';
                    }else{
                        $msgPassword.='<p class="error">Cambiamento password non riuscito, i sistemi sono al momentamentamnete non disponibili</strong>';
                    } 
                }else{
                    $msgPassword.='<p class="error">la vecchia password inserita non corrisponde</strong>';
                }
            }
        }else{
            $msgPassword="<ul>".$msgPassword."</ul>";   
        }
    }
    $db->Disconnect();
}



$content=str_replace("<formCommenti/>",$formCommenti,$content);
$content=str_replace("<msgCommenti/>",$msgCommenti,$content);
$content=str_replace("<msgCommenti_delete/>",$msgCommenti_delete,$content);
$content=str_replace("<msgCorsi/>",$msgCorso,$content);
$content=str_replace("<msgPassword/>",$msgPassword,$content);

echo $content;

?>
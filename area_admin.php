<?php
session_start();
echo $_SESSION['user'];
echo time()-$_SESSION['time'];
// Se non hai fatto il login o la tua sessione (durata max 1 h di inattività) è scaduta
if(!isset($_SESSION['user']) || !isset($_SESSION['time']) || time()-$_SESSION['time']>3600){
    unset($_SESSION['user']); 
    unset($_SESSION['time']);
    $_SESSION['sessione']='<p>Sessione Scaduta</p>';
    header('Location:login.php');
}
else if($_SESSION['user']!='admin'){
    unset($_SESSION['user']); 
    unset($_SESSION['time']);
    $_SESSION['sessione']='<p>Non hai i permessi di admin per accedere a quest\' area.</p>';
    header('Location:login.php');
}

require_once 'utilita.php';
require_once 'database.php';

$content=file_get_contents("area_riservata.html");
#dichiarazioni
$msgCommenti='';

#variabili forse da eliminare
$vecchia='';

$nuova='';
$errorf='<ul>';




$errori1='<ul>';

$cancella='';

#inizio
$db=new Connection();
$dbOK=$db->Connect();
if($dbOK){
    # sezione elimina commenti
    if(isset($_POST['trova'])){
        # in base ai campi inseriti imposto la query
        $user=isset($_POST['com_utente'])?pulisciInput($_POST['com_utente']):'';
        $classe=isset($_POST['com_classe'])?pulisciInput($_POST['com_classe']):'';
        if($user || $classe ){
            $query_commenti='SELECT nome_utente,datav,classe_laurea,commento,p_complessivo,p_acc_fisica, p_servizio_inclusione,tempestivita_burocratica, p_insegnamento FROM Valutazione WHERE ';
            $query_commenti.= $user? "nome_utente='".$user."' AND ":'';
            $query_commenti.= $classe? "classe_laurea='".$classe."'":'';
            
            if (substr($query_commenti, -4) == " AND ") {
                $query = substr($query, 0, -4);
            }
            $query_commenti.=';';

            if($commenti=$db->ExecQueryAssoc($query_commenti)){
                $formCommenti='<form id="formEliminaCommenti" action="" method="post"><fieldset><legend>seleziona i commenti da eliminare</legend>';
                foreach($commenti as $c){
                    $commento='<span>'.$c['nome_utente'].'|'.$c['classe_laurea'].'|'.date("d-m-Y",strtotime($v['datav'])).':';
                    $commento.='<p>'.$c['commento'].'</p>';
                    $commento.='<dl>
                        <dt>Punteggio complessivo:</dt><dd> '.$c['p_complessivo'].' </dd>
                        <dt>Punteggio accessibilita fisica:</dt><dd> '.$c['p_acc_fisica'].' </dd>
                        <dt>Punteggio servizio inclusione:</dt><dd> '.$c['p_servizio_inclusione'].' </dd>
                        <dt>Punteggio tempestivita burocratica:</dt><dd> '.$c['p_tempestivita'].' </dd>
                        <dt>Punteggio insegnamento:</dt><dd> '.$c['p_insegnamento'].' </dd>
                    </dl></span>';

                    $formCommenti.='<label for="'.$c['nome_utente'].'-'.$c['classe_laurea'].'">'.$commento.'</label>';
                    $formCommenti.='<input type="checkbox" id="'.$c['nome_utente'].'-'.$c['classe_laurea'].'" name="'.$c['nome_utente'].'-'.$c['classe_laurea'].'"/>';
                    $formCommenti.= '<input type="submit" id="delete_commento" name="delete_commento value="elimina commenti selezionati"/></fieldset></form>';
                }
                $msgCommenti.='<msgDeleteComments>';
            }else{
                $msgCommenti.='<p>nessun commento</p>';
            }
        }else{
            #nessun valore inserito nel form perla ricerca
            $msgCommenti.='<p>riempire almeno uno dei tre campi</p>';
        }  
    }

    #sezione gestione corsi
    if(isset($_POST['add_corso'])){}
    if(isset($_POST['delete_corso'])){}
    #sezione  cambio password da gestire 
    if(isset($_POST['submit1']) && check()){
        $vecchia=PulisciInput($_POST['Vecchiapassword']);
        $nuova=PulisciInput($_POST['newpassword']);
        $rep=PulisciInput($_POST['repepassword']);
        echo $vecchia;
        echo $nuova;
        echo $rep;
        $errori1='<ul>';
        if (!preg_match('/^[@a-zA-Z0-9._-]{4,20}$/',$vecchia)){
            $errori1.='<li>Il campo vecchia password non può essere vuoto e non può contenere spazi e deve contenere da 4 a 20 caratteri alfanumerici (sono ammessi i seguenti caratteri: @ . _ - )</li>';
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
                $query="Select * from Credenziale where utente='admin' && pw=\"".$nuova."\";";
                if($r=$db->ExecQueryAssoc($query)){
                    $errori1.="<li>Password già usata</li>";
                }
                else{
                    $query3="Select * from Credenziale where utente='admin' && pw=\"".$vecchia."\";";
                    if($r=$db->ExecQueryAssoc($query3)){
                    $query2="UPDATE Credenziale SET attuale=0 WHERE utente='admin' and pw=\"".$vecchia."\";";
                    $query2.="INSERT INTO Credenziale(pw, data_inserimento, utente, attuale) VALUES('".$nuova."',curdate(),'admin',1);";
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
<?php 
require_once 'utilita.php';
require_once 'database.php';
$target=PulisciInput($_GET['nclasse']);
$content=file_get_contents('classe.html');
$errori='';
$classe='';
$illustrazione='';
$valutazione='';
$commenti='';
$den='';
$universita='';
$db=new Connection();
$query1="Select denominazione,illustrazione,durata from ClassediLaurea where num_classe=\"$target\";";
$db=new Connection();
    $dbOK=$db->Connect();
    if($dbOK){
        if($res1=$db->ExecQueryAssoc($query1)){
            $classe=$res1[0]['denominazione'];
            $den=$target." - ".$classe;
            $illustrazione="<p>".$res1[0]['illustrazione']."</p>";
            $illustrazione.="<p>Durata: ".$res1[0]['durata']."</p>";
            $query2="SELECT CAST(AVG(p_complessivo) AS DECIMAL(3,2)) as \"pc\" ,CAST(AVG(p_acc_fisica) AS DECIMAL(3,2)) as \"pf\" ,CAST(AVG(p_servizio_inclusione) AS DECIMAL(3,2)) as \"ps\" ,CAST(AVG(tempestivita_burocratica) AS DECIMAL(3,2)) as \"tb\",CAST(AVG(p_insegnamento) AS DECIMAL(3,2)) as \"pi\" 
            FROM `Valutazione` WHERE classe_laurea=\"$target\";";
            if($res2=$db->ExecQueryAssoc($query2)){
                
                $valutazione="<h2>Valutazione</h2>
                            <ul>
                            <li>Complessivo: ".$res2[0]['pc']."</li>
                            <li>Accessibilità fisica: ".$res2[0]['pf']."</li>
                            <li>Servizio inclusione: ".$res2[0]['ps']."</li>
                            <li>Tempestività burocratica: ".$res2[0]['tb']."</li>
                            <li>Insegnamento: ".$res2[0]['pi']."</li></ul>";
                $query3="SELECT ateneo FROM `CorsodiStudio` WHERE classe_laurea=\"$target\";";
                $universita.='<h2>Dove la puoi trovare</h2><ul>';
                if($res3=$db->ExecQueryAssoc($query3)){
                    foreach($res3 as $uni){
                        $universita.='<li>'.$uni['ateneo'].'</li>'; 
                        $query4="SELECT nome,accesso,link FROM `CorsodiStudio` WHERE classe_laurea=\"$target\" and ateneo=\"".$uni['ateneo']."\";";
                        
                        if($res4=$db->ExecQueryAssoc($query4)){
                            $universita.="<li><ul>";
                            foreach($res4 as $c){
                                $universita.="<li>".$c['nome']."  ".$c['accesso']."</li>";
                                $universita.="<li> <a href=\"".$c['link']."\">Approfondisci</a></li>";
                            }
                            $universita.="</ul>";
                            $query5="SELECT datav,commento,ateneo,Valutazione.nome_utente as n  FROM Valutazione join Iscrizione on Valutazione.nome_utente=Iscrizione.nome_utente WHERE classe_laurea=\"$target\" and dataf IS NOT NULL;";
                            if($res5=$db->ExecQueryAssoc($query5)){
                                $commenti.="<dl>";
                                foreach($res5 as $d){
                                    $commenti.="<dt>".$d['n']."</dt>";
                                    $commenti.="<dd>".$d['ateneo']."</dd>";
                                    $commenti.="<dd>".$d['datav']." - ".$d['commento'];
                                }
                            $commenti.="</dl>";
                            }
                            else{
                                $errori.='<p>Non è stato trovata alcuna Valutazione associata</p>';
                            }
                        }
                        else{
                            $errori.='<p>Non è stato trovata alcuna Corso associato alla ricerca selezionata</p>';
                        }
                    }  
            
            }
            
            else{
                $errori.='<p>Non è stato trovata alcuna università</p>';
            }
        }
            else{
                $errori.='<p>Non è stato trovata alcuna valutazione</p>';
            }
        }   
           else{
            $errori.='<p>Non è stato trovata alcuna informazione in base alla sua ricerca</p>';
           }
           $db->Disconnect();
        }
    else{
        $errori.='<p>Ci scusiamo la connessione non è  riuscita, attendere e riprova</p>';
    } 
$content=str_replace('<name/>',$_GET['area'],$content);
$content=str_replace('<titolo/>',$den,$content);
$content=str_replace('<descrizione/>',$illustrazione,$content);

$content=str_replace('<classe/>', $classe,$content);

$content=str_replace('<val/>',$valutazione,$content);
$content=str_replace('<universita/>',$universita,$content);
$content=str_replace('<comment/>',$commenti,$content);
$content=str_replace('<error/>',$errori,$content);
echo $content;
?>
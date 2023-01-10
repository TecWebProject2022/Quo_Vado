<?php 
require_once 'utilita.php';
require_once 'database.php';
$target=PulisciInput($_GET['nclasse']);
$content=file_get_contents('classe.html');
$errori='';
$contenuto='';
$area='';
$classe='';

$db=new Connection();
    $dbOK=$db->Connect();
    if($dbOK){
        $query_classe="SELECT denominazione,illustrazione,area_disciplinare,gruppo_disciplinare,durata FROM ClassediLaurea WHERE num_classe=\"$target\";";
        if($classi=$db->ExecQueryAssoc($query_classe)){
            $area=$classi[0]['area_disciplinare'];
            $classe=$target.$classi[0]['denominazione'];
            $contenuto.='<h1 id="title">'.$target.'-'.$classi[0]['denominazione'].'</h1>';
            $contenuto.='<h2 >descrizione</h2>';
            $contenuto.='<p id="dettagliClasse">Area disciplinare: '.$classi[0]['area_disciplinare'].' | gruppo disciplinare: '.$classi[0]['gruppo_disciplinare'].' 
            | tipologia: '.$classi[0]['durata'].'</p>';
            $contenuto.='<p id="illustrazioneClasse">'.$classi[0]['illustrazione'].'</p>'; #temporaneo, necessario inserire descrizioni nel db
            
            # stampa punteggio complessivo
            $query_valComplessiva="SELECT CAST(AVG(p_complessivo) AS DECIMAL(3,2)) as \"pc\" ,
            CAST(AVG(p_acc_fisica) AS DECIMAL(3,2)) as \"pf\" ,CAST(AVG(p_servizio_inclusione) AS DECIMAL(3,2)) as \"ps\" ,
            CAST(AVG(tempestivita_burocratica) AS DECIMAL(3,2)) as \"tb\",CAST(AVG(p_insegnamento) AS DECIMAL(3,2)) as \"pi\" 
            FROM `Valutazione` WHERE classe_laurea=\"$target\";";
            if($valComplessiva=$db->ExecQueryAssoc($query_valComplessiva)){
        
                $contenuto.="<p>Valutazione degli Utenti:</p><ul>
                            <li>Complessivo: ".$valComplessiva[0]['pc']."</li>
                            <li>Accessibilità fisica: ".$valComplessiva[0]['pf']."</li>
                            <li>Servizio inclusione: ".$valComplessiva[0]['ps']."</li>
                            <li>Tempestività burocratica: ".$valComplessiva[0]['tb']."</li>
                            <li>Insegnamento: ".$valComplessiva[0]['pi']."</li></ul>";
            }
            else{
                $errori.='<p>Non è stato trovata alcuna valutazione</p>';
            }
            # se ottengo tag (da filtro, al primo caricamento della pagina sara sempr false) allora la query chiedera solo le valutazioni corrispondenti
            if(isset($_GET['tag'])){
                $targetTag=PulisciInput($_GET['tag']);
                $query_valutazione="SELECT nome_utente,datav,commento,p_complessivo,p_acc_fisica,p_servizio_inclusione,tempestivita_burocratica,p_insegnamento 
                FROM Valutazione WHERE classe_laurea=\"$target\" AND tag=\"$targetTag\";";
            }else{
                $query_valutazione="SELECT nome_utente,datav,commento,p_complessivo,p_acc_fisica,p_servizio_inclusione,tempestivita_burocratica,p_insegnamento 
                FROM Valutazione WHERE classe_laurea=\"$target\";";
            }
            if($valutazioni=$db->ExecQueryAssoc($query_valutazione)){
                $contenuto.='<p>Commenti:</p>';
                $contenuto.='<ul id="listaCommenti">';
                foreach($valutazioni as $v){
                    $contenuto.='<li id="commento"><strong>'.$v['nome_utente']."|".$v['datav']."</strong><p id=testoCommento>".$v['commento']."</p>";
                    $contenuto.='<ul id="valutazioneCommento">
                            <li>Complessivo: '.$v['p_complessivo']."</li>
                            <li>Accessibilità fisica: ".$v['p_acc_fisica']."</li>
                            <li>Servizio inclusione: ".$v['p_servizio_inclusione']."</li>
                            <li>Tempestività burocratica: ".$v['tempestivita_burocratica']."</li>
                            <li>Insegnamento: ".$v['p_insegnamento']."</li></ul></li>";
                }
                $contenuto.='</ul><span><input type="button" id="aggiuntaCommento" value="aggiungi un commento" onclick="addComment()"></span>
                <span><input type="button" id="mostraCommenti" value="mostra altri commenti" onclick="showComments()"></span>';
            }else{
                $errori.="<p>Opss,si è verficato un errore di conessione: impossibile caricare i commenti. Riprova</p>";
            }
            # corsi di studio associati
            $query_corso_di_studio="SELECT ateneo,nome,accesso,link FROM CorsodiStudio WHERE classe_laurea=\"$target\";";
            if($corsi=$db->ExecQueryAssoc($query_corso_di_studio)){
                #display corsi
                $contenuto.='<ul id="corsi">';
                foreach($corsi as $c){
                    $contenuto.='<li id="corso"><a href="'.$c['link'].'"><strong>'.$c['nome'].'</strong></a> |'.$c['accesso'];
                    # se riesce a procurarsi il link bene, altrimenti semplicemente non lo inserisco
                    $ateneo=$c['ateneo'];
                    $query_link_ateneo="SELECT link FROM Ateneo WHERE nome=\"$ateneo\";";
                    if($linkAteneo=$db->ExecQueryAssoc($query_link_ateneo)){
                        $contenuto.=' | <a href="'.$linkAteneo[0]['link'].'">'.$c['ateneo'].'</a>';
                    }else{
                        $contenuto.=' | '.$c['ateneo']; 
                    }
                    $contenuto.='</li>';
                }
            }else{
                $errori.="<p>Opss si è verficato un errore di conessione: impossibile caricare i corsi di laurea, riprova</p>";
            }
        }
        else{
            $errori.="<p>nessun risultato presente</p>";
        }
        $db->Disconnect();
    }else{
        $errori.="<p>Ci scusiamo, la connessione non e' riuscita, attendere e riprova</p>";
    } 
    
    $content=str_replace("<area/>",$area,$content); 
    $content=str_replace("<classe/>",$classe,$content); 
    $content=str_replace("<content/>",$contenuto,$content);
    $content=str_replace("<error/>",$errori,$content);
    echo $content;
 
?>

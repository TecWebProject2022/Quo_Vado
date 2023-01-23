<?php 
require_once 'utilita.php';
require_once 'database.php';
if(!isset($_GET['nclasse'])){
    $target='';
}
else{
$target=PulisciInput($_GET['nclasse']);
}
$content=file_get_contents('classe.html');
$errori='';
$contenuto='';
$area='';
$classe='';
$targetTag='';
$tags=array(
    1=>"commento generale",
    2=>"commento riguardante l'inclusivita"
);

$db=new Connection();
    $dbOK=$db->Connect();
    if($dbOK){
        $query_classe="SELECT denominazione,illustrazione,area_disciplinare,gruppo_disciplinare,durata FROM ClassediLaurea WHERE num_classe=\"$target\";";
        if($classi=$db->ExecQueryAssoc($query_classe)){
            $area=$classi[0]['area_disciplinare'];
            $classe=$target.'-'.$classi[0]['denominazione'];
            $contenuto.='<h1 id="title">'.$target.' - '.$classi[0]['denominazione'].'</h1>';
            $contenuto.='<h2 class="titles_area_classi">Descrizione</h2>';
            $contenuto.='<ul id="identikit_corso"><li id="descrizione"><p id="dettagliClasse">Area disciplinare: '.$classi[0]['area_disciplinare'].' | Gruppo disciplinare: '.$classi[0]['gruppo_disciplinare'].' 
            | Tipologia: '.$classi[0]['durata'].'</p>';
            $contenuto.='<p id="illustrazioneClasse">'.$classi[0]['illustrazione'].'</p></li>'; #temporaneo, necessario inserire descrizioni nel db

            # stampa punteggio complessivo
            $query_valComplessiva="SELECT CAST(AVG(p_complessivo) AS DECIMAL(3,2)) as \"pc\" ,
            CAST(AVG(p_acc_fisica) AS DECIMAL(3,2)) as \"pf\" ,CAST(AVG(p_servizio_inclusione) AS DECIMAL(3,2)) as \"ps\" ,
            CAST(AVG(tempestivita_burocratica) AS DECIMAL(3,2)) as \"tb\",CAST(AVG(p_insegnamento) AS DECIMAL(3,2)) as \"pi\" 
            FROM `Valutazione` WHERE classe_laurea=\"$target\";";
            if($valComplessiva=$db->ExecQueryAssoc($query_valComplessiva)){
        
                $contenuto.="<li id='valutazione'><p>Valutazione degli utenti</p><ul>
                            <li class='highlight'>Complessivo: ".$valComplessiva[0]['pc']."</li>
                            <li>Accessibilità fisica: ".$valComplessiva[0]['pf']."</li>
                            <li class='highlight'>Servizio inclusione: ".$valComplessiva[0]['ps']."</li>
                            <li>Tempestività burocratica: ".$valComplessiva[0]['tb']."</li>
                            <li class='highlight'>Insegnamento: ".$valComplessiva[0]['pi']."</li></ul></li></ul>";
            }
            else{
                $errori.='<p>Non è stata trovata alcuna valutazione</p>';
            }

            # corsi di studio associati
            $query_corso_di_studio="SELECT ateneo,nome,accesso,link FROM CorsodiStudio WHERE classe_laurea=\"$target\";";
            if($corsi=$db->ExecQueryAssoc($query_corso_di_studio)){
                #display corsi
                $contenuto.='<h2 class="titles_area_classi">I corsi di studio di questa classe di laurea</h2>';
                $contenuto.='<ul id="corsi">';
                foreach($corsi as $c){
                    $contenuto.='<li><a href="'.$c['link'].'" target="_blank"><strong>'.$c['nome'].'</strong></a> | '.$c['accesso'];
                    # se riesce a procurarsi il link bene, altrimenti semplicemente non lo inserisco
                    $ateneo=$c['ateneo'];
                    $query_link_ateneo="SELECT link FROM Ateneo WHERE nome=\"$ateneo\";";
                    if($linkAteneo=$db->ExecQueryAssoc($query_link_ateneo)){
                        $contenuto.=' | <a href="'.$linkAteneo[0]['link'].'" target="_blank">'.$c['ateneo'].'</a>';
                    }else{
                        $contenuto.=' | '.$c['ateneo']; 
                    }
                    $contenuto.='</li>';
                }
                $contenuto.='</ul>';
            }else{
                $errori.="<p class='error'>Opss, si è verficato un errore di connessione: impossibile caricare i corsi di laurea. Per favore, riprova più tardi.</p>";
            }    
            #sezione commenti
            #filtro, con solo due elementi ma potenzialmente potrei averne n
            $contenuto.='<form id="filtro" class="filter" action="classe.php?nclasse='.$target.'" method="get" >
            <fieldset>
            <legend>Seleziona i commenti che vuoi visualizzare</legend>
                <label for="commento_generale">
                    <input type="checkbox" name="filtri[]" id="commento_generale"  value="1"/>
                    commento generale
                </label>

                <label for="inclusivita">
                    <input type="checkbox" name="filtri[]" id="inclusivita"  value="2" />
                    inclusività
                </label>
                
                    <input type="submit" class="submit" name="filterTags" id="filter_button" value="filtra commenti"/>

                    <input type="hidden" name="nclasse" value="'.$target.'"/>
                    <input type="hidden" name="area" value="'.$area.'"/>
                </fieldset>
            </form>';
            # se ottengo tag (da filtro, al primo caricamento della pagina sara sempre false) allora la query chiedera solo le valutazioni corrispondenti
            
            if(isset($_GET['filterTags'])){
                $filtri=isset($_GET['filtri']) ? $_GET['filtri'] :'';
                if($filtri!=''){
                    if(count($filtri)>0){
                        $targetTag=implode(',',$filtri);
                    }
                }
                else{
                   "<p class=\"error\">Attenzione non hai selezionato alcun filtro</p>";
                   header('classe.php#filtro');
                }
                
               
            }
            if($targetTag && preg_match('/^\d+(,\d+)*$/',$targetTag)){
                $query_valutazione='SELECT Valutazione.nome_utente as n ,datav, commento, tag, p_complessivo, p_acc_fisica, p_servizio_inclusione, tempestivita_burocratica, p_insegnamento, Iscrizione.corso AS corso, Iscrizione.ateneo AS ateneo
                FROM Valutazione
                INNER JOIN Iscrizione ON Valutazione.nome_utente = Iscrizione.nome_utente and Valutazione.classe_laurea=Iscrizione.classe
                WHERE Valutazione.classe_laurea = "'.$target.'" AND tag IN ('.$targetTag.');';
            }else{
                $query_valutazione='SELECT Valutazione.nome_utente as n ,datav, commento, tag, p_complessivo, p_acc_fisica, p_servizio_inclusione, tempestivita_burocratica, p_insegnamento, Iscrizione.corso AS corso, Iscrizione.ateneo AS ateneo
                FROM Valutazione
                INNER JOIN Iscrizione ON Valutazione.nome_utente = Iscrizione.nome_utente and Valutazione.classe_laurea=Iscrizione.classe
                WHERE Valutazione.classe_laurea = "'.$target.'";';
            }
            #stampa commenti
            if($valutazioni=$db->ExecQueryAssoc($query_valutazione)){
                $contenuto.='<h2 class="titles_area_classi">I commenti degli studenti di questa classe di laurea</h2>';
                $contenuto.='<ul id="listaCommenti">';
                foreach($valutazioni as $v){
                    $contenuto.='<li class="commento"><strong>'.$v['n'].' | '.date("d-m-Y",strtotime($v['datav']))." | ".$v['corso']."-".$v['ateneo']."</strong><p class=\"testoCommento\">".$v['commento']."</p>";
                    $contenuto.='<ul class="valutazioneCommento">
                            <li>Complessivo: '.$v['p_complessivo']." | </li>
                            <li>Accessibilità fisica: ".$v['p_acc_fisica']." | </li>
                            <li>Servizio inclusione: ".$v['p_servizio_inclusione']." | </li>
                            <li>Tempestività burocratica: ".$v['tempestivita_burocratica']." | </li>
                            <li>Insegnamento: ".$v['p_insegnamento']." | </li>
                            <li id='tag_commento'>Tag: ".$tags[$v['tag']]."</li></ul></li>";
                }
                $contenuto.="</ul>";
            }
            else{
                $errori.="<p class='error'>Nessun commento presente per il filtro selezionato</p>";
            }
            #aggiunta commento
            #controllo se sono in presenza di un utente loggato
            session_start();
            $contenuto.='<error/>';
            if(!isset($_SESSION['user'])){
                $contenuto.='<p class="invito"><a href="registrazione_utente.php">Iscriviti</a> o <a href="login.php">Accedi</a> per lasciare un commento!</p>';
            }
            else{
                $query_iscrizione='SELECT nome_utente,corso FROM Iscrizione WHERE classe = "'.$target.'" AND nome_utente="'.pulisciInput($_SESSION['user']).'";';
                if($iscritto=$db->ExecQueryAssoc($query_iscrizione)){
                    $erroriNuovoCommento=isset($_GET['erroriCommenti'])?$_GET['erroriCommenti']:'';
                    $contenuto.='<form id="formCommento" action="addComment.php" method="post" onsubmit=" return Validate(event)">
                    <fieldset>
                        <legend>Agguingi un commento!</legend>
                        <label for="commento" >commento:</label>
                        <span><textarea id="commento" name="commento" rows="4" cols="40"
                        msg-data-empty="inserisci il commento" msg-data-invalid="il commento non puo contenere caratteri speciali e deve essere compreso tra 1 e 200 caratteri"></textarea></span>

                        <label for="p_complessivo">punteggio complessivo:</label>
                        <span><input type="number" id="p_complessivo" name="p_complessivo" placeholder="1" value="1" min="1" max="5" required
                            msg-data-empty="inserisci il punteggio complessivo del corso" msg-data-invalid="il punteggio deve essere compreso tra 1 e 5"/></span>
                        <label for="p_acc_fisica">punteggio accessibilità fisica:</label>
                        <span><input type="number" id="p_acc_fisica" name="p_acc_fisica" placeholder="1" value="1" min="1" max="5" required
                            msg-data-empty="inserisci il punteggio accessibilità fisica del corso" msg-data-invalid="il punteggio deve essere compreso tra 1 e 5"/></span>
                        <label for="p_inclusione">punteggio servizio inclusione:</label>
                        <span><input type="number" id="p_inclusione" name="p_inclusione" placeholder="1" value="1" min="1" max="5" required
                            msg-data-empty="inserisci il punteggio servizio inclusione del corso" msg-data-invalid="il punteggio deve essere compreso tra 1 e 5"/></span>
                        <label for="p_tempestivita">punteggio tempestivita burocratica: </label>
                        <span><input type="number" id="p_tempestivita" name="p_tempestivita" placeholder="1" value="1" min="1" max="5" required
                            msg-data-empty="inserisci il punteggio tempestivita burocratica del corso" msg-data-invalid="il punteggio deve essere compreso tra 1 e 5"/></span>
                        <label for="p_insegnamento">punteggio insegnamento:</label>
                        <span><input type="number" id="p_insegnamento" name="p_insegnamento"placeholder="1" value="1" min="1" max="5" required
                            msg-data-empty="inserisci il punteggio insegnamento del corso" msg-data-invalid="il punteggio deve essere compreso tra 1 e 5"/></span>   
                        <label for="tag">Il tuo commento riguarda:</label>
                        <span><select name="tag" id="tag" data-msg-empty="Per favore, aiutaci a capire di cosa parla il tuo commento">
                            <option value="1">Inclusivita\'</option>
                            <option value="2">commento generale</option></select></span>
                        
                        <input type="hidden" name="classe" value="'.$target.'"/>
                        <input type="hidden" name="area" value="'.$area.'"/>

                        <input type="submit" class="submit"  name="submit" value="pubblica"/>
                        <input type="reset"  name="cancella" value="cancella"/>
                    </fieldset>
                    </form><span><strong>'.$erroriNuovoCommento.'</strong></span>';
                }else{
                    if($_SESSION['user']!='admin'){
                        $errori='<p class="invito">Ciao '.$_SESSION['user'].', per lasciare un commento aggiungi il corso di laurea appartenente alla classe '.$classe.' che hai frequentato nella tua <a href="area_utente.php">area personale</a>!</p>';
                    }else{
                        $errori='<p class="invito">Gestisci i corsi e i commenti della classe di laurea '.$classe.' dal <a href="area_admin.php">pannello di controllo</a>!</p>';
                    }
                }
            }
        }
        else{
            $errori.="<p class='error'>Nessun risultato presente</p>";
        }
        $db->Disconnect();
    }else{
        $errori.="<p class='error'>Ci scusiamo, la connessione non &egrave; riuscita. Per favore, attendere e riprovare</p>";
    }
 
    $content=str_replace("<area/>",$area,$content); 
    $content=str_replace("<classe/>",$classe,$content); 
    $content=str_replace("<content/>",$contenuto,$content);
    $content=str_replace("<error/>",$errori,$content);
    echo $content;
 
?>
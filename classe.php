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
$Com='';
$db=new Connection();
    $dbOK=$db->Connect();
    if($dbOK){
        $contenuto.='<error/>';
        $query_classe="SELECT denominazione,illustrazione,area_disciplinare,gruppo_disciplinare,durata FROM ClassediLaurea WHERE num_classe=\"$target\";";
        if($classi=$db->ExecQueryAssoc($query_classe)){
            $area=$classi[0]['area_disciplinare'];
            $classe=$target.'-'.$classi[0]['denominazione'];
            $contenuto.='<h1 id="title">'.str_replace("_"," ",$target).' - '.$classi[0]['denominazione'].'</h1>';
            $contenuto.='<h2 class="titles_area_classi">Descrizione</h2>';
            $contenuto.='<ul id="identikit_corso"><li id="descrizione"><p id="dettagliClasse">Area disciplinare: '.str_replace("_"," ",$classi[0]['area_disciplinare']).' | Gruppo disciplinare: '.$classi[0]['gruppo_disciplinare'].' 
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
            
            # se ottengo tag (da filtro, al primo caricamento della pagina sara sempre false) allora la query chiedera solo le valutazioni corrispondenti
            
            if(isset($_GET['filterTags'])){
                $filtri=isset($_GET['filtri']) ? $_GET['filtri'] :'';
                if($filtri!=''){
                    if(count($filtri)>0){
                        $targetTag=implode(',',$filtri);
                    }
                }
                else{
                   $errori.="<p class=\"error\">Attenzione non hai selezionato alcun filtro</p>";
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
                            <li class='tag_commento'>Tag: ".$tags[$v['tag']]."</li></ul></li>";
                }
                $contenuto.="</ul>";
            }
            else{
                $errori.="<p class='error'>Nessun commento presente per il filtro selezionato</p>";
            }
            #filtro, con solo due elementi ma potenzialmente potrei averne n
           
            $contenuto.='<p id="filtro" class="invito">Seleziona un filtro per filtrare  i commenti che vuoi visualizzare</p><form aria-describedBy="filtro" id="filtroform" class="filter" action="classe.php?nclasse='.$target.'" method="get" onsubmit="return Validate()" >
            <fieldset>
            <legend>Seleziona un filtro</legend>
                <label for="commento_generale">
                    <input type="checkbox" name="filtri[]" id="commento_generale"  value="1"/>
                    commento generale
                </label><br/>

                <label for="inclusivita">
                    <input type="checkbox" name="filtri[]" id="inclusivita"  value="2" />
                    inclusività
                </label><br/>
                
                    <input type="submit" class="submit" name="filterTags" id="filter_button" value="filtra commenti"/>

                    <input type="hidden" name="nclasse" value="'.$target.'"/>
                    <input type="hidden" name="area" value="'.$area.'"/>
                </fieldset>
            </form>';
            $contenuto.="<p id='currentFilter'>Filtri applicati:";
            if(isset($_GET['filtri'])){
                foreach( $_GET['filtri'] as $f ){
                   switch($f){
                    case 1: $contenuto.=" commento generale";
                            break;
                    case 2:  $contenuto.=" inclusività";
                            break;
                   }
                }
            }
            $contenuto.="<p>";
            #aggiunta commento
            #controllo se sono in presenza di un utente loggato
            session_start();
           
            if(!isset($_SESSION['user'])){
                $contenuto.='<p class="invito"><a href="registrazione_utente.php">Iscriviti</a> o <a href="login.php">Accedi</a> per lasciare un commento!</p>';
            }
            else{
                $query_iscrizione='SELECT nome_utente,corso FROM Iscrizione WHERE classe = "'.$target.'" AND nome_utente="'.$_SESSION['user'].'";';
                if(!$iscritto=$db->ExecQueryAssoc($query_iscrizione)){
                    if($_SESSION['user']!='admin'){
                        
                        $Com='<p class="invito">Ciao '.$_SESSION['user'].', per lasciare un commento aggiungi il corso di laurea appartenente alla classe '.$classe.' che hai frequentato nella tua <a href="area_utente.php">area personale</a>!</p>';
                    }
                }
                else{
                    $query="SELECT * FROM Valutazione where nome_utente=\"".$_SESSION['user']."\" and classe_laurea=\"".$target."\"";
                        if(!$d=$db->ExecQueryAssoc($query)){
                            $Com='<p class="invito">Ciao '.$_SESSION['user'].', per lasciare un commento accedi alla  tua <a href="area_utente.php">area personale</a>!</p>';
                        }
                }
            }
        }
        else{
            $errori.="<p class='error'>Nessun risultato presente</p>";
        }
        $db->Disconnect();
    }else{
        $errori.="<p class='error'>Ci scusiamo, la connessione non &egrave; riuscita. Per favore, attendere e riprovare. </p>";
    }
 
    $content=str_replace("<area/>",$area,$content); 
    $content=str_replace("<classe/>",$classe,$content); 
    $content=str_replace("<content/>",$contenuto,$content);
    $content=str_replace("<error/>",$errori,$content);
    $content=str_replace("<com/>",$Com,$content);
    echo $content;
 
?>
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
                
                $valutazione="<ul>
                            <li>Complessivo: ".$res2[0]['pc']."</li>
                            <li>Accessibilità fisica: ".$res2[0]['pf']."</li>
                            <li>Servizio inclusione: ".$res2[0]['ps']."</li>
                            <li>Tempestività burocratica: ".$res2[0]['tb']."</li>
                            <li>Insegnamento: ".$res2[0]['pi']."</li></ul>";
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
        $errori.='<p>Ci scusiamo la connessione non riuscita, attendere e riprova</p>';
    } 
$content=str_replace('<valutazione/>',$valutazione,$content); 
$content=str_replace('<descrizione/>',$illustrazione,$content);
$content=str_replace('<titolo/>',$den,$content);
$content=str_replace('<classe/>', $classe,$content);
$content=str_replace('<name/>',$_GET['area'],$content);
echo $content;
?>
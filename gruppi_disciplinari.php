<?php
require_once 'utilita.php';
require_once 'database.php';
$target=PulisciInput($_GET['area']);
$content=file_get_contents('gruppi_disciplinari.html');
$errori='';
$contenuto='';
$query_gruppi="Select DISTINCT(gruppo_disciplinare) from ClassediLaurea where area_disciplinare=\"$target\";";
$db=new Connection();
    $dbOK=$db->Connect();
    if($dbOK){
        if($gruppi=$db->ExecQuery($query_gruppi)){
           $contenuto.='<h2>'.$target.'</h2>';
           $contenuto.='<ul>';
           foreach($gruppi as $r){
            $contenuto.="<li>".$r['gruppo_disciplinare']."</li>";
            $query_classi="Select num_classe, denominazione from ClassediLaurea where gruppo_disciplinare=\"".$r['gruppo_disciplinare']."\";";
            if($classi=$db->ExecQuery($query_classi)){
                $contenuto.="<li><ul>";
                foreach($classi as $c){
                    $contenuto.="<li>".$c['num_classe']." - <a href='classe.php?nclasse=".$c['num_classe']."'>".$c['denominazione']."</a></li>";
                }
                $contenuto.="</li></ul>";
                }
            else{
                $errori.="<p>Opss si è verficato un errore di conessione, riprova</p>";
            }
            
           }
        $contenuto.="</ul>";
        }
        else{
            $errori.="<p>nessun risultato presente</p>";
        }
        $db->Disconnect();
    }
    
    else{
        $errori.='<p>Ci scusiamo la connessione non riuscita, attendere e riprova</p>';
    }  
    $content=str_replace("<name/>",$target,$content); 
    $content=str_replace("<content/>",$contenuto,$content);
    $content=str_replace("<error/>",$errori,$content);
    echo $content;

    
?>
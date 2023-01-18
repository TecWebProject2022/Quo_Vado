<?php
require_once 'utilita.php';
require_once 'database.php';
if(!isset($_GET['area'])){
    $_GET['area']='';
}
$target=PulisciInput($_GET['area']);
$content=file_get_contents('gruppi_disciplinari.html');
$errori='';
$contenuto='';
$query_gruppi="Select DISTINCT(gruppo_disciplinare) from ClassediLaurea where area_disciplinare=\"$target\";";
$db=new Connection();
    $dbOK=$db->Connect();
    if($dbOK){
        if($gruppi=$db->ExecQueryAssoc($query_gruppi)){
            $content=str_replace("<titolo/>","<h1 id='title'>".str_replace("_"," ",$target)."</h1>",$content);
           $contenuto.='<ul class="group_container">';
           foreach($gruppi as $r){
            $contenuto.="<li class='gruppo'>".$r['gruppo_disciplinare']."</li>";
            $query_classi="Select num_classe, denominazione from ClassediLaurea where gruppo_disciplinare=\"".$r['gruppo_disciplinare']."\";";
            if($classi=$db->ExecQueryAssoc($query_classi)){
                $contenuto.="<li class='elenco_classi'><ul class='decoration'>";
                foreach($classi as $c){
                    $contenuto.="<li class='noDecoration'>".$c['num_classe']." - <a href='classe.php?nclasse=".$c['num_classe']."&area=".$target."'>".$c['denominazione']."</a></li>";
                }
                $contenuto.="</ul>";
                }
            else{
                $errori.="<p>Opss si è verficato un errore di conessione, riprova</p>";
            }
            
           }
        $contenuto.="</ul>";
        }
        else{
            $errori.="<p class='invito'>Nessun risultato presente</p>";
        }
        $db->Disconnect();
    }
    
    else{
        $errori.='<p>Ci scusiamo la connessione non riuscita, attendere e riprova</p>';
    }  
    $content=str_replace("<name/>","<span>".str_replace("_"," ",$target)."</span>",$content); 
    $content=str_replace("<content/>",$contenuto,$content);
    $content=str_replace("<error/>",$errori,$content);
    echo $content;

    
?>
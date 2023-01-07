<?php
require_once 'utilita.php';
require_once 'database.php';
$target=PulisciInput($_GET['area']);
$query_gruppi="Select DISTINCT(gruppo_disciplinare) from ClassediLaurea where area_disciplinare=\"$target\";";
$db=new Connection();
    $dbOK=$db->Connect();
    if($dbOK){
        if($gruppi=$db->ExecQuery($query_gruppi)){
           print_r ($gruppi);
           foreach($gruppi as $r){
            $query_classi="Select num_classe, denominazione from ClassediLaurea where gruppo_disciplinare=\"".$r['gruppo_disciplinare']."\";";
            $classi=$db->ExecQuery($query_classi);
            if($classi){
                print_r($classi);
            }
            else{
                echo'<p>Opss Errore di conessione</p>';
            }
            
           }
        }
        else{
            echo'<p>nessun risultato presente</p>';
        }
        $db->Disconnect();
    }
    
    else{
            echo '<p>Ci scusiamo la connessione non riuscita, attendere e riprova</p>';
    }   
    
?>
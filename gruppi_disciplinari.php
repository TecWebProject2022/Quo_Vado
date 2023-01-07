<?php
require_once 'utilita.php';
require_once 'database.php';
$prova=PulisciInput($_GET['area']);
echo $prova;
$query_gruppi="Select DISTINCT(gruppo_disciplinare) from ClassediLaurea where area_disciplinare=\"$prova\";";
$db=new Connection();
    $dbOK=$db->Connect();
    if($dbOK){
        if($gruppi=$db->ExecQuery($query_gruppi)){
            echo'cd';
           print_r ($gruppi);
           foreach($gruppi as $r){
           echo $r['gruppo_disciplinare'];
           $p=$r['gruppo_disciplinare'];
           $query_classi="Select num_classe, denominazione from ClassediLaurea where gruppo_disciplinare=\"$p\";";
           $classi=$db->ExecQuery($query_classi);
           print_r($classi);
        }
        }
        $db->Disconnect();
    }
    
    else{
            echo 'Connessione non riuscita, attendere e riprova';
    }   
    
?>
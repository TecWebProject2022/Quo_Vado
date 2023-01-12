<?php
require_once 'utilita.php';
require_once 'database.php';

session_start();
$errori='';
#preparazione e controllo variabili
$commento=trim($_POST['commento']);
$complessivo=PulisciInput($_POST['p_complessivo']);
$accessibilita=PulisciInput($_POST['p_acc_fisica']);
$tempestivita=PulisciInput($_POST['p_tempestivita']);
$inclusione=pulisciInput($_POST['p_inclusione']);
$insegnamento=PulisciInput($_POST['p_insegnamento']);
$classe=PulisciInput($_POST['classe']);
$area=PulisciInput($_POST['area']);
$username=PulisciInput($_SESSION['user']);
$tag=PulisciInput($_POST['tag']);

#controlli:
$max_l = 200;
if(strlen($commento) > $max_l) {
    $errori .= "<li>Il commento è troppo lungo, la lunghezza massima consentita è $max_l caratteri</li>";
}
/*
$min_valore = 1;
$max_valore = 5;

if(!is_numeric($complessivo) || $complessivo < $min_valore || $complessivo > $max_valore) {
    $errori .= "<li>Il valore del parametro complessivo deve essere un numero compreso tra $min_valore e $max_valore</li>";
}

if(!is_numeric($accessibilita) || $accessibilita < $min_valore || $accessibilita > $max_valore) {
    $errori .= "<li>Il valore del parametro accessibilità deve essere un numero compreso tra $min_valore e $max_valore</li>";
}

if(!is_numeric($inclusione) || $inclusione < $min_valore || $inclusione > $max_valore) {
    $errori .= "<li>Il valore del parametro inclusione deve essere un numero compreso tra $min_valore e $max_valore</li>";
}

if(!is_numeric($tempestivita) || $tempestivita < $min_valore || $tempestivita > $max_valore) {
    $errori .= "<li>Il valore del parametro tempestività deve essere un numero compreso tra $min_valore e $max_valore</li>";
}

if(!is_numeric($insegnamento) || $insegnamento < $min_valore || $insegnamento > $max_valore) {
    $errori .= "<li>Il valore del parametro insegnamento deve essere un numero compreso tra $min_valore e $max_valore</li>";
}

if($tag != 1 && $tag != 2) {
    $errori .= "<li>Il tag deve essere 1 o 2</li>";
}

if(empty($classe)) {
    $errori .= "<li>La classe non può essere vuota</li>";
}*/

if( isset($_POST['submit'])){
    
    if(!$errori){
        #connessione al db 
        $db=new Connection();
        $dbOK=$db->Connect();
        if($dbOK){
            $data = Date('y-m-d');
            $query="SELECT nome_utente from Valutazione where nome_utente=\"".$username."\" AND classe_laurea=\"".$classe."\"";
            if($r=$db->ExecQueryAssoc($query)){
                # aggiornamento commento
                $insert = "INSERT INTO Valutazione(nome_utente,classe_laurea,datav,commento,tag,p_complessivo,p_acc_fisica,p_servizio_inclusione,tempestivita_burocratica,p_insegnamento) VALUES('".$username."','".$classe."','".$data."','".$commento."','".$tag."','".$complessivo."','".$accessibilita."','".$inclusione."','".$tempestivita."','".$insegnamento."');";

                if(!$db->Update($update)){
                    $errori.="Impossibile aggiornare il commento";
                }
            }else{
                #inserimento commento
                $insert = "INSERT INTO Valutazione(nome_utente,classe_laurea,datav,commento,tag,p_complessivo,p_acc_fisica,p_servizio_inclusione,tempestivita_burocratica,p_insegnamento) VALUES('".$username."','".$classe."', '".$data."','".$commento."','".$tag."','".$complessivo."','".$accessibilita."','".$inclusione."','".$tempestivita."','".$insegnamento."');";

                if(!$q=$db->Insert($insert)){
                        $errori.="Inserimento non riuscito";
                }
            }
            $db->Disconnect();
        }else{
            $errori.="Connessione non riuscita";
        }
    }else{
        $errori='<ul>'.$errori.'</ul>';
    }
}else{}

header('Location: classe.php?nclasse='.$classe.'&area='.$area.'$&erroriCommenti='.$errori);
?>
<?php
session_start();
echo time()-$_SESSION['time'];
require_once 'utilita.php';
require_once 'database.php';
// Se non hai fatto il login o la tua sessione (durata max 1 h di inattività) è scaduta
if(!isset($_SESSION['user']) || !isset($_SESSION['time']) || time()-$_SESSION['time']>3600){
    unset($_SESSION['user']); 
    unset($_SESSION['time']);
    $_SESSION['sessione']='<p>Sessione Scaduta</p>';
    header('Location:login.php');
}
$errori='';
$contenuto='';
$user=$_SESSION['user'];
$query1="Select  * from Utente where nome_utente=\"$user\";";
$db=new Connection();
$dbOK=$db->Connect();
if($dbOK){
    if($res1=$db->ExecQueryAssoc($query1)){
        $contenuto.="<h2>Dati Personali";
        $contenuto.="<dl>";
        $contenuto.="<dt>Nome Utente: </dt><dd>".$res1[0]['nome_utente']."</dd>";
        $contenuto.="<dt>Nome: </dt><dd>".$res1[0]['nome']."</dd>";
        $contenuto.="<dt>:Cognome: </dt><dd>".$res1[0]['cognome']."</dd>";
        $contenuto.="<dt>Data di nascita: </dt><dd>".$res1[0]['data_nascita']."</dd>";
        $contenuto.="<dt>Genere: </dt><dd>".$res1[0]['genere']."</dd>";
        $contenuto.="<dt>Scuola superiore frequentata: </dt><dd>".$res1[0]['scuola_sup']."</dd>";
        $contenuto.="</dl>";
        $query2="Select ateneo, classe,corso, datai, dataf,punteggio_scuola_provenienza  from Iscrizione where nome_utente=\"$user\"";
        if($res2=$db->ExecQueryAssoc($query2)){
            $contenuto.="<ol>";
            foreach($res2 as $i){
                $contenuto.="<li>";
                $contenuto.="<ul><li>Ateneo: ".$i['ateneo']."</li>";
                $contenuto.="<ul><li>Classe di Laurea: ".$i['classe']."</li>";
                $contenuto.="<ul><li>Corso di Studi: ".$i['corso']."</li>";
                $contenuto.="<ul><li>Data inizio studi: ".date("d/m/Y",strtotime($i['datai']))."</li>";
                $contenuto.="<ul><li>Data fine studi: ".date("d/m/Y",strtotime($i['dataf']))."</li>";
                $contenuto.="<ul><li>Punteggio di affinità con la scuoa superiore frequentata: ".$i['punteggio_scuola_provenienza']."</li></ul>";
                $contenuto.="</li>"; 
            }
            $contenuto.="</ol>";
        }
        else{
            $errori.="<p>Siamo spiacenti ma i dati non sono al momento dipsonibili</p>";
        }
    }
    else{
        $errori.="<p>Siamo spiacenti ma i dati non sono al momento dipsonibili</p>";
    }
}
else{
    $errori.="<p>Siamo spiacenti ma i dati non sono al momento dipsonibili</p>";
}
$content=file_get_contents("area_riservata.html");
$content=str_replace("<content/>",$contenuto,$content);
$content=str_replace("<errori/>",$errori,$content);
echo $content;
echo"<a href='logout.php'>LOGOUT</a>";
?>
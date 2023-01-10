<?php
require_once 'utilita.php';
require_once 'database.php';
$nome='';
$cognome='';
$data='';
$genere='';
$scuola='';
$username='';
$password='';
$content=file_get_contents('registrazione_utente.html');
$content=str_replace('<valoreNome/>',$nome,$content);
$content=str_replace('<valoreCognome/>',$cognome,$content);
$content=str_replace('<valoreDataNascita/>',$data,$content);
$content=str_replace('<valoreGenere/>',$genere,$content);
$content=str_replace('<valoreScuola/>',$scuola,$content);
$content=str_replace('<Username/>',$username,$content);
$content=str_replace('<valorePassword/>',$password,$content);

echo $content;

?>
<?php 
function PulisciInput($value){
    $value=trim($value);
    $value=strip_tags($value); #toglie i tag se lo uso su qualsiasi cosa lui rimuove dal tag di apertura a quello di chiusura 
    #$value=htmlentities($value); #traduce tag html con i codice relativi-> prende tutto come carattri se inverto non trovo nulla
    return $value;
}
function check(){
    return isset($_SESSION['user'])  &&  isset($_SESSION['time'])  && time()-$_SESSION['time']<3600;
}
?>
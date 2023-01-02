<?php 
function PulisciInput($value){
    $value=trim($value);
    $value=strip_tags($value); #toglie i tag se lo uso su qualsiasi cosa lui rimuove dal tag di apertura a quello di chiusura 
    #$value=htmlentities($value); #traduce tag html con i codice relativi-> prende tutto come carattri se inverto non trovo nulla
    return $value;
}
?>
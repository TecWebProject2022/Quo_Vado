<?php 
function PulisciInput($value){
    $value=trim($value);
    $value=strip_tags($value); #toglie i tag se lo uso su qualsiasi cosa lui rimuove dal tag di apertura a quello di chiusura 
    #$value=htmlentities($value); #traduce tag html con i codice relativi-> prende tutto come carattri se inverto non trovo nulla
    return $value;
}
<<<<<<< HEAD

function filterSql($string) {
    $string = preg_replace('/(SELECT|UPDATE|DELETE|INSERT|WHERE|FROM|TRUNCATE|DROP|SHOW|TABLE|USE|DATABASE|LOAD DATA|INFILE|INTO TABLE|DESCRIBE|EXPLAIN|AND|OR|UNION|CREATE|ALTER|DROP|RENAME|BACKUP|RESTORE|GRANT|REVOKE|LOCK|UNLOCK|SET)/i', '', $string);
    return $string;
=======
function check(){
    return isset($_SESSION['user'])  &&  isset($_SESSION['time'])  && time()-$_SESSION['time']<3600;
>>>>>>> 2ef54460b131825945f9d18467449d6d83c2bba6
}
?>
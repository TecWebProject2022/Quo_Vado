<?php 
function PulisciInput($value){
    $value=trim($value);
    $value=strip_tags($value); #toglie i tag se lo uso su qualsiasi cosa lui rimuove dal tag di apertura a quello di chiusura 
    #$value=htmlentities($value); #traduce tag html con i codice relativi-> prende tutto come carattri se inverto non trovo nulla
    return $value;
}

function filterSql($string) {
    $string = preg_replace('/(SELECT|UPDATE|DELETE|INSERT|WHERE|FROM|TRUNCATE|DROP|SHOW|TABLE|USE|DATABASE|LOAD DATA|INFILE|INTO TABLE|DESCRIBE|EXPLAIN|AND|OR|UNION|CREATE|ALTER|DROP|RENAME|BACKUP|RESTORE|GRANT|REVOKE|LOCK|UNLOCK|SET)/i', '', $string);
    return $string;
}
?>
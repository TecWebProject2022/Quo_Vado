<?php

require_once 'database.php';
require_once 'utilita.php';

class userData {
    private $name="";
    private $lastname="";
    private $birthday="";
    private $gender="";
    private $username="";
    private $password="";
    private $school="";
    private $errors="";
# setter variabili, ognuno implementa gli opportuni controlli sulla variabile in ingresso e in caso di errori ritorna un 
# messaggio di errore adatto nella forma <li>messaggio errore</li>
    private function setName($value){
        $value = PulisciInput($value);
        $error = '';
        if(empty($value)){
            $error = "<li>Il nome non può essere vuoto.</li>";
        }
        elseif(strlen($value) > 20){
            $error = "<li>Il nome non può avere più di 20 caratteri.</li>";
        }
        elseif(!preg_match('/^[a-zA-Z\']+$/', $value)){
            $error = "<li>Il nome può contenere solo lettere e l'apostrofo.</li>";
        }
        else{
            $this->name = $value;
        }
        return $error;
    }
   
    private function setLastName($value){
        $value = PulisciInput($value);
        $error = '';
        if(empty($value)){
            $error = "<li>Il cognome non può essere vuoto.</li>";
        }
        elseif(strlen($value) > 30){
            $error = "<li>Il cognome non può avere più di 30 caratteri.</li>";
        }
        elseif(!preg_match('/^[a-zA-Z\']+$/', $value)){
            $error = "<li>Il cognome può contenere solo lettere e l'apostrofo (').</li>";
        }
        else{
            $this->lastname = $value;
        }
        return $error;
    }
    
    private function setBirthday($value) {
        $value = PulisciInput($value);
        
        if(strlen($value)==0){
                return '<li>Data non inserita</li>';
        }
        
        $error = "";
        #controllo il formato
        if (preg_match("/^[0-9]{2}-[0-9]{2}-[0-9]{4}$/", $value)) {
            #se la data e' nel formato mm-gg-yyyy estraggo mese giorno e anno
            list($month, $day, $year) = explode("-", $value);
        
            #controllo che il mese sia giusto
            if ($month >= 1 && $month <= 12) {
            # controllo che il giorno sia giusto
                if ($day >= 1 && $day <= 31) {
                    #controllo che l'anno sia tra il 1900 e quello corrente, sembra abbastanza
                    if ($year >= 1900 && $year <= date("Y")) {
                        #data valida
                        $this->birthday = $value;
                    } else {
                        #anno non valido
                        $error .= "<li>Il formato della data non e' corretto</li>";
                    }
                } else {
                    #giorno non valido
                    $error .= "<li>Il formato della data non e' corretto.</li>";
                }
            } else {
            #mese non valido
            $error .= "<li>Il formato della data non e' corretto.</li>";
            }
        } else {
            #formato della data non corretto
            $error .= "<li>Il formato della data non e' corretto.</li>";
        }
        
        #ritorno il messaggio d'errore
        return $error;
    }
    
    private function setGender($value){
        $value=PulisciInput($value);
        $error='';
        if(strlen($value)==0){
            $error.='<li>Genere non inserito</li>';
        }
        else{
            if (strlen($value)<=2 && ($value=="ND" or $value=="M" or $value=="F"))
                $error.='<li>Formato non corretto, i valori ammessi sono: ND(non definito), M (maschio) e F (femmina)</li>';
            else $this->gender = $value;
        }
        return $error;
    }

    private function setSchool($value){
        $value=PulisciInput($value);
        $error='';

        if(strlen($value)==0){
            $error.='<li>Scuola non inserita</li>';
        }
        else{
            if ($value!="industriale" || $value!="commerciale" || $value!="scientifico" || $value!="linguistico"|| $value!="classico")
                $error.='<li>Formato non corretto.</li>';
            else $this->school = $value;
        }
        return $error;
    }
    
    private function setUsername($value){
        $value = PulisciInput($value);
        $error = '';
        if(empty($value)){
            $error .= "<li>L'username non può essere vuoto.</li>";
        }
        elseif(strlen($value) > 40){
            $error .= "<li>L'username non può avere più di 20 caratteri.</li>";
        }
        elseif(!preg_match('/^[a-zA-Z\d]+$/', $value)){
            $error .= "<li>L'username può contenere solo lettere e numeri (').</li>";
        }
        else{
            $this->usernname = $value;
        }
        return $error;
    }
   
    private function setPassword($value){
        $error="";
        $value=PulisciInput($value);
        
        #controllo che la pw sia di 8 caratteri e che sia nel formato corretto
        if (strlen($string)<8 || strlen($string)>16 ) {
            $error.="<li>La password deve essere compresa tra gli otto e i sedici caratteri.</li>";
        }elseif (!preg_match("/[0-9]/", $string) || !preg_match("/[A-Z]/", $string) || !preg_match("/[a-z]/", $string) || !preg_match("/[!£$@]/", $string)) {
            $error.="<li>La password deve contenere almeno una lettera maiuscola, una minuscola, un numero e uno dei seguenti caratteri speciali: ! £ $ @</li>";
        }else {
            #la password va bene
            $this->password=$value;
        }

        return $error;
    }
 #costruttore, se ci sono errori errors contiene una lista ul contenente tutti i messaggi di errore   
    public function __construct($_name,$_lastname,$_birthday,$_gender,$_school,$_username,$_password){
        $this->errors= $this->setName($name) . $this->setLastName($_lastname).$this->setBirthday($_birthday) . $this->setGender($_gender) . $this->setSchool($_school) .$this->setUsername($_username ). $this->setPassword($_password);
        $this->errors= $this->errors?"<ul>".$this->errors."</ul>":"";
    }

#conversione in stringa, restituisce gli eventuali messaggi di errore, altrimenti la stringa vuota
    public function __toString(){
        return $this->errors;}



#getters
    public function getName(){
        return $this->name;}
    public function getLastName(){
        return $this->last_name;}
    public function getBirthday(){
        return $this->birthday;}
    
    public function getGender(){
        return $this->gender;}
    public function getSchool(){
        return $this->school;}
    public function getUserame(){
        return $this->username;}
    public function getPassword(){
        return $this->password;}

# funzione per il salvataggio dei dati sul db, errori...
    public function save(){
        if(!$errors){
            $db=new Connection();
            $dbOK=$db->Connect();
            if(!$dbOK){
                $errors.="<p>Errore di connessione, prova di nuovo</p>";
            }else{
                $insertion="INSERT INTO Utente(nome,cognome,data_nascita,genere,scuola_sup,nome_utente) VALUES (\'". $this->name ."\',\'". $this->lastname ."\',\'". $this->birthday ."\',\'". $this->gender ."\',\'". $this->school ."\',\'". $this->username ."\');"."INSERT INTO Credenziale(utente,pw,attuale,data_inserimento) VALUES (\'" . $this->username."\',\'". $this->password ."\'1\','".date("Y-m-d")."\')";
                $errors.=$db->Mquery($insertion);
            }
            $db->disconnect();
        }
        $errors.="<li>Impossibile effettuare la registrazione, dati non inseriti correttamente</li>";
    }
}



?>
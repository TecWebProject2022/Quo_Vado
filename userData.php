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
        $value=PulisciInput($value);
        $error='';
        if(strlen($value)==0){
            $error.='<li>Nome non inserito</li>';
        }
        else{
            if (preg_match("/\d/",$value))
                $error.='<li> Il nome non puo contenere nuemeri</li>';
            else $this->name = $value;
        }
        return $error;}
    
    private function setLastName($value){
        $value=PulisciInput($value);
        $error='';
        if(strlen($value)==0){
            $error.='<li>Cognome non inserito</li>';
        }
        else{
            if (preg_match("/\d/",$value))
                $error.='<li> Il cognome non puo contenere nuemeri</li>';
            else $this->surname = $value;
        }
        return $error;}
    
    private function setBirthday($value){
        $value=PulisciInput($value);
        $error='';
        if(strlen($value)==0){
            $error.='<li>Data di nascita non inserita</li>';
        }
        else{
            if (preg_match("/\d/",$value))
                $error.='<li>Formato della data non corretto</li>';
            else $this->nome = $value;
        }
        return $error;}
    
    private function setGender($value){
        $value=PulisciInput($value);
        $error='';
        if(strlen($value)==0){
            $error.='<li>Nome non inserito</li>';
        }
        else{
            if (preg_match("/\d/",$value))
                $error.='<li> Il nome non puo contenere nuemeri</li>';
            else $this->nome = $value;
        }
        return $error;}
    
    private function setUsername($value){
        $value=PulisciInput($value);
        $error='';
        if(strlen($value)==0){
            $error.='<li>Nome non inserito</li>';
        }
        else{
            if (preg_match("/\d/",$value))
                $error.='<li> Il nome non puo contenere nuemeri</li>';
            else $this->nome = $value;
        }
        return $error;}
    
    private function setPassword($value){
        $value=PulisciInput($value);
        $error='';
        if(strlen($value)==0){
            $error.='<li>Nome non inserito</li>';
        }
        else{
            if (preg_match("/\d/",$value))
                $error.='<li> Il nome non puo contenere nuemeri</li>';
            else $this->nome = $value;
        }
        return $error;}
 #costruttore, se ci sono errori errors contiene una lista ul contenente tutti i messaggi di errore   
    public function __construct($_nome,$_birthday,$_gender,$_school,$_username,$_password){
        $this->errors= $this->setName($_POST['name']) . $this->setLastName($_POST['surname']) . $this->setBirthday($_POST['birthday']) . $this->setGender($_POST['gender']) . $this->setSchool($_POST['school']) .$this->setUsername($_POST['username']) . $this->setPassword($_POST['password']); 
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
                $insertion="INSERT INTO Utente(nome,cognome,data_nascita,genere,scuola_sup,nome_utente) VALUES (". $this->name .",". $this->lastname .",". $this->birthday .",". $this->gender .",". $this->school .",". $this->username .");"."INSERT INTO Credenziale(utente,pw,attuale,data_inserimento) VALUES (" . $this->username.",". $this->password ."1)";
                $errors.=$db->Mquery($insertion);
            }
            $db->disconnect();
        }
        $errors.="<li>Impossibile effettuare la registrazione, dati non inseriti correttamente</li>";
    }
}



?>
<?php 
class Connection{
    private const HOST='127.0.0.1';
    private const DATABASE='mbrugin';
    private const USERNAME='mbrugin';
    private const PASSWORD='eingohha5Iemuaba';
    private $conn;
    public function Connect() {
        
        mysqli_report(MYSQLI_REPORT_ERROR);

        $this->conn = new mysqli(Connection::HOST, Connection::USERNAME, Connection::PASSWORD, Connection::DATABASE);

        if(!$this->conn->connect_errno){
            return true;
        } else {
            return false;
        }
    }
    public function Disconnect(){
        
       $this->conn->close();
    }
    public function Login($user,$pw){
        $query = "SELECT * FROM Utente inner join Credenziale on nome_utente=utente WHERE nome_utente=\"$user\" and pw=\"$pw\" and attuale=1 ";
        $query_result = $this->conn->query($query) or die("Errore in openDBConnection: " . $this->conn->error);

        if ($query_result->num_rows==1){
            return true;
        }
        else{
            return false;
        }
            $query_result->free();
            return $result;
    }

    public function Mquery($query){
        if($this->conn->multi_query($query)){
            return $error_message= "<p>Errore in openDBConnection: " . $this->conn->error."</p>";
        }        
    }
}
    

?>

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
    public function Login($query){
        $query_result = $this->conn->query($query);
        if($query_result){

            if ($query_result->num_rows==1){
                $query_result->free();
                return true;
            }
            else{
                $query_result->free();
                return false;
            }
        }
        return false;


           
    }

    public function multiInsert($query){
        if($this->conn->multi_query($query)){
            return $error_message= "<p>Errore in openDBConnection: " . $this->conn->error."</p>";
        }        
    }
    public function ExecQueryAssoc($query){
       
        $query_result = $this->conn->query($query);

        if (!$query_result->num_rows){
           
            return null;
        }
        else {
            $result = array();
            while ($row = $query_result->fetch_array(MYSQLI_ASSOC)) {
                array_push($result, $row);
            }
            $query_result->free();
            return $result;
        }
    }
    public function Insert($query){
    $query_result = $this->conn->query($query);
    
    if($this->conn->affected_rows==1){
        return true;
    }
    return false;
}
    public function ExecQueryNum($query){
       
        $query_result = $this->conn->query($query);

        if (!$query_result->num_rows){
            return null;
        }
        else {
            $result = array();
            while ($row = $query_result->fetch_array(MYSQLI_NUM)) {
                array_push($result, $row);
            }
            $query_result->free();
            return $result;
        }
    }
    
}
    

?>

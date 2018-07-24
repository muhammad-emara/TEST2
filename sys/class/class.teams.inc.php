<?php
if (!isset($_SESSION)) {
    session_start();
}
include_once 'DB_Connect.inc.php';

/**
 * Description of class
 *
 * @author muham_000
 */
class teams extends DB_Connect {
//put your code here
    
    	private $db;

	public $variables;

    public function __construct($data = array()) {
        
        parent::__construct();
        
        $this->variables  = $data;
       // $this->db=  $this->pdo;
    }
    
    /*
     * build user selections options to be selected
     */
    public function biuldTeamsOptions() {// Connection data (server_address, database, name, poassword)
       
         $sql = "select Team_Id,Team_Name from teams ";
        $html="";
        $result=array();
       try {
           $result=  $this->query($sql);
           }
       
         catch (PDOException $e) {
           
            return($e->getMessage());
        }
        

 
        
        
        $html = '<option value="0"></option>';
                foreach ($result as $row) {

                    $html.='<option value="'.$row['Team_Id'].'">'.$row['Team_Name'].'</option>';


                   
                }
          

            
        
        $html.='';

        return $html;
        
    }

  
}

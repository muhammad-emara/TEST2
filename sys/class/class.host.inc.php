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
class host extends DB_Connect {
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
    public function getAllHostName() {// Connection data (server_address, database, name, poassword)
        
    
       
                $sql = "SELECT  distinct t.`cmdb_host_name` from (select distinct `cmdb_host_name` FROM mss_db.`mss_cmdb` union (select distinct host_name from cnoc_handovers)  union (select distinct host_name from cnoc_activities)) as t order by  cmdb_host_name ";
        $html="";
        $result=array();
       try {
           $result=  $this->query($sql);
           }
       
         catch (PDOException $e) {
           
            return($e->getMessage());
        }
        return  $result;//customers":'.json_encode($result).'}';
       
        

    }
    
     public function getAllHostdata() {// Connection data (server_address, database, name, poassword)
        
    
       
                $sql = "SELECT distinct * FROM `mss_cmdb` order by cmdb_host_name ";
        $html="";
        $result=array();
       try {
           $result=  $this->query($sql);
           }
       
         catch (PDOException $e) {
           
            return($e->getMessage());
        }
        return  $result;//customers":'.json_encode($result).'}';
       
        

    }
    
    public function getAllHostPerCustomer($custname=null) {// Connection data (server_address, database, name, poassword)
        
    if($custname!=null or isset($_POST['code']))
    {
        $custname=  $custname==null? htmlentities($_POST['code'], ENT_QUOTES):htmlentities($custname, ENT_QUOTES);
       
                $sql ="SELECT  distinct t.`cmdb_host_name` from ((select distinct `cmdb_host_name` FROM mss_db.`mss_cmdb` where `cmdb_customer_code`=:custcode) union (select distinct host_name from cnoc_handovers where Cust_code=:custcode1)  union (select distinct host_name from cnoc_activities where Cust_code=:custcode2) union (select distinct host_name from bib_srs where Cust_code=:custcode3)) as t  order by cmdb_host_name"; //"SELECT  distinct t.`cmdb_host_name` from ( (select distinct `cmdb_host_name` FROM mss_db.`mss_cmdb` where `cmdb_customer_code`=:custcode) union (select distinct host_name from cnoc_handovers where Cust_code=:custcode1)  union (select distinct host_name from cnoc_activities where Cust_code=:custcode2)) as t  order by cmdb_host_name";//"SELECT distinct cmdb_host_name FROM `mss_cmdb` where `cmdb_customer_code`=:custcode order by cmdb_host_name ";
               
        $html="";
        $result=array();
       try {
           $result=  $this->query($sql,array('custcode'=>$custname,'custcode1'=>$custname,'custcode2'=>$custname,'custcode3'=>$custname),  PDO::FETCH_OBJ);
           }
       
         catch (PDOException $e) {
           
            return($e->getMessage());
        }
        return json_encode($result);
       
        

    }
    }


  
}

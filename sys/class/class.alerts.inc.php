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
class alerts extends DB_Connect {
//put your code here
    
    	private $db;

	public $variables;

    public function __construct($data = array()) {
        
        parent::__construct();
        
        $this->variables  = $data;
       // $this->db=  $this->pdo;
    }
    
    /*
     * get last id of alerts
     */
    
    public function getLastAlertId($class=null)
    {
       // $lastAlertId=0;
        $option="";
       if ($class!=null) {
           $option=" where alert_class='".$class."'";
           
       }
       
        
           $sql = 'SELECT `alert_id` FROM `alerts_tbl` '.$option.' order by alert_id desc limit 1 ';
$msg='';
            try {
                
                $result = $this->row($sql);
                if ($result > 0) {
                   // $_SESSION['Activityid_sess']=$serial;

                    // $msg= 'true ';// Items inserted Before'.implode(' || ',$_srInserted);//array("phone"=>'123-12313',"email"=>'test@test.com','city'=>'Medicine Hat','address'=>'556 19th Street NE')
                   //if you need that json then uncommet that // return json_encode(array("lastAlertID" => $result['alert_id']));
                   return $result['alert_id'];
                }
                else { 
                    return 0;
                
                }
                
               
            } catch (PDOException $e) {
                
                return  -1;//( 'error pdo ' . $e->getMessage());
            }
        
        
        
    }
    
       public function setAlert($type,$class,$itmid=null,$data)
    {
        

        
        $sql = "INSERT INTO `alerts_tbl`( `alert_type`, `alert_class`,alert_itmId ,`alert_user`, `Data`) VALUES (:type,:class,:itmid,:user,:data)";
       //$this->lastInsertId();
        try {
            $res = $this->query($sql, array( "type" => $type, "class" => $class, "itmid" => $itmid, "user" => $_SESSION['user']['email'],  "data" => $data));
           // $this->log->logActions($_SESSION['user']['email'], ' New Cnoc Activity', 'Succes ad  ', 'DATA :TTid =>'. $TT_id.', cust_name => '.$activitycustName.', host_name =>'. $activityhostName.', reason =>'. $activityReason.', activityDate => '.$activitydate.',  related_Team =>'. $activityteam);
        } catch (Exception $e) {
            // $this->db=null;
            $this->log->logActions($_SESSION['user']['email'], 'Add New Alert ', 'Faild Delete due to '.$e->getMessage(), 'Data :');
            return ($e->getMessage());
        }

        /*
         * Fails if username doesn't match a DB entry
         */
        if (!$res) {
            return FALSE; //"Your username or password is invalid.";
        } else {
            return TRUE; //success
        }
        
        
        
    }
  

  
}

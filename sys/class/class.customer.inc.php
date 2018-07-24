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
class customer extends DB_Connect {
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
    public function getCustomers() {// Connection data (server_address, database, name, poassword)
        //`Cust_code`, `host_name`
    
       
                $sql = "SELECT distinct t.`customer_code` from (select distinct `customer_code` FROM mss_db.`mss_customer` union (select distinct Cust_code from cnoc_handovers) union (select distinct Cust_code from cnoc_activities) union (select distinct Cust_code from bib_srs)) as t order by customer_code ";
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
    
    
    
       public function getCustomers_BIB() {// Connection data (server_address, database, name, poassword)
        //`Cust_code`, `host_name`
    
       
                $sql = "select distinct Cust_code as customer_code from bib_srs order by Cust_code ";
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
    
      /*     * *********get all Customer Activaion json ****** */

    //($_REQUEST['from'],$_REQUEST['to'],$_REQUEST['FilterName'],$_REQUEST['Operator'],$_REQUEST['FilterValue']));
    public function cnocGetcustomeractivationJson($from = null, $to = null, $filterName = null, $Operator = null, $FilterValue = null) {// Connection data (server_address, database, name, poassword)
     /*   $option = '';
        if ($filterName == null and $from == null and $to == null) {
            $option = " WHERE STR_TO_DATE(`activity_date`, '%d/%m/%Y')='" . date('Y-m-d') . "'";
        } else {
            $option = " WHERE STR_TO_DATE(replace(activity_date,'/',','),'%d,%m,%Y %T')>= STR_TO_DATE(replace('" . $from . "','/',','),'%m,%d,%Y %T') and STR_TO_DATE(replace(activity_date,'/',','),'%d,%m,%Y %T')<= STR_TO_DATE(replace('" . $to . "','/',','),'%m,%d,%Y %T')";
            // $coulmnName = '';
            //  $operator = '';
            // $value = '';
            $additionalFilters = '';
            switch ($filterName) {
                case 'All':
                    //   $coulmnName = '';
                    $additionalFilters = '';
                    break;
                case 'Customer':
                    // $coulmnName = 'Cust_code';
                    $additionalFilters = ' Cust_code';
                    break;
                case 'Host Name':
                    $additionalFilters = ' host_name';
                    break;
                case 'Reason':
                    $additionalFilters = ' activity_reason';
                    break;
                case 'Closed by':
                    $additionalFilters = ' Closed_by';
                    break;
                case 'Assigned To':
                    $additionalFilters = ' assigned_to';
                    break;
                case 'Incident Number':
                    $additionalFilters = ' SM_ticket';
                    break;
            }//end filter name
            /////get vlide operator

            switch ($Operator) {
                case 'Equal':
                    //   $coulmnName = '';
                    $additionalFilters .= " ='" . trim($FilterValue) . "' ";
                    break;
                case 'Contain':
                    // $coulmnName = 'Cust_code';
                    $additionalFilters.=" Like '%" . trim($FilterValue) . "%' ";
                    break;
                case 'StartWith':
                    $additionalFilters.=" Like '" . trim($FilterValue) . "%' ";
                    break;
                case 'EndWith':
                    $additionalFilters.=" Like '%" . trim($FilterValue) . "' ";
                    break;
                case 'NotEqual':
                    $additionalFilters.=" != '" . trim($FilterValue) . "' ";
                    break;
                case 'NotContain':
                    $additionalFilters.=" Not Like '%" . trim($FilterValue) . "%' ";
                    break;
                case 'GreaterThan':
                    $additionalFilters.=" > '" . trim($FilterValue) . "' ";
                    break;
                case 'LessThan':
                    $additionalFilters.=" < '" . trim($FilterValue) . "' ";
                    break;
            }//get valid operator
            switch ($filterName) {
                case 'All':
                    //   $coulmnName = '';
                    $additionalFilters = '';
                    break;
            }
            if ($filterName != 'All') {
                $additionalFilters = ' and (' . $additionalFilters . ') ';
            }

            $option.=$additionalFilters;
        }
*/

     $sql = "SELECT c.cmdb_customer_code, cust.customer_name , c.cmdb_host_name , c.cmdb_region_name , c.cmdb_access_account_no , c.cmdb_site_status,cust.customer_type FROM mss_db.`mss_cmdb` as c left join mss_db.mss_customer as cust on cust.customer_code=c.cmdb_customer_code order by c.cmdb_customer_code";
//$sql = "select * from mss_cmdb order by cmdb_customer_code";
        $html = "";
        //var_dump($sql);
        $result = array();
        try {
            $result = $this->query($sql, array(), PDO::FETCH_OBJ);
        } catch (PDOException $e) {

            return($e->getMessage());
        }
        return '{"customers":' . json_encode($result) . '}'; //json_encode($result);
    }

  
}

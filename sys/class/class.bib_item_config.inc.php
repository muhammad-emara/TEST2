<?php
if (!isset($_SESSION)) {
    session_start();
}
include_once 'DB_Connect.inc.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class
 * For Collect all user functions
 * @author muham_ Emara 2015
 */
class bib_item_config extends DB_Connect {
    
    	private $db;

	public $variables;

    public function __construct($data = array()) {
        
        parent::__construct();
        
        $this->variables  = $data;
       // $this->db=  $this->pdo;
    }
   

    /** rigester for new users
     * 
     * 
     */
    //AddNewCnocHandoverCategory
    public function AddNewBIBZone() {
        
        $Cat_name=$_POST['input-catName'];
        $qr = $this->query("INSERT INTO bib_zone(`zone_name`, `add_by`) values('" .htmlentities( $Cat_name) . "','" . $_SESSION['user']['email'] . "')") or die(mysql_error());
        return $qr;
    }

    /** get all Cncoc Categories for table view
     *
     * @return mixed TRUE on success, message on error
     */
    public function biuldZonesTable() {// Connection data (server_address, database, name, poassword)
       
         $sql = "SELECT `zone_name`, `add_by`, `add_time` FROM `bib_zone` ";
        $html="";
        $result=array();
       try {
           $result=  $this->query($sql);
           }
       
         catch (PDOException $e) {
           
            return($e->getMessage());
        }
        

 
        
        
        $html = '<table class="table table-striped table-condensed table-bordered" id="sample_1">
										<thead>
											<tr>
												<th style="width:8px"><input type="checkbox" class="group-checkable" data-set=".checkboxes" /></th>
												
												<th class="hidden-phone">Zone Name</th>
												<th class="hidden-phone">Added By</th>
                                                                                                <th class="hidden-phone">Create Time</th>
												
												<th>Actions</th>
											</tr>
										</thead>
										<tbody>';
                foreach ($result as $row) {

                    $html.='<tr class="odd gradeX">
												<td><input type="checkbox" class="checkboxes" value="' . $row['zone_name'] . '" /></td>
												
                                                                                                    <td>'.$row['zone_name'].'</td>
												<td class="hidden-phone"><a href="mailto:' . $row['add_by'] . '@etisalat.ae">' . $row['add_by'] . '</a></td>
												<td class="hidden-phone">' . ($row['add_time'] == '' ? '-' : $row['add_time']) . '</td>
												
												';
                    IF((($_SESSION['currentRole']=='Admin')or ($_SESSION['teamName']=='supperAdmins')))
                    {$html.='<td class="center">
													
													<a href="#" class="icon huge"><i class="icon-remove" id="removeBIBCat_'.$row['zone_name'].'" data-d="input-id='.$row['zone_name'].'&token='.$_SESSION['token'].'&action=bibZone_delete"></i></a>&nbsp;		
												</td>';
                    }
                    else
                    {
                        $html.='<td class="center">You don\'t have AUTH </td>';
                        
                    }
											$html.='</tr>';


                    // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
                }
          

            
        
        $html.='</tbody></table>';

        return $html;
        
    }

   
/*
 * 
 * Delete Cat provided by his ID 
 * 
 */
    public function processDeleteBIBZone() {
        /*
         * Fails if the proper action was not submitted
         */
        // echo "<script>alert('".$_POST['input-username']."')</script>";
//        if ($_POST['action'] != 'bibZone_delete') {
//            return "Invalid action supplied for process Delete CNOC Handover Category.";
//        }
        if ($_POST['action'] != 'bibZone_delete') {
            return "Invalid action supplied for process Delete BIB Zone.";
        }
        /*
         * Escapes the user input for security
         */
        $unid = htmlentities($_POST['input-id'], ENT_QUOTES);
      

        /*
         * Retrieves the matching info from the DB if it exists
         */
        $sql = "delete from bib_zone where zone_name=:id";
        try {
            $res=  $this->query($sql ,array("id"=>$unid));
            $this->log->logActions($_SESSION['user']['email'], 'Delete BIB Zone', 'Success Delete', 'Zone ID :'.$unid.' deleted');
           
        } catch (Exception $e) {
           // $this->db=null;
            return ($e->getMessage());
            $this->log->logActions($_SESSION['user']['email'], 'Delete BIB Zone', 'Faild Delete due to '.$e->getMessage(), 'Zone ID :'.$unid.' deleted');
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
    
   /*
     * build user selections options to be selected
     */
    //getAllCnocHandoverCatName
    public function getAllBIBZoneName() {// Connection data (server_address, database, name, poassword)
        
    
       
                $sql = "SELECT `zone_name` FROM `bib_zone` order by zone_name ";
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
    //==================END ZONE =================================//////
   /////////////--------------- OLT ------------------------------/////// 
    //AddNewCnocHandoverCategory
    public function AddNewBIBOLT() {
        
        if(!isset($_POST['input-catName']) || !isset($_POST['select-bib-zone_name'])){
        
        
            return FALSE;
        }
        $Cat_name=$_POST['input-catName'];
        $Zone_name=$_POST['select-bib-zone_name'];
        
        $qr = $this->query("INSERT INTO bib_olt(`zone_name`,`olt_name`, `add_by`) values('" .htmlentities( $Zone_name) . "','" .htmlentities( $Cat_name) . "','" . $_SESSION['user']['email'] . "')") or die(mysql_error());
        return $qr;
    }

    /** get all Cncoc Categories for table view
     *
     * @return mixed TRUE on success, message on error
     */
    public function biuldOLTTable() {// Connection data (server_address, database, name, poassword)
       
         $sql = "SELECT zone_name,`olt_name`, `add_by`, `add_time` FROM `bib_olt` ";
        $html="";
        $result=array();
       try {
           $result=  $this->query($sql);
           }
       
         catch (PDOException $e) {
           
            return($e->getMessage());
        }
        

 
        
        
        $html = '<table class="table table-striped table-condensed table-bordered" id="sample_1">
										<thead>
											<tr>
												<th style="width:8px"><input type="checkbox" class="group-checkable" data-set=".checkboxes" /></th>
												<th class="hidden-phone">Zone Name</th>
												<th class="hidden-phone">OLT Name</th>
												<th class="hidden-phone">Added By</th>
                                                                                                <th class="hidden-phone">Create Time</th>
												
												<th>Actions</th>
											</tr>
										</thead>
										<tbody>';
                foreach ($result as $row) {
$rowKey=$row['zone_name'].'__'.$row['olt_name'];
                    $html.='<tr class="odd gradeX">
												<td><input type="checkbox" class="checkboxes" value="' . $rowKey . '" /></td>
												 <td>'.$row['zone_name'].'</td>
                                                                                                    <td>'.$row['olt_name'].'</td>
												<td class="hidden-phone"><a href="mailto:' . $row['add_by'] . '@etisalat.ae">' . $row['add_by'] . '</a></td>
												<td class="hidden-phone">' . ($row['add_time'] == '' ? '-' : $row['add_time']) . '</td>
												
												';
                     IF((($_SESSION['currentRole']=='Admin')or ($_SESSION['teamName']=='supperAdmins')))
                    {$html.='<td class="center">
													
													<a href="#" class="icon huge"><i class="icon-remove" id="removeBIBCat_'.$rowKey.'" data-d="input-id='.$rowKey.'&token='.$_SESSION['token'].'&action=bibOlt_delete"></i></a>&nbsp;		
												</td>';
                    }
                    else
                    {
                        $html.='<td class="center">You don\'t have AUTH </td>';
                        
                    }
											$html.='</tr>';


                    // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
                }
          

            
        
        $html.='</tbody></table>';

        return $html;
        
    }

   
/*
 * 
 * Delete Cat provided by his ID 
 * 
 */
    public function processDeleteBIBOLT() {
        /*
         * Fails if the proper action was not submitted
         */
        // echo "<script>alert('".$_POST['input-username']."')</script>";
//        if ($_POST['action'] != 'bibZone_delete') {
//            return "Invalid action supplied for process Delete CNOC Handover Category.";
//        }
        if ($_POST['action'] != 'bibOlt_delete') {
            return "Invalid action supplied for process Delete BIB OLT.";
        }
        /*
         * Escapes the user input for security
         */
       // $unid = htmlentities($_POST['input-id'], ENT_QUOTES);
      
        $unid = explode('__', htmlentities($_POST['input-id'], ENT_QUOTES)) ;
      

        /*
         * Retrieves the matching info from the DB if it exists
         */
      //  $sql = "delete from bib_subnets where zone_name=:zid and subnet_name=:sid";
        /*
         * Retrieves the matching info from the DB if it exists
         */
        $sql = "delete from bib_olt where zone_name=:zid and olt_name=:id";
        try {
            $res=  $this->query($sql ,array("zid"=>$unid[0],"id"=>$unid[1]));
            $this->log->logActions($_SESSION['user']['email'], 'Delete BIB OLT', 'Success Delete', 'OLT ID :'.$unid[0].$unid[1].' deleted');
           
        } catch (Exception $e) {
           // $this->db=null;
            return ($e->getMessage());
            $this->log->logActions($_SESSION['user']['email'], 'Delete BIB OLT', 'Faild Delete due to '.$e->getMessage(), 'OLT ID :'.$unid[0].$unid[1].' deleted');
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
    
   /*
     * build user selections options to be selected
     */
    //getAllCnocHandoverCatName
    public function getAllBIBOLTName($zonename=NULL) {// Connection data (server_address, database, name, poassword)
        
    
       
                $sql = "SELECT `olt_name` FROM `bib_olt`".$zonename==NULL?'':" where zone_name:zid"." order by olt_name ";
        $html="";
        $result=array();
       try {
           $result=  $this->query($sql,array("zid"=>$zonename));
           }
       
         catch (PDOException $e) {
           
            return($e->getMessage());
        }
        return  $result;//customers":'.json_encode($result).'}';
       
        

    } 
        public function getAllOLTPerZone($custname=null) {// Connection data (server_address, database, name, poassword)
        
    if($custname!=null or isset($_POST['code']))
    {
        $custname=  $custname==null? htmlentities($_POST['code'], ENT_QUOTES):htmlentities($custname, ENT_QUOTES);
       
                $sql ="SELECT `olt_name` FROM `bib_olt` where zone_name=:zid  order by olt_name"; //"SELECT  distinct t.`cmdb_host_name` from ( (select distinct `cmdb_host_name` FROM mss_db.`mss_cmdb` where `cmdb_customer_code`=:custcode) union (select distinct host_name from cnoc_handovers where Cust_code=:custcode1)  union (select distinct host_name from cnoc_activities where Cust_code=:custcode2)) as t  order by cmdb_host_name";//"SELECT distinct cmdb_host_name FROM `mss_cmdb` where `cmdb_customer_code`=:custcode order by cmdb_host_name ";
               
        $html="";
        $result=array();
       try {
           $result=  $this->query($sql,array('zid'=>$custname),  PDO::FETCH_OBJ);
           }
       
         catch (PDOException $e) {
           
            return($e->getMessage());
        }
        return json_encode($result);
       
        

    }
    }
    
    //==================END OTL =================================//////
   /////////////--------------- Subnet ------------------------------/////// 


    //AddNewCnocHandoverCategory
    public function AddNewBIBsubnet() {
        
        $Zone_name=$_POST['select-bib-zone_name'];
        $Olt_name=$_POST['select-bib-olt_name'];
        $subnet_name=$_POST['txt-bib-subnet'];
        $gateway_name=$_POST['txt-bib-gateway'];
        $subnet_from=$_POST['txt-bib-subnet_from'];
        $subnet_to=$_POST['txt-bib-subnet_to'];
        
        if (!isset($Zone_name) or empty($Zone_name) or !isset($Olt_name) or empty($Olt_name) or !isset($subnet_name) or empty($subnet_name)or !isset($gateway_name) or empty($gateway_name)or !isset($subnet_from) or empty($subnet_from)or !isset($subnet_to) or empty($subnet_to)) {
            return FALSE;
        }
        
        $qr = $this->query("INSERT INTO bib_subnets(`zone_name`,`olt_name`,`subnet_name`, `Gateway`, `SubRange_from`, `SubRange_to`, `add_by`) values('" .htmlentities( $Zone_name) . "','" .htmlentities( $Olt_name) . "','" . htmlentities( $subnet_name). "','" . htmlentities( $gateway_name). "','" . htmlentities($subnet_from) . "','" . htmlentities( $subnet_to). "','" . $_SESSION['user']['email'] . "')") or die(mysql_error());
        return $qr;
    }

    /** 
     *
     * @return mixed TRUE on success, message on error
     */
    public function biuldsubnetTable() {// Connection data (server_address, database, name, poassword)
       
         $sql = "SELECT `zone_name`,`olt_name`, `subnet_name`, `Gateway`, `SubRange_from`, `SubRange_to`, `add_by`, `add_time` FROM `bib_subnets`";
        $html="";
        $result=array();
       try {
           $result=  $this->query($sql);
           }
       
         catch (PDOException $e) {
           
            return($e->getMessage());
        }
        

 
        
        
        $html = '<table class="table table-striped table-condensed table-bordered" id="sample_1">
										<thead>
											<tr>
												<th style="width:8px"><input type="checkbox" class="group-checkable" data-set=".checkboxes" /></th>
												
												<th class="hidden-phone">Zone Name</th>
                                                                                                <th class="hidden-phone">OLT Name</th>
                                                                                                
												<th class="hidden-phone">Subnet</th>
												<th class="hidden-phone">gateway</th>
												<th class="hidden-phone">SubRange From</th>
												<th class="hidden-phone">SubRange To</th>
												<th class="hidden-phone">Added By</th>
                                                                                                <th class="hidden-phone">Create Time</th>
												
												<th>Actions</th>
											</tr>
										</thead>
										<tbody>';
                foreach ($result as $row) {
                    //`zone_name`, `subnet_name`, `Gateway`, `SubRange_from`, `SubRange_to`, `add_by`, `add_time`
$rowKey=$row['zone_name'].'__'.$row['subnet_name'];
                    $html.='<tr class="odd gradeX">
												<td><input type="checkbox" class="checkboxes" value="' . $rowKey . '" /></td>
												
                                                                                                    <td>'.$row['zone_name'].'</td>
                                                                                                        <td>'.$row['olt_name'].'</td>
                                                                                                    <td>'.$row['subnet_name'].'</td>
                                                                                                    <td>'.$row['Gateway'].'</td>
                                                                                                    <td>'.$row['SubRange_from'].'</td>
                                                                                                    <td>'.$row['SubRange_to'].'</td>
                                                                                                   
												<td class="hidden-phone"><a href="mailto:' . $row['add_by'] . '@etisalat.ae">' . $row['add_by'] . '</a></td>
												<td class="hidden-phone">' . ($row['add_time'] == '' ? '-' : $row['add_time']) . '</td>
												
												';
                     IF((($_SESSION['currentRole']=='Admin')or ($_SESSION['teamName']=='supperAdmins')))
                    {$html.='<td class="center">
													
													<a href="#" class="icon huge"><i class="icon-remove" id="removeBIBCat_'.$rowKey.'" data-d="input-id='.$rowKey.'&token='.$_SESSION['token'].'&action=bibsubnet_delete"></i></a>&nbsp;		
												</td>';
                    }
                    else
                    {
                        $html.='<td class="center">You don\'t have AUTH </td>';
                        
                    }
											$html.='</tr>';


                    // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
                }
          

            
        
        $html.='</tbody></table>';

        return $html;
        
    }

   
/*
 * 
 * Delete Cat provided by his ID 
 * 
 */
    public function processDeleteBIBsubnet() {
        /*
         * Fails if the proper action was not submitted
         */
        // echo "<script>alert('".$_POST['input-username']."')</script>";
//        if ($_POST['action'] != 'bibZone_delete') {
//            return "Invalid action supplied for process Delete CNOC Handover Category.";
//        }
        if ($_POST['action'] != 'bibsubnet_delete') {
            return "Invalid action supplied for process Delete BIB Subnet.";
        }
        /*
         * Escapes the user input for security
         */
        $unid = explode('__', htmlentities($_POST['input-id'], ENT_QUOTES)) ;
      

        /*
         * Retrieves the matching info from the DB if it exists
         */
        $sql = "delete from bib_subnets where zone_name=:zid and subnet_name=:sid";
        try {
            $res=  $this->query($sql ,array("zid"=>$unid[0],"sid"=>$unid[1]));
            $this->log->logActions($_SESSION['user']['email'], 'Delete BIB SUBNET', 'Success Delete', 'subnet ID :'.$_POST['input-id'].' deleted');
           
        } catch (Exception $e) {
           // $this->db=null;
            return ($e->getMessage());
            $this->log->logActions($_SESSION['user']['email'], 'Delete BIB SUBNET', 'Faild Delete due to '.$e->getMessage(), 'subnet ID :'.$_POST['input-id'].' deleted');
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
    
   /*
     * build user selections options to be selected
     */
    //getAllCnocHandoverCatName
    public function getAllBIBsubnetName($zonename=NULL) {// Connection data (server_address, database, name, poassword)
        
   
       
                $sql = "SELECT `subnet_name` FROM `bib_subnets`".$zonename==NULL?'':" where zone_name:zid"." order by olt_name ";
        $html="";
        $result=array();
       try {
           $result=  $this->query($sql,array("zid"=>$unid[0]));
           }
       
         catch (PDOException $e) {
           
            return($e->getMessage());
        }
        return  $result;//customers":'.json_encode($result).'}';
       
        

    } 
    
    
       public function getAllSubnetPerOLT($custname=null) {// Connection data (server_address, database, name, poassword)
        
    if($custname!=null or isset($_POST['code']))
    {
        $custname=  $custname==null? htmlentities($_POST['code'], ENT_QUOTES):htmlentities($custname, ENT_QUOTES);
       
                $sql ="SELECT `subnet_name` FROM `bib_subnets` where olt_name=:zid  order by subnet_name"; //"SELECT  distinct t.`cmdb_host_name` from ( (select distinct `cmdb_host_name` FROM mss_db.`mss_cmdb` where `cmdb_customer_code`=:custcode) union (select distinct host_name from cnoc_handovers where Cust_code=:custcode1)  union (select distinct host_name from cnoc_activities where Cust_code=:custcode2)) as t  order by cmdb_host_name";//"SELECT distinct cmdb_host_name FROM `mss_cmdb` where `cmdb_customer_code`=:custcode order by cmdb_host_name ";
               
        $html="";
        $result=array();
       try {
           $result=  $this->query($sql,array('zid'=>$custname),  PDO::FETCH_OBJ);
           }
       
         catch (PDOException $e) {
           
            return($e->getMessage());
        }
        return json_encode($result);
       
        

    }
    }
    
    
    
       public function get_subnetips($custname=null) {// Connection data (server_address, database, name, poassword)
        
    if($custname!=null or isset($_POST['code']))
    {
        $custname=  $custname==null? htmlentities($_POST['code'], ENT_QUOTES):htmlentities($custname, ENT_QUOTES);
       //SELECT `IP_address` FROM `bib_srs` WHERE `sr_status`!= 'Cancelled' and `Subnet-Mask` = '10.10.10.0/28' 
                $sql ="SELECT `IP_address` FROM `bib_srs` where `sr_status`!= 'Cancelled' and `Subnet-Mask` =:zid  order by IP_address"; //"SELECT  distinct t.`cmdb_host_name` from ( (select distinct `cmdb_host_name` FROM mss_db.`mss_cmdb` where `cmdb_customer_code`=:custcode) union (select distinct host_name from cnoc_handovers where Cust_code=:custcode1)  union (select distinct host_name from cnoc_activities where Cust_code=:custcode2)) as t  order by cmdb_host_name";//"SELECT distinct cmdb_host_name FROM `mss_cmdb` where `cmdb_customer_code`=:custcode order by cmdb_host_name ";
               
        $html="";
        $result=array();
       try {
           $result=  $this->query($sql,array('zid'=>$custname));
           }
       
         catch (PDOException $e) {
           
            return($e->getMessage());
        }
        
        $usedips=null;
         foreach ($result as $row) {
             
             $usedips[]= $row['IP_address'];
             
             //print("\n");
         }
        
        
  //      $result2 = $result->db->fetch(PDO::FETCH_ASSOC);
//print_r($result2);

       // $newArray1 = array_values($result);
       // print_r($usedips);
      //  echo implode( ',', call_user_func_array('array_merge', $result ) );
        
        
        $subnetObj= new subnet();
        $arr=$subnetObj->subnet_ipRange($custname);
      //  print_r($arr);
        $resarr= array_diff($arr, $usedips);
      if ($usedips==null) {
          $resarr= $arr;
      }
      if($resarr==null)
      {
          $resarr[]='No Avl Ips';
      }

     // $resarr= array_diff($arr, $usedips);
  //   print_r($resarr);
        
//        for ($i = 0; $i < count($resarr); $i++) {
//    $newArray[] = ['IP_address' => $resarr[$i]];
//};
     
    $newArray = array_values($resarr);
        
        return json_encode($newArray);
       
        

    }
    }
    
    
    
           public function getAllgetwayPerSubnet($custname=null) {// Connection data (server_address, database, name, poassword)
        
    if($custname!=null or isset($_POST['code']))
    {
        $custname=  $custname==null? htmlentities($_POST['code'], ENT_QUOTES):htmlentities($custname, ENT_QUOTES);
       
                $sql ="SELECT distinct `Gateway` FROM `bib_subnets` where subnet_name=:zid  order by Gateway"; //"SELECT  distinct t.`cmdb_host_name` from ( (select distinct `cmdb_host_name` FROM mss_db.`mss_cmdb` where `cmdb_customer_code`=:custcode) union (select distinct host_name from cnoc_handovers where Cust_code=:custcode1)  union (select distinct host_name from cnoc_activities where Cust_code=:custcode2)) as t  order by cmdb_host_name";//"SELECT distinct cmdb_host_name FROM `mss_cmdb` where `cmdb_customer_code`=:custcode order by cmdb_host_name ";
               
        $html="";
        $result=array();
       try {
           $result=  $this->query($sql,array('zid'=>$custname),  PDO::FETCH_OBJ);
           }
       
         catch (PDOException $e) {
           
            return($e->getMessage());
        }
        return json_encode($result);
       
        

    }
    }
    
    //==================END Subnet =================================//////
   /////////////--------------- Status ------------------------------/////// 
        public function AddNewBIBStatus() {
        
        $Cat_name=$_POST['input-catName'];
        $qr = $this->query("INSERT INTO bib_status(`status_name`, `add_by`) values('" .htmlentities( $Cat_name) . "','" . $_SESSION['user']['email'] . "')") or die(mysql_error());
        return $qr;
    }

    /** get all Cncoc Categories for table view
     *
     * @return mixed TRUE on success, message on error
     */
    public function biuldStatusTable() {// Connection data (server_address, database, name, poassword)
       
         $sql = "SELECT `status_name`, `add_by`, `add_time` FROM `bib_status` ";
        $html="";
        $result=array();
       try {
           $result=  $this->query($sql);
           }
       
         catch (PDOException $e) {
           
            return($e->getMessage());
        }
        

 
        
        
        $html = '<table class="table table-striped table-condensed table-bordered" id="sample_1">
										<thead>
											<tr>
												<th style="width:8px"><input type="checkbox" class="group-checkable" data-set=".checkboxes" /></th>
												
												<th class="hidden-phone">Status Name</th>
												<th class="hidden-phone">Added By</th>
                                                                                                <th class="hidden-phone">Create Time</th>
												
												<th>Actions</th>
											</tr>
										</thead>
										<tbody>';
                foreach ($result as $row) {

                    $html.='<tr class="odd gradeX">
												<td><input type="checkbox" class="checkboxes" value="' . $row['status_name'] . '" /></td>
												
                                                                                                    <td>'.$row['status_name'].'</td>
												<td class="hidden-phone"><a href="mailto:' . $row['add_by'] . '@etisalat.ae">' . $row['add_by'] . '</a></td>
												<td class="hidden-phone">' . ($row['add_time'] == '' ? '-' : $row['add_time']) . '</td>
												
												';
                    IF((($_SESSION['currentRole']=='Admin')or ($_SESSION['teamName']=='supperAdmins')))
                    {$html.='<td class="center">
													
													<a href="#" class="icon huge"><i class="icon-remove" id="removeBIBCat_'.$row['status_name'].'" data-d="input-id='.$row['status_name'].'&token='.$_SESSION['token'].'&action=bibStatus_delete"></i></a>&nbsp;		
												</td>';
                    }
                    else
                    {
                        $html.='<td class="center">You don\'t have AUTH </td>';
                        
                    }
											$html.='</tr>';


                    // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
                }
          

            
        
        $html.='</tbody></table>';

        return $html;
        
    }

   
/*
 * 
 * Delete Cat provided by his ID 
 * 
 */
    public function processDeleteBIBStatus() {
        /*
         * Fails if the proper action was not submitted
         */
        // echo "<script>alert('".$_POST['input-username']."')</script>";
//        if ($_POST['action'] != 'bibZone_delete') {
//            return "Invalid action supplied for process Delete CNOC Handover Category.";
//        }
        if ($_POST['action'] != 'bibStatus_delete') {
            return "Invalid action supplied for process Delete BIB Status.";
        }
        /*
         * Escapes the user input for security
         */
        $unid = htmlentities($_POST['input-id'], ENT_QUOTES);
      

        /*
         * Retrieves the matching info from the DB if it exists
         */
        $sql = "delete from bib_status where status_name=:id";
        try {
            $res=  $this->query($sql ,array("id"=>$unid));
            $this->log->logActions($_SESSION['user']['email'], 'Delete BIB Status', 'Success Delete', 'Status ID :'.$unid.' deleted');
           
        } catch (Exception $e) {
           // $this->db=null;
            return ($e->getMessage());
            $this->log->logActions($_SESSION['user']['email'], 'Delete BIB Status', 'Faild Delete due to '.$e->getMessage(), 'Status ID :'.$unid.' deleted');
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
    
   /*
     * build user selections options to be selected
     */
    //getAllCnocHandoverCatName
    public function getAllBIBStatusName() {// Connection data (server_address, database, name, poassword)
        
    
       
                $sql = "SELECT `status_name` FROM `bib_status` order by status_name ";
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
    //==================END Status =================================//////
    

 


}

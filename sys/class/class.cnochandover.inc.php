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
class cnochandover extends DB_Connect {

//put your code here

    private $db;
    public $variables;
    public $alertObj; //=new alerts();

    public function __construct($data = array()) {

        parent::__construct();

        $this->variables = $data;
        $this->alertObj = new alerts();

        // $this->db=  $this->pdo;
    }

    /*
     * build add new activities
     */

    public function cnocAddNewHandover() {// Connection data (server_address, database, name, poassword)
        /*
         * inproper action call
         */
        if ($_POST['action'] != 'CNOC_AddHandover' OR (htmlentities($_SESSION['user']['email'], ENT_QUOTES))=="dsm_r" OR !isset($_SESSION['user']['email'])) {
            return "Invalid action supplied for process Add new CNOC HandOver.";
        }

        $TT_id = (htmlentities($_POST['txt-cnoc-handover_tt'], ENT_QUOTES));
        // $activitycustName = ((htmlentities($_POST['select-cnoc-activitycustName'], ENT_QUOTES)) == 'other' ? htmlentities($_POST['txt-cnoc-activitycustName'], ENT_QUOTES) : htmlentities($_POST['select-cnoc-activitycustName'], ENT_QUOTES));
        //  $activityhostName = (strtolower(htmlentities($_POST['select-cnoc-activityhostName'], ENT_QUOTES)) == 'other' ? htmlentities($_POST['txt-cnoc-activityhostName'], ENT_QUOTES) : htmlentities($_POST['select-cnoc-activityhostName'], ENT_QUOTES));
        //  $activityReason = (strtolower(htmlentities($_POST['select-cnoc-activityReason'], ENT_QUOTES)) == 'other' ? htmlentities($_POST['txt-cnoc-activityReason'], ENT_QUOTES) : htmlentities($_POST['select-cnoc-activityReason'], ENT_QUOTES));


        $handovercustName = (strtolower(htmlentities($_POST['select-cnoc-handovercustName'], ENT_QUOTES)) == 'other' ? htmlentities($_POST['txt-cnoc-handovercustName'], ENT_QUOTES) : htmlentities($_POST['select-cnoc-handovercustName'], ENT_QUOTES));
        $handoverhostName = (strtolower(htmlentities($_POST['select-cnoc-handoverhostName'], ENT_QUOTES)) == 'other' ? htmlentities($_POST['txt-cnoc-handoverhostName'], ENT_QUOTES) : htmlentities($_POST['select-cnoc-handoverhostName'], ENT_QUOTES));
        $handoverCategory = (htmlentities($_POST['select-cnoc-handoverCategory'], ENT_QUOTES));


        $handoverCMS_DKT = (htmlentities($_POST['txt-cnoc-handoverCMS-DKT'], ENT_QUOTES));


        $handoverStartdate = (htmlentities($_POST['txt-cnoc-handoverStartdate'], ENT_QUOTES));


        $ReqBy = (htmlentities($_SESSION['user']['email'], ENT_QUOTES));


        $HandoverRemarks = nl2br(htmlentities($_POST['HandoverRemarks'], ENT_QUOTES));
        if ($HandoverRemarks == '') {
            $HandoverRemarks = null;
        }
        /*
         * INSERT INTO `cnoc_handovers`(`handover_id`, `SM_ticket`, `Cust_code`, `host_name`, `handover_Category`, `CMS/DKT`, `Last_update`, `close_action`, `create_time`, `complete_time`, `last_assign_time`, `created_by`, `assigned_to`, `handover_status`, `Closed_by`, `IM_Startdate`)
         */
        $sql = "INSERT INTO `cnoc_handovers`(`SM_ticket`, `Cust_code`, `host_name`, `handover_Category`, `CMS/DKT`, `created_by`, `IM_Startdate`) VALUES (:TTid,:cust_name,:host_name,:Cat,:CMS_DKT,:reqby,:startDate)";
        //$this->lastInsertId();
        $params = array("TTid" => $TT_id, "cust_name" => $handovercustName, "host_name" => $handoverhostName, "Cat" => $handoverCategory, "CMS_DKT" => $handoverCMS_DKT, "reqby" => $ReqBy, "startDate" => $handoverStartdate);
        try {
            $res = $this->query($sql, $params);


            $HandoverID = $this->lastInsertId();
            /*             * *****adding new remark************ */

            $sql_r = "INSERT INTO `cnoc_handoversremarks`( `handover_id`, `Remark_TXT`, `Editor`) VALUES (:activityId,:remark,:by)";
            //$this->lastInsertId();
            try {
                $res = $this->query($sql_r, array("remark" => $HandoverRemarks, "by" => $ReqBy, "activityId" => $HandoverID));
                $this->log->logActions($_SESSION['user']['email'], ' New Cnoc Handover Remark', 'Succes add ', 'DATA :remark =>' . $HandoverRemarks . ', by => ' . $ReqBy . ',  for activityId =>' . $HandoverID);
            } catch (Exception $e) {
                // $this->db=null;
                $this->log->logActions($_SESSION['user']['email'], 'Add New Cnoc Activity', 'Faild Add due to ' . $e->getMessage(), 'DATA :remark =>' . $ActivityRemarks . ', by => ' . $ReqBy . ',  for activityId =>' . $activityID);
                return ($e->getMessage());
            }



             $this->alertObj->setAlert('INSERT', 'cnoc_handovers', null, 'DATA :' . implode(',', $params) );
            $this->log->logActions($_SESSION['user']['email'], ' New Cnoc Handover', 'Succes add  ', 'DATA :' . implode(',', $params) . '');
        } catch (Exception $e) {
            // $this->db=null;
            $this->log->logActions($_SESSION['user']['email'], 'Add New Cnoc HandOver', 'Faild Add due to ' . $e->getMessage(), 'Data :');
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

//end of cnocAddNewActivity function




    /*     * **********************biuld available  table ******************************* */

    public function biuldCnocDailyHandoverTable($show = 'my',$cat='all') {// Connection data (server_address, database, name, poassword)
        $condit = '';
        $arr = array();
        if ($show == 'my') {

            $condit = ' WHERE assigned_to=:user or created_by=:user1 ';
            $arr = array('user1' => $_SESSION['user']['email'], 'user' => $_SESSION['user']['email']);
        } elseif ($show == 'all') {


	if(  ( $_SESSION['teamName'] == 'supperAdmins'))
		{}
		else{


                 $condit = " where handover_status !='Completed' ";
		}

if($cat!='all')
            {
    if($cat=="TEAM_BO")
            {
                $condit = " where handover_status !='Completed'  and (handover_Category not in ('NODE DOWN','LAN DOWN','LAN BGP ISSUE','Interconnect Link Down','LAN FLAP','LAN BGP ISSUE','NNM Issue','INTAKE TEMPERATURE','Interconnect Link Down') and handover_status !='Pending Customer' )";
                
            }//if TEAM_BO
            elseif($cat=="TEAM_SD")
            {
                $condit = " where handover_status !='Completed'  and (handover_Category in ('NODE DOWN','Interconnect Link Down','LAN BGP ISSUE','LAN FLAP','Interconnect Link Down','LAN BGP ISSUE','NNM Issue','LAN DOWN','INTAKE TEMPERATURE','Interconnect Link Down') or handover_status ='Pending Customer') ";
            }//TEAM_SD
            else{
			
				$str_cat=(htmlentities($cat, ENT_QUOTES));
				$str_cat=str_replace("_.._"," ",$str_cat);

                 $condit = " where handover_status !='Completed'  and handover_Category='".$str_cat."'";
            }
            }
            else
            {
		if( ( $_SESSION['teamName'] == 'supperAdmins'))
		{}
		else{


                 $condit = " where handover_status !='Completed' ";
		}
            }

        } elseif ($show == 'today') {
		if($cat!='all')
            {
                    if($cat=="TEAM_BO")
            {
                $condit = " WHERE STR_TO_DATE(`IM_Startdate`, '%d/%m/%Y')='" . date('Y-m-d') . "' and (handover_Category not in ('NODE DOWN','LAN FLAP','NNM Issue','LAN DOWN','INTAKE TEMPERATURE','Interconnect Link Down') and handover_status !='Pending Customer' )";
                
            }//if TEAM_BO
            elseif($cat=="TEAM_SD")
            {
                $condit = " WHERE STR_TO_DATE(`IM_Startdate`, '%d/%m/%Y')='" . date('Y-m-d') . "' and (handover_Category in ('NODE DOWN','Interconnect Link Down','LAN BGP ISSUE','LAN FLAP','NNM Issue','LAN DOWN','INTAKE TEMPERATURE','Interconnect Link Down') or handover_status ='Pending Customer' )";
            }//TEAM_SD
            else{
			
				$str_cat=(htmlentities($cat, ENT_QUOTES));
				$str_cat=str_replace("_.._"," ",$str_cat);

                 $condit = " WHERE STR_TO_DATE(`IM_Startdate`, '%d/%m/%Y')='" . date('Y-m-d') . "' and handover_Category='".$str_cat."'";
            }
            }
            else
            {
                 $condit = " WHERE STR_TO_DATE(`IM_Startdate`, '%d/%m/%Y')='" . date('Y-m-d') . "' ";
            }


           // $condit = " WHERE STR_TO_DATE(`IM_Startdate`, '%d/%m/%Y')='" . date('Y-m-d') . "' ";
        }


        /* if(isset($_REQUEST['show']) && $_REQUEST['show']=='all'){$condit=' ';}
          elseif(isset($_REQUEST['show']) && $_REQUEST['show']=='today'){$condit=" WHERE STR_TO_DATE(`activity_date`, '%d/%m/%Y')='".date('Y-m-d')."' ";}
          else {$condit=' WHERE assigned_to=:user or created_by=:user1 ';} */
        //$sql = "SELECT `handover_id`, `SM_ticket`, `Cust_code`, `host_name`, `handover_Category`, `CMS/DKT`, `Last_update`, `close_action`, `create_time`, `complete_time`, `last_assign_time`, `created_by`, `assigned_to`, `handover_status`, `Closed_by`, `IM_Startdate` FROM `cnoc_handovers` " . $condit . " ORDER BY STR_TO_DATE(replace(IM_Startdate,'/',','),'%d,%m,%Y %T') ASC";
$sql = "SELECT `handover_id`, `SM_ticket`, `Cust_code`, `host_name`, `handover_Category`, `CMS/DKT`, `Last_update`, `close_action`, `create_time`, `complete_time`, `last_assign_time`, `created_by`, `assigned_to`, `handover_status`, `Closed_by`, `IM_Startdate` ,cust_top34.`TOP34`,`cust_vip`.`vip_` FROM `cnoc_handovers` left JOIN cust_vip ON (cust_vip.Customer_code)=(cnoc_handovers.Cust_code) left JOIN cust_top34 ON cust_top34.customer_code=cnoc_handovers.Cust_code " . $condit . " ORDER BY STR_TO_DATE(replace(IM_Startdate,'/',','),'%d,%m,%Y %T') ASC";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql, $arr);
        } catch (PDOException $e) {

            return($e->getMessage());
        }




        $html = '<table class="table table-striped table-bordered" id="sample_1">
										<thead>
											<tr>
												<th>Class</th>
												<th>Ticket#</th>
												<th>Customer Code</th>
                                                                                                <th>Host Name</th>
                                                                                                <th>Cust Type</th>
												 <th>Reference CMS/DKT</th>
                                                                                                <th>Category</th>
												<th>Incident Date</th>
                                                                                                <th>Handover Status</th>
                                                                                                <th>Last Update</th>
                                                                                                
                                                                                                <th>Assigned To</th>
                                                                                                
                                                                                                <th>Created By</th>
                                                                                                  <th>Action</th>
												
												
												
												
											</tr>
										</thead>
										<tbody>';
        foreach ($result as $row) {

            $assignedUser = '';
//set status color
            $statusLable = '';
$statusLable2 = '';
$CustLabel='';
 $CustType = '';
  $CustType2 = '';
$MGM_esclat='';
$category_VIP = array("IP Black List", "Website Issue", "VPN Issue","Voice/Video Traffic ");

 switch ($row['vip_']) {
                case "VIP":
                    $CustType2 = 'VIP';
                    $CustLabel='label label-important';
                    $MGM_esclat='bgcolor="#F0FFFF"';
if($row['handover_status']=="Pending" and (in_array($row['handover_Category'],$category_VIP)))
{
$row['handover_status']="MGMT Escalated";
}

                    break;
                default: $CustType2 = 'Enterprise';
                    break;
            }


            switch ($row['TOP34']) {
                case "TOP 34":
                    $CustType = 'TOP 34';
                    break;
                default: $CustType = 'Enterprise';
                    break;
            }
            switch ($row['handover_status']) {
                case "Pending":
                    $statusLable = 'label-important';
                    break;
                case "In Progress":
                    $statusLable = 'label-info';
                    break;
                case "Completed":
                    $statusLable = 'label-success';
                    break;
                case "Postponded":
                    $statusLable = 'label-warning';
                    break;
case "MGMT Escalated":
                    $statusLable = 'label-primary';//FA0DFE
$MGM_esclat='bgcolor="#F0FFFF"';
                    break;

                case "Canceled":
                    $statusLable = ''; //#d3d3d3
                    break;
            }//set status color




switch ($row['handover_Category']) {
                case "NODE DOWN":
                    $statusLable2 = 'label-important';
                    break;
                case "WAN FLAP":
                    $statusLable2= 'label-warning';
                    break;
                case "WAN DOWN":
                    $statusLable2 = 'label-warning';
                    break;
                case "BGP Flap":
                    $statusLable2 = 'label-warning';
                    break;
                case "Canceled":
                    $statusLable2 = 'label-warning'; //#d3d3d3
                    break;
            }//set Category color
//display assign button or no 

            $assignedUser = $row['assigned_to'];
            if ($row['handover_status'] != 'Completed' AND (htmlentities($_SESSION['user']['email'], ENT_QUOTES))!="dsm_r") {
                if ($row['assigned_to'] == null or $row['assigned_to'] == 'Completed') {
                    $row['assigned_to'] = '<a href="javascript:;" class="btn pick-handover" id="pickbtn_' . $row['handover_id'] . '"  data-id="' . $row['handover_id'] . '">Pick It</a>';
                } else {
                    if ($_SESSION['user']['email'] == $row['assigned_to']) {
                        $row['assigned_to'].='<a href="javascript:;" class="btn unpick-handover" id="pickbtn_' . $row['handover_id'] . '"  data-id="' . $row['handover_id'] . '">unPick It</a>';
                    } elseif ($_SESSION['user']['email'] != $row['assigned_to']) {
                        $row['assigned_to'].='<a href="javascript:;" class="btn pick-handover" id="pickbtn_' . $row['handover_id'] . '"  data-id="' . $row['handover_id'] . '">Pick It</a>';
                    }
                }
            } else {
                if ($row['assigned_to'] == null or $row['assigned_to'] == 'Completed') {
                    // $row['assigned_to'] = '<a href="javascript:;" class="btn pick-activity" id="pickbtn_'.$row['activity_id'].'"  data-id="' . $row['activity_id'] . '">Pick It</a>';
                } else {
                    if ($_SESSION['user']['email'] == $row['assigned_to']) {
                        //  $row['assigned_to'].='<a href="javascript:;" class="btn unpick-activity" id="pickbtn_'.$row['activity_id'].'"  data-id="' . $row['activity_id'] . '">unPick It</a>';
                    } elseif ($_SESSION['user']['email'] != $row['assigned_to']) {
                        //  $row['assigned_to'].='<a href="javascript:;" class="btn pick-activity" id="pickbtn_'.$row['activity_id'].'"  data-id="' . $row['activity_id'] . '">Pick It</a>';
                    }
                }
            }//complted
//`handover_id`, `SM_ticket`, `Cust_code`, `host_name`, `handover_Category`, `CMS/DKT`, `Last_update`, `close_action`, `create_time`, `complete_time`, `last_assign_time`, `created_by`, `assigned_to`, `handover_status`, `Closed_by`, `IM_Startdate`

            $html.='<tr class="odd gradeX " data-txt="' . $row['handover_id'] . '" '.$MGM_esclat.'   style="cursor:pointer;"><td>'.$row['vip_'].'</td>
               
												
												<td>' . $row['SM_ticket'] . '</td>
                                                                                                    <td class="'.$CustLabel.'">' . $row['Cust_code'] . '</td>
                                                                                                        <td>' . $row['host_name'] . '</td>
                                                                                                            <td>' . $CustType . '</td>
                                                                                                       <td id=cnoc_handoverDKT_' . $row['handover_id'] . '>' . $row['CMS/DKT'] . '</td>
                                                                                                        <td id=cnoc_handoverCategory_' . $row['handover_id'] . '><span class="label ' . $statusLable2 . '">' . $row['handover_Category'] . '</span></td>
                                                                                                            <td id=cnoc_handoverdate_' . $row['handover_id'] . '>' . $row['IM_Startdate'] . '</td>
                                                                                                                <td id=cnoc_handoverstatus_' . $row['handover_id'] . '><span class="label ' . $statusLable . '">' . $row['handover_status'] . '</span></td>
												<td id=cnoc_handoverLastUpdate_' . $row['handover_id'] . '>' . $row['Last_update'] . '</td>
                                                                                                    <td >' . $row['assigned_to'] . '</td>
                                                                                                        <td>' . $row['created_by'] . '</td>
                                                                               <td id=cnoc_handoveraction_' . $row['handover_id'] . '>';
            if ($show == 'my' and $row['handover_status'] != 'Completed') {


                if (true) {//date('d-m-Y',strtotime(str_replace('/', '-',$row['activity_date'])))<= date('d-m-Y'))
                    $html.='<a class="icon huge view-handover" href="javascript:;" data-id="' . $row['handover_id'] . '"><i class="icon-zoom-in"></i></a>&nbsp;';
                }
                if((htmlentities($_SESSION['user']['email'], ENT_QUOTES))!="dsm_r"){$html.='<a href="javascript:;" class="icon huge delete-handover" data-id="' . $row['handover_id'] . '"><i class="icon-remove"></i></a>';}
            } elseif ($show == 'all' and ( ($_SESSION['currentRole'] == 'Admin')or ( $_SESSION['teamName'] == 'supperAdmins'))) {
                $html.=' <a class="icon huge view-handover" href="javascript:;" data-id="' . $row['handover_id'] . '"><i class="icon-zoom-in"></i></a>&nbsp;';

                if ((htmlentities($_SESSION['user']['email'], ENT_QUOTES))!="dsm_r") {
                    $html.='<a href="javascript:;" class="icon huge delete-handover" data-id="' . $row['handover_id'] . '"><i class="icon-remove"></i></a>';
                }
            } elseif ($show == 'today') {



                if ($assignedUser == $_SESSION['user']['email'] and $row['handover_status'] != 'Completed') {
                    $html.='  <a class="icon huge view-handover" href="javascript:;" data-id="' . $row['handover_id'] . '"><i class="icon-zoom-in"></i></a>&nbsp;';

                    if((htmlentities($_SESSION['user']['email'], ENT_QUOTES))!="dsm_r"){$html.='<a href="javascript:;" class="icon huge delete-handover" data-id="' . $row['handover_id'] . '"><i class="icon-remove"></i></a>';}
                }
            }




            $html.='</td></tr>';
        }




        $html.='</tbody></table>';

        return $html;
    }

//biuld table


    /*
     * Edit activity
     */


//Edit activity

    /*     * **************end edit cnoc activity
     * 
     * 
     * 
     */
    /*
     * Edit activity
     */

    public function cnocEditHandover() {

        //return $_POST['action'];
        if ($_POST['action'] != 'CNOC_EditHandover' or (htmlentities($_SESSION['user']['email'], ENT_QUOTES))=="dsm_r" OR !isset($_SESSION['user']['email'])) {
            return FALSE; //se;//"Invalid action supplied for forget password Form.";
        }
        $uname = htmlentities($_SESSION['user']['email'], ENT_QUOTES);
        $closer = htmlentities($_POST['select-cnoc-handoverstatus'], ENT_QUOTES) == 'Completed' ? htmlentities($_SESSION['user']['email'], ENT_QUOTES) : NULL;
        $id = htmlentities($_POST['Handoverid'], ENT_QUOTES);
        $handover_Cat = htmlentities($_POST['select-cnoc-handoverCat'], ENT_QUOTES);
        $handover_date = htmlentities($_POST['txt-cnoc-handoverdate'], ENT_QUOTES);

        $handover_DKT = htmlentities($_POST['txt-cnoc-HandoverCMS_DKT'], ENT_QUOTES);

        $close_action = htmlentities($_POST['select-cnoc-handoverstatus'], ENT_QUOTES) == 'Completed' ? htmlentities($_POST['HandoverCloseAction'], ENT_QUOTES) : NULL;
        $close_Time = htmlentities($_POST['select-cnoc-handoverstatus'], ENT_QUOTES) == 'Completed' ? date('Y-m-d G:i:s') : NULL;


        $handover_status = htmlentities($_POST['select-cnoc-handoverstatus'], ENT_QUOTES);
        /*
         * UPDATE set `activity_reason`=[value-5],`activity_date`=[value-6],`realted_team`=[value-7],`close_action`=[value-8] `activity_status`=[value-14] WHERE `activity_id` =
         * */

        $sql = "UPDATE 
	`cnoc_handovers` 
SET  
	
	`handover_Category` = :cat, 
	`CMS/DKT` = :dkt, 
	`Last_update` = now(), 
	`close_action` = :closaction, 
	`complete_time` = :compl_time, 	 
	`handover_status` = :status, 
	`Closed_by` = :closer, 
	`IM_Startdate` = :strdate WHERE handover_id=:id";
        try {
            $arr = array("id" => $id, "cat" => $handover_Cat, "dkt" => $handover_DKT, "closaction" => $close_action, "compl_time" => $close_Time, "status" => $handover_status, "closer" => $closer, "strdate" => $handover_date);
            $countRows = $this->query($sql, $arr);
            //  var_dump($arr);
            //  var_dump($countRows);
            if ($countRows > 0) {

                $this->alertObj->setAlert('UPDATE', 'cnoc_handovers', $id, 'Handover ID  :' . $id . ' Edit values :' + implode(', ', $arr));
                $this->log->logActions($_SESSION['user']['email'], 'Edit Handovers', 'Success Edit', 'Handovers ID  :' . $id . ' Edit values :' + implode(', ', $arr));

                return true; //$_SESSION['user']['email'].'<a href="javascript:;" class="btn unpick-activity"  data-id="'.$id.'">unPick It</a>';;
            } else {
                return FALSE;
            }
            //success
            if ($handover_status=='Pending Customer') {
                return $this->cnocUnPickHandover2($id);  
            }
            
        } catch (Exception $e) {

            $this->log->logActions($_SESSION['user']['email'], 'Edit Handover', 'Fail Edit', 'Handover ID  :' . $id . ' Edit values: ' + implode(', ', $arr));
            return (FALSE);
        }
    }

//Edit Handover

    /*     * **************end edit cnoc Handover


      /*
     * 
     * Pick Activity 
     * for the current session
     */

    public function cnocPickHandoverS() {

        //return $_POST['action'];
        if ($_POST['action'] != 'CNOC_pickHandover' or (htmlentities($_SESSION['user']['email'], ENT_QUOTES))=="dsm_r" OR !isset($_SESSION['user']['email'])) {
            return FALSE; //se;//"Invalid action supplied for forget password Form.";
        }
        $uname = htmlentities($_SESSION['user']['email'], ENT_QUOTES);
        $id = htmlentities($_POST['Handoverid'], ENT_QUOTES);

$stat=$_POST['stat'];//<span class="label label-info">In Progress</span>


        $sql = "UPDATE cnoc_handovers set assigned_to=:uname,`handover_status`=:status  ,`Last_update`=now()  where handover_id=:id";
        try {
		if($stat=='<span class="label label-important">Pending</span>'){
			 $sql = "UPDATE cnoc_handovers set assigned_to=:uname,`handover_status`=:status  ,`Last_update`=now()  where handover_id=:id";
                        $countRows = $this->query($sql, array("id" => $id, "uname" => $uname, "status" => "In Progress"));
}
else{
 $sql = "UPDATE cnoc_handovers set assigned_to=:uname ,`Last_update`=now()  where handover_id=:id";
                        $countRows = $this->query($sql, array("id" => $id, "uname" => $uname));
}

          //  $countRows = $this->query($sql, array("id" => $id, "uname" => $uname, "status" => "In Progress"));
            //var_dump($countRows);
            if ($countRows > 0) {

                $this->alertObj->setAlert('UPDATE', 'cnoc_handovers', $id, 'Handover ID  :' . $id . ' picked');
                $this->log->logActions($_SESSION['user']['email'], 'Pick Handover', 'Success Pick', 'Handover ID  :' . $id . ' picked');

                return $_SESSION['user']['email'] . '<a href="javascript:;" class="btn unpick-handover" id="pickbtn_' . $_POST['Handoverid'] . '"  data-id="' . $id . '">unPick It</a>';
                ;
            } else {
                return FALSE;
            }
            //success
        } catch (Exception $e) {

            $this->log->logActions($_SESSION['user']['email'], 'Pick Hnadover', 'Fail Pick', 'Handover ID  :' . $id . ' picked');
            return (FALSE);
        }
    }

//pick activity
    /*
     * Unpick activit
     * 
     */

    public function cnocUnPickHandover() {

        //return $_POST['action'];
        if ($_POST['action'] != 'CNOC_unpickHandover' or (htmlentities($_SESSION['user']['email'], ENT_QUOTES))=="dsm_r" OR !isset($_SESSION['user']['email'])) {
            return FALSE; //se;//"Invalid action supplied for forget password Form.";
        }
        $uname = htmlentities($_SESSION['user']['email'], ENT_QUOTES);
        $id = htmlentities($_POST['Handoverid'], ENT_QUOTES);
$stat=$_POST['stat'];



        $sql = "UPDATE cnoc_handovers set assigned_to=null,`handover_status`=:status,`Last_update`=now()  where handover_id=:id";

        try {

if($stat=='<span class="label label-info">In Progress</span>')
{
$sql = "UPDATE cnoc_handovers set assigned_to=null,`handover_status`=:status,`Last_update`=now()  where handover_id=:id";

 $countRows = $this->query($sql, array("id" => $id, "status" => "Pending"));
}else
{
	$sql = "UPDATE cnoc_handovers set assigned_to=null,`Last_update`=now()  where handover_id=:id";

 $countRows = $this->query($sql, array("id" => $id));

}

          //  $countRows = $this->query($sql, array("id" => $id, "status" => "Pending"));
            //var_dump($countRows);
            if ($countRows > 0) {

                $this->alertObj->setAlert('UPDATE', 'cnoc_handovers', $id, 'Handover ID  :' . $id . ' Upicked');
                $this->log->logActions($_SESSION['user']['email'], 'UnPick Handover', 'Success unPick', 'Handover ID  :' . $id . ' unpicked');

                return '<a href="javascript:;" class="btn pick-handover" id="pickbtn_' . $_POST['Handoverid'] . '" data-id="' . $id . '">Pick It</a>';
            } else {
                return FALSE;
            }
            //success
        } catch (Exception $e) {

            $this->log->logActions($_SESSION['user']['email'], 'unPick Handover', 'Fail unPick', 'Handover ID  :' . $id . ' unpicked');
            return (FALSE);
        }
    }
    
    
    private function cnocUnPickHandover2($idp) {

        //return $_POST['action'];
      
       
        $id = $idp;


        try {


	$sql = "UPDATE cnoc_handovers set assigned_to=null,`Last_update`=now()  where handover_id=:id";

 $countRows = $this->query($sql, array("id" => $id));



          //  $countRows = $this->query($sql, array("id" => $id, "status" => "Pending"));
            //var_dump($countRows);
            if ($countRows > 0) {

                $this->alertObj->setAlert('UPDATE', 'cnoc_handovers', $id, 'Handover ID  :' . $id . ' Upicked');
                $this->log->logActions($_SESSION['user']['email'], 'UnPick Handover', 'Success unPick', 'Handover ID  :' . $id . ' unpicked');

                return '<a href="javascript:;" class="btn pick-handover" id="pickbtn_' . $_POST['Handoverid'] . '" data-id="' . $id . '">Pick It</a>';
            } else {
                return FALSE;
            }
            //success
        } catch (Exception $e) {

            $this->log->logActions($_SESSION['user']['email'], 'unPick Handover', 'Fail unPick', 'Handover ID  :' . $id . ' unpicked');
            return (FALSE);
        }
    }


//pick activity




    /*     * ****************
     * cnocGetHandoverDetails
     * to get activity details using its id
     * retrun json data
     */

    public function cnocGetHandoverDetails() {


        $serial = $_POST['handoverid'];

        if ($_POST['action'] != 'CNOC_getHandoverDetails' OR !isset($_SESSION['user']['email'])) {
            return false; //"Invalid action supplied for retrive Activity Data.";
        }
        $sql = 'SELECT `handover_id`, `SM_ticket`, `Cust_code`, `host_name`, `handover_Category`, `CMS/DKT`, `Last_update`, `close_action`, `create_time`, `complete_time`, `last_assign_time`, `created_by`, `assigned_to`, `handover_status`, `Closed_by`, `IM_Startdate` FROM `cnoc_handovers` WHERE `handover_id`=:ser';
        $msg = '';
        try {

            $result = $this->row($sql, array('ser' => $serial));
            if ($result > 0) {
                $_SESSION['Handoverid_sess'] = $serial;

                // $msg= 'true ';// Items inserted Before'.implode(' || ',$_srInserted);//array("phone"=>'123-12313',"email"=>'test@test.com','city'=>'Medicine Hat','address'=>'556 19th Street NE')
                echo json_encode(array("TTid" => $result['SM_ticket'], "custname" => $result['Cust_code'], "hostname" => $result['host_name'], "Category" => $result['handover_Category'], "CMS_DKT" => $result['CMS/DKT'], "_Startdate" => $result['IM_Startdate'], "reqby" => $result['created_by'], "status" => $result['handover_status'], "assignedto" => $result['assigned_to'], "completetime" => $result['complete_time'], "close_action" => $result['close_action']));
            } else {
                return FALSE;
            }
        } catch (PDOException $e) {

            return( 'error pdo ' . $e->getMessage());
        }
        //echo json_encode(array("phone"=>'123-12313',"email"=>'test@test.com','city'=>'Medicine Hat','address'=>'556 19th Street NE'));
        //return;
    }

    /*
     * Check if inserted before
     */

    public function CNOC_handoverInsertedBefore() {

        if ($_POST['action'] != 'checkHandoverInsertedBefore' OR !isset($_SESSION['user']['email'])) {
            return "Invalid action supplied for Check Inserted Before";
        }
        $found = false;

        $TTSer = htmlentities(trim($_POST['serial']));
        // Fetching single value
        $ID = $this->single("SELECT `SM_ticket` FROM `cnoc_handovers` WHERE `SM_ticket`= :id ", array('id' => $TTSer));
        return $this->sQuery->rowcount();
        if ($this->sQuery->rowcount() > 0) {
            $found = true;
        }

        return $found;
    }

    /*
     * get ticket activity details by using im ticket
     * 
     * 
     */

    public function cnocGetHandoverDetailsByIM() {


        $serial = htmlentities(trim($_POST['Handoverid']));

        if ($_POST['action'] != 'CNOC_getHandoverDetailsByIM' OR !isset($_SESSION['user']['email'])) {
            return false; //"Invalid action supplied for retrive Activity Data.";
        }
        $sql = 'SELECT `handover_id`, `SM_ticket`, `Cust_code`, `host_name`, `handover_Category`, `CMS/DKT`, `Last_update`, `close_action`, `create_time`, `complete_time`, `last_assign_time`, `created_by`, `assigned_to`, `handover_status`, `Closed_by`, `IM_Startdate` FROM `cnoc_handovers` WHERE `SM_ticket`=:ser';
        $msg = '';
      // var_dump($serial);
         $this->log->logActions($_SESSION['user']['email'], ' New Cnoc Handover Search By IM', 'Succes add ', 'DATA :IM =>' . $serial );
        try {

            $result = $this->row($sql, array('ser' => $serial));
            //var_dump($result);
            if ($result > 0) {
             //   echo'test';
                $_SESSION['Handoverid_sess'] = $serial;

                // $msg= 'true ';// Items inserted Before'.implode(' || ',$_srInserted);//array("phone"=>'123-12313',"email"=>'test@test.com','city'=>'Medicine Hat','address'=>'556 19th Street NE')
                echo json_encode(array("TTid" => $result['SM_ticket'], "custname" => $result['Cust_code'], "hostname" => $result['host_name'], "Category" => $result['handover_Category'], "CMS_DKT" => $result['CMS/DKT'], "_Startdate" => $result['IM_Startdate'], "reqby" => $result['created_by'], "status" => $result['handover_status'], "assignedto" => $result['assigned_to'], "completetime" => $result['complete_time'], "close_action" => $result['close_action']));
            } else {
                return FALSE;
            }
        } catch (PDOException $e) {

            return( 'error pdo ' . $e->getMessage());
        }
    }

//end get ativity by using im number

    public function cnocGetActivityAssignHist() {// Connection data (server_address, database, name, poassword)
        if ($_POST['action'] != 'CNOC_getActivityAssignHist' OR !isset($_SESSION['user']['email'])) {
            return false; //"Invalid action supplied for retrive Activity assign History Data.";
        }
        if (isset($_POST['Activityid'])) {
            $Activityid = htmlentities($_POST['Activityid'], ENT_QUOTES);

            $sql = "SELECT `user` ,`assign_time`FROM `cnoc_activity_assignhistory` WHERE `activity_id`=:activityid order by assign_time desc ";
            $html = "";
            $result = array();
            try {
                $result = $this->query($sql, array('activityid' => $Activityid), PDO::FETCH_OBJ);
            } catch (PDOException $e) {

                return($e->getMessage());
            }
            return json_encode($result);
        }
    }

    public function cnocGetHandoverAssignHist() {// Connection data (server_address, database, name, poassword)
        if ($_POST['action'] != 'CNOC_getHandoverAssignHist' OR !isset($_SESSION['user']['email'])) {
            return false; //"Invalid action supplied for retrive Activity assign History Data.";
        }
        if (isset($_POST['Handoverid'])) {
            $HandoverID = htmlentities($_POST['Handoverid'], ENT_QUOTES);

            $sql = "SELECT `user` ,`assign_time`FROM `cnoc_handover_assignhistory` WHERE `handover_id`=:handoverid order by assign_time desc ";
            $html = "";
            $result = array();
            try {
                $result = $this->query($sql, array('handoverid' => $HandoverID), PDO::FETCH_OBJ);
            } catch (PDOException $e) {

                return($e->getMessage());
            }
            return json_encode($result);
        }
    }

   

    public function cnocAddHandoverRemarks() {// Connection data (server_address, database, name, poassword)
        /*
         * inproper action call
         */
        if ($_POST['action'] != 'CNOC_AddHandoverRemark' or (htmlentities($_SESSION['user']['email'], ENT_QUOTES))=="dsm_r" OR !isset($_SESSION['user']['email'])) {
            return "Invalid action supplied for process Add new Handover Remarks.";
        }

        $HandoverRemarks = nl2br(htmlentities($_POST['HandoverRemarks'], ENT_QUOTES));

        $HandoverID = (htmlentities($_SESSION['Handoverid_sess'], ENT_QUOTES));
        $ReqBy = (htmlentities($_SESSION['user']['email'], ENT_QUOTES));

        $sql = "INSERT INTO `cnoc_handoversremarks`( handover_id,`Remark_TXT`, `Editor`) VALUES (:HandoverId,:remark,:by)";
        //$this->lastInsertId();
        try {
            $res = $this->query($sql, array("remark" => $HandoverRemarks, "by" => $ReqBy, "HandoverId" => $HandoverID));
            $this->log->logActions($_SESSION['user']['email'], ' New Cnoc Handover Remark', 'Succes ad d ', 'DATA :remark =>' . $HandoverRemarks . ', by => ' . $ReqBy . ',  for activityId =>' . $HandoverID);
        } catch (Exception $e) {
            // $this->db=null;
            $this->log->logActions($_SESSION['user']['email'], 'Add New Cnoc Handover', 'Faild Delete due to ' . $e->getMessage(), 'DATA :remark =>' . $HandoverRemarks . ', by => ' . $ReqBy . ',  for HandoverId =>' . $HandoverID);
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

//add new Handover remarkS
//end of cnocAddNewActivity function

    public function cnocGetHandoverRemarks() {// Connection data (server_address, database, name, poassword)
        if ($_POST['action'] != 'CNOC_getHandoverRemarks' OR !isset($_SESSION['user']['email'])) {
            return false; //"Invalid action supplied for retrive Activity assign History Data.";
        }
        if (isset($_POST['Handoverid']) or isset($_SESSION['Handoverid_sess'])) {
            $Handoverid = htmlentities($_POST['Handoverid'], ENT_QUOTES) == null ? $_SESSION['Handoverid_sess'] : htmlentities($_POST['Handoverid'], ENT_QUOTES);

            $sql = "SELECT  `Remark_TXT`, `Editor`, `Remark_tTme` FROM `cnoc_handoversremarks` WHERE handover_id=:handoverid order by Remark_tTme desc ";
            $html = "";
            $result = array();
            try {
                $result = $this->query($sql, array('handoverid' => $Handoverid), PDO::FETCH_OBJ);
            } catch (PDOException $e) {

                return($e->getMessage());
            }
            return json_encode($result);
        }
    }

    public function cnocGetHandoverAttaches() {// Connection data (server_address, database, name, poassword)
        if ($_POST['action'] != 'CNOC_getHandoverAttachs' OR !isset($_SESSION['user']['email'])) {
            return false; //"Invalid action supplied for retrive Activity assign History Data.";
        }
        if (isset($_POST['Handoverid']) or isset($_SESSION['Handoverid_sess'])) {
            $Handoverid = htmlentities($_POST['Handoverid'], ENT_QUOTES) == null ? $_SESSION['Handoverid_sess'] : htmlentities($_POST['Handoverid'], ENT_QUOTES);

            $sql = "SELECT  * FROM `handover_attachedfiles` WHERE Handover_id=:handoverid order by Attach_time desc ";
            $html = "";
            $result = array();
            try {
                $result = $this->query($sql, array('handoverid' => $Handoverid), PDO::FETCH_OBJ);
            } catch (PDOException $e) {

                return($e->getMessage());
            }
            return json_encode($result);
        }
    }

    public function handover_insertupload() {// Connection data (server_address, database, name, poassword)
        if ($_POST['action'] != 'handover_attach' or (htmlentities($_SESSION['user']['email'], ENT_QUOTES))=="dsm_r" OR !isset($_SESSION['user']['email'])) {
            return "Invalid action supplied for Attach file.";
        }
        // var_dump($_POST);
        $fName = trim('assets/comm/uploads/handOver/' . $_POST['fileName']);
        if ($fName == NULL) {
            return "Invalid File Name supplied .";
        }
 $this->log->logActions($_SESSION['user']['email'], ' New Cnoc Handover Upload File', 'Succes add ', 'DATA :File Name =>' . $_POST['fileName'] );
        // echo '<br/> fname : '.$fName.'<br/>';
        try {
            //$handle = fopen($fName, "r");S

            $c = -1;
            //  $_sql = array();
            // chmod('../comm/fileuplaod_temp/', 0777);
//$_POST['fileName']
            $sql = 'INSERT INTO `handover_attachedfiles`( `Attach_or_name`, `Attach_Location`, `uploaded_By`,`Handover_id`) VALUES (:attachname,:location,:upby,:handoverid)';

            try {
                // unlink($fName);
                $arrs = array("attachname" => $_POST['orgfileName'], "location" => $fName, "upby" => $_SESSION['user']['email'], "handoverid" => $_POST['handoverid']);
                $result = $this->query($sql, $arrs);
                if ($result > 0) {

                    $this->log->logActions($_SESSION['user']['email'], 'Upload New Handover Attach', 'Success Upload', 'DATA : added');

                    return TRUE;
                } else {
                    return FALSE;
                }
            } catch (PDOException $e) {
                unlink($fName);
                $this->log->logActions($_SESSION['user']['email'], 'Upload New Handover Attach', 'error pdo ' . $e->getMessage(), 'DATA :' . implode(',', $sql) . ' Not added');
                return('error pdo ' . $e->getMessage());
            }
        } catch (Exception $ex) {
            unlink($fName);
            return('error opening ' . $ex->getMessage());
        }
    }

//end of handover_insertupload

    /*     * *********get all aactivities json ****** */

    //($_REQUEST['from'],$_REQUEST['to'],$_REQUEST['FilterName'],$_REQUEST['Operator'],$_REQUEST['FilterValue']));
    public function cnocGetHandoverJson($from = null, $to = null, $filterName = null, $Operator = null, $FilterValue = null) {// Connection data (server_address, database, name, poassword)
        $option = '';
        if ($filterName == null and $from == null and $to == null) {
           // $option = " WHERE STR_TO_DATE(`IM_Startdate`, '%d/%m/%Y')='" . date('Y-m-d') . "'";
$option = " WHERE STR_TO_DATE(`IM_Startdate`, '%d/%m/%Y')='" . date('Y-m-d') . "' or (activity_status!='Completed' and cust_top34.`TOP34`='TOP 34'  and STR_TO_DATE(`IM_Startdate`, '%d/%m/%Y')<='" . date('Y-m-d') . "')";//`activity_status`, `Closed_by` ,cust_top34.`TOP34`
        } else {
            $option = " WHERE STR_TO_DATE(replace(IM_Startdate,'/',','),'%d,%m,%Y %T')>= STR_TO_DATE(replace('" . $from . "','/',','),'%m,%d,%Y %T') and STR_TO_DATE(replace(IM_Startdate,'/',','),'%d,%m,%Y %T')<= STR_TO_DATE(replace('" . $to . "','/',','),'%m,%d,%Y %T')";
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
                case 'Category':
                    $additionalFilters = ' handover_Category';
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
                case 'Status':
                    $additionalFilters = ' activity_status';
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


        $sql = "SELECT `ttr`, `handover_id`, `SM_ticket`, `Cust_code`, `host_name`, `handover_Category`, `IM_Startdate`, `CMS_DKT`, `close_action`, `create_time`, `complete_time`, `last_assign_time`, `created_by`, `assigned_to`, `activity_status`, `Closed_by` ,cust_top34.`TOP34` FROM `cnoc_handovers_v` left JOIN cust_top34 ON cust_top34.customer_code=cnoc_handovers_v.Cust_code " . $option . "  ORDER BY STR_TO_DATE(replace(IM_Startdate,'/',','),'%d,%m,%Y %T') ASC ";
        $html = "";
        //var_dump($sql);
        $result = array();
        try {
            $result = $this->query($sql, array(), PDO::FETCH_OBJ);
        } catch (PDOException $e) {

            return($e->getMessage());
        }
        return '{"handovers":' . json_encode($result) . '}'; //json_encode($result);
    }

    /*     * *******************************************************
     * 
     * Delete Cnoc Handover provided by his ID 
     * ************************************************
     */

    public function cnocdeleteHandover() {
        /*
         * Fails if the proper action was not submitted
         */
        // echo "<script>alert('".$_POST['input-username']."')</script>";
        if ($_POST['action'] != 'CNOC_removeHandover' or (htmlentities($_SESSION['user']['email'], ENT_QUOTES))=="dsm_r" OR !isset($_SESSION['user']['email'])) {
            return "Invalid action supplied for process Delete Handover.";
        }
        /*
         * Escapes the user input for security
         */
        $Actvid = htmlentities($_POST['Handoverid'], ENT_QUOTES);


        /*
         * Retrieves the matching info from the DB if it exists
         */
        $sql = "DELETE FROM `cnoc_handovers` WHERE `handover_id`=:ser ";
        try {
            $res = $this->query($sql, array("ser" => $Actvid));
            $this->alertObj->setAlert('DELETE', 'cnoc_handovers', $Actvid, 'Handover ID  :' . $Actvid . ' Deleted');
            $this->log->logActions($_SESSION['user']['email'], 'Delete CNOC Handover', ' Delete success ', 'Handover id :' . $Actvid . ' deleted');
        } catch (Exception $e) {
            // $this->db=null;
            $this->log->logActions($_SESSION['user']['email'], 'Delete Handover', 'Faild Delete due to ' . $e->getMessage(), 'Handover Id:' . $Actvid . ' ');
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

//cnoc Delete Handover
    
    /*     * *******************************************************
     * 
     * Delete Cnoc Handover provided by his ID 
     * ************************************************
     */

    public function cnocdeleteHandoverAttach() {
        /*
         * Fails if the proper action was not submitted
         */
        // echo "<script>alert('".$_POST['input-username']."')</script>";
        if ($_POST['action'] != 'CNOC_removeHandoverAttach' or (htmlentities($_SESSION['user']['email'], ENT_QUOTES))=="dsm_r" OR !isset($_SESSION['user']['email'])) {
            return "Invalid action supplied for process Delete Handover Attach.";
        }
        /*
         * Escapes the user input for security
         */
        $Actvid = htmlentities($_POST['Handoverid'], ENT_QUOTES);


        /*
         * Retrieves the matching info from the DB if it exists
         */
        $sql = "DELETE FROM `handover_attachedfiles` WHERE `HandoverAttach_id`=:ser ";
        try {
            $res = $this->query($sql, array("ser" => $Actvid));
            //$this->alertObj->setAlert('DELETE', 'cnoc_Handover', $Actvid, 'Handover Attach ID  :' . $Actvid . ' Deleted');
            $this->log->logActions($_SESSION['user']['email'], 'Delete CNOC Handover', ' Delete success ', 'Handover Attach id :' . $Actvid . ' deleted');
        } catch (Exception $e) {
            // $this->db=null;
            $this->log->logActions($_SESSION['user']['email'], 'Delete Handover Attach', 'Faild Delete due to ' . $e->getMessage(), 'Handover Id:' . $Actvid . ' ');
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

//cnoc Delete Handover attach

    public function cnocGetHandoverStatusPercentage() {
        /*
          if ($_POST['action'] != 'CNOC_getActivityStatusPrecentage') {
          return false ;//"Invalid action supplied for retrive Activity assign History Data.";
          } */



        $sql = "SELECT `cc` as `y`, `activity_status` as `name` FROM cnoc_handoverstatus_percentage ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql, array()); // PDO::FETCH_OBJ
        } catch (PDOException $e) {

            return($e->getMessage());
        }

        /*  defulat color  $rows = array();
          foreach ($result as $r)
          {
          $row[0] = $r['name'];
          $row[1] = $r['y'];;
          array_push($rows,$row);

          }


          return
          json_encode($rows, JSON_NUMERIC_CHECK);

         */
        return '{"activities":' . json_encode($result) . '}'; //json_encode($result);
    }

//cnocGetActivityStatusPercentage
}
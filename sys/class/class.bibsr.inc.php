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
class bibsr extends DB_Connect {

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
    
    public function clean($string) {
   $string = str_replace(' ', '', $string); // Replaces all spaces with hyphens.

   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}

    public function bibAddNewSR() {// Connection data (server_address, database, name, poassword)
        /*
         * inproper action call
         */

        //  echo "test";
        // print_r($_POST);
        // return "hot hot ";die;
        if ($_POST['action'] != 'bib_AddSR' OR ( htmlentities($_SESSION['user']['email'], ENT_QUOTES)) == "dsm_r" OR ! isset($_SESSION['user']['email'])) {
            return "Invalid action supplied for process Add new BIB SR.";
        }

        $sr_tt = (htmlentities($_POST['txt-bib-sr_tt'], ENT_QUOTES));
        $handovercustName = (strtolower(htmlentities($_POST['select-bib-handovercustName'], ENT_QUOTES)) == 'other' ? htmlentities($_POST['txt-bib-handovercustName'], ENT_QUOTES) : htmlentities($_POST['select-bib-handovercustName'], ENT_QUOTES));
      //  $handoverhostName = (strtolower(htmlentities($_POST['select-bib-handoverhostName'], ENT_QUOTES)) == 'other' ? htmlentities($_POST['txt-bib-handoverhostName'], ENT_QUOTES) : htmlentities($_POST['select-bib-handoverhostName'], ENT_QUOTES));
        //mss-companynameandaccountnumber-region-bib   example : mss-ethihadmodernart25641801-auh-bib
            
        //  $activityReason = (strtolower(htmlentities($_POST['select-cnoc-activityReason'], ENT_QUOTES)) == 'other' ? htmlentities($_POST['txt-cnoc-activityReason'], ENT_QUOTES) : htmlentities($_POST['select-cnoc-activityReason'], ENT_QUOTES));

$statusName = (strtolower(htmlentities($_POST['select-bib-srstatus'], ENT_QUOTES)) == 'other' ? htmlentities($_POST['select-bib-srstatus'], ENT_QUOTES) : htmlentities($_POST['select-bib-srstatus'], ENT_QUOTES));

        $olt_name = (htmlentities($_POST['select-bib-olt_name'], ENT_QUOTES));
        $zone_name = (htmlentities($_POST['select-bib-zone_name'], ENT_QUOTES));


        $subnet_name = (htmlentities($_POST['select-bib-subnet_name'], ENT_QUOTES));
        $gateway = (htmlentities($_POST['txt-bib-gateway'], ENT_QUOTES));
        $sr_IP = (htmlentities($_POST['select-bib-sr_IP'], ENT_QUOTES));
        $sr_Date = (htmlentities($_POST['txt-bib-sr_Date'], ENT_QUOTES));
        $WONumber = (htmlentities($_POST['txt-bib-WONumber'], ENT_QUOTES));

//$WONumber = (strtolower(htmlentities($_POST['txt-bib-WONumber'], ENT_QUOTES)) == '' ? htmlentities($_POST['txt-cnoc-activityReason'], ENT_QUOTES) : htmlentities($_POST['select-cnoc-activityReason'], ENT_QUOTES));
        $AccountNo = (htmlentities($_POST['txt-bib-AccountNo'], ENT_QUOTES));
        $SerialNumber = (htmlentities($_POST['txt-bib-SerialNumber'], ENT_QUOTES));
        $AccountNo = (htmlentities($_POST['txt-bib-AccountNo'], ENT_QUOTES));
        $ONTSerialNumber = (htmlentities($_POST['txt-bib-ONTSerialNumber'], ENT_QUOTES));
        $EID = (htmlentities($_POST['txt-bib-EID'], ENT_QUOTES));
$handovercustName2= $this->clean($handovercustName);
$handovercustName2= strlen($handovercustName2)>=15?substr($handovercustName2, 0,15):$handovercustName2;


$handoverhostName =strtolower( 'mss-'.$handovercustName2.$AccountNo.'-'.$zone_name.'-bib');
        $ReqBy = (htmlentities($_SESSION['user']['email'], ENT_QUOTES));


        $HandoverRemarks = nl2br(htmlentities($_POST['HandoverRemarks'], ENT_QUOTES));
        if ($HandoverRemarks == '') {
            $HandoverRemarks = null;
        }
        /*
         * INSERT INTO `cnoc_handovers`(`handover_id`, `SM_ticket`, `Cust_code`, `host_name`, `handover_Category`, `CMS/DKT`, `Last_update`, `close_action`, `create_time`, `complete_time`, `last_assign_time`, `created_by`, `assigned_to`, `handover_status`, `Closed_by`, `IM_Startdate`)
         */


        //  INSERT INTO `bib_srs`(`sr_id`, `sr_ticket`, `Cust_code`, `host_name`, `zone_name`, `olt_name`, `Subnet-Mask`, `gateway`, `ONT_Serial_Number`, `IP_address`, `Serial_Number`, `WO_Number`, `Account_No`, `sr_date`, `EID`, `Last_update`, `close_action`, `create_time`, `complete_time`, `last_assign_time`, `created_by`, `assigned_to`, `sr_status`, `Closed_by`) VALUES();

        $sql = "INSERT INTO `bib_srs`(sr_status,`sr_ticket`, `Cust_code`, `host_name`, `zone_name`, `olt_name`, `Subnet-Mask`, `gateway`, `ONT_Serial_Number`, `IP_address`, `Serial_Number`, `WO_Number`, `Account_No`, `sr_date`, `EID`,`created_by`) VALUES (:srstatus,:sr_ticket, :Cust_code, :host_name, :zone_name, :olt_name, :Subnet_Mask, :gateway, :ONT_Serial_Number, :IP_address, :Serial_Number, :WO_Number, :Account_No, :sr_date, :EID,:created_by)";
        //$this->lastInsertId();
        $params = array("srstatus"=>$statusName, "sr_ticket" => $sr_tt, "Cust_code" => $handovercustName, "host_name" => $handoverhostName, "zone_name" => $zone_name, "olt_name" => $olt_name, "Subnet_Mask" => $subnet_name, "gateway" => $gateway, "ONT_Serial_Number" => $ONTSerialNumber, "IP_address" => $sr_IP, "Serial_Number" => $SerialNumber, "WO_Number" => $WONumber, "Account_No" => $AccountNo, "sr_date" => $sr_Date, "EID" => $EID, "created_by" => $ReqBy);
        try {
            $res = $this->query($sql, $params);


            $HandoverID = $this->lastInsertId();
            /*             * *****adding new remark************ */

            $sql_r = "INSERT INTO `bib_srremarks`( `sr_id`, `Remark_TXT`, `Editor`) VALUES (:activityId,:remark,:by)";
            //$this->lastInsertId();
            try {
                $res = $this->query($sql_r, array("remark" => $HandoverRemarks, "by" => $ReqBy, "activityId" => $HandoverID));
                $this->log->logActions($_SESSION['user']['email'], ' New BIB SR Remark', 'Succes add ', 'DATA :remark =>' . $HandoverRemarks . ', by => ' . $ReqBy . ',  for activityId =>' . $HandoverID);
            } catch (Exception $e) {
                // $this->db=null;
                $this->log->logActions($_SESSION['user']['email'], 'Add New BIB SR', 'Faild Add due to ' . $e->getMessage(), 'DATA :remark =>' . $ActivityRemarks . ', by => ' . $ReqBy . ',  for activityId =>' . $activityID);
                return ($e->getMessage());
            }



            $this->alertObj->setAlert('INSERT', 'BIBSR', null, 'DATA :' . implode(',', $params));
            $this->log->logActions($_SESSION['user']['email'], ' New BIB SR', 'Succes add  ', 'DATA :' . implode(',', $params) . '');
        } catch (Exception $e) {
            // $this->db=null;
            $this->log->logActions($_SESSION['user']['email'], 'Add New BIB SR', 'Faild Add due to ' . $e->getMessage(), 'Data :');
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

    public function biuldCnocDailySRTable($show = 'my', $cat = 'all') {// Connection data (server_address, database, name, poassword)
        $condit = '';
        $arr = array();
        if ($show == 'my') {

            $condit = ' WHERE assigned_to=:user or created_by=:user1 ';
            $arr = array('user1' => $_SESSION['user']['email'], 'user' => $_SESSION['user']['email']);
        } elseif ($show == 'all') {


            if (( $_SESSION['teamName'] == 'supperAdmins')) {
                
            } else {


                //$condit = " where handover_status !='Completed' ";
            }

//            if ($cat != 'all') {
//                if ($cat == "TEAM_BO") {
//                    $condit = " where handover_status !='Completed'  and (handover_Category not in ('NODE DOWN','LAN DOWN','LAN BGP ISSUE','Interconnect Link Down','LAN FLAP','LAN BGP ISSUE','NNM Issue','INTAKE TEMPERATURE','Interconnect Link Down') and handover_status !='Pending Customer' )";
//                }//if TEAM_BO
//                elseif ($cat == "TEAM_SD") {
//                    $condit = " where handover_status !='Completed'  and (handover_Category in ('NODE DOWN','Interconnect Link Down','LAN BGP ISSUE','LAN FLAP','Interconnect Link Down','LAN BGP ISSUE','NNM Issue','LAN DOWN','INTAKE TEMPERATURE','Interconnect Link Down') or handover_status ='Pending Customer') ";
//                }//TEAM_SD
//                else {
//
//                    $str_cat = (htmlentities($cat, ENT_QUOTES));
//                    $str_cat = str_replace("_.._", " ", $str_cat);
//
//                    $condit = " where handover_status !='Completed'  and handover_Category='" . $str_cat . "'";
//                }
//            } else {
//                if (( $_SESSION['teamName'] == 'supperAdmins')) {
//                    
//                } else {
//
//
//                    $condit = " where handover_status !='Completed' ";
//                }
//            }
        } elseif ($show == 'today') {
            if ($cat != 'all') {
//                if ($cat == "TEAM_BO") {
//                    $condit = " WHERE STR_TO_DATE(`IM_Startdate`, '%d/%m/%Y')='" . date('Y-m-d') . "' and (handover_Category not in ('NODE DOWN','LAN FLAP','NNM Issue','LAN DOWN','INTAKE TEMPERATURE','Interconnect Link Down') and handover_status !='Pending Customer' )";
//                }//if TEAM_BO
//                elseif ($cat == "TEAM_SD") {
//                    $condit = " WHERE STR_TO_DATE(`IM_Startdate`, '%d/%m/%Y')='" . date('Y-m-d') . "' and (handover_Category in ('NODE DOWN','Interconnect Link Down','LAN BGP ISSUE','LAN FLAP','NNM Issue','LAN DOWN','INTAKE TEMPERATURE','Interconnect Link Down') or handover_status ='Pending Customer' )";
//                }//TEAM_SD
//                else {
//
//                    $str_cat = (htmlentities($cat, ENT_QUOTES));
//                    $str_cat = str_replace("_.._", " ", $str_cat);
//
//                    $condit = " WHERE STR_TO_DATE(`IM_Startdate`, '%d/%m/%Y')='" . date('Y-m-d') . "' and handover_Category='" . $str_cat . "'";
//                }
            } else {
                $condit = " WHERE STR_TO_DATE(`sr_date`, '%d/%m/%Y')='" . date('Y-m-d') . "' ";
            }


             $condit = " WHERE sr_date='" . date('Y-m-d') . "' ";
        }


        /* if(isset($_REQUEST['show']) && $_REQUEST['show']=='all'){$condit=' ';}
          elseif(isset($_REQUEST['show']) && $_REQUEST['show']=='today'){$condit=" WHERE STR_TO_DATE(`activity_date`, '%d/%m/%Y')='".date('Y-m-d')."' ";}
          else {$condit=' WHERE assigned_to=:user or created_by=:user1 ';} */
        //$sql = "SELECT `handover_id`, `SM_ticket`, `Cust_code`, `host_name`, `handover_Category`, `CMS/DKT`, `Last_update`, `close_action`, `create_time`, `complete_time`, `last_assign_time`, `created_by`, `assigned_to`, `handover_status`, `Closed_by`, `IM_Startdate` FROM `cnoc_handovers` " . $condit . " ORDER BY STR_TO_DATE(replace(IM_Startdate,'/',','),'%d,%m,%Y %T') ASC";
        //SELECT `sr_id`, `sr_ticket`, `Cust_code`, `host_name`, `zone_name`, `olt_name`, `Subnet-Mask`, `gateway`, `ONT_Serial_Number`, `IP_address`, `Serial_Number`, `WO_Number`, `Account_No`, `sr_date`, `EID`, `Last_update`, `close_action`, `create_time`, `complete_time`, `last_assign_time`, `created_by`, `assigned_to`, `sr_status`, `Closed_by` FROM `bib_srs`
//$sql = "SELECT `handover_id`, `SM_ticket`, `Cust_code`, `host_name`, `handover_Category`, `CMS/DKT`, `Last_update`, `close_action`, `create_time`, `complete_time`, `last_assign_time`, `created_by`, `assigned_to`, `handover_status`, `Closed_by`, `IM_Startdate` ,cust_top34.`TOP34`,`cust_vip`.`vip_` FROM `cnoc_handovers` left JOIN cust_vip ON (cust_vip.Customer_code)=(cnoc_handovers.Cust_code) left JOIN cust_top34 ON cust_top34.customer_code=cnoc_handovers.Cust_code " . $condit . " ORDER BY STR_TO_DATE(replace(IM_Startdate,'/',','),'%d,%m,%Y %T') ASC";
        $sql = "SELECT `sr_id`, `sr_ticket`, `Cust_code`, `host_name`, `zone_name`, `olt_name`, `Subnet-Mask`, `gateway`, `ONT_Serial_Number`, `IP_address`, `Serial_Number`, `WO_Number`, `Account_No`, `sr_date`, `EID`, `Last_update`, `close_action`, `create_time`, `complete_time`, `last_assign_time`, `created_by`, `assigned_to`, `sr_status`, `Closed_by` FROM `bib_srs`  " . $condit . " ORDER BY sr_date ASC";
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
<th>SNO</th>
<th>Date</th>
<th>SR Number</th>
<th>WO Number</th>
<th>Account No</th>
<th>Customer</th>
<th>Hostname</th>
<th>Serial Number</th>
<!--<th>ZONE</th>-->
<th>OLT</th>
<th>IP address</th>
<th>Gateway</th>
<th>Subnet</th>
<th>ONT Serial Number</th>
<<!--th>EID</th>-->
<th>Status</th>
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
            $CustLabel = '';
            $CustType = '';
            $CustType2 = '';
            $MGM_esclat = '';
            $category_VIP = array("IP Black List", "Website Issue", "VPN Issue", "Voice/Video Traffic ");

//            switch ($row['vip_']) {
//                case "VIP":
//                    $CustType2 = 'VIP';
//                    $CustLabel = 'label label-important';
//                    $MGM_esclat = 'bgcolor="#F0FFFF"';
//                    if ($row['handover_status'] == "Pending" and ( in_array($row['handover_Category'], $category_VIP))) {
//                        $row['handover_status'] = "MGMT Escalated";
//                    }
//
//                    break;
//                default: $CustType2 = 'Enterprise';
//                    break;
//            }


//            switch ($row['TOP34']) {
//                case "TOP 34":
//                    $CustType = 'TOP 34';
//                    break;
//                default: $CustType = 'Enterprise';
//                    break;
//            }
            switch ($row['sr_status']) {
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
                    $statusLable = 'label-primary'; //FA0DFE
                    $MGM_esclat = 'bgcolor="#F0FFFF"';
                    break;

                case "Canceled":
                    $statusLable = ''; //#d3d3d3
                    break;
            }//set status color



//
//            switch ($row['handover_Category']) {
//                case "NODE DOWN":
//                    $statusLable2 = 'label-important';
//                    break;
//                case "WAN FLAP":
//                    $statusLable2 = 'label-warning';
//                    break;
//                case "WAN DOWN":
//                    $statusLable2 = 'label-warning';
//                    break;
//                case "BGP Flap":
//                    $statusLable2 = 'label-warning';
//                    break;
//                case "Canceled":
//                    $statusLable2 = 'label-warning'; //#d3d3d3
//                    break;
//            }//set Category color
//display assign button or no 

            $assignedUser = $row['assigned_to'];
          //  if ($row['sr_status'] != 'Completed' AND ( htmlentities($_SESSION['user']['email'], ENT_QUOTES)) != "dsm_r") 
                    if (( htmlentities($_SESSION['user']['email'], ENT_QUOTES)) != "dsm_r") {
                if ($row['assigned_to'] == null or $row['assigned_to'] == 'Completed') {
                    $row['assigned_to'] = '<a href="javascript:;" class="btn pick-sr" id="pickbtn_' . $row['sr_id'] . '"  data-id="' . $row['sr_id'] . '">Pick It</a>';
                } else {
                    if ($_SESSION['user']['email'] == $row['assigned_to']) {
                        $row['assigned_to'] .= '<a href="javascript:;" class="btn unpick-sr" id="pickbtn_' . $row['sr_id'] . '"  data-id="' . $row['sr_id'] . '">unPick It</a>';
                    } elseif ($_SESSION['user']['email'] != $row['assigned_to']) {
                        $row['assigned_to'] .= '<a href="javascript:;" class="btn pick-sr" id="pickbtn_' . $row['sr_id'] . '"  data-id="' . $row['sr_id'] . '">Pick It</a>';
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
/*
 * <th>SNO</th>
<th>Date</th>
<th>SR Number</th>
<th>WO Number</th>
<th>Account No</th>
<th>Customer</th>
<th>Hostname</th>
<th>Serial Number</th>
<th>ZONE</th>
<th>OLT</th>
<th>IP address</th>
<th>Gateway</th>
<th>Subnet</th>
<th>ONT Serial Number</th>
<th>EID</th>
<th>Status</th>
<th>Last Update</th>
<th>Assigned To</th>
<th>Created By</th>
 * 
 */
            $html .= '<tr class="odd gradeX " data-txt="' . $row['sr_id'] . '" ' . $MGM_esclat . '   style="cursor:pointer;"><td>' . $row['sr_id'] . '</td>
               
												
												<td>' . $row['sr_date'] . '</td>
                                                                                                    <td>' . $row['sr_ticket'] . '</td>
												<td>' . $row['WO_Number'] . '</td>
												<td>' . $row['Account_No'] . '</td>
                                                                                             <td class="' . $CustLabel . '">' . $row['Cust_code'] . '</td>
												<td>' . $row['host_name'] . '</td>
												<td>' . $row['Serial_Number'] . '</td>
												<!--<td>' . $row['zone_name'] . '</td>-->
												<td>' . $row['olt_name'] . '</td>
												<td>' . $row['IP_address'] . '</td>
												<td>' . $row['gateway'] . '</td>
												<td>' . $row['Subnet-Mask'] . '</td>
												<td>' . $row['ONT_Serial_Number'] . '</td>
												
												
                                                                                                    <!-- <td>' . $row['EID'] . '</td>-->
												
                                                                                                     <td id=bib_srstatus_' . $row['sr_id'] . '><span class="label ' . $statusLable . '">' . $row['sr_status'] . '</span></td>
                                                                                                    <td id=bib_srLastUpdate_' . $row['sr_id'] . '>' . $row['Last_update'] . '</td>
												<td>' . $row['assigned_to'] . '</td>
												<td>' . $row['created_by'] . '</td>
                                                                                                    
                                                                               <td id=bib_sraction_' . $row['sr_id'] . '>';
           // if ($show == 'my' and $row['handover_status'] != 'Completed') 
            if ($show == 'my'){


                if (true) {//date('d-m-Y',strtotime(str_replace('/', '-',$row['activity_date'])))<= date('d-m-Y'))
                    $html .= '<a class="icon huge view-sr" href="javascript:;" data-id="' . $row['sr_id'] . '"><i class="icon-zoom-in"></i></a>&nbsp;';
                }
                if ((htmlentities($_SESSION['user']['email'], ENT_QUOTES)) != "dsm_r") {
                    $html .= '<a href="javascript:;" class="icon huge delete-sr" data-id="' . $row['sr_id'] . '"><i class="icon-remove"></i></a>';
                }
            } elseif ($show == 'all' and ( ($_SESSION['currentRole'] == 'Admin')or ( $_SESSION['teamName'] == 'supperAdmins')) or TRUE) {
                $html .= ' <a class="icon huge view-sr" href="javascript:;" data-id="' . $row['sr_id'] . '"><i class="icon-zoom-in"></i></a>&nbsp;';

                if ((htmlentities($_SESSION['user']['email'], ENT_QUOTES)) != "dsm_r" and ( ($_SESSION['currentRole'] == 'Admin')or ( $_SESSION['teamName'] == 'supperAdmins'))) {
                    $html .= '<a href="javascript:;" class="icon huge delete-sr" data-id="' . $row['sr_id'] . '"><i class="icon-remove"></i></a>';
                }
            } elseif ($show == 'today') {



                if ($assignedUser == $_SESSION['user']['email'] ) {
                    $html .= '  <a class="icon huge view-sr" href="javascript:;" data-id="' . $row['sr_id'] . '"><i class="icon-zoom-in"></i></a>&nbsp;';

                    if ((htmlentities($_SESSION['user']['email'], ENT_QUOTES)) != "dsm_r" and ( ($_SESSION['currentRole'] == 'Admin')or ( $_SESSION['teamName'] == 'supperAdmins'))) {
                        $html .= '<a href="javascript:;" class="icon huge delete-sr" data-id="' . $row['sr_id'] . '"><i class="icon-remove"></i></a>';
                    }
                }
            }




            $html .= '</td></tr>';
        }




        $html .= '</tbody></table>';

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

    public function bibEditSR() {
        
        
        
        /*
         *    $sr_tt = (htmlentities($_POST['txt-bib-sr_tt'], ENT_QUOTES));
        $handovercustName = (strtolower(htmlentities($_POST['select-bib-handovercustName'], ENT_QUOTES)) == 'other' ? htmlentities($_POST['txt-bib-handovercustName'], ENT_QUOTES) : htmlentities($_POST['select-bib-handovercustName'], ENT_QUOTES));
      //  $handoverhostName = (strtolower(htmlentities($_POST['select-bib-handoverhostName'], ENT_QUOTES)) == 'other' ? htmlentities($_POST['txt-bib-handoverhostName'], ENT_QUOTES) : htmlentities($_POST['select-bib-handoverhostName'], ENT_QUOTES));
        //mss-companynameandaccountnumber-region-bib   example : mss-ethihadmodernart25641801-auh-bib
            
        //  $activityReason = (strtolower(htmlentities($_POST['select-cnoc-activityReason'], ENT_QUOTES)) == 'other' ? htmlentities($_POST['txt-cnoc-activityReason'], ENT_QUOTES) : htmlentities($_POST['select-cnoc-activityReason'], ENT_QUOTES));



        $olt_name = (htmlentities($_POST['select-bib-olt_name'], ENT_QUOTES));
        $zone_name = (htmlentities($_POST['select-bib-zone_name'], ENT_QUOTES));


        $subnet_name = (htmlentities($_POST['select-bib-subnet_name'], ENT_QUOTES));
        $gateway = (htmlentities($_POST['txt-bib-gateway'], ENT_QUOTES));
        $sr_IP = (htmlentities($_POST['select-bib-sr_IP'], ENT_QUOTES));
        $sr_Date = (htmlentities($_POST['txt-bib-sr_Date'], ENT_QUOTES));
        $WONumber = (htmlentities($_POST['txt-bib-WONumber'], ENT_QUOTES));

//$WONumber = (strtolower(htmlentities($_POST['txt-bib-WONumber'], ENT_QUOTES)) == '' ? htmlentities($_POST['txt-cnoc-activityReason'], ENT_QUOTES) : htmlentities($_POST['select-cnoc-activityReason'], ENT_QUOTES));
        $AccountNo = (htmlentities($_POST['txt-bib-AccountNo'], ENT_QUOTES));
        $SerialNumber = (htmlentities($_POST['txt-bib-SerialNumber'], ENT_QUOTES));
        $AccountNo = (htmlentities($_POST['txt-bib-AccountNo'], ENT_QUOTES));
        $ONTSerialNumber = (htmlentities($_POST['txt-bib-ONTSerialNumber'], ENT_QUOTES));
        $EID = (htmlentities($_POST['txt-bib-EID'], ENT_QUOTES));

$handoverhostName = 'mss-'.$handovercustName.$AccountNo.'-'.$zone_name.'-bib';
        $ReqBy = (htmlentities($_SESSION['user']['email'], ENT_QUOTES));
 `sr_id` = [ VALUE -1 ],
  `sr_ticket` = ,
  `Cust_code` = ,
  `host_name` = ,
  `zone_name` = ,
  `olt_name` = ,
  `Subnet-Mask` = ,
  `gateway` = ,
  `ONT_Serial_Number` = ,
  `IP_address` = ,
  `Serial_Number` = ,
  `WO_Number` = ,
  `Account_No` = ,
  `sr_date` = ,
  `EID` = ,
  `Last_update` = [ VALUE -16 ],
  `close_action` = [ VALUE -17 ],
  `create_time` = [ VALUE -18 ],
  `complete_time` = [ VALUE -19 ],
  `last_assign_time` = [ VALUE -20 ],
  `created_by` = [ VALUE -21 ],
  `assigned_to` = [ VALUE -22 ],
  `sr_status` = [ VALUE -23 ],
  `Closed_by` = [ VALUE -24 ]
         * 
         */

        //return $_POST['action'];
        if ($_POST['action'] != 'BIB_EditSR' or ( htmlentities($_SESSION['user']['email'], ENT_QUOTES)) == "dsm_r" OR ! isset($_SESSION['user']['email'])) {
            return FALSE; //se;//"Invalid action supplied for forget password Form.";
        }
        $uname = htmlentities($_SESSION['user']['email'], ENT_QUOTES);
        $closer = htmlentities($_POST['select-bib-srstatus'], ENT_QUOTES) == 'Completed' ? htmlentities($_SESSION['user']['email'], ENT_QUOTES) : NULL;
        $id = htmlentities($_POST['Handoverid'], ENT_QUOTES);
        $handovercustName =(htmlentities($_POST['txt-bib-handovercustName2'], ENT_QUOTES));// (strtolower(htmlentities($_POST['select-bib-handovercustName'], ENT_QUOTES)) == 'other' ? htmlentities($_POST['txt-bib-handovercustName'], ENT_QUOTES) : htmlentities($_POST['select-bib-handovercustName'], ENT_QUOTES));
           $olt_name = (htmlentities($_POST['select-bib-olt_name'], ENT_QUOTES));
        $zone_name = (htmlentities($_POST['select-bib-zone_name'], ENT_QUOTES));


        $subnet_name = (htmlentities($_POST['select-bib-subnet_name'], ENT_QUOTES));
        $gateway = (htmlentities($_POST['txt-bib-gateway'], ENT_QUOTES));
        $sr_IP = (htmlentities($_POST['select-bib-sr_IP'], ENT_QUOTES));
        $sr_Date = (htmlentities($_POST['txt-bib-sr_Date'], ENT_QUOTES));
        $WONumber = (htmlentities($_POST['txt-bib-WONumber'], ENT_QUOTES));

//$WONumber = (strtolower(htmlentities($_POST['txt-bib-WONumber'], ENT_QUOTES)) == '' ? htmlentities($_POST['txt-cnoc-activityReason'], ENT_QUOTES) : htmlentities($_POST['select-cnoc-activityReason'], ENT_QUOTES));
        $AccountNo = (htmlentities($_POST['txt-bib-AccountNo'], ENT_QUOTES));
        $SerialNumber = (htmlentities($_POST['txt-bib-SerialNumber'], ENT_QUOTES));
        $AccountNo = (htmlentities($_POST['txt-bib-AccountNo'], ENT_QUOTES));
        $ONTSerialNumber = (htmlentities($_POST['txt-bib-ONTSerialNumber'], ENT_QUOTES));
        $EID = (htmlentities($_POST['txt-bib-EID'], ENT_QUOTES));

$handovercustName2= $this->clean($handovercustName);
$handovercustName2= strlen($handovercustName2)>=15?substr($handovercustName2, 0,15):$handovercustName2;


$handoverhostName =strtolower( 'mss-'.$handovercustName2.$AccountNo.'-'.$zone_name.'-bib');
        
        
       // $handover_Cat = htmlentities($_POST['select-cnoc-handoverCat'], ENT_QUOTES);
       // $handover_date = htmlentities($_POST['txt-cnoc-handoverdate'], ENT_QUOTES);

       // $handover_DKT = htmlentities($_POST['txt-cnoc-HandoverCMS_DKT'], ENT_QUOTES);

      //  $close_action = htmlentities($_POST['select-cnoc-handoverstatus'], ENT_QUOTES) == 'Completed' ? htmlentities($_POST['HandoverCloseAction'], ENT_QUOTES) : NULL;
        $close_Time = htmlentities($_POST['select-bib-srstatus'], ENT_QUOTES) == 'Completed' ? date('Y-m-d G:i:s') : NULL;


        $handover_status = htmlentities($_POST['select-bib-srstatus'], ENT_QUOTES);
        /*
         * UPDATE set `activity_reason`=[value-5],`activity_date`=[value-6],`realted_team`=[value-7],`close_action`=[value-8] `activity_status`=[value-14] WHERE `activity_id` =
         * */

        $sql = "UPDATE 
	`bib_srs` 
SET  
	
	`sr_status` = :status, 
	
	`Last_update` = now(), 
	
	`complete_time` = :compl_time, 	 
	
	`Closed_by` = :closer ,
      
  
  `host_name` =:handoverhostName ,
  `zone_name` =:zone_name ,
  `olt_name` =:olt_name ,
  `Subnet-Mask` =:subnet_name ,
  `gateway` =:gateway ,
  `ONT_Serial_Number` =:ONTSerialNumber ,
  `IP_address` =:sr_IP ,
  `Serial_Number` =:SerialNumber ,
  `WO_Number` =:WONumber ,
  `Account_No` =:AccountNo ,
  `sr_date` =:sr_Date ,
  `EID` =:EID
	 WHERE sr_id=:id";
        try {
            $arr = array("handoverhostName" => $handoverhostName,"zone_name" => $zone_name,"olt_name" => $olt_name,"subnet_name" => $subnet_name,"gateway" => $gateway,"ONTSerialNumber" => $ONTSerialNumber,"sr_IP" => $sr_IP,"SerialNumber" => $SerialNumber,"WONumber" => $WONumber,"AccountNo" => $AccountNo,"sr_Date" => $sr_Date,"EID" => $EID,"id" => $id, "compl_time" => $close_Time, "status" => $handover_status, "closer" => $closer);
            $countRows = $this->query($sql, $arr);
            //  var_dump($arr);
            //  var_dump($countRows);
            if ($countRows > 0) {

                $this->alertObj->setAlert('UPDATE', 'BIBSR', $id, 'SR ID  :' . $id . ' Edit values :' + implode(', ', $arr));
                $this->log->logActions($_SESSION['user']['email'], 'Edit SR', 'Success Edit', 'SR ID  :' . $id . ' Edit values :' + implode(', ', $arr));

                return true; //$_SESSION['user']['email'].'<a href="javascript:;" class="btn unpick-activity"  data-id="'.$id.'">unPick It</a>';;
            } else {
                return FALSE;
            }
            //success
//            if ($handover_status == 'Pending Customer') {
//                return $this->cnocUnPickHandover2($id);
//            }
        } catch (Exception $e) {

            $this->log->logActions($_SESSION['user']['email'], 'Edit SR', 'Fail Edit', 'SR ID  :' . $id . ' Edit values: ' + implode(', ', $arr));
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

    public function bibPickSR() {

        //return $_POST['action'];
        if ($_POST['action'] != 'BIB_pickSR' or ( htmlentities($_SESSION['user']['email'], ENT_QUOTES)) == "dsm_r" OR ! isset($_SESSION['user']['email'])) {
            return FALSE; //se;//"Invalid action supplied for forget password Form.";
        }
        $uname = htmlentities($_SESSION['user']['email'], ENT_QUOTES);
        $id = htmlentities($_POST['Handoverid'], ENT_QUOTES);

        $stat = $_POST['stat']; //<span class="label label-info">In Progress</span>


        $sql = "UPDATE bib_srs set assigned_to=:uname,`handover_status`=:status  ,`Last_update`=now()  where sr_id=:id";
        try {
            if ($stat == '<span class="label label-important">Pending</span>') {
                $sql = "UPDATE bib_srs set assigned_to=:uname,`sr_status`=:status  ,`Last_update`=now()  where sr_id=:id";
                $countRows = $this->query($sql, array("id" => $id, "uname" => $uname, "status" => "In Progress"));
            } else {
                $sql = "UPDATE bib_srs set assigned_to=:uname ,`Last_update`=now()  where sr_id=:id";
                $countRows = $this->query($sql, array("id" => $id, "uname" => $uname));
            }

            //  $countRows = $this->query($sql, array("id" => $id, "uname" => $uname, "status" => "In Progress"));
            //var_dump($countRows);
            if ($countRows > 0) {

                $this->alertObj->setAlert('UPDATE', 'BIBSR', $id, 'SR ID  :' . $id . ' picked');
                $this->log->logActions($_SESSION['user']['email'], 'Pick SR', 'Success Pick', 'SR ID  :' . $id . ' picked');

                return $_SESSION['user']['email'] . '<a href="javascript:;" class="btn unpick-sr" id="pickbtn_' . $_POST['Handoverid'] . '"  data-id="' . $id . '">unPick It</a>';
                ;
            } else {
                return FALSE;
            }
            //success
        } catch (Exception $e) {

            $this->log->logActions($_SESSION['user']['email'], 'Pick SR', 'Fail Pick', 'SR ID  :' . $id . ' picked');
            return (FALSE);
        }
    }

//pick activity
    /*
     * Unpick activit
     * 
     */

    public function bibUnPickSR() {

        //return $_POST['action'];
        if ($_POST['action'] != 'BIB_unpickSR' or ( htmlentities($_SESSION['user']['email'], ENT_QUOTES)) == "dsm_r" OR ! isset($_SESSION['user']['email'])) {
            return FALSE; //se;//"Invalid action supplied for forget password Form.";
        }
        $uname = htmlentities($_SESSION['user']['email'], ENT_QUOTES);
        $id = htmlentities($_POST['Handoverid'], ENT_QUOTES);
        $stat = $_POST['stat'];



        $sql = "UPDATE bib_srs set assigned_to=null,`handover_status`=:status,`Last_update`=now()  where sr_id=:id";

        try {

            if ($stat == '<span class="label label-info">In Progress</span>') {
                $sql = "UPDATE bib_srs set assigned_to=null,`sr_status`=:status,`Last_update`=now()  where sr_id=:id";

                $countRows = $this->query($sql, array("id" => $id, "status" => "Pending"));
            } else {
                $sql = "UPDATE bib_srs set assigned_to=null,`Last_update`=now()  where sr_id=:id";

                $countRows = $this->query($sql, array("id" => $id));
            }

            //  $countRows = $this->query($sql, array("id" => $id, "status" => "Pending"));
            //var_dump($countRows);
            if ($countRows > 0) {

                $this->alertObj->setAlert('UPDATE', 'BIBSR', $id, 'SR ID  :' . $id . ' Upicked');
                $this->log->logActions($_SESSION['user']['email'], 'UnPick SR', 'Success unPick', 'SR ID  :' . $id . ' unpicked');

                return '<a href="javascript:;" class="btn pick-sr" id="pickbtn_' . $_POST['Handoverid'] . '" data-id="' . $id . '">Pick It</a>';
            } else {
                return FALSE;
            }
            //success
        } catch (Exception $e) {

            $this->log->logActions($_SESSION['user']['email'], 'unPick SR', 'Fail unPick', 'SR ID  :' . $id . ' unpicked');
            return (FALSE);
        }
    }

    private function cnocUnPickHandover2($idp) {

        //return $_POST['action'];


        $id = $idp;


        try {


            $sql = "UPDATE bib_srs set assigned_to=null,`Last_update`=now()  where sr_id=:id";

            $countRows = $this->query($sql, array("id" => $id));



            //  $countRows = $this->query($sql, array("id" => $id, "status" => "Pending"));
            //var_dump($countRows);
            if ($countRows > 0) {

                $this->alertObj->setAlert('UPDATE', 'BIBSR', $id, 'SR ID  :' . $id . ' Upicked');
                $this->log->logActions($_SESSION['user']['email'], 'UnPick SR', 'Success unPick', 'SR ID  :' . $id . ' unpicked');

                return '<a href="javascript:;" class="btn pick-sr" id="pickbtn_' . $_POST['Handoverid'] . '" data-id="' . $id . '">Pick It</a>';
            } else {
                return FALSE;
            }
            //success
        } catch (Exception $e) {

            $this->log->logActions($_SESSION['user']['email'], 'unPick SR', 'Fail unPick', 'SR ID  :' . $id . ' unpicked');
            return (FALSE);
        }
    }

//pick activity




    /*     * ****************
     * cnocGetHandoverDetails
     * to get activity details using its id
     * retrun json data
     */

    public function bibGetSRDetails() {


        $serial = $_POST['handoverid'];

        if ($_POST['action'] != 'BIB_getSRDetails' OR ! isset($_SESSION['user']['email'])) {
            return false; //"Invalid action supplied for retrive Activity Data.";
        }
        $sql = 'SELECT `sr_id`, `sr_ticket`, `Cust_code`, `host_name`, `zone_name`, `olt_name`, `Subnet-Mask` as `Subnet_Mask`, `gateway`, `ONT_Serial_Number`, `IP_address`, `Serial_Number`, `WO_Number`, `Account_No`, `sr_date`, `EID`, `Last_update`, `close_action`, `create_time`, `complete_time`, `last_assign_time`, `created_by`, `assigned_to`, `sr_status`, `Closed_by` FROM `bib_srs` WHERE `sr_id`=:ser';
        $msg = '';
        
          $result = array();
            try {
                $result = $this->query($sql, array('ser' => $serial), PDO::FETCH_OBJ);
                if($result!=NULL){
                    $_SESSION['Handoverid_sess'] = $serial;
                    return json_encode($result);
                }
            } catch (PDOException $e) {

                return($e->getMessage());
            }
            
        
        //echo json_encode(array("phone"=>'123-12313',"email"=>'test@test.com','city'=>'Medicine Hat','address'=>'556 19th Street NE'));
        //return;
    }

    /*
     * Check if inserted before
     */

    public function BIB_srInsertedBefore() {

        if ($_POST['action'] != 'checkSRInsertedBefore' OR ! isset($_SESSION['user']['email'])) {
            return "Invalid action supplied for Check Inserted Before";
        }
        $found = false;

        $TTSer = htmlentities(trim($_POST['serial']));
        // Fetching single value
        $ID = $this->single("SELECT `sr_ticket` FROM `bib_srs` WHERE `sr_ticket`= :id ", array('id' => $TTSer));
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

    public function bibGetSRDetailsBySR() {


        $serial = htmlentities(trim($_POST['Handoverid']));

        if ($_POST['action'] != 'BIB_getSRDetailsBySR' OR ! isset($_SESSION['user']['email'])) {
            return false; //"Invalid action supplied for retrive Activity Data.";
        }
        $sql = 'SELECT `sr_id`, `sr_ticket`, `Cust_code`, `host_name`, `zone_name`, `olt_name`, `Subnet-Mask` as `Subnet_Mask`, `gateway`, `ONT_Serial_Number`, `IP_address`, `Serial_Number`, `WO_Number`, `Account_No`, `sr_date`, `EID`, `Last_update`, `close_action`, `create_time`, `complete_time`, `last_assign_time`, `created_by`, `assigned_to`, `sr_status`, `Closed_by` FROM `bib_srs` WHERE `sr_ticket`=:ser';
        $msg = '';
        
          $result = array();
            try {
                $result = $this->query($sql, array('ser' => $serial), PDO::FETCH_OBJ);
                if($result!=NULL){
                  //  $_SESSION['Handoverid_sess'] = $serial;
                    return json_encode($result);
                }
            } catch (PDOException $e) {

                return($e->getMessage());
            }
            
    }
    
     public function bibGetSRDetailsByAccount() {


        $serial = htmlentities(trim($_POST['Handoverid']));

        if ($_POST['action'] != 'BIB_getSRDetailsByAccount' OR ! isset($_SESSION['user']['email'])) {
            return false; //"Invalid action supplied for retrive Activity Data.";
        }
        $sql = 'SELECT `sr_id`, `sr_ticket`, `Cust_code`, `host_name`, `zone_name`, `olt_name`, `Subnet-Mask` as `Subnet_Mask`, `gateway`, `ONT_Serial_Number`, `IP_address`, `Serial_Number`, `WO_Number`, `Account_No`, `sr_date`, `EID`, `Last_update`, `close_action`, `create_time`, `complete_time`, `last_assign_time`, `created_by`, `assigned_to`, `sr_status`, `Closed_by` FROM `bib_srs` WHERE `Account_No`=:ser order by sr_id desc limit 1';
        $msg = '';
        
          $result = array();
            try {
                $result = $this->query($sql, array('ser' => $serial), PDO::FETCH_OBJ);
                if($result!=NULL){
                  //  $_SESSION['Handoverid_sess'] = $serial;
                    return json_encode($result);
                }
            } catch (PDOException $e) {

                return($e->getMessage());
            }
            
    }

//end get ativity by using im number

    public function cnocGetActivityAssignHist() {// Connection data (server_address, database, name, poassword)
        if ($_POST['action'] != 'CNOC_getActivityAssignHist' OR ! isset($_SESSION['user']['email'])) {
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

    public function bibGetSRAssignHist() {// Connection data (server_address, database, name, poassword)
        if ($_POST['action'] != 'BIB_getSRAssignHist' OR ! isset($_SESSION['user']['email'])) {
            return false; //"Invalid action supplied for retrive Activity assign History Data.";
        }
        if (isset($_POST['Handoverid'])) {
            $HandoverID = htmlentities($_POST['Handoverid'], ENT_QUOTES);

            $sql = "SELECT `user` ,`assign_time`FROM `bib_sr_assignhistory` WHERE `sr_id`=:handoverid order by assign_time desc ";
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

    public function bibAddSRRemarks() {// Connection data (server_address, database, name, poassword)
        /*
         * inproper action call
         */
        if ($_POST['action'] != 'BIB_AddSRRemark' or ( htmlentities($_SESSION['user']['email'], ENT_QUOTES)) == "dsm_r" OR ! isset($_SESSION['user']['email'])) {
            return "Invalid action supplied for process Add new SR Remarks.";
        }

        $HandoverRemarks = nl2br(htmlentities($_POST['HandoverRemarks'], ENT_QUOTES));

        $HandoverID = (htmlentities($_SESSION['Handoverid_sess'], ENT_QUOTES));
        $ReqBy = (htmlentities($_SESSION['user']['email'], ENT_QUOTES));

        $sql = "INSERT INTO `bib_srremarks`( sr_id,`Remark_TXT`, `Editor`) VALUES (:HandoverId,:remark,:by)";
        //$this->lastInsertId();
        try {
            $res = $this->query($sql, array("remark" => $HandoverRemarks, "by" => $ReqBy, "HandoverId" => $HandoverID));
            $this->log->logActions($_SESSION['user']['email'], ' New BIB SR Remark', 'Succes ad d ', 'DATA :remark =>' . $HandoverRemarks . ', by => ' . $ReqBy . ',  for activityId =>' . $HandoverID);
        } catch (Exception $e) {
            // $this->db=null;
            $this->log->logActions($_SESSION['user']['email'], 'Add New BIB SR', 'Faild Delete due to ' . $e->getMessage(), 'DATA :remark =>' . $HandoverRemarks . ', by => ' . $ReqBy . ',  for HandoverId =>' . $HandoverID);
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

    public function bibGetSRRemarks() {// Connection data (server_address, database, name, poassword)
        if ($_POST['action'] != 'BIB_getSRRemarks' OR ! isset($_SESSION['user']['email'])) {
            return false; //"Invalid action supplied for retrive Activity assign History Data.";
        }
        if (isset($_POST['Handoverid']) or isset($_SESSION['Handoverid_sess'])) {
            $Handoverid = htmlentities($_POST['Handoverid'], ENT_QUOTES) == null ? $_SESSION['Handoverid_sess'] : htmlentities($_POST['Handoverid'], ENT_QUOTES);

            $sql = "SELECT  `Remark_TXT`, `Editor`, `Remark_tTme` FROM `bib_srremarks` WHERE sr_id=:handoverid order by Remark_tTme desc ";
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
        if ($_POST['action'] != 'CNOC_getHandoverAttachs' OR ! isset($_SESSION['user']['email'])) {
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
        if ($_POST['action'] != 'handover_attach' or ( htmlentities($_SESSION['user']['email'], ENT_QUOTES)) == "dsm_r" OR ! isset($_SESSION['user']['email'])) {
            return "Invalid action supplied for Attach file.";
        }
        // var_dump($_POST);
        $fName = trim('assets/comm/uploads/handOver/' . $_POST['fileName']);
        if ($fName == NULL) {
            return "Invalid File Name supplied .";
        }
        $this->log->logActions($_SESSION['user']['email'], ' New Cnoc Handover Upload File', 'Succes add ', 'DATA :File Name =>' . $_POST['fileName']);
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
    public function bibGetSRJson($from = null, $to = null, $filterName = null, $Operator = null, $FilterValue = null) {// Connection data (server_address, database, name, poassword)
        $option = '';
        if ($filterName == null and $from == null and $to == null) {
            // $option = " WHERE STR_TO_DATE(`IM_Startdate`, '%d/%m/%Y')='" . date('Y-m-d') . "'";
            $option = " WHERE sr_date='" . date('Y-m-d') . "' or (sr_date<='" . date('Y-m-d') . "')"; //`activity_status`, `Closed_by` ,cust_top34.`TOP34`
        } else {
            $option = " WHERE sr_date >= STR_TO_DATE(replace('" . $from . "','/',','),'%m,%d,%Y %T') and sr_date <= STR_TO_DATE(replace('" . $to . "','/',','),'%m,%d,%Y %T')";
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
                    $additionalFilters = 'Cust_code';
                    break;
                case 'Host Name':
                    $additionalFilters = ' host_name';
                    break;
                case 'ZONE':
                    $additionalFilters = ' zone_name';
                    break;
                case 'OLT':
                    $additionalFilters = ' olt_name';
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
                case 'SR Number':
                    $additionalFilters = ' sr_ticket';
                    break;
                case 'Status':
                    $additionalFilters = ' sr_status';
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
                    $additionalFilters .= " Like '%" . trim($FilterValue) . "%' ";
                    break;
                case 'StartWith':
                    $additionalFilters .= " Like '" . trim($FilterValue) . "%' ";
                    break;
                case 'EndWith':
                    $additionalFilters .= " Like '%" . trim($FilterValue) . "' ";
                    break;
                case 'NotEqual':
                    $additionalFilters .= " != '" . trim($FilterValue) . "' ";
                    break;
                case 'NotContain':
                    $additionalFilters .= " Not Like '%" . trim($FilterValue) . "%' ";
                    break;
                case 'GreaterThan':
                    $additionalFilters .= " > '" . trim($FilterValue) . "' ";
                    break;
                case 'LessThan':
                    $additionalFilters .= " < '" . trim($FilterValue) . "' ";
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

            $option .= $additionalFilters;
        }


        $sql = "SELECT * FROM `bib_srs` " . $option . "  ORDER BY sr_date ASC ";
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

    public function bibdeleteSR() {
        /*
         * Fails if the proper action was not submitted
         */
        // echo "<script>alert('".$_POST['input-username']."')</script>";
        if ($_POST['action'] != 'BIB_removesr' or ( htmlentities($_SESSION['user']['email'], ENT_QUOTES)) == "dsm_r" OR ! isset($_SESSION['user']['email'])) {
            return "Invalid action supplied for process Delete SR.";
        }
        /*
         * Escapes the user input for security
         */
        $Actvid = htmlentities($_POST['Handoverid'], ENT_QUOTES);


        /*
         * Retrieves the matching info from the DB if it exists
         */
        $sql = "DELETE FROM `bib_srs` WHERE `sr_id`=:ser ";
        try {
            $res = $this->query($sql, array("ser" => $Actvid));
            $this->alertObj->setAlert('DELETE', 'BIBSR', $Actvid, 'SR ID  :' . $Actvid . ' Deleted');
            $this->log->logActions($_SESSION['user']['email'], 'Delete BIB SR', ' Delete success ', 'SR id :' . $Actvid . ' deleted');
        } catch (Exception $e) {
            // $this->db=null;
            $this->log->logActions($_SESSION['user']['email'], 'Delete SR', 'Faild Delete due to ' . $e->getMessage(), 'SR Id:' . $Actvid . ' ');
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
        if ($_POST['action'] != 'CNOC_removeHandoverAttach' or ( htmlentities($_SESSION['user']['email'], ENT_QUOTES)) == "dsm_r" OR ! isset($_SESSION['user']['email'])) {
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

    public function bibGetSRStatusPercentage() {
        /*
          if ($_POST['action'] != 'CNOC_getActivityStatusPrecentage') {
          return false ;//"Invalid action supplied for retrive Activity assign History Data.";
          } */



        $sql = "SELECT `cc` as `y`, `sr_status` as `name` FROM bib_srstatus_percentage ";
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
    
     public function bibGetSRStatusZone() {
        /*
          if ($_POST['action'] != 'CNOC_getActivityStatusPrecentage') {
          return false ;//"Invalid action supplied for retrive Activity assign History Data.";
          } */



        $sql = "SELECT COUNT(*)as y,zone_name,sr_status FROM `bib_srs` where MONTH(sr_date)=month(CURRENT_TIMESTAMP) GROUP BY zone_name,sr_status ";
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

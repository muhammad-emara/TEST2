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
class activity extends DB_Connect {

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

    public function cnocAddNewActivity() {// Connection data (server_address, database, name, poassword)
        /*
         * inproper action call
         */
        if ($_POST['action'] != 'CNOC_AddActivity' OR (htmlentities($_SESSION['user']['email'], ENT_QUOTES))=="dsm_r" OR !isset($_SESSION['user']['email'])) {
            return "Invalid action supplied for process Add new Activity.";
        }

        $TT_id = (htmlentities($_POST['txt-cnoc-activity_tt'], ENT_QUOTES));
        // $activitycustName = ((htmlentities($_POST['select-cnoc-activitycustName'], ENT_QUOTES)) == 'other' ? htmlentities($_POST['txt-cnoc-activitycustName'], ENT_QUOTES) : htmlentities($_POST['select-cnoc-activitycustName'], ENT_QUOTES));
        //  $activityhostName = (strtolower(htmlentities($_POST['select-cnoc-activityhostName'], ENT_QUOTES)) == 'other' ? htmlentities($_POST['txt-cnoc-activityhostName'], ENT_QUOTES) : htmlentities($_POST['select-cnoc-activityhostName'], ENT_QUOTES));
        //  $activityReason = (strtolower(htmlentities($_POST['select-cnoc-activityReason'], ENT_QUOTES)) == 'other' ? htmlentities($_POST['txt-cnoc-activityReason'], ENT_QUOTES) : htmlentities($_POST['select-cnoc-activityReason'], ENT_QUOTES));


        $activitycustName = (strtolower(htmlentities($_POST['select-cnoc-activitycustName'], ENT_QUOTES)) == 'other' ? htmlentities($_POST['txt-cnoc-activitycustName'], ENT_QUOTES) : htmlentities($_POST['select-cnoc-activitycustName'], ENT_QUOTES));
        $activityhostName = (strtolower(htmlentities($_POST['select-cnoc-activityhostName'], ENT_QUOTES)) == 'other' ? htmlentities($_POST['txt-cnoc-activityhostName'], ENT_QUOTES) : htmlentities($_POST['select-cnoc-activityhostName'], ENT_QUOTES));
        $activityReason = (htmlentities($_POST['select-cnoc-activityReason'], ENT_QUOTES));


        $activitydate = (htmlentities($_POST['txt-cnoc-activitydate'], ENT_QUOTES));


        $activityStartdate = (htmlentities($_POST['txt-cnoc-activityStartdate'], ENT_QUOTES));

        $activityteam = (htmlentities($_POST['select-cnoc-activityteam'], ENT_QUOTES));
        $ReqBy = (htmlentities($_SESSION['user']['email'], ENT_QUOTES));


        $ActivityRemarks = nl2br(htmlentities($_POST['ActivityRemarks'], ENT_QUOTES));
        if ($ActivityRemarks == '') {
            $ActivityRemarks = null;
        }

        $sql = "INSERT INTO `cnoc_activities`(`SM_ticket`, `Cust_code`, `host_name`, `activity_reason`, `activity_date`, `realted_team`, `created_by`,activity_Startdate) VALUES (:TTid,:cust_name,:host_name,:reason,:activityDate,:related_Team,:reqby,:startDate)";
        //$this->lastInsertId();
        try {
            $res = $this->query($sql, array("TTid" => $TT_id, "cust_name" => $activitycustName, "host_name" => $activityhostName, "reason" => $activityReason, "activityDate" => $activitydate, "related_Team" => $activityteam, "reqby" => $ReqBy, "startDate" => $activityStartdate));


            $activityID = $this->lastInsertId();
            /*             * *****adding new remark************ */

            $sql_r = "INSERT INTO `cnoc_activitiesremarks`( Activity_id,`Remark_TXT`, `Editor`) VALUES (:activityId,:remark,:by)";
            //$this->lastInsertId();
            try {
                $res = $this->query($sql_r, array("remark" => $ActivityRemarks, "by" => $ReqBy, "activityId" => $activityID));
                $this->log->logActions($_SESSION['user']['email'], ' New Cnoc Activity Remark', 'Succes ad d ', 'DATA :remark =>' . $ActivityRemarks . ', by => ' . $ReqBy . ',  for activityId =>' . $activityID);
            } catch (Exception $e) {
                // $this->db=null;
                $this->log->logActions($_SESSION['user']['email'], 'Add New Cnoc Activity', 'Faild Delete due to ' . $e->getMessage(), 'DATA :remark =>' . $ActivityRemarks . ', by => ' . $ReqBy . ',  for activityId =>' . $activityID);
                return ($e->getMessage());
            }



            $this->alertObj->setAlert('INSERT', 'cnoc_activities', null, 'DATA :TTid =>' . $TT_id . ', cust_name => ' . $activitycustName . ', host_name =>' . $activityhostName . ', reason =>' . $activityReason . ', activityDate => ' . $activitydate . ',  related_Team =>' . $activityteam);
            $this->log->logActions($_SESSION['user']['email'], ' New Cnoc Activity', 'Succes ad  ', 'DATA :TTid =>' . $TT_id . ', cust_name => ' . $activitycustName . ', host_name =>' . $activityhostName . ', reason =>' . $activityReason . ', activityDate => ' . $activitydate . ',  related_Team =>' . $activityteam);
        } catch (Exception $e) {
            // $this->db=null;
            $this->log->logActions($_SESSION['user']['email'], 'Add New Cnoc Activity', 'Faild Delete due to ' . $e->getMessage(), 'Data :');
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

    public function biuldCnocDailyActivityTable($show = 'my') {// Connection data (server_address, database, name, poassword)
        $condit = '';
        $arr = array();
        if ($show == 'my') {

            $condit = ' WHERE assigned_to=:user or created_by=:user1 ';
            $arr = array('user1' => $_SESSION['user']['email'], 'user' => $_SESSION['user']['email']);
        } elseif ($show == 'all') {
            $condit = ' ';
        } elseif ($show == 'today') {
            $condit = " WHERE STR_TO_DATE(`activity_date`, '%d/%m/%Y')='" . date('Y-m-d') . "' ";
        }


        /* if(isset($_REQUEST['show']) && $_REQUEST['show']=='all'){$condit=' ';}
          elseif(isset($_REQUEST['show']) && $_REQUEST['show']=='today'){$condit=" WHERE STR_TO_DATE(`activity_date`, '%d/%m/%Y')='".date('Y-m-d')."' ";}
          else {$condit=' WHERE assigned_to=:user or created_by=:user1 ';} */
        $sql = "SELECT activity_id,`SM_ticket`, `Cust_code`, `host_name`, `activity_reason`,activity_status, `activity_date`, `realted_team`, `created_by`, `assigned_to` FROM `cnoc_activities` " . $condit . " ORDER BY STR_TO_DATE(replace(activity_date,'/',','),'%d,%m,%Y %T') ASC";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql, $arr);
        } catch (PDOException $e) {

            return($e->getMessage());
        }


        //`SM_ticket`, `Cust_code`, `host_name`, `activity_reason`,activity_status, `activity_date`, `realted_team`, `created_by`, `assigned_to`

        $html = '<table class="table table-striped table-bordered" id="sample_1">
										<thead>
											<tr>
												<th></th>
												<th>Ticket#</th>
												<th>Customer Code</th>
                                                                                                <th>Host Name</th>
												
                                                                                                <th>Reason</th>
												<th>Activity Date</th>
                                                                                                <th>Activity Status</th>
                                                                                                <th>Realted Team</th>
                                                                                                
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
            switch ($row['activity_status']) {
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
                case "Canceled":
                    $statusLable = ''; //#d3d3d3
                    break;
            }//set status color
//display assign button or no 
            
            
            $date_cal = $row['activity_date'] ;
            
    

   $fileds="info=".preg_replace('/[^A-Za-z0-9\-]/', '',$row['SM_ticket'])."||". $row['Cust_code'] ."||".$row['host_name']."||".$row['activity_reason'] ."||".$row['activity_date'] ."||".$row['realted_team']."'";


            $assignedUser = $row['assigned_to'];
            if ($row['activity_status'] != 'Completed' and (htmlentities($_SESSION['user']['email'], ENT_QUOTES))!="dsm_r") {
                if ($row['assigned_to'] == null or $row['assigned_to'] == 'Completed') {
                    $row['assigned_to'] = '<a href="javascript:;" class="btn pick-activity" id="pickbtn_' . $row['activity_id'] . '"  data-id="' . $row['activity_id'] . '">Pick It</a>';
                } else {
                    if ($_SESSION['user']['email'] == $row['assigned_to']) {
                        $row['assigned_to'].='<a href="javascript:;" class="btn unpick-activity" id="pickbtn_' . $row['activity_id'] . '"  data-id="' . $row['activity_id'] . '">unPick It</a>';
                    } elseif ($_SESSION['user']['email'] != $row['assigned_to']) {
                        $row['assigned_to'].='<a href="javascript:;" class="btn pick-activity" id="pickbtn_' . $row['activity_id'] . '"  data-id="' . $row['activity_id'] . '">Pick It</a>';
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


            $html.='<tr class="odd gradeX " data-txt="' . $row['activity_id'] . '"   style="cursor:pointer;"><td></td>
               
												
												<td>' . $row['SM_ticket'] . '</td>
                                                                                                    <td>' . $row['Cust_code'] . '</td>
                                                                                                        <td>' . $row['host_name'] . '</td>
                                                                                                        <td id=cnoc_activityreason_' . $row['activity_id'] . '>' . $row['activity_reason'] . '</td>
                                                                                                            <td id=cnoc_activitydate_' . $row['activity_id'] . '>' . $row['activity_date'] . '</td>
                                                                                                                <td id=cnoc_activitystatus_' . $row['activity_id'] . '><span class="label ' . $statusLable . '">' . $row['activity_status'] . '</span></td>
												<td id=cnoc_activityteam_' . $row['activity_id'] . '>' . $row['realted_team'] . '</td>
                                                                                                    <td >' . $row['assigned_to'] . '</td>
                                                                                                        <td>' . $row['created_by'] . '</td>
                                                                               <td id=cnoc_activityaction_' . $row['activity_id'] . '>';
            if ($show == 'my' and $row['activity_status'] != 'Completed') {


                if (true) {//date('d-m-Y',strtotime(str_replace('/', '-',$row['activity_date'])))<= date('d-m-Y'))
                    $html.='<a class="icon huge view-activity" href="javascript:;" data-id="' . $row['activity_id'] . '"><i class="icon-zoom-in"></i></a>&nbsp;';
                }
                if((htmlentities($_SESSION['user']['email'], ENT_QUOTES))!="dsm_r"){$html.='<a href="javascript:;" class="icon huge delete-activity" data-id="' . $row['activity_id'] . '"><i class="icon-remove"></i></a>';}
               // activity_id,`SM_ticket`, `Cust_code`, `host_name`, `activity_reason`,activity_status, `activity_date`, `realted_team`, `created_by`, `assigned_to`
                 $html.='<a href="'.'assets/inc/ical.php?to=users-cnoc-bo@etisalat.ae&'.$fileds.'" class="icon huge schedul-activity" data-id="' . $fileds . '"><i class="icon-time"></i></a>';
            } elseif ($show == 'all' and ( ($_SESSION['currentRole'] == 'Admin')or ( $_SESSION['teamName'] == 'supperAdmins'))) {
                $html.=' <a class="icon huge view-activity" href="javascript:;" data-id="' . $row['activity_id'] . '"><i class="icon-zoom-in"></i></a>&nbsp;';

                if (true) {
				if((htmlentities($_SESSION['user']['email'], ENT_QUOTES))!="dsm_r"){$html.='<a href="javascript:;" class="icon huge delete-activity" data-id="' . $row['activity_id'] . '"><i class="icon-remove"></i></a>';}
$html.='<a href="'.'assets/inc/ical.php?to=users-cnoc-bo@etisalat.ae&'.$fileds.'" class="icon huge schedul-activity" data-id="' . $fileds . '"><i class="icon-time"></i></a>';
                }
            } elseif ($show == 'today') {



                if ($assignedUser == $_SESSION['user']['email'] and $row['activity_status'] != 'Completed') {
                    $html.='  <a class="icon huge view-activity" href="javascript:;" data-id="' . $row['activity_id'] . '"><i class="icon-zoom-in"></i></a>&nbsp;';

                    if((htmlentities($_SESSION['user']['email'], ENT_QUOTES))!="dsm_r"){$html.='<a href="javascript:;" class="icon huge delete-activity" data-id="' . $row['activity_id'] . '"><i class="icon-remove"></i></a>';}
$html.='<a href="'.'assets/inc/ical.php?to=users-cnoc-bo@etisalat.ae&'.$fileds.'" class="icon huge schedul-activity" data-id="' . $fileds . '"><i class="icon-time"></i></a>';
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

    public function cnocEditActivity() {

        //return $_POST['action'];
        if ($_POST['action'] != 'CNOC_EditActivity' OR (htmlentities($_SESSION['user']['email'], ENT_QUOTES))=="dsm_r" OR !isset($_SESSION['user']['email'])) {
            return FALSE; //se;//"Invalid action supplied for forget password Form.";
        }
        $uname = htmlentities($_SESSION['user']['email'], ENT_QUOTES);
        $closer = htmlentities($_POST['select-cnoc-activityStatus'], ENT_QUOTES) == 'Completed' ? htmlentities($_SESSION['user']['email'], ENT_QUOTES) : NULL;
        $id = htmlentities($_POST['Activityid'], ENT_QUOTES);
        $activity_reason = htmlentities($_POST['select-cnoc-activityReason'], ENT_QUOTES);
        $activity_date = htmlentities($_POST['txt-cnoc-activitydate'], ENT_QUOTES);

        $activity_Startdate = htmlentities($_POST['txt-cnoc-activityStartdate'], ENT_QUOTES);
        $realted_team = htmlentities($_POST['select-cnoc-activityteam'], ENT_QUOTES);
        $close_action = htmlentities($_POST['select-cnoc-activityStatus'], ENT_QUOTES) == 'Completed' ? htmlentities($_POST['ActivityCloseAction'], ENT_QUOTES) : NULL;
        $close_Time = htmlentities($_POST['select-cnoc-activityStatus'], ENT_QUOTES) == 'Completed' ? date('Y-m-d G:i:s') : NULL;


        $activity_status = htmlentities($_POST['select-cnoc-activityStatus'], ENT_QUOTES);
        /*
         * UPDATE set `activity_reason`=[value-5],`activity_date`=[value-6],`realted_team`=[value-7],`close_action`=[value-8] `activity_status`=[value-14] WHERE `activity_id` =
         * */

        $sql = "UPDATE cnoc_activities set `activity_reason`=:reason,`activity_date`=:Act_date,`realted_team`=:rel_Team,`close_action`=:closeact,`activity_status`=:status,Closed_by=:closer,complete_time=:compeletTime,activity_Startdate=:startTime WHERE activity_id=:id";
        try {

            $countRows = $this->query($sql, array("id" => $id, "reason" => $activity_reason, "Act_date" => $activity_date, "rel_Team" => $realted_team, "closeact" => $close_action, "status" => $activity_status, "closer" => $closer, "compeletTime" => $close_Time, "startTime" => $activity_Startdate));
            //var_dump($countRows);
            if ($countRows > 0) {

                $this->alertObj->setAlert('UPDATE', 'cnoc_activities', $id, 'Activity ID  :' . $id . ' Edit values :' + implode(', ', array("id" => $id, "reason" => $activity_reason, "Act_date" => $activity_date, "rel_Team" => $realted_team, "closeact" => $close_action, "status" => $activity_status)));
                $this->log->logActions($_SESSION['user']['email'], 'Edit Activity', 'Success Edit', 'Activity ID  :' . $id . ' Edit values :' + implode(', ', array("id" => $id, "reason" => $activity_reason, "Act_date" => $activity_date, "rel_Team" => $realted_team, "closeact" => $close_action, "status" => $activity_status)));

                return true; //$_SESSION['user']['email'].'<a href="javascript:;" class="btn unpick-activity"  data-id="'.$id.'">unPick It</a>';;
            } else {
                return FALSE;
            }
            //success
        } catch (Exception $e) {

            $this->log->logActions($_SESSION['user']['email'], 'Edit Activity', 'Fail Edit', 'Activity ID  :' . $id . ' Edit values: ' + implode(', ', array("id" => $id, "reason" => $activity_reason, "Act_date" => $activity_date, "rel_Team" => $realted_team, "closeact" => $close_action, "status" => $activity_status)));
            return (FALSE);
        }
    }

//Edit activity

    /*     * **************end edit cnoc activity

      /*
     * 
     * Pick Activity 
     * for the current session
     */

    public function cnocPickActivity() {

        //return $_POST['action'];
        if ($_POST['action'] != 'CNOC_pickActivity' OR (htmlentities($_SESSION['user']['email'], ENT_QUOTES))=="dsm_r" OR !isset($_SESSION['user']['email'])) {
            return FALSE; //se;//"Invalid action supplied for forget password Form.";
        }
        $uname = htmlentities($_SESSION['user']['email'], ENT_QUOTES);
        $id = htmlentities($_POST['Activityid'], ENT_QUOTES);
        $sql = "UPDATE cnoc_activities set assigned_to=:uname,`activity_status`=:status  where activity_id=:id";
        try {

            $countRows = $this->query($sql, array("id" => $id, "uname" => $uname, "status" => "In Progress"));
            //var_dump($countRows);
            if ($countRows > 0) {

                $this->alertObj->setAlert('UPDATE', 'cnoc_activities', $id, 'Activity ID  :' . $id . ' picked');
                $this->log->logActions($_SESSION['user']['email'], 'Pick Activity', 'Success Pick', 'Activity ID  :' . $id . ' picked');

                return $_SESSION['user']['email'] . '<a href="javascript:;" class="btn unpick-activity" id="pickbtn_' . $_POST['Activityid'] . '"  data-id="' . $id . '">unPick It</a>';
                ;
            } else {
                return FALSE;
            }
            //success
        } catch (Exception $e) {

            $this->log->logActions($_SESSION['user']['email'], 'Pick Activity', 'Fail Pick', 'Activity ID  :' . $id . ' picked');
            return (FALSE);
        }
    }

//pick activity
    /*
     * Unpick activit
     * 
     */

    public function cnocUnPickActivity() {

        //return $_POST['action'];
        if ($_POST['action'] != 'CNOC_unpickActivity' OR (htmlentities($_SESSION['user']['email'], ENT_QUOTES))=="dsm_r" OR !isset($_SESSION['user']['email'])) {
            return FALSE; //se;//"Invalid action supplied for forget password Form.";
        }
        $uname = htmlentities($_SESSION['user']['email'], ENT_QUOTES);
        $id = htmlentities($_POST['Activityid'], ENT_QUOTES);
        $sql = "UPDATE cnoc_activities set assigned_to=null,`activity_status`=:status  where activity_id=:id";

        try {

            $countRows = $this->query($sql, array("id" => $id, "status" => "Pending"));
            //var_dump($countRows);
            if ($countRows > 0) {

                $this->alertObj->setAlert('UPDATE', 'cnoc_activities', $id, 'Activity ID  :' . $id . ' Upicked');
                $this->log->logActions($_SESSION['user']['email'], 'UnPick Activity', 'Success unPick', 'Activity ID  :' . $id . ' unpicked');

                return '<a href="javascript:;" class="btn pick-activity" id="pickbtn_' . $_POST['Activityid'] . '" data-id="' . $id . '">Pick It</a>';
            } else {
                return FALSE;
            }
            //success
        } catch (Exception $e) {

            $this->log->logActions($_SESSION['user']['email'], 'unPick Activity', 'Fail unPick', 'Activity ID  :' . $id . ' unpicked');
            return (FALSE);
        }
    }

//pick activity




    /*     * ****************
     * cnocGetActivityDetails
     * to get activity details using its id
     * retrun json data
     */

    public function cnocGetActivityDetails() {


        $serial = $_POST['Activityid'];

        if ($_POST['action'] != 'CNOC_getActivityDetails' OR !isset($_SESSION['user']['email'])) {
            return false; //"Invalid action supplied for retrive Activity Data.";
        }
        $sql = 'SELECT `SM_ticket`,close_action, `Cust_code`, `host_name`, `activity_reason`, `activity_date`, `realted_team`, `close_action`, `create_time`, `complete_time`,  `created_by`, `assigned_to`, `activity_status`,activity_Startdate FROM `cnoc_activities` WHERE `activity_id`=:ser';
        $msg = '';
        try {

            $result = $this->row($sql, array('ser' => $serial));
            if ($result > 0) {
                $_SESSION['Activityid_sess'] = $serial;

                // $msg= 'true ';// Items inserted Before'.implode(' || ',$_srInserted);//array("phone"=>'123-12313',"email"=>'test@test.com','city'=>'Medicine Hat','address'=>'556 19th Street NE')
                echo json_encode(array("TTid" => $result['SM_ticket'], "custname" => $result['Cust_code'], "hostname" => $result['host_name'], "reason" => $result['activity_reason'], "activityDate" => $result['activity_date'], "activity_Startdate" => $result['activity_Startdate'], "related_Team" => $result['realted_team'], "reqby" => $result['created_by'], "status" => $result['activity_status'], "assignedto" => $result['assigned_to'], "completetime" => $result['complete_time'], "close_action" => $result['close_action']));
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

    public function CNOC_ActivityInsertedBefore() {

        if ($_POST['action'] != 'checkInsertedBefore' OR (htmlentities($_SESSION['user']['email'], ENT_QUOTES))=="dsm_r" OR !isset($_SESSION['user']['email'])) {
            return "Invalid action supplied for Check Inserted Before";
        }
        $found = false;

        $routerSer = htmlentities(trim($_POST['serial']));
        // Fetching single value
        $stockID = $this->single("SELECT `SM_ticket` FROM `cnoc_activities` WHERE `SM_ticket`= :id ", array('id' => $routerSer));
        //return $this->sQuery->rowcount();
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

    public function cnocGetActivityDetailsByIM() {


        $serial = htmlentities(trim($_POST['Activityid']));

        if ($_POST['action'] != 'CNOC_getActivityDetailsByIM' OR !isset($_SESSION['user']['email'])) {
            return false; //"Invalid action supplied for retrive Activity Data.";
        }
        $sql = 'SELECT activity_id,`SM_ticket`,close_action, `Cust_code`, `host_name`, `activity_reason`, `activity_date`, `realted_team`, `close_action`, `create_time`, `complete_time`,  `created_by`, `assigned_to`, `activity_status`,activity_Startdate FROM `cnoc_activities` WHERE `SM_ticket`=:ser';
        $msg = '';
        try {

            $result = $this->row($sql, array('ser' => $serial));
            if ($result > 0) {
                $_SESSION['Activityid_sess'] = $result['activity_id'];

                // $msg= 'true ';// Items inserted Before'.implode(' || ',$_srInserted);//array("phone"=>'123-12313',"email"=>'test@test.com','city'=>'Medicine Hat','address'=>'556 19th Street NE')
                echo json_encode(array("Tid" => $result['activity_id'], "TTid" => $result['SM_ticket'], "custname" => $result['Cust_code'], "hostname" => $result['host_name'], "reason" => $result['activity_reason'], "activityDate" => $result['activity_date'], "activity_Startdate" => $result['activity_Startdate'], "related_Team" => $result['realted_team'], "reqby" => $result['created_by'], "status" => $result['activity_status'], "assignedto" => $result['assigned_to'], "completetime" => $result['complete_time'], "close_action" => $result['close_action']));
            } else {
                return FALSE;
            }
        } catch (PDOException $e) {

            return( 'error pdo ' . $e->getMessage());
        }
        //echo json_encode(array("phone"=>'123-12313',"email"=>'test@test.com','city'=>'Medicine Hat','address'=>'556 19th Street NE'));
        //return;
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

    /*
     * build add new activities
     */

    public function cnocAddActivityRemarks() {// Connection data (server_address, database, name, poassword)
        /*
         * inproper action call
         */
        if ($_POST['action'] != 'CNOC_AddActivityRemark' OR (htmlentities($_SESSION['user']['email'], ENT_QUOTES))=="dsm_r" OR !isset($_SESSION['user']['email'])) {
            return "Invalid action supplied for process Add new Activity Remarks.";
        }

        $ActivityRemarks = nl2br(htmlentities($_POST['ActivityRemarks'], ENT_QUOTES));

        $activityID = (htmlentities($_SESSION['Activityid_sess'], ENT_QUOTES));
        $ReqBy = (htmlentities($_SESSION['user']['email'], ENT_QUOTES));

        $sql = "INSERT INTO `cnoc_activitiesremarks`( Activity_id,`Remark_TXT`, `Editor`) VALUES (:activityId,:remark,:by)";
        //$this->lastInsertId();
        try {
            $res = $this->query($sql, array("remark" => $ActivityRemarks, "by" => $ReqBy, "activityId" => $activityID));
            $this->log->logActions($_SESSION['user']['email'], ' New Cnoc Activity Remark', 'Succes ad d ', 'DATA :remark =>' . $ActivityRemarks . ', by => ' . $ReqBy . ',  for activityId =>' . $activityID);
        } catch (Exception $e) {
            // $this->db=null;
            $this->log->logActions($_SESSION['user']['email'], 'Add New Cnoc Activity', 'Faild Delete due to ' . $e->getMessage(), 'DATA :remark =>' . $ActivityRemarks . ', by => ' . $ReqBy . ',  for activityId =>' . $activityID);
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

    public function cnocGetActivityRemarks() {// Connection data (server_address, database, name, poassword)
        if ($_POST['action'] != 'CNOC_getActivityRemarks' OR !isset($_SESSION['user']['email'])) {
            return false; //"Invalid action supplied for retrive Activity assign History Data.";
        }
        if (isset($_POST['Activityid']) or isset($_SESSION['Activityid_sess'])) {
            $Activityid = htmlentities($_POST['Activityid'], ENT_QUOTES) == null ? $_SESSION['Activityid_sess'] : htmlentities($_POST['Activityid'], ENT_QUOTES);

            $sql = "SELECT  `Remark_TXT`, `Editor`, `Remark_tTme` FROM `cnoc_activitiesremarks` WHERE Activity_id=:activityid order by Remark_tTme desc ";
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

    /*     * *********get all aactivities json ****** */

    //($_REQUEST['from'],$_REQUEST['to'],$_REQUEST['FilterName'],$_REQUEST['Operator'],$_REQUEST['FilterValue']));
    public function cnocGetActivityJson($from = null, $to = null, $filterName = null, $Operator = null, $FilterValue = null) {// Connection data (server_address, database, name, poassword)
        $option = '';
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


        $sql = "SELECT `SM_ticket`, `Cust_code`, `host_name`, `activity_reason`, `activity_date`, `realted_team`, `create_time`, `complete_time`, `assigned_to`, `activity_status`,activity_Startdate,ttr FROM cnoc_activities_v " . $option . "  ORDER BY STR_TO_DATE(replace(activity_date,'/',','),'%d,%m,%Y %T') ASC ";
        $html = "";
        //var_dump($sql);
        $result = array();
        try {
            $result = $this->query($sql, array(), PDO::FETCH_OBJ);
        } catch (PDOException $e) {

            return($e->getMessage());
        }
        return '{"activities":' . json_encode($result) . '}'; //json_encode($result);
    }

    /*     * *******************************************************
     * 
     * Delete Cnoc Activity provided by his ID 
     * ************************************************
     */

    public function cnocdeleteActivity() {
        /*
         * Fails if the proper action was not submitted
         */
        // echo "<script>alert('".$_POST['input-username']."')</script>";
        if ($_POST['action'] != 'CNOC_removeActivity' OR (htmlentities($_SESSION['user']['email'], ENT_QUOTES))=="dsm_r" OR !isset($_SESSION['user']['email'])) {
            return "Invalid action supplied for process Delete Forecast.";
        }
        /*
         * Escapes the user input for security
         */
        $Actvid = htmlentities($_POST['Activityid'], ENT_QUOTES);


        /*
         * Retrieves the matching info from the DB if it exists
         */
        $sql = "DELETE FROM `cnoc_activities` WHERE `activity_id`=:ser ";
        try {
            $res = $this->query($sql, array("ser" => $Actvid));
            $this->alertObj->setAlert('DELETE', 'cnoc_activities', $Actvid, 'Activity ID  :' . $Actvid . ' Deleted');
            $this->log->logActions($_SESSION['user']['email'], 'Delete CNOC Activity', ' Delete success ', 'Activity id :' . $Actvid . ' deleted');
        } catch (Exception $e) {
            // $this->db=null;
            $this->log->logActions($_SESSION['user']['email'], 'Delete Activity', 'Faild Delete due to ' . $e->getMessage(), 'Activity Id:' . $Actvid . ' ');
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

//cnoc Delete aACtivty

    public function cnocGetActivityStatusPercentage() {
        /*
          if ($_POST['action'] != 'CNOC_getActivityStatusPrecentage') {
          return false ;//"Invalid action supplied for retrive Activity assign History Data.";
          } */



        $sql = "SELECT `cc` as `y`, `activity_status` as `name` FROM cnoc_activitystatus_percentage ";
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

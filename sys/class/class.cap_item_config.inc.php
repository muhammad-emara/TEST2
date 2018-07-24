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
class cap_item_config extends DB_Connect {

    private $db;
    public $variables;

    public function __construct($data = array()) {

        parent::__construct();

        $this->variables = $data;
        // $this->db=  $this->pdo;
    }

    /** rigester for new users
     * 
     * 
     */
    //AddNewCnocHandoverCategory
    public function AddNewCapService() {

        $Cat_name = $_POST['input-catName'];
        $Launchdate = ($_POST['txt-Launchdate'] == null or $_POST['txt-Launchdate'] == '') ? null : date("Y-m-d", strtotime($_POST['txt-Launchdate']));
        $catOwner = $_POST['input-catOwner'];
        $qr = $this->query("INSERT INTO cap_service(`service_name`,`Service_owner`, `Lunch_date`, `add_by`) values('" . htmlentities($Cat_name) . "','" . htmlentities($catOwner) . "','" . htmlentities($Launchdate) . "','" . $_SESSION['user']['email'] . "')") or die(mysql_error());
        return $qr;
    }

    public function AddNewCapTechno() {

        $Cat_name = $_POST['input-catName'];
        $qr = $this->query("INSERT INTO cap_techno(`techno_name`, `add_by`) values('" . htmlentities($Cat_name) . "','" . $_SESSION['user']['email'] . "')") or die(mysql_error());
        return $qr;
    }
     public function AddNewCapinfraitem() {

        $Cat_name = $_POST['input-catName'];
        $qr = $this->query("INSERT INTO cap_infraitem(`resrc_name`, `add_by`) values('" . htmlentities($Cat_name) . "','" . $_SESSION['user']['email'] . "')") or die(mysql_error());
        return $qr;
    }
    public function AddNewCapspaceitem() {

        $Cat_name = $_POST['input-catName'];
        $qr = $this->query("INSERT INTO cap_spaceitem(`resrc_name`, `add_by`) values('" . htmlentities($Cat_name) . "','" . $_SESSION['user']['email'] . "')") or die(mysql_error());
        return $qr;
    }
    public function AddNewCapassetsitem() {

        $Cat_name = $_POST['input-catName'];
        $qr = $this->query("INSERT INTO cap_assetsitem(`resrc_name`, `add_by`) values('" . htmlentities($Cat_name) . "','" . $_SESSION['user']['email'] . "')") or die(mysql_error());
        return $qr;
    }
    public function AddNewCappoweritem() {

        $Cat_name = $_POST['input-catName'];
        $qr = $this->query("INSERT INTO cap_poweritem(`resrc_name`, `add_by`) values('" . htmlentities($Cat_name) . "','" . $_SESSION['user']['email'] . "')") or die(mysql_error());
        return $qr;
    }
    
     public function AddNewCapsectionitem() {

        $Cat_name = $_POST['input-catName'];
        $qr = $this->query("INSERT INTO cap_sectionitem(`resrc_name`, `add_by`) values('" . htmlentities($Cat_name) . "','" . $_SESSION['user']['email'] . "')") or die(mysql_error());
        return $qr;
    }

    public function AddNewCAPServiceTechno() {

        //print_r($_POST);die;

        $service_name = $_POST['select-cap-service_name'];
        $techno_name = htmlentities(is_array($_POST['select-cap-techno_name']) ? implode(', ', $_POST['select-cap-techno_name']) : $_POST['select-cap-techno_name'], ENT_QUOTES);

        // print_r($techno_name);die;
        $qr = $this->query("INSERT INTO cap_servicetechno(`service_name`, `tehno_name`, `add_by`) values('" . htmlentities($service_name) . "','" . htmlentities($techno_name) . "','" . $_SESSION['user']['email'] . "')") or die(mysql_error());
        return $qr;
    }

    public function AddNewCAPfutureService() {

        //print_r($_POST);die;

        $service_name = $_POST['select-cap-service_name'];
        $techno_name = htmlentities($_POST['serviceConsiderations']);

        // print_r($techno_name);die;
        $qr = $this->query("INSERT INTO cap_futureservice(`service_name`, `Considerations`, `add_by`) values('" . htmlentities($service_name) . "','" . htmlentities($techno_name) . "','" . $_SESSION['user']['email'] . "')") or die(mysql_error());
        return $qr;
    }
    
    
       public function AddNewCap_infrautili() {

        //print_r($_POST);die;

       $rsc_name = $_POST['select-cap-resrc_name'];
       $item_name = nl2br( $_POST['cap-infra_name']);
    
        $cpu_u = $_POST['txt-cap-resrc-cpu_u'];//utilizatrion
        $cpu_c = $_POST['txt-cap-resrc-cpu_c'];//utilizatrion
        $cpu_measure_util = $_POST['cpu_measure_util'];
        $memory_u= $_POST['txt-cap-resrc-memory_u'];
        $memory_c= $_POST['txt-cap-resrc-memory_c'];
        $memory_measure_util = $_POST['memory_measure_util'];
        $inter_u = $_POST['txt-cap-resrc-inter_u'];
        $inter_c = $_POST['txt-cap-resrc-inter_c'];
        $interface_measure_util = $_POST['interface_measure_util'];
        
        $CPU="('".$rsc_name."','".$item_name."','CPU','".$cpu_u." ".$cpu_measure_util."','".$cpu_c." ".$cpu_measure_util."','" . $_SESSION['user']['email'] . "')";
        $MEMORY="('".$rsc_name."','".$item_name."','Memory','".$memory_u." ".$memory_measure_util."','".$memory_c." ".$memory_measure_util."','" . $_SESSION['user']['email'] . "')";
        $Inter="('".$rsc_name."','".$item_name."','Interface/Disk','".$inter_u." ".$interface_measure_util."','".$inter_c." ".$interface_measure_util."','" . $_SESSION['user']['email'] . "')";

        // print_r($techno_name);die;
        //$qr = $this->query("INSERT INTO cap_futureservice(`service_name`, `Considerations`, `add_by`) values('" . htmlentities($service_name) . "','" . htmlentities($techno_name) . "','" . $_SESSION['user']['email'] . "')") or die(mysql_error());
        $qr = $this->query("INSERT INTO `cap_infrautli`(`resrc_name`,infra_details, `resrc_item`, `currentutilized`, `capacity`, `add_by`) VALUES " .$CPU.",".$MEMORY.",".$Inter ."ON DUPLICATE KEY UPDATE currentutilized=VALUES(currentutilized),capacity=VALUES(capacity),add_time=CURRENT_TIMESTAMP,add_by=VALUES(add_by)") or die(mysql_error());
        return $qr>0?TRUE:FALSE;
    }
    
    
    
    
      public function AddNewCap_manpowerutili() {

        //print_r($_POST);die;

       $rsc_name = htmlentities( $_POST['select-cap-manitem_nameutil']);
   
    
        $number_u = htmlentities( $_POST['txt-cap_man-number_u']);//utilizatrion
        $number_c = htmlentities( $_POST['txt-cap_man-number_c']);//utilizatrion

        $qr = $this->query("INSERT INTO `cap_powerutli`(`resrc_name`, `currentutilized`, `capacity`, `add_by`) VALUES ('" .$rsc_name."','".$number_u."','".$number_c ."','" . $_SESSION['user']['email'] . "') ON DUPLICATE KEY UPDATE currentutilized=VALUES(currentutilized),capacity=VALUES(capacity),add_time=CURRENT_TIMESTAMP,add_by=VALUES(add_by)") or die(mysql_error());
        return $qr>0?TRUE:FALSE;
    }

    public function biuldServiceTechnoTable() {// Connection data (server_address, database, name, poassword)
        $sql = "SELECT DISTINCT `servicetechno_id`, cap_servicetechno.`service_name`,cap_service.Service_owner,cap_service.Lunch_date ,cap_servicetechno.`tehno_name`, cap_servicetechno.`add_by`, cap_servicetechno.`add_time` FROM `cap_servicetechno` 
left JOIN
cap_service ON
cap_service.service_name=cap_servicetechno.service_name ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }






        $html = '<table class="table table-striped table-condensed table-bordered" id="sample_1">
										<thead>
											<tr>
												<th style="width:8px"><input type="checkbox" class="group-checkable" data-set=".checkboxes" /></th>
												
												<th class="hidden-phone">Service Name</th>
												<th class="hidden-phone">Service Owner</th>
												<th class="hidden-phone">Service Launch Date</th>
												<th class="hidden-phone">Techno Name</th>
												<th class="hidden-phone">Added By</th>
                                                                                                <th class="hidden-phone">Create Time</th>
												
												<th>Actions</th>
											</tr>
										</thead>
										<tbody>';
        foreach ($result as $row) {

            $html .= '<tr class="odd gradeX">
												<td><input type="checkbox" class="checkboxes" value="' . $row['service_name'] . '" /></td>
												
												
                                                                                                    <td>' . $row['service_name'] . '</td>
                                                                                                    <td>' . $row['Service_owner'] . '</td>
                                                                                                    
                                                                                                    <td>' . ($row['Lunch_date'] == '0000-00-00' ? 'NA' : (date('Y', strtotime($row['Lunch_date'])) . "-" . date('M', strtotime($row['Lunch_date'])))) . '</td>
                                                                                                        <td>' . $row['tehno_name'] . '</td>
                                                                                                     
												<td class="hidden-phone"><a href="mailto:' . $row['add_by'] . '@etisalat.ae">' . $row['add_by'] . '</a></td>
												<td class="hidden-phone">' . ($row['add_time'] == '' ? '-' : $row['add_time']) . '</td>
												
												';
            IF ((($_SESSION['currentRole'] == 'Admin')or ( $_SESSION['teamName'] == 'supperAdmins'))) {
                $html .= '<td class="center">
													
													<a href="#" class="icon huge"><i class="icon-remove" id="removeCapservTecho_' . $row['service_name'] . '" data-d="input-id=' . $row['servicetechno_id'] . '&token=' . $_SESSION['token'] . '&action=capServiceTechno_delete"></i></a>&nbsp;		
												</td>';
            } else {
                $html .= '<td class="center">You don\'t have AUTH </td>';
            }
            $html .= '</tr>';


            // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }




        $html .= '</tbody></table>';

        return $html;
    }

     public function biuldServiceTechnoTable_dashboard() {// Connection data (server_address, database, name, poassword)
        $sql = "SELECT DISTINCT cap_servicetechno.`service_name`,cap_servicetechno.`tehno_name` FROM `cap_servicetechno` left JOIN cap_service ON cap_service.service_name=cap_servicetechno.service_name  ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }





        $html = '<table style="width: 623px; margin-left: auto; margin-right: auto;" border="1" cellspacing="0" cellpadding="0">
										<thead>
											<tr>
                                <td valign="top" width="312">
                                    <h1 style="text-align: center;"><strong>Services</strong></h1>
                                </td>
                                <td valign="top" width="311">
                                    <h1 style="text-align: center;"><strong>Technology</strong></h1>
                                </td>
                            </tr>
										</thead>
										<tbody>';
        foreach ($result as $row) {

            $html .= '<tr>
                <td valign="top" width="312">
                                    <p><strong>' . $row['service_name'] . '</strong></p>
                                </td>
                                <td valign="top" width="311">
                                    <p>' . $row['tehno_name'] . '</p>
                                </td>';
		
            $html .= '</tr>';


            // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }




        $html .= '</tbody></table>';

        return $html;
    }

    
    public function biuldfutureServiceTable() {// Connection data (server_address, database, name, poassword)
        $sql = "SELECT DISTINCT `servicetechno_id`, cap_futureservice.`service_name`,Considerations,cap_service.Service_owner,cap_service.Lunch_date , cap_futureservice.`add_by`, cap_futureservice.`add_time` FROM `cap_futureservice` 
left JOIN
cap_service ON
cap_service.service_name=cap_futureservice.service_name ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }






        $html = '<table class="table table-striped table-condensed table-bordered" id="sample_1">
										<thead>
											<tr>
												<th style="width:8px"><input type="checkbox" class="group-checkable" data-set=".checkboxes" /></th>
												
												<th class="hidden-phone">Service Name</th>
												<th class="hidden-phone">Service Owner</th>
												<th class="hidden-phone">Considerations</th>
												<th class="hidden-phone">Expected Launch Date</th>
												<th class="hidden-phone">Added By</th>
                                                                                                <th class="hidden-phone">Create Time</th>
												
												<th>Actions</th>
											</tr>
										</thead>
										<tbody>';
        foreach ($result as $row) {

            $html .= '<tr class="odd gradeX">
												<td><input type="checkbox" class="checkboxes" value="' . $row['service_name'] . '" /></td>
												
												
                                                                                                    <td>' . $row['service_name'] . '</td>
                                                                                                    <td>' . $row['Service_owner'] . '</td>
                                                                                                    <td>' . $row['Considerations'] . '</td>
                                                                                                    <td>' . ($row['Lunch_date'] == '0000-00-00' ? 'NA' : (date('Y', strtotime($row['Lunch_date'])) . "-" . date('M', strtotime($row['Lunch_date'])))) . '</td>
                                                                                                     
												<td class="hidden-phone"><a href="mailto:' . $row['add_by'] . '@etisalat.ae">' . $row['add_by'] . '</a></td>
												<td class="hidden-phone">' . ($row['add_time'] == '' ? '-' : $row['add_time']) . '</td>
												
												';
            IF ((($_SESSION['currentRole'] == 'Admin')or ( $_SESSION['teamName'] == 'supperAdmins'))) {
                $html .= '<td class="center">
													
													<a href="#" class="icon huge"><i class="icon-remove" id="removeCapfutureservTecho_' . $row['service_name'] . '" data-d="input-id=' . $row['servicetechno_id'] . '&token=' . $_SESSION['token'] . '&action=CAPfutureService_delete"></i></a>&nbsp;		
												</td>';
            } else {
                $html .= '<td class="center">You don\'t have AUTH </td>';
            }
            $html .= '</tr>';


            // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }




        $html .= '</tbody></table>';

        return $html;
    }

    
        public function biuldCap_infrautiliTable() {// Connection data (server_address, database, name, poassword)
        $sql = "SELECT cap_infrautli_id,`resrc_name`, infra_details,`resrc_item`, `currentutilized`, `capacity`, `add_by`, `add_time` FROM `cap_infrautli` ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }






        $html = '<table class="table table-striped table-condensed table-bordered" id="sample_1">
										<thead>
											<tr>
												<th style="width:8px"><input type="checkbox" class="group-checkable" data-set=".checkboxes" /></th>
												
												<th class="hidden-phone">Resource Name</th>
												<th class="hidden-phone">Item Name</th>
												<th class="hidden-phone">Infra Item</th>
												<th class="hidden-phone">utilized</th>
												<th class="hidden-phone">Capacity</th>
												<th class="hidden-phone">Added By</th>
                                                                                                <th class="hidden-phone">Create/Update Time</th>
												
												<th>Actions</th>
											</tr>
										</thead>
										<tbody>';
        foreach ($result as $row) {

            $html .= '<tr class="odd gradeX">
												<td><input type="checkbox" class="checkboxes" value="' . $row['resrc_name'] . '" /></td>
												
												
                                                                                                    <td>' . $row['resrc_name'] . '</td>
                                                                                                    <td>' . $row['infra_details'] . '</td>
                                                                                                    <td>' . $row['resrc_item'] . '</td>
                                                                                                    <td>' . $row['currentutilized'] . '</td>
                                                                                                    <td>' . $row['capacity'] . '</td>
                                                                                                        <td class="hidden-phone"><a href="mailto:' . $row['add_by'] . '@etisalat.ae">' . $row['add_by'] . '</a></td>
                                                                                                    <!--<td>' . ($row['add_time'] == '0000-00-00' ? 'NA' : (date('Y', strtotime($row['add_time'])) . "-" . date('M', strtotime($row['add_time'])))) . '</td>-->
                                                                                                    <td>' .$row['add_time']. '</td>
                                                                                                     
												
												
												
												';
            IF ((($_SESSION['currentRole'] == 'Admin')or ( $_SESSION['teamName'] == 'supperAdmins'))) {
                $html .= '<td class="center">
													
													<a href="#" class="icon huge"><i class="icon-remove" id="removeCapresutli_' . $row['cap_infrautli_id'] . '" data-d="input-id2=' . $row['cap_infrautli_id'].'&input-id=' . $row['resrc_name'] . '&token=' . $_SESSION['token'] . '&action=Capresutli_delete"></i></a>&nbsp;		
												</td>';
            } else {
                $html .= '<td class="center">You don\'t have AUTH </td>';
            }
            $html .= '</tr>';


            // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }




        $html .= '</tbody></table>';

        return $html;
    }
     public function biuldCap_infrautiliTable_dashboard() {// Connection data (server_address, database, name, poassword)
        $sql = "SELECT distinct `resrc_name`,infra_details, `resrc_item`, `currentutilized`, `capacity` FROM `cap_infrautli` order by `resrc_name`,infra_details, `resrc_item` ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }






        $html = '<table style="width: 623px; margin-left: auto; margin-right: auto;" border="1" cellspacing="0" cellpadding="0">
										<thead>
											   <tr>
                                <td valign="top" width="195">
                                    <p align="center"><strong>Resources</strong></p>
                                </td>
                                <td colspan="3" valign="top" width="210">
                                    <p align="center"><strong>Current Utilized</strong></p>
                                </td>
                                <td colspan="3" valign="top" width="219">
                                    <p align="center"><strong>Capacity</strong></p>
                                </td>
                            </tr>
										</thead>
										<tbody>';
        $resc_tmp='';
        $item_tmp='';
        $cpu_u='';
        $cpu_c='';
        $memo_u='';
        $memo_c='';
        $interface_u='';
        $interface_c='';
        $flag=0;
        
       
       // var_dump($result);
        
        foreach ($result as $row) {
            $flag++;
           // echo  "<br>-------------------------------<br/>loop: ".$flag."<br/>";
            
            if($resc_tmp==''){
                //echo "res_temp is ".$resc_tmp."<br/>";
                $resc_tmp=$row['resrc_name'];
                $item_tmp=$row['infra_details'];
                // echo "res_temp is now ".$resc_tmp."<br/>";
               //  echo "item_tmp is now ".$item_tmp."<br/>";
                
            $temphtml='<tr>
                                <td valign="top" width="195">
                                    <p><strong>'.$resc_tmp.'</strong></p>
                                </td>
                                <td valign="top" width="60">
                                    <p align="center"><strong>CPU</strong></p>
                                </td>
                                <td valign="top" width="65">
                                    <p align="center"><strong>Memory</strong></p>
                                </td>
                                <td valign="top" width="84">
                                    <p align="center"><strong>Interface</strong></p>
                                </td>
                                <td valign="top" width="65">
                                    <p align="center"><strong>CPU</strong></p>
                                </td>
                                <td valign="top" width="72">
                                    <p align="center"><strong>Memory</strong></p>
                                </td>
                                <td valign="top" width="83">
                                    <p align="center"><strong>Interface/Disk</strong></p>
                                </td>
                            </tr>';
            
         //     echo '<br/> strpos1 is ';
      //  var_dump(strpos($html,$temphtml));
       // echo '<br/>';
            $html.= strpos($html,$temphtml)!=FALSE?'':$temphtml;
           // echo'<br/>'.$html.'<br/>';
            
            
            }elseif($resc_tmp!=$row['resrc_name']){
           //      echo "res_temp2 is ".$resc_tmp."<br/>";
//                $html.='<tr>
//                                <td valign="top" width="195">
//                                    <p><strong>'.$resc_tmp.'</strong></p>
//                                </td>
//                                <td valign="top" width="60">
//                                    <p align="center"><strong>CPU</strong></p>
//                                </td>
//                                <td valign="top" width="65">
//                                    <p align="center"><strong>Memory</strong></p>
//                                </td>
//                                <td valign="top" width="84">
//                                    <p align="center"><strong>Interface</strong></p>
//                                </td>
//                                <td valign="top" width="65">
//                                    <p align="center"><strong>CPU</strong></p>
//                                </td>
//                                <td valign="top" width="72">
//                                    <p align="center"><strong>Memory</strong></p>
//                                </td>
//                                <td valign="top" width="83">
//                                    <p align="center"><strong>Interface/Disk</strong></p>
//                                </td>
//                            </tr>';
//                
//                $html.='<tr>
//                                <td valign="top" width="195">
//                                    <p>'.$item_tmp.'</p>
//                                </td>
//                                <td valign="top" width="60">
//                                    <p align="center">'.$cpu_u.'</p>
//                                </td>
//                                <td valign="top" width="65">
//                                    <p align="center">'.$memo_u.'</p>
//                                    <p align="center">&nbsp;</p>
//                                </td>
//                                <td valign="top" width="84">
//                                    <p align="center">'.$interface_u.'</p>
//                                    <p align="center">&nbsp;</p>
//                                </td>
//                                <td valign="top" width="65">
//                                    <p align="center">'.$cpu_c.'</p>
//                                </td>
//                                <td valign="top" width="72">
//                                    <p align="center">'.$memo_c.'</p>
//                                </td>
//                                <td valign="top" width="83">
//                                    <p align="center">'.$interface_c.'</p>
//                                    <p align="center">&nbsp;</p>
//                                </td>
//                            </tr>';
//               
//            
//             $cpu_u='';
//        $cpu_c='';
//        $memo_u='';
//        $memo_c='';
//        $interface_u='';
//        $interface_c='';
//                $resc_tmp=$row['resrc_name'];
//                $item_tmp=$row['infra_details'];
         //        echo "res_temp2 is now ".$resc_tmp."<br/>";
         //        echo "item_tmp2 is now ".$item_tmp."<br/>";
                
            }
            
           // echo $resc_tmp.'<br/>';
            if($item_tmp!=$row['infra_details'])
            {// add new header
               $temphtml='<tr>
                                <td valign="top" width="195">
                                    <p><strong>'.$resc_tmp.'</strong></p>
                                </td>
                                <td valign="top" width="60">
                                    <p align="center"><strong>CPU</strong></p>
                                </td>
                                <td valign="top" width="65">
                                    <p align="center"><strong>Memory</strong></p>
                                </td>
                                <td valign="top" width="84">
                                    <p align="center"><strong>Interface</strong></p>
                                </td>
                                <td valign="top" width="65">
                                    <p align="center"><strong>CPU</strong></p>
                                </td>
                                <td valign="top" width="72">
                                    <p align="center"><strong>Memory</strong></p>
                                </td>
                                <td valign="top" width="83">
                                    <p align="center"><strong>Interface/Disk</strong></p>
                                </td>
                            </tr>';
           //    echo '<br/> strpos2 is ';
       // var_dump(strpos($html,$temphtml));
      //  echo '<br/>';
                
                 $html.= strpos($html,$temphtml)!=FALSE?'':$temphtml;
                
               //  echo "item_tmp2 is ".$item_tmp."<br/>";
               
                
                $html.='<tr>
                                <td valign="top" width="195">
                                    <p>'.$item_tmp.'</p>
                                </td>
                                <td valign="top" width="60">
                                    <p align="center">'.$cpu_u.'</p>
                                </td>
                                <td valign="top" width="65">
                                    <p align="center">'.$memo_u.'</p>
                                    <p align="center">&nbsp;</p>
                                </td>
                                <td valign="top" width="84">
                                    <p align="center">'.$interface_u.'</p>
                                    <p align="center">&nbsp;</p>
                                </td>
                                <td valign="top" width="65">
                                    <p align="center">'.$cpu_c.'</p>
                                </td>
                                <td valign="top" width="72">
                                    <p align="center">'.$memo_c.'</p>
                                </td>
                                <td valign="top" width="83">
                                    <p align="center">'.$interface_c.'</p>
                                    <p align="center">&nbsp;</p>
                                </td>
                            </tr>';
               
            
             $cpu_u='';
        $cpu_c='';
        $memo_u='';
        $memo_c='';
        $interface_u='';
        $interface_c='';
     $item_tmp=$row['infra_details'];
     $resc_tmp=$row['resrc_name'];
   //  echo "item_tmp2 now is ".$item_tmp."<br/>";
                
            }
           // echo $row['resrc_item'].'<br/>';
            switch ($row['resrc_item']) {
                case "CPU":
                    $cpu_c=$row['capacity'];
                    $cpu_u=$row['currentutilized'];
                 //   echo '<br/>CPU_u is '.$cpu_u.' -CPU_c '.$cpu_c.'<br/>';
                   


                    break;
                case "Interface/Disk":
                    $interface_c=$row['capacity'];
                    $interface_u=$row['currentutilized'];
                  //  echo '<br/>disk_u is '.$interface_u.' -disk_c '.$interface_c.'<br/>';


                    break;
                case "Memory":
                    $memo_c=$row['capacity'];
                    $memo_u=$row['currentutilized'];
                //    echo '<br/>memo_u is '.$memo_u.' -memo_c '.$memo_c.'<br/>';


                    break;

            }
            

           
         


            // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }
        
        
        /***************display the last row ****************************/
        $temphtml='<tr>
                                <td valign="top" width="195">
                                    <p><strong>'.$resc_tmp.'</strong></p>
                                </td>
                                <td valign="top" width="60">
                                    <p align="center"><strong>CPU</strong></p>
                                </td>
                                <td valign="top" width="65">
                                    <p align="center"><strong>Memory</strong></p>
                                </td>
                                <td valign="top" width="84">
                                    <p align="center"><strong>Interface</strong></p>
                                </td>
                                <td valign="top" width="65">
                                    <p align="center"><strong>CPU</strong></p>
                                </td>
                                <td valign="top" width="72">
                                    <p align="center"><strong>Memory</strong></p>
                                </td>
                                <td valign="top" width="83">
                                    <p align="center"><strong>Interface/Disk</strong></p>
                                </td>
                            </tr>';
     //   echo '<br/> strpos3 is ';
     //   var_dump(strpos($html,$temphtml));
     //   echo '<br/>';
        
         $html.= strpos($html,$temphtml)!=FALSE?'':$temphtml;
                $html.='<tr>
                                <td valign="top" width="195">
                                    <p>'.$item_tmp.'</p>
                                </td>
                                <td valign="top" width="60">
                                    <p align="center">'.$cpu_u.'</p>
                                </td>
                                <td valign="top" width="65">
                                    <p align="center">'.$memo_u.'</p>
                                    <p align="center">&nbsp;</p>
                                </td>
                                <td valign="top" width="84">
                                    <p align="center">'.$interface_u.'</p>
                                    <p align="center">&nbsp;</p>
                                </td>
                                <td valign="top" width="65">
                                    <p align="center">'.$cpu_c.'</p>
                                </td>
                                <td valign="top" width="72">
                                    <p align="center">'.$memo_c.'</p>
                                </td>
                                <td valign="top" width="83">
                                    <p align="center">'.$interface_c.'</p>
                                    <p align="center">&nbsp;</p>
                                </td>
                            </tr>';
               
            
             $cpu_u='';
        $cpu_c='';
        $memo_u='';
        $memo_c='';
        $interface_u='';
        $interface_c='';
 /***************END display the last row ****************************/



        $html .= '</tbody></table>';

        return $html;
    }
    
        
        public function biuldCap_mapowertiliTable() {// Connection data (server_address, database, name, poassword)
        $sql = "SELECT `resrc_name`, `currentutilized`, `capacity`, `add_by`, `add_time` FROM `cap_powerutli` ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }






        $html = '<table class="table table-striped table-condensed table-bordered" id="sample_1">
										<thead>
											<tr>
												<th style="width:8px"><input type="checkbox" class="group-checkable" data-set=".checkboxes" /></th>
												
												<th class="hidden-phone">Resource Name</th>
												<th class="hidden-phone">Current Utilized</th>												
												<th class="hidden-phone">Capacity</th>
												<th class="hidden-phone">Added By</th>
                                                                                                <th class="hidden-phone">Create/Update Time</th>
												
												<th>Actions</th>
											</tr>
										</thead>
										<tbody>';
        foreach ($result as $row) {

            $html .= '<tr class="odd gradeX">
												<td><input type="checkbox" class="checkboxes" value="' . $row['resrc_name'] . '" /></td>
												
												
                                                                                                    <td>' . $row['resrc_name'] . '</td>                                                                                                   
                                                                                                    <td>' . $row['currentutilized'] . '</td>
                                                                                                    <td>' . $row['capacity'] . '</td>
                                                                                                        <td class="hidden-phone"><a href="mailto:' . $row['add_by'] . '@etisalat.ae">' . $row['add_by'] . '</a></td>
                                                                                                    <!--<td>' . ($row['add_time'] == '0000-00-00' ? 'NA' : (date('Y', strtotime($row['add_time'])) . "-" . date('M', strtotime($row['add_time'])))) . '</td>-->
                                                                                                    <td>' .$row['add_time']. '</td>
                                                                                                     
												
												
												
												';
            IF ((($_SESSION['currentRole'] == 'Admin')or ( $_SESSION['teamName'] == 'supperAdmins'))) {
                $html .= '<td class="center">
													
													<a href="#" class="icon huge"><i class="icon-remove" id="removeCapmanutli_' . $row['resrc_name'] . '" data-d="input-id2=' . $row['resrc_name'].'&input-id=' . $row['resrc_name'] . '&token=' . $_SESSION['token'] . '&action=Capmanutli_delete"></i></a>&nbsp;		
												</td>';
            } else {
                $html .= '<td class="center">You don\'t have AUTH </td>';
            }
            $html .= '</tr>';


            // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }




        $html .= '</tbody></table>';

        return $html;
    }
     public function biuldCap_mapowertiliTable_dashboard() {// Connection data (server_address, database, name, poassword)
        $sql = "SELECT distinct `resrc_name`, `currentutilized`, `capacity` FROM `cap_powerutli` ";
       $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }






        $html = '<table style="width: 623px; margin-left: auto; margin-right: auto;" border="1" cellspacing="0" cellpadding="0">
<thead>
<tr>
<td valign="top" width="195">
<p align="center"><span style="font-size: medium;"><strong>Resources</strong></span></p>
</td>
<td valign="top" width="210">
<p align="center"><span style="font-size: medium;"><strong>Current Utilized</strong></span></p>
</td>
<td valign="top" width="219">
<p align="center"><span style="font-size: medium;"><strong>Capacity</strong></span></p>
</td>
</tr>
</thead>
										<tbody>';
        $total_satff='';
        $etisalat_satff='';
        $out_satff='';
        foreach ($result as $row) {
            switch ($row['resrc_name']) {
                case "Etisalat Staff":
                    $etisalat_satff='<tr class="odd gradeX">
												
                                                                                                    <td><p><span style="font-size: medium;"><strong>' . $row['resrc_name'] . '</strong></span></p></td>                                                                                                   
                                                                                                    <td>' . $row['currentutilized'] . '</td>
                                                                                                    <td>' . $row['capacity'] . '</td></tr>
                                                                                                ';
           
                    
                     break;
                case "Outsource staff":
                        $out_satff='<tr class="odd gradeX">
												
                                                                                                    <td><p><span style="font-size: medium;"><strong>' . $row['resrc_name'] . '</strong></span></p></td>                                                                                                   
                                                                                                    <td>' . $row['currentutilized'] . '</td>
                                                                                                    <td>' . $row['capacity'] . '</td></tr>
                                                                                                ';
           
                     break;
                case "Total MSS Staff":
                        $total_satff='<tr class="odd gradeX">
												
                                                                                                    <td><p><span style="font-size: medium;"><strong>' . $row['resrc_name'] . '</strong></span></p></td>                                                                                                   
                                                                                                    <td>' . $row['currentutilized'] . '</td>
                                                                                                    <td>' . $row['capacity'] . '</td></tr>
                                                                                                ';
           
                 


                    break;

                default:
                    $html .= '<tr class="odd gradeX">
												
                                                                                                    <td><p><span style="font-size: medium;"><strong>' . $row['resrc_name'] . '</strong></span></p></td>                                                                                                   
                                                                                                    <td>' . $row['currentutilized'] . '</td>
                                                                                                    <td>' . $row['capacity'] . '</td>
                                                                                                ';
           
            $html .= '</tr>';
                    break;
            }
            

            


            // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }




        $html .=$total_satff.$etisalat_satff.$out_satff. '</tbody></table>';

        return $html;
    }
    
    
    /**************************************Space Utilization *********************/
    
      public function AddNewCap_spacepowerutili() {

        //print_r($_POST);die;

       $rsc_name = htmlentities( $_POST['select-cap-manitem_nameutil']);
   
    
        $number_u = htmlentities( $_POST['txt-cap_man-number_u']);//utilizatrion
        $number_c = htmlentities( $_POST['txt-cap_man-number_c']);//utilizatrion

        $qr = $this->query("INSERT INTO `cap_spaceutli`(`resrc_name`, `currentutilized`, `capacity`, `add_by`) VALUES ('" .$rsc_name."','".$number_u."','".$number_c ."','" . $_SESSION['user']['email'] . "') ON DUPLICATE KEY UPDATE currentutilized=VALUES(currentutilized),capacity=VALUES(capacity),add_time=CURRENT_TIMESTAMP,add_by=VALUES(add_by)") or die(mysql_error());
        return $qr>0?TRUE:FALSE;
    }
    
         public function processDeleteCapspaceutli() {
        /*
         * Fails if the proper action was not submitted
         */
        // echo "<script>alert('".$_POST['input-username']."')</script>";
//        if ($_POST['action'] != 'bibZone_delete') {
//            return "Invalid action supplied for process Delete CNOC Handover Category.";
//        }
        if ($_POST['action'] != 'Capspaceutli_delete') {
            return "Invalid action supplied for process Delete Capcity Space Utility.";
        }
        /*
         * Escapes the user input for security
         */
        $unid = htmlentities($_POST['input-id'], ENT_QUOTES);
        $unid2 = $_POST['input-id2'];
       // $infra_itms= explode('\n',$unid2);
       // print_r($infra_itms);die;
        


        /*
         * Retrieves the matching info from the DB if it exists
         */
        $sql = "delete from cap_spaceutli where resrc_name=:id2";
        //print $sql; print $unid2;die;
        try {
            $res = $this->query($sql, array("id2" => $unid));
            $this->log->logActions($_SESSION['user']['email'], 'Delete Cap Mapower utiliti', 'Success Delete', 'Resource ID :' . $unid.'-'.$unid2 . ' deleted');
        } catch (Exception $e) {
            // $this->db=null;
            return ($e->getMessage());
            $this->log->logActions($_SESSION['user']['email'], 'Delete Cap Resource', 'Faild Delete due to ' . $e->getMessage(), 'Cap Mapower ID :' . $unid.'-'.$unid2 . ' deleted');
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
    
    
     public function biuldCap_spacepowertiliTable() {// Connection data (server_address, database, name, poassword)
        $sql = "SELECT `resrc_name`, `currentutilized`, `capacity`, `add_by`, `add_time` FROM `cap_spaceutli` ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }






        $html = '<table class="table table-striped table-condensed table-bordered" id="sample_1">
										<thead>
											<tr>
												<th style="width:8px"><input type="checkbox" class="group-checkable" data-set=".checkboxes" /></th>
												
												<th class="hidden-phone">Resource Name</th>
												<th class="hidden-phone">Current Utilized</th>												
												<th class="hidden-phone">Capacity</th>
												<th class="hidden-phone">Added By</th>
                                                                                                <th class="hidden-phone">Create/Update Time</th>
												
												<th>Actions</th>
											</tr>
										</thead>
										<tbody>';
        foreach ($result as $row) {

            $html .= '<tr class="odd gradeX">
												<td><input type="checkbox" class="checkboxes" value="' . $row['resrc_name'] . '" /></td>
												
												
                                                                                                    <td>' . $row['resrc_name'] . '</td>                                                                                                   
                                                                                                    <td>' . $row['currentutilized'] . '</td>
                                                                                                    <td>' . $row['capacity'] . '</td>
                                                                                                        <td class="hidden-phone"><a href="mailto:' . $row['add_by'] . '@etisalat.ae">' . $row['add_by'] . '</a></td>
                                                                                                    <!--<td>' . ($row['add_time'] == '0000-00-00' ? 'NA' : (date('Y', strtotime($row['add_time'])) . "-" . date('M', strtotime($row['add_time'])))) . '</td>-->
                                                                                                    <td>' .$row['add_time']. '</td>
                                                                                                     
												
												
												
												';
            IF ((($_SESSION['currentRole'] == 'Admin')or ( $_SESSION['teamName'] == 'supperAdmins'))) {
                $html .= '<td class="center">
													
													<a href="#" class="icon huge"><i class="icon-remove" id="removeCapspaceutli_' . $row['resrc_name'] . '" data-d="input-id2=' . $row['resrc_name'].'&input-id=' . $row['resrc_name'] . '&token=' . $_SESSION['token'] . '&action=Capspaceutli_delete"></i></a>&nbsp;		
												</td>';
            } else {
                $html .= '<td class="center">You don\'t have AUTH </td>';
            }
            $html .= '</tr>';


            // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }




        $html .= '</tbody></table>';

        return $html;
    }
     public function biuldCap_spacepowertiliTable_dashboard() {// Connection data (server_address, database, name, poassword)
        $sql = "SELECT distinct `resrc_name`, `currentutilized`, `capacity` FROM `cap_spaceutli` ";
       $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }






        $html = '<table style="width: 623px; margin-left: auto; margin-right: auto;" border="1" cellspacing="0" cellpadding="0">
<thead>
<tr>
<td valign="top" width="195">
<p align="center"><span style="font-size: medium;"><strong>Resources</strong></span></p>
</td>
<td valign="top" width="210">
<p align="center"><span style="font-size: medium;"><strong>Current Utilized</strong></span></p>
</td>
<td valign="top" width="219">
<p align="center"><span style="font-size: medium;"><strong>Capacity</strong></span></p>
</td>
</tr>
</thead>
										<tbody>';
        $total_satff='';
        $etisalat_satff='';
        $out_satff='';
        foreach ($result as $row) {
            switch ($row['resrc_name']) {
                case "Etisalat Staff":
                    $etisalat_satff='<tr class="odd gradeX">
												
                                                                                                    <td><p><span style="font-size: medium;"><strong>' . $row['resrc_name'] . '</strong></span></p></td>                                                                                                   
                                                                                                    <td>' . $row['currentutilized'] . '</td>
                                                                                                    <td>' . $row['capacity'] . '</td></tr>
                                                                                                ';
           
                    
                     break;
                case "Outsource staff":
                        $out_satff='<tr class="odd gradeX">
												
                                                                                                    <td><p><span style="font-size: medium;"><strong>' . $row['resrc_name'] . '</strong></span></p></td>                                                                                                   
                                                                                                    <td>' . $row['currentutilized'] . '</td>
                                                                                                    <td>' . $row['capacity'] . '</td></tr>
                                                                                                ';
           
                     break;
                case "Total MSS Staff":
                        $total_satff='<tr class="odd gradeX">
												
                                                                                                    <td><p><span style="font-size: medium;"><strong>' . $row['resrc_name'] . '</strong></span></p></td>                                                                                                   
                                                                                                    <td>' . $row['currentutilized'] . '</td>
                                                                                                    <td>' . $row['capacity'] . '</td></tr>
                                                                                                ';
           
                 


                    break;

                default:
                    $html .= '<tr class="odd gradeX">
												
                                                                                                    <td><p><span style="font-size: medium;"><strong>' . $row['resrc_name'] . '</strong></span></p></td>                                                                                                   
                                                                                                    <td>' . $row['currentutilized'] . '</td>
                                                                                                    <td>' . $row['capacity'] . '</td>
                                                                                                ';
           
            $html .= '</tr>';
                    break;
            }
            

            


            // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }




        $html .=$total_satff.$etisalat_satff.$out_satff. '</tbody></table>';

        return $html;
    }
    
    
    /***************end of space utilization ***************************/
    
      /**************************************Fixed Asset Utilization *********************/
    
      public function AddNewCap_assetpowerutili() {

        //print_r($_POST);die;

       $rsc_name = htmlentities( $_POST['select-cap-manitem_nameutil']);
   
    
        $number_u = htmlentities( $_POST['txt-cap_man-number_u']);//utilizatrion
        $number_c = htmlentities( $_POST['txt-cap_man-number_c']);//utilizatrion

        $qr = $this->query("INSERT INTO `cap_assetutli`(`resrc_name`, `currentutilized`, `capacity`, `add_by`) VALUES ('" .$rsc_name."','".$number_u."','".$number_c ."','" . $_SESSION['user']['email'] . "') ON DUPLICATE KEY UPDATE currentutilized=VALUES(currentutilized),capacity=VALUES(capacity),add_time=CURRENT_TIMESTAMP,add_by=VALUES(add_by)") or die(mysql_error());
        return $qr>0?TRUE:FALSE;
    }
    
         public function processDeleteCapassetutli() {
        /*
         * Fails if the proper action was not submitted
         */
        // echo "<script>alert('".$_POST['input-username']."')</script>";
//        if ($_POST['action'] != 'bibZone_delete') {
//            return "Invalid action supplied for process Delete CNOC Handover Category.";
//        }
        if ($_POST['action'] != 'Capassetutli_delete') {
            return "Invalid action supplied for process Delete Capcity Assets Utility.";
        }
        /*
         * Escapes the user input for security
         */
        $unid = htmlentities($_POST['input-id'], ENT_QUOTES);
        $unid2 = $_POST['input-id2'];
       // $infra_itms= explode('\n',$unid2);
       // print_r($infra_itms);die;
        


        /*
         * Retrieves the matching info from the DB if it exists
         */
        $sql = "delete from cap_assetutli where resrc_name=:id2";
        //print $sql; print $unid2;die;
        try {
            $res = $this->query($sql, array("id2" => $unid));
            $this->log->logActions($_SESSION['user']['email'], 'Delete Cap Mapower utiliti', 'Success Delete', 'Resource ID :' . $unid.'-'.$unid2 . ' deleted');
        } catch (Exception $e) {
            // $this->db=null;
            return ($e->getMessage());
            $this->log->logActions($_SESSION['user']['email'], 'Delete Cap Resource', 'Faild Delete due to ' . $e->getMessage(), 'Cap Mapower ID :' . $unid.'-'.$unid2 . ' deleted');
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
    
    
     public function biuldCap_assetpowertiliTable() {// Connection data (server_address, database, name, poassword)
        $sql = "SELECT `resrc_name`, `currentutilized`, `capacity`, `add_by`, `add_time` FROM `cap_assetutli` ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }






        $html = '<table class="table table-striped table-condensed table-bordered" id="sample_1">
										<thead>
											<tr>
												<th style="width:8px"><input type="checkbox" class="group-checkable" data-set=".checkboxes" /></th>
												
												<th class="hidden-phone">Resource Name</th>
												<th class="hidden-phone">Current Utilized</th>												
												<th class="hidden-phone">Capacity</th>
												<th class="hidden-phone">Added By</th>
                                                                                                <th class="hidden-phone">Create/Update Time</th>
												
												<th>Actions</th>
											</tr>
										</thead>
										<tbody>';
        foreach ($result as $row) {

            $html .= '<tr class="odd gradeX">
												<td><input type="checkbox" class="checkboxes" value="' . $row['resrc_name'] . '" /></td>
												
												
                                                                                                    <td>' . $row['resrc_name'] . '</td>                                                                                                   
                                                                                                    <td>' . $row['currentutilized'] . '</td>
                                                                                                    <td>' . $row['capacity'] . '</td>
                                                                                                        <td class="hidden-phone"><a href="mailto:' . $row['add_by'] . '@etisalat.ae">' . $row['add_by'] . '</a></td>
                                                                                                    <!--<td>' . ($row['add_time'] == '0000-00-00' ? 'NA' : (date('Y', strtotime($row['add_time'])) . "-" . date('M', strtotime($row['add_time'])))) . '</td>-->
                                                                                                    <td>' .$row['add_time']. '</td>
                                                                                                     
												
												
												
												';
            IF ((($_SESSION['currentRole'] == 'Admin')or ( $_SESSION['teamName'] == 'supperAdmins'))) {
                $html .= '<td class="center">
													
													<a href="#" class="icon huge"><i class="icon-remove" id="removeCapassetutli_' . $row['resrc_name'] . '" data-d="input-id2=' . $row['resrc_name'].'&input-id=' . $row['resrc_name'] . '&token=' . $_SESSION['token'] . '&action=Capassetutli_delete"></i></a>&nbsp;		
												</td>';
            } else {
                $html .= '<td class="center">You don\'t have AUTH </td>';
            }
            $html .= '</tr>';


            // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }




        $html .= '</tbody></table>';

        return $html;
    }
     public function biuldCap_assetpowertiliTable_dashboard() {// Connection data (server_address, database, name, poassword)
        $sql = "SELECT distinct `resrc_name`, `currentutilized`, `capacity` FROM `cap_assetutli` ";
       $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }






        $html = '<table style="width: 623px; margin-left: auto; margin-right: auto;" border="1" cellspacing="0" cellpadding="0">
<thead>
<tr>
<td valign="top" width="195">
<p align="center"><span style="font-size: medium;"><strong>Resources</strong></span></p>
</td>
<td valign="top" width="210">
<p align="center"><span style="font-size: medium;"><strong>Current Utilized</strong></span></p>
</td>
<td valign="top" width="219">
<p align="center"><span style="font-size: medium;"><strong>Capacity</strong></span></p>
</td>
</tr>
</thead>
										<tbody>';
        $total_satff='';
        $etisalat_satff='';
        $out_satff='';
        foreach ($result as $row) {
            switch ($row['resrc_name']) {
                case "Etisalat Staff":
                    $etisalat_satff='<tr class="odd gradeX">
												
                                                                                                    <td><p><span style="font-size: medium;"><strong>' . $row['resrc_name'] . '</strong></span></p></td>                                                                                                   
                                                                                                    <td>' . $row['currentutilized'] . '</td>
                                                                                                    <td>' . $row['capacity'] . '</td></tr>
                                                                                                ';
           
                    
                     break;
                case "Outsource staff":
                        $out_satff='<tr class="odd gradeX">
												
                                                                                                    <td><p><span style="font-size: medium;"><strong>' . $row['resrc_name'] . '</strong></span></p></td>                                                                                                   
                                                                                                    <td>' . $row['currentutilized'] . '</td>
                                                                                                    <td>' . $row['capacity'] . '</td></tr>
                                                                                                ';
           
                     break;
                case "Total MSS Staff":
                        $total_satff='<tr class="odd gradeX">
												
                                                                                                    <td><p><span style="font-size: medium;"><strong>' . $row['resrc_name'] . '</strong></span></p></td>                                                                                                   
                                                                                                    <td>' . $row['currentutilized'] . '</td>
                                                                                                    <td>' . $row['capacity'] . '</td></tr>
                                                                                                ';
           
                 


                    break;

                default:
                    $html .= '<tr class="odd gradeX">
												
                                                                                                    <td><p><span style="font-size: medium;"><strong>' . $row['resrc_name'] . '</strong></span></p></td>                                                                                                   
                                                                                                    <td>' . $row['currentutilized'] . '</td>
                                                                                                    <td>' . $row['capacity'] . '</td>
                                                                                                ';
           
            $html .= '</tr>';
                    break;
            }
            

            


            // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }




        $html .=$total_satff.$etisalat_satff.$out_satff. '</tbody></table>';

        return $html;
    }
    
    
    /***************end of Fixed Asset utilization ***************************/
    
    
    
    
       public function biuldfutureServiceTable_dashboard() {// Connection data (server_address, database, name, poassword)
        $sql = "SELECT DISTINCT cap_futureservice.`service_name`,Considerations FROM `cap_futureservice` left JOIN cap_service ON cap_service.service_name=cap_futureservice.service_name   ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }





        $html = '<table style="width: 623px; margin-left: auto; margin-right: auto;" border="1" cellspacing="0" cellpadding="0">
										<thead>
											<tr>
                                <td valign="top" width="312">
                                    <h1 style="text-align: center;"><strong>Services</strong></h1>
                                </td>
                                <td valign="top" width="311">
                                    <h1 style="text-align: center;"><strong>Considerations</strong></h1>
                                </td>
                            </tr>
										</thead>
										<tbody>';
        foreach ($result as $row) {

            $html .= '<tr>
                <td valign="top" width="312">
                                    <p><strong>' . $row['service_name'] . '</strong></p>
                                </td>
                                <td valign="top" width="311">
                                    <p>' . $row['Considerations'] . '</p>
                                </td>';
		
            $html .= '</tr>';


            // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }




        $html .= '</tbody></table>';

        return $html;
    }

    
    /** get all Cncoc Categories for table view
     *
     * @return mixed TRUE on success, message on error
     */
    public function biuldServicesTable() {// Connection data (server_address, database, name, poassword)
        $sql = "SELECT distinct `service_name`,`Service_owner`, `Lunch_date`, `add_by`, `add_time` FROM `cap_service` order by Lunch_date ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }






        $html = '<table class="table table-striped table-condensed table-bordered" id="sample_1">
										<thead>
											<tr>
												<th style="width:8px"><input type="checkbox" class="group-checkable" data-set=".checkboxes" /></th>
												
												<th class="hidden-phone">Service Name</th>
												<th class="hidden-phone">Service Owner</th>
												<th class="hidden-phone">Service Launch Date</th>
												<th class="hidden-phone">Added By</th>
                                                                                                <th class="hidden-phone">Create Time</th>
												
												<th>Actions</th>
											</tr>
										</thead>
										<tbody>';
        foreach ($result as $row) {

            $html .= '<tr class="odd gradeX">
												<td><input type="checkbox" class="checkboxes" value="' . $row['service_name'] . '" /></td>
												
												
                                                                                                    <td>' . $row['service_name'] . '</td>
                                                                                                    <td>' . $row['Service_owner'] . '</td>
                                                                                                    <td>' . ($row['Lunch_date'] == '0000-00-00' ? 'NA' : (date('Y', strtotime($row['Lunch_date'])) . "-" . date('M', strtotime($row['Lunch_date'])))) . '</td>
                                                                                                     
												<td class="hidden-phone"><a href="mailto:' . $row['add_by'] . '@etisalat.ae">' . $row['add_by'] . '</a></td>
												<td class="hidden-phone">' . ($row['add_time'] == '' ? '-' : $row['add_time']) . '</td>
												
												';
            IF ((($_SESSION['currentRole'] == 'Admin')or ( $_SESSION['teamName'] == 'supperAdmins'))) {
                $html .= '<td class="center">
													
													<a href="#" class="icon huge"><i class="icon-remove" id="removeCapCat_' . $row['service_name'] . '" data-d="input-id=' . $row['service_name'] . '&token=' . $_SESSION['token'] . '&action=capService_delete"></i></a>&nbsp;		
												</td>';
            } else {
                $html .= '<td class="center">You don\'t have AUTH </td>';
            }
            $html .= '</tr>';


            // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }




        $html .= '</tbody></table>';

        return $html;
    }

    public function biuldTechnoTable() {// Connection data (server_address, database, name, poassword)
        $sql = "SELECT `techno_name`, `add_by`, `add_time` FROM `cap_techno` ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }





        $html = '<table class="table table-striped table-condensed table-bordered" id="sample_1">
										<thead>
											<tr>
												<th style="width:8px"><input type="checkbox" class="group-checkable" data-set=".checkboxes" /></th>
												
												<th class="hidden-phone">Technology Name</th>
												<th class="hidden-phone">Added By</th>
                                                                                                <th class="hidden-phone">Create Time</th>
												
												<th>Actions</th>
											</tr>
										</thead>
										<tbody>';
        foreach ($result as $row) {

            $html .= '<tr class="odd gradeX">
												<td><input type="checkbox" class="checkboxes" value="' . $row['techno_name'] . '" /></td>
												
                                                                                                    <td>' . $row['techno_name'] . '</td>
												<td class="hidden-phone"><a href="mailto:' . $row['add_by'] . '@etisalat.ae">' . $row['add_by'] . '</a></td>
												<td class="hidden-phone">' . ($row['add_time'] == '' ? '-' : $row['add_time']) . '</td>
												
												';
            IF ((($_SESSION['currentRole'] == 'Admin')or ( $_SESSION['teamName'] == 'supperAdmins'))) {
                $html .= '<td class="center">
													
													<a href="#" class="icon huge"><i class="icon-remove" id="removeCapTechno_' . $row['techno_name'] . '" data-d="input-id=' . $row['techno_name'] . '&token=' . $_SESSION['token'] . '&action=capTechno_delete"></i></a>&nbsp;		
												</td>';
            } else {
                $html .= '<td class="center">You don\'t have AUTH </td>';
            }
            $html .= '</tr>';


            // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }




        $html .= '</tbody></table>';

        return $html;
    }
    
    public function biuldinfraitemTable() {// Connection data (server_address, database, name, poassword)
        $sql = "SELECT `resrc_name`, `add_by`, `add_time` FROM `cap_infraitem` ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }





        $html = '<table class="table table-striped table-condensed table-bordered" id="sample_1">
										<thead>
											<tr>
												<th style="width:8px"><input type="checkbox" class="group-checkable" data-set=".checkboxes" /></th>
												
												<th class="hidden-phone">Resource Name</th>
												<th class="hidden-phone">Added By</th>
                                                                                                <th class="hidden-phone">Create Time</th>
												
												<th>Actions</th>
											</tr>
										</thead>
										<tbody>';
        foreach ($result as $row) {

            $html .= '<tr class="odd gradeX">
												<td><input type="checkbox" class="checkboxes" value="' . $row['resrc_name'] . '" /></td>
												
                                                                                                    <td>' . $row['resrc_name'] . '</td>
												<td class="hidden-phone"><a href="mailto:' . $row['add_by'] . '@etisalat.ae">' . $row['add_by'] . '</a></td>
												<td class="hidden-phone">' . ($row['add_time'] == '' ? '-' : $row['add_time']) . '</td>
												
												';
            IF ((($_SESSION['currentRole'] == 'Admin')or ( $_SESSION['teamName'] == 'supperAdmins'))) {
                $html .= '<td class="center">
													
													<a href="#" class="icon huge"><i class="icon-remove" id="removeCapTechno_' . $row['resrc_name'] . '" data-d="input-id=' . $row['resrc_name'] . '&token=' . $_SESSION['token'] . '&action=capinfraItem_delete"></i></a>&nbsp;		
												</td>';
            } else {
                $html .= '<td class="center">You don\'t have AUTH </td>';
            }
            $html .= '</tr>';


            // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }




        $html .= '</tbody></table>';

        return $html;
    }
      public function biuldspaceitemTable() {// Connection data (server_address, database, name, poassword)
        $sql = "SELECT `resrc_name`, `add_by`, `add_time` FROM `cap_spaceitem` ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }





        $html = '<table class="table table-striped table-condensed table-bordered" id="sample_1">
										<thead>
											<tr>
												<th style="width:8px"><input type="checkbox" class="group-checkable" data-set=".checkboxes" /></th>
												
												<th class="hidden-phone">Resource Name</th>
												<th class="hidden-phone">Added By</th>
                                                                                                <th class="hidden-phone">Create Time</th>
												
												<th>Actions</th>
											</tr>
										</thead>
										<tbody>';
        foreach ($result as $row) {

            $html .= '<tr class="odd gradeX">
												<td><input type="checkbox" class="checkboxes" value="' . $row['resrc_name'] . '" /></td>
												
                                                                                                    <td>' . $row['resrc_name'] . '</td>
												<td class="hidden-phone"><a href="mailto:' . $row['add_by'] . '@etisalat.ae">' . $row['add_by'] . '</a></td>
												<td class="hidden-phone">' . ($row['add_time'] == '' ? '-' : $row['add_time']) . '</td>
												
												';
            IF ((($_SESSION['currentRole'] == 'Admin')or ( $_SESSION['teamName'] == 'supperAdmins'))) {
                $html .= '<td class="center">
													
													<a href="#" class="icon huge"><i class="icon-remove" id="removeCapTechno_' . $row['resrc_name'] . '" data-d="input-id=' . $row['resrc_name'] . '&token=' . $_SESSION['token'] . '&action=capspaceitem_delete"></i></a>&nbsp;		
												</td>';
            } else {
                $html .= '<td class="center">You don\'t have AUTH </td>';
            }
            $html .= '</tr>';


            // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }




        $html .= '</tbody></table>';

        return $html;
    }
      public function biuldassetitemTable() {// Connection data (server_address, database, name, poassword)
        $sql = "SELECT `resrc_name`, `add_by`, `add_time` FROM `cap_assetsitem` ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }





        $html = '<table class="table table-striped table-condensed table-bordered" id="sample_1">
										<thead>
											<tr>
												<th style="width:8px"><input type="checkbox" class="group-checkable" data-set=".checkboxes" /></th>
												
												<th class="hidden-phone">Resource Name</th>
												<th class="hidden-phone">Added By</th>
                                                                                                <th class="hidden-phone">Create Time</th>
												
												<th>Actions</th>
											</tr>
										</thead>
										<tbody>';
        foreach ($result as $row) {

            $html .= '<tr class="odd gradeX">
												<td><input type="checkbox" class="checkboxes" value="' . $row['resrc_name'] . '" /></td>
												
                                                                                                    <td>' . $row['resrc_name'] . '</td>
												<td class="hidden-phone"><a href="mailto:' . $row['add_by'] . '@etisalat.ae">' . $row['add_by'] . '</a></td>
												<td class="hidden-phone">' . ($row['add_time'] == '' ? '-' : $row['add_time']) . '</td>
												
												';
            IF ((($_SESSION['currentRole'] == 'Admin')or ( $_SESSION['teamName'] == 'supperAdmins'))) {
                $html .= '<td class="center">
													
													<a href="#" class="icon huge"><i class="icon-remove" id="removeCapTechno_' . $row['resrc_name'] . '" data-d="input-id=' . $row['resrc_name'] . '&token=' . $_SESSION['token'] . '&action=capassetitem_delete"></i></a>&nbsp;		
												</td>';
            } else {
                $html .= '<td class="center">You don\'t have AUTH </td>';
            }
            $html .= '</tr>';


            // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }




        $html .= '</tbody></table>';

        return $html;
    }
      public function biuldpoweritemTable() {// Connection data (server_address, database, name, poassword)
        $sql = "SELECT `resrc_name`, `add_by`, `add_time` FROM `cap_poweritem` ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }





        $html = '<table class="table table-striped table-condensed table-bordered" id="sample_1">
										<thead>
											<tr>
												<th style="width:8px"><input type="checkbox" class="group-checkable" data-set=".checkboxes" /></th>
												
												<th class="hidden-phone">Resource Name</th>
												<th class="hidden-phone">Added By</th>
                                                                                                <th class="hidden-phone">Create Time</th>
												
												<th>Actions</th>
											</tr>
										</thead>
										<tbody>';
        foreach ($result as $row) {

            $html .= '<tr class="odd gradeX">
												<td><input type="checkbox" class="checkboxes" value="' . $row['resrc_name'] . '" /></td>
												
                                                                                                    <td>' . $row['resrc_name'] . '</td>
												<td class="hidden-phone"><a href="mailto:' . $row['add_by'] . '@etisalat.ae">' . $row['add_by'] . '</a></td>
												<td class="hidden-phone">' . ($row['add_time'] == '' ? '-' : $row['add_time']) . '</td>
												
												';
            IF ((($_SESSION['currentRole'] == 'Admin')or ( $_SESSION['teamName'] == 'supperAdmins'))) {
                $html .= '<td class="center">
													
													<a href="#" class="icon huge"><i class="icon-remove" id="removeCapTechno_' . $row['resrc_name'] . '" data-d="input-id=' . $row['resrc_name'] . '&token=' . $_SESSION['token'] . '&action=cappoweritem_delete"></i></a>&nbsp;		
												</td>';
            } else {
                $html .= '<td class="center">You don\'t have AUTH </td>';
            }
            $html .= '</tr>';


            // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }




        $html .= '</tbody></table>';

        return $html;
    }
     public function biuldsectionitemTable() {// Connection data (server_address, database, name, poassword)
        $sql = "SELECT `resrc_name`, `add_by`, `add_time` FROM `cap_sectionitem` ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }





        $html = '<table class="table table-striped table-condensed table-bordered" id="sample_1">
										<thead>
											<tr>
												<th style="width:8px"><input type="checkbox" class="group-checkable" data-set=".checkboxes" /></th>
												
												<th class="hidden-phone">section Name</th>
												<th class="hidden-phone">Added By</th>
                                                                                                <th class="hidden-phone">Create Time</th>
												
												<th>Actions</th>
											</tr>
										</thead>
										<tbody>';
        foreach ($result as $row) {

            $html .= '<tr class="odd gradeX">
												<td><input type="checkbox" class="checkboxes" value="' . $row['resrc_name'] . '" /></td>
												
                                                                                                    <td>' . $row['resrc_name'] . '</td>
												<td class="hidden-phone"><a href="mailto:' . $row['add_by'] . '@etisalat.ae">' . $row['add_by'] . '</a></td>
												<td class="hidden-phone">' . ($row['add_time'] == '' ? '-' : $row['add_time']) . '</td>
												
												';
            IF ((($_SESSION['currentRole'] == 'Admin')or ( $_SESSION['teamName'] == 'supperAdmins'))) {
                $html .= '<td class="center">
													
													<a href="#" class="icon huge"><i class="icon-remove" id="removeCapTechno_' . $row['resrc_name'] . '" data-d="input-id=' . $row['resrc_name'] . '&token=' . $_SESSION['token'] . '&action=capsectionitem_delete"></i></a>&nbsp;		
												</td>';
            } else {
                $html .= '<td class="center">You don\'t have AUTH </td>';
            }
            $html .= '</tr>';


            // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }




        $html .= '</tbody></table>';

        return $html;
    }

    /*
     * 
     * Delete Cat provided by his ID 
     * 
     */

    public function processDeleteCapService() {
        /*
         * Fails if the proper action was not submitted
         */
        // echo "<script>alert('".$_POST['input-username']."')</script>";
//        if ($_POST['action'] != 'bibZone_delete') {
//            return "Invalid action supplied for process Delete CNOC Handover Category.";
//        }
        if ($_POST['action'] != 'capService_delete') {
            return "Invalid action supplied for process Delete Capcity Service.";
        }
        /*
         * Escapes the user input for security
         */
        $unid = htmlentities($_POST['input-id'], ENT_QUOTES);


        /*
         * Retrieves the matching info from the DB if it exists
         */
        $sql = "delete from cap_service where service_name=:id";
        try {
            $res = $this->query($sql, array("id" => $unid));
            $this->log->logActions($_SESSION['user']['email'], 'Delete Cap Service', 'Success Delete', 'Service ID :' . $unid . ' deleted');
        } catch (Exception $e) {
            // $this->db=null;
            return ($e->getMessage());
            $this->log->logActions($_SESSION['user']['email'], 'Delete Cap Service', 'Faild Delete due to ' . $e->getMessage(), 'Cap Service ID :' . $unid . ' deleted');
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
    
    
     public function processDeleteCapresutli() {
        /*
         * Fails if the proper action was not submitted
         */
        // echo "<script>alert('".$_POST['input-username']."')</script>";
//        if ($_POST['action'] != 'bibZone_delete') {
//            return "Invalid action supplied for process Delete CNOC Handover Category.";
//        }
        if ($_POST['action'] != 'Capresutli_delete') {
            return "Invalid action supplied for process Delete Capcity respource Utility.";
        }
        /*
         * Escapes the user input for security
         */
        $unid = htmlentities($_POST['input-id'], ENT_QUOTES);
        $unid2 = $_POST['input-id2'];
       // $infra_itms= explode('\n',$unid2);
       // print_r($infra_itms);die;
        


        /*
         * Retrieves the matching info from the DB if it exists
         */
        $sql = "delete from cap_infrautli where cap_infrautli_id=:id2";
        //print $sql; print $unid2;die;
        try {
            $res = $this->query($sql, array("id2" => $unid2));
            $this->log->logActions($_SESSION['user']['email'], 'Delete Cap Resource utiliti', 'Success Delete', 'Resource ID :' . $unid.'-'.$unid2 . ' deleted');
        } catch (Exception $e) {
            // $this->db=null;
            return ($e->getMessage());
            $this->log->logActions($_SESSION['user']['email'], 'Delete Cap Resource', 'Faild Delete due to ' . $e->getMessage(), 'Cap resource ID :' . $unid.'-'.$unid2 . ' deleted');
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

    
      public function processDeleteCapmanutli() {
        /*
         * Fails if the proper action was not submitted
         */
        // echo "<script>alert('".$_POST['input-username']."')</script>";
//        if ($_POST['action'] != 'bibZone_delete') {
//            return "Invalid action supplied for process Delete CNOC Handover Category.";
//        }
        if ($_POST['action'] != 'Capmanutli_delete') {
            return "Invalid action supplied for process Delete Capcity Manpower Utility.";
        }
        /*
         * Escapes the user input for security
         */
        $unid = htmlentities($_POST['input-id'], ENT_QUOTES);
        $unid2 = $_POST['input-id2'];
       // $infra_itms= explode('\n',$unid2);
       // print_r($infra_itms);die;
        


        /*
         * Retrieves the matching info from the DB if it exists
         */
        $sql = "delete from cap_powerutli where resrc_name=:id2";
        //print $sql; print $unid2;die;
        try {
            $res = $this->query($sql, array("id2" => $unid));
            $this->log->logActions($_SESSION['user']['email'], 'Delete Cap Mapower utiliti', 'Success Delete', 'Resource ID :' . $unid.'-'.$unid2 . ' deleted');
        } catch (Exception $e) {
            // $this->db=null;
            return ($e->getMessage());
            $this->log->logActions($_SESSION['user']['email'], 'Delete Cap Resource', 'Faild Delete due to ' . $e->getMessage(), 'Cap Mapower ID :' . $unid.'-'.$unid2 . ' deleted');
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
    public function processAddintro($intro=null) {
      
      if($intro!=NULL){
          $unid = htmlspecialchars($intro);//($_POST['input-id'], ENT_QUOTES);
          $qr = $this->query("UPDATE `cap_intro` SET `intro`='" . $unid . "', `add_by`='" . $_SESSION['user']['email'] . "'")  or die(mysql_error());
        return $qr;
          
      }
    }
    public function processAddanalsys($intro=null) {
      
      if($intro!=NULL){
          $unid = htmlspecialchars($intro);//($_POST['input-id'], ENT_QUOTES);
          $qr = $this->query("UPDATE `cap_analysis` SET `analysis`='" . $unid . "', `add_by`='" . $_SESSION['user']['email'] . "'")  or die(mysql_error());
        return $qr;
          
      }
    }
    
    
    public function processAddinfrautili($intro=null,$serv=null) {
      
      if($intro!=NULL){
          $unid = htmlspecialchars($intro);//($_POST['input-id'], ENT_QUOTES);
          $serv=htmlspecialchars($serv);;
          
           $qr = $this->query("INSERT INTO `cap_infrautli`(`resrc_name`, `currentutilized`,`add_by`) VALUES ('" . htmlentities($serv) . "','" . htmlentities($unid) . "','" . $_SESSION['user']['email'] . "')") or die(mysql_error());
        return $qr;
          
          
          
        //  $qr = $this->query("UPDATE `cap_intro` SET `intro`='" . $unid . "', `add_by`='" . $_SESSION['user']['email'] . "'")  or die(mysql_error());
       // return $qr;
          
      }
    }
    
     public function processAddinfrcap($intro=null,$serv=null) {
      
      if($intro!=NULL){
          $unid = htmlspecialchars($intro);//($_POST['input-id'], ENT_QUOTES);
          $serv=htmlspecialchars($serv);;
          //UPDATE cap_infrautli a INNER JOIN ( SELECT resrc_name , MAX(add_time) max_time FROM cap_infrautli GROUP BY resrc_name ) b ON a.resrc_name = b.resrc_name AND a.add_time = b.max_time SET `capacity`='CAPCITY FIELD2&lt;br&gt;' 
           $qr = $this->query("update  cap_infrautli a inner join( SELECT resrc_name , MAX(add_time) max_time FROM cap_infrautli where resrc_name='".$serv."' GROUP BY resrc_name) b ON a.resrc_name = b.resrc_name AND a.add_time = b.max_time

set a.capacity='" . $unid . "', a.add_by='" . $_SESSION['user']['email'] . "' ,add_time=CURRENT_TIMESTAMP ") or die(mysql_error());
        return $qr;
          
          
          
        //  $qr = $this->query("UPDATE `cap_intro` SET `intro`='" . $unid . "', `add_by`='" . $_SESSION['user']['email'] . "'")  or die(mysql_error());
       // return $qr;
          
      }
    }
    
    /************************************ 4 resource utilization *********************/
    //MAN Power Resources
     public function processAddpowerautili($intro=null,$serv=null) {
      
      if($intro!=NULL){
          $unid = htmlspecialchars($intro);//($_POST['input-id'], ENT_QUOTES);
          $serv=htmlspecialchars($serv);;
          
           $qr = $this->query("INSERT INTO `cap_powerutli`(`resrc_name`, `currentutilized`,`add_by`) VALUES ('" . htmlentities($serv) . "','" . htmlentities($unid) . "','" . $_SESSION['user']['email'] . "')") or die(mysql_error());
        return $qr;
          
          
          
        //  $qr = $this->query("UPDATE `cap_intro` SET `intro`='" . $unid . "', `add_by`='" . $_SESSION['user']['email'] . "'")  or die(mysql_error());
       // return $qr;
          
      }
    }
    
     public function processAddpowercap($intro=null,$serv=null) {
      
      if($intro!=NULL){
          $unid = htmlspecialchars($intro);//($_POST['input-id'], ENT_QUOTES);
          $serv=htmlspecialchars($serv);;
          //UPDATE cap_infrautli a INNER JOIN ( SELECT resrc_name , MAX(add_time) max_time FROM cap_infrautli GROUP BY resrc_name ) b ON a.resrc_name = b.resrc_name AND a.add_time = b.max_time SET `capacity`='CAPCITY FIELD2&lt;br&gt;' 
           $qr = $this->query("update  cap_powerutli a inner join( SELECT resrc_name , MAX(add_time) max_time FROM cap_powerutli where resrc_name='".$serv."' GROUP BY resrc_name) b ON a.resrc_name = b.resrc_name AND a.add_time = b.max_time

set a.capacity='" . $unid . "', a.add_by='" . $_SESSION['user']['email'] . "' ,add_time=CURRENT_TIMESTAMP ") or die(mysql_error());
        return $qr;
          
          
          
        //  $qr = $this->query("UPDATE `cap_intro` SET `intro`='" . $unid . "', `add_by`='" . $_SESSION['user']['email'] . "'")  or die(mysql_error());
       // return $qr;
          
      }
    }
    //Fixed Asset Resources
     public function processAddassetutili($intro=null,$serv=null) {
      
      if($intro!=NULL){
          $unid = htmlspecialchars($intro);//($_POST['input-id'], ENT_QUOTES);
          $serv=htmlspecialchars($serv);;
          
           $qr = $this->query("INSERT INTO `cap_assetutli`(`resrc_name`, `currentutilized`,`add_by`) VALUES ('" . htmlentities($serv) . "','" . htmlentities($unid) . "','" . $_SESSION['user']['email'] . "')") or die(mysql_error());
        return $qr;
          
          
          
        //  $qr = $this->query("UPDATE `cap_intro` SET `intro`='" . $unid . "', `add_by`='" . $_SESSION['user']['email'] . "'")  or die(mysql_error());
       // return $qr;
          
      }
    }
    
     public function processAddassetcap($intro=null,$serv=null) {
      
      if($intro!=NULL){
          $unid = htmlspecialchars($intro);//($_POST['input-id'], ENT_QUOTES);
          $serv=htmlspecialchars($serv);;
          //UPDATE cap_infrautli a INNER JOIN ( SELECT resrc_name , MAX(add_time) max_time FROM cap_infrautli GROUP BY resrc_name ) b ON a.resrc_name = b.resrc_name AND a.add_time = b.max_time SET `capacity`='CAPCITY FIELD2&lt;br&gt;' 
           $qr = $this->query("update  cap_assetutli a inner join( SELECT resrc_name , MAX(add_time) max_time FROM cap_assetutli where resrc_name='".$serv."' GROUP BY resrc_name) b ON a.resrc_name = b.resrc_name AND a.add_time = b.max_time

set a.capacity='" . $unid . "', a.add_by='" . $_SESSION['user']['email'] . "' ,add_time=CURRENT_TIMESTAMP ") or die(mysql_error());
        return $qr;
          
          
          
        //  $qr = $this->query("UPDATE `cap_intro` SET `intro`='" . $unid . "', `add_by`='" . $_SESSION['user']['email'] . "'")  or die(mysql_error());
       // return $qr;
          
      }
    }
    
      //10th Floor Space Capacity
     public function processAddspaceutili($intro=null,$serv=null) {
      
      if($intro!=NULL){
          $unid = htmlspecialchars($intro);//($_POST['input-id'], ENT_QUOTES);
          $serv=htmlspecialchars($serv);;
          
           $qr = $this->query("INSERT INTO `cap_spaceutli`(`resrc_name`, `currentutilized`,`add_by`) VALUES ('" . htmlentities($serv) . "','" . htmlentities($unid) . "','" . $_SESSION['user']['email'] . "')") or die(mysql_error());
        return $qr;
          
          
          
        //  $qr = $this->query("UPDATE `cap_intro` SET `intro`='" . $unid . "', `add_by`='" . $_SESSION['user']['email'] . "'")  or die(mysql_error());
       // return $qr;
          
      }
    }
    
     public function processAddspacecap($intro=null,$serv=null) {
      
      if($intro!=NULL){
          $unid = htmlspecialchars($intro);//($_POST['input-id'], ENT_QUOTES);
          $serv=htmlspecialchars($serv);;
          //UPDATE cap_infrautli a INNER JOIN ( SELECT resrc_name , MAX(add_time) max_time FROM cap_infrautli GROUP BY resrc_name ) b ON a.resrc_name = b.resrc_name AND a.add_time = b.max_time SET `capacity`='CAPCITY FIELD2&lt;br&gt;' 
           $qr = $this->query("update  cap_spaceutli a inner join( SELECT resrc_name , MAX(add_time) max_time FROM cap_spaceutli where resrc_name='".$serv."' GROUP BY resrc_name) b ON a.resrc_name = b.resrc_name AND a.add_time = b.max_time

set a.capacity='" . $unid . "', a.add_by='" . $_SESSION['user']['email'] . "' ,add_time=CURRENT_TIMESTAMP ") or die(mysql_error());
        return $qr;
          
          
          
        //  $qr = $this->query("UPDATE `cap_intro` SET `intro`='" . $unid . "', `add_by`='" . $_SESSION['user']['email'] . "'")  or die(mysql_error());
       // return $qr;
          
      }
    }
    
    
    
    
    
     //MAN Power - Resources utilized in Respective Sections
     public function processAddsectionpowerutili($intro=null,$serv=null) {
      
      if($intro!=NULL){
          $unid = htmlspecialchars($intro);//($_POST['input-id'], ENT_QUOTES);
          $serv=htmlspecialchars($serv);;
          
           $qr = $this->query("INSERT INTO `cap_powersectionutli`(`resrc_name`, `currentutilized`,`add_by`) VALUES ('" . htmlentities($serv) . "','" . htmlentities($unid) . "','" . $_SESSION['user']['email'] . "') ON DUPLICATE KEY UPDATE currentutilized=VALUES(currentutilized),add_time=CURRENT_TIMESTAMP,add_by=VALUES(add_by)") or die(mysql_error());
        return $qr;
          
          
          
        //  $qr = $this->query("UPDATE `cap_intro` SET `intro`='" . $unid . "', `add_by`='" . $_SESSION['user']['email'] . "'")  or die(mysql_error());
       // return $qr;
          
      }
    }
    
   
    
     
    
    
     public function processAddsectionpowercap($intro=null,$serv=null) {
      
      if($intro!=NULL){
          $unid = htmlspecialchars($intro);//($_POST['input-id'], ENT_QUOTES);
          $serv=htmlspecialchars($serv);;
          //UPDATE cap_infrautli a INNER JOIN ( SELECT resrc_name , MAX(add_time) max_time FROM cap_infrautli GROUP BY resrc_name ) b ON a.resrc_name = b.resrc_name AND a.add_time = b.max_time SET `capacity`='CAPCITY FIELD2&lt;br&gt;' 
//           $qr = $this->query("update  cap_powersectionutli a inner join( SELECT resrc_name , MAX(add_time) max_time FROM cap_powersectionutli where resrc_name='".$serv."' GROUP BY resrc_name) b ON a.resrc_name = b.resrc_name AND a.add_time = b.max_time
//
//set a.capacity='" . $unid . "', a.add_by='" . $_SESSION['user']['email'] . "' ,add_time=CURRENT_TIMESTAMP ") or die(mysql_error());
//           
            $qr = $this->query("INSERT INTO `cap_powersectionutli`(`resrc_name`, `capacity`,`add_by`) VALUES ('" . htmlentities($serv) . "','" . htmlentities($unid) . "','" . $_SESSION['user']['email'] . "') ON DUPLICATE KEY UPDATE capacity=VALUES(capacity),add_time=CURRENT_TIMESTAMP,add_by=VALUES(add_by)") or die(mysql_error());
        return $qr;
          
          
          
        //  $qr = $this->query("UPDATE `cap_intro` SET `intro`='" . $unid . "', `add_by`='" . $_SESSION['user']['email'] . "'")  or die(mysql_error());
       // return $qr;
          
      }
    }
    
    
    
    
  
    
       
    /************************************************************************/
      public function processGetintro() {
      
          
          
           $sql = "select intro from `cap_intro` ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }
        
        foreach ($result as $row) {
            
            $html=$row['intro'];
        }
        return $html;
    
    }
    
    public function processGetanalsys() {
      
          
          
           $sql = "select analysis from `cap_analysis` ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }
        
        foreach ($result as $row) {
            
            $html=$row['analysis'];
        }
        return $html;
    
    }
    
    
      public function getservutliization() {
      
          
          
      $sql = "SELECT `resrc_name`, `currentutilized`, `capacity`, `add_by`, `add_time` FROM `cap_infrautli` where resrc_name='".htmlspecialchars($_POST['code'])."' ORDER by add_time DESC limit 1 ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }
        
        foreach ($result as $row) {
            
            $html=$row['currentutilized'];
        }
        return htmlspecialchars_decode($html);
    
    }
    
    /*********************add utilization here *****************/
    
     public function getassetutliization() {
      
          
          
      $sql = "SELECT `resrc_name`, `currentutilized`, `capacity`, `add_by`, `add_time` FROM `cap_assetutli` where resrc_name='".htmlspecialchars($_POST['code'])."' ORDER by add_time DESC limit 1 ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }
        
        foreach ($result as $row) {
            
            $html=$row['currentutilized'];
        }
        return htmlspecialchars_decode($html);
    
    }
    public function getpowerutliization() {
      
          
          
      $sql = "SELECT `resrc_name`, `currentutilized`, `capacity`, `add_by`, `add_time` FROM `cap_powerutli` where resrc_name='".htmlspecialchars($_POST['code'])."' ORDER by add_time DESC limit 1 ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }
        
        foreach ($result as $row) {
            
            $html=$row['currentutilized'];
        }
        return htmlspecialchars_decode($html);
    
    }
    
     public function getspaceutliization() {
      
          
          
      $sql = "SELECT `resrc_name`, `currentutilized`, `capacity`, `add_by`, `add_time` FROM `cap_spaceutli` where resrc_name='".htmlspecialchars($_POST['code'])."' ORDER by add_time DESC limit 1 ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }
        
        foreach ($result as $row) {
            
            $html=$row['currentutilized'];
        }
        return htmlspecialchars_decode($html);
    
    }
    
    //section team 
    public function getpowersectionutlization() {
      
          
          
      $sql = "SELECT `resrc_name`, `currentutilized`, `capacity`, `add_by`, `add_time` FROM `cap_powersectionutli` where resrc_name='".htmlspecialchars($_POST['code'])."' ORDER by add_time DESC limit 1 ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }
        
        
         $html = '<table class="table table-striped table-condensed table-bordered" id="sample_1">
										<thead>
											<tr>
                                                                                        <th class="hidden-phone">Section Name</th>
												<th class="hidden-phone">Current WorkLoad</th>												
												<th class="hidden-phone">Utilization</th>
												<th class="hidden-phone">Added By</th>
                                                                                                <th class="hidden-phone">Create/Update Time</th>
												
											
											</tr>
										</thead>
										<tbody>';
        
        foreach ($result as $row) {
            
          //  $html=$row['currentutilized'];
            
         $html .= '<tr class="odd gradeX">
											
												
												
                                                                                                    <td>' . $row['resrc_name'] . '</td>                                                                                                   
                                                                                                    <td>' . $row['currentutilized'] . '</td>
                                                                                                    <td>' . $row['capacity'] . '</td>
                                                                                                        <td class="hidden-phone"><a href="mailto:' . $row['add_by'] . '@etisalat.ae">' . $row['add_by'] . '</a></td>
                                                                                                    <!--<td>' . ($row['add_time'] == '0000-00-00' ? 'NA' : (date('Y', strtotime($row['add_time'])) . "-" . date('M', strtotime($row['add_time'])))) . '</td>-->
                                                                                                    <td>' .$row['add_time']. '</td>
                                                                                                     
												
												
												
												';
            
            $html .= '</tr>';


            // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }




        $html .= '</tbody></table>';
        return htmlspecialchars_decode($html);
    
    }
    
    
     public function getpowersectionutlization_dashboard() {
      
          
          
      $sql = "SELECT distinct `resrc_name`, `currentutilized`, `capacity` FROM `cap_powersectionutli` ORDER by resrc_name  ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }
        
        
         $html = '<table class="table table-striped table-condensed table-bordered" id="sample_1">
										<thead>
										<tr>
<td valign="top" width="162">
<p align="center"><span style="font-size: medium;"><strong>Section</strong></span></p>
</td>
<td valign="top" width="252">
<p align="center"><span style="font-size: medium;"><strong>Work Load</strong></span></p>
</td>
<td valign="top" width="209">
<p align="center"><span style="font-size: medium;"><strong>Utilization</strong></span></p>
</td>
</tr>
										</thead>
										<tbody>';
        
        foreach ($result as $row) {
            
          //  $html=$row['currentutilized'];
            
         $html .= '<tr class="odd gradeX" align="left">
											
												
												
                                                                                                    <td align="center">' . $row['resrc_name'] . '</td>                                                                                                   
                                                                                                    <td>' . $row['currentutilized'] . '</td>
                                                                                                    <td>' . $row['capacity'] . '</td>
                                                                                               
												';
            
            $html .= '</tr>';


            // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }




        $html .= '</tbody></table>';
        return htmlspecialchars_decode($html);
    
    }
    
    
     //section team 
   
    
     /***************************MGM Issues Options ******************************/ 
      public function processAddmgmissues($intro=null,$sect=null,$issue=null) {
      
      if($intro!=NULL && $sect!=null && $issue!=null){
          $unid = htmlspecialchars($intro);//($_POST['input-id'], ENT_QUOTES);
          $serv=htmlspecialchars($sect);
          $issue=htmlspecialchars($issue);
          
           $qr = $this->query("INSERT INTO `cap_mgm_mainissue`(`section_name`, `issue_relatedto`, `issues_remark`, `add_by`) VALUES ('" . htmlentities($serv) . "','" . htmlentities($issue) ."','" . htmlentities($unid) . "','" . $_SESSION['user']['email'] . "') ON DUPLICATE KEY UPDATE issues_remark=VALUES(issues_remark),add_time=CURRENT_TIMESTAMP,add_by=VALUES(add_by)") or die(mysql_error());
        return $qr;
          
          
          
        //  $qr = $this->query("UPDATE `cap_intro` SET `intro`='" . $unid . "', `add_by`='" . $_SESSION['user']['email'] . "'")  or die(mysql_error());
       // return $qr;
          
      }
    }
     public function getmgmissues() {
      
          
          
      $sql = "SELECT `section_name`, `issue_relatedto`, `issues_remark`, `add_by`, `add_time` FROM `cap_mgm_mainissue` where section_name='".htmlspecialchars($_POST['code'])."' and issue_relatedto='".htmlspecialchars($_POST['code2'])."' ORDER by add_time DESC limit 1 ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }
        
        $html = '<table class="table table-striped table-condensed table-bordered" id="sample_1">
										<thead>
											<tr>
												
												
												<th class="hidden-phone">Section Name</th>
												<th class="hidden-phone">Issue retated to</th>												
												<th class="hidden-phone">Issue Remark</th>
											
											</tr>
										</thead>
										<tbody>';
        
        foreach ($result as $row) {
            
           // $html=$row['issues_remark']; 
            $html .= '<tr class="odd gradeX" align="left">
							
												 <td align="center">' . $row['section_name'] . '</td>                                                                                                   
                                                                                                    <td>' . $row['issue_relatedto'] . '</td>
                                                                                                    <td>' . $row['issues_remark'] . '</td>
                                                                                               ';
        
            $html .= '</tr>';


            // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }




        $html .= '</tbody></table>';
        return htmlspecialchars_decode($html);
    
    }
    
    
     public function getmgmissues_dashboard() {
      
          
          
      $sql = "SELECT distinct `section_name` FROM `cap_mgm_mainissue` ";
      //$sql2 = "SELECT distinct  `issue_relatedto`, `issues_remark` FROM `cap_mgm_mainissue` where section_name='".htmlspecialchars($_POST['code'])."'  ORDER by issue_relatedto ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }
        
        $html = '<table class="LightList1" style="margin-left: auto; margin-right: auto;" border="1" cellspacing="0" cellpadding="0">
										<thead align="center">
										
												
												
												<td align="center"><strong>Section Name</strong></td>
																							
												<td align="center"><strong>Issue Remark</strong></td>
											
											
										</thead>
										<tbody>';
        
        foreach ($result as $row) {
            
           // $html=$row['issues_remark']; 
            $html .= '<tr class="odd gradeX" align="left">
							
					 <td align="center">' . $row['section_name'] . '</td> <td>';
            $sql2 = "SELECT distinct  `issue_relatedto`, `issues_remark` FROM `cap_mgm_mainissue` where section_name='".$row['section_name']."'  ORDER by issue_relatedto ";
       
        $result2 = array();
        try {
            $result2 = $this->query($sql2);
        } catch (PDOException $e) {

            return($e->getMessage());
        }
        $html2="<table>";
        foreach ($result2 as $row2) {
           $html2.='<tr algin="left"><td><strong>Issue ralted to '.strtoupper($row2['issue_relatedto']).' :<strong></td><td>'.$row2['issues_remark'].'</td></tr>' ;
        }
        if($html2=="<table>"){$html2.='<tr></tr>';}
        $html2.="</table>";
        $html .= $html2;
        //echo '<br>section name '.$row['section_name'].'<br>';
        //var_dump($html2);
       // echo '<br><br>';
            
            
        
            $html .= '</td></tr>';


            // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }




        $html .= '</tbody></table>';
        return htmlspecialchars_decode($html);
    
    }
    /***************************END MGM Issues Options ******************************/ 
    
    
  /***************************SIP Options ******************************/  
    
      public function getsipoption() {
      
          
          
      $sql = "SELECT `section_name`, `issue_relatedto`, `issues_remark`, `add_by`, `add_time` FROM `cap_sip_options` where section_name='".htmlspecialchars($_POST['code'])."' and issue_relatedto='".htmlspecialchars($_POST['code2'])."' ORDER by add_time DESC limit 1 ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }
        
      
        
        
        
        
         $html = '<table class="table table-striped table-condensed table-bordered" id="sample_1">
										<thead>
											<tr>
												
												
												<th class="hidden-phone">Section Name</th>
												<th class="hidden-phone">Issue retated to</th>												
												<th class="hidden-phone">Issue Remark</th>
											
											</tr>
										</thead>
										<tbody>';
        
        foreach ($result as $row) {
            
           // $html=$row['issues_remark']; 
            $html .= '<tr class="odd gradeX" align="left">
							
												 <td align="center">' . $row['section_name'] . '</td>                                                                                                   
                                                                                                    <td>' . $row['issue_relatedto'] . '</td>
                                                                                                    <td>' . $row['issues_remark'] . '</td>
                                                                                               ';
        
            $html .= '</tr>';


            // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }




        $html .= '</tbody></table>';
        return htmlspecialchars_decode($html);
    
    }
    
       public function getsipoption_dashboard() {
      
          
          
      $sql = "SELECT distinct `section_name` FROM `cap_sip_options` ";
      //$sql2 = "SELECT distinct  `issue_relatedto`, `issues_remark` FROM `cap_mgm_mainissue` where section_name='".htmlspecialchars($_POST['code'])."'  ORDER by issue_relatedto ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }
        
        $html = '<table class="LightList1" style="margin-left: auto; margin-right: auto;" border="1" cellspacing="0" cellpadding="0">
										<thead align="center">
										
												
												
												<td align="center"><strong>Section Name</strong></td>
																							
												<td align="center"><strong>Issue Remark</strong></td>
											
											
										</thead>
										<tbody>';
        
        foreach ($result as $row) {
            
           // $html=$row['issues_remark']; 
            $html .= '<tr class="odd gradeX" align="left">
							
					 <td align="center">' . $row['section_name'] . '</td> <td>';
            $sql2 = "SELECT distinct  `issue_relatedto`, `issues_remark` FROM `cap_sip_options` where section_name='".$row['section_name']."'  ORDER by issue_relatedto ";
       
        $result2 = array();
        try {
            $result2 = $this->query($sql2);
        } catch (PDOException $e) {

            return($e->getMessage());
        }
        $html2="<table>";
        foreach ($result2 as $row2) {
           $html2.='<tr algin="left"><td><strong>Issue ralted to '.strtoupper($row2['issue_relatedto']).' :<strong></td><td>'.$row2['issues_remark'].'</td></tr>' ;
        }
        if($html2=="<table>"){$html2.='<tr></tr>';}
        $html2.="</table>";
        $html .= $html2;
        //echo '<br>section name '.$row['section_name'].'<br>';
        //var_dump($html2);
       // echo '<br><br>';
            
            
        
            $html .= '</td></tr>';


            // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }




        $html .= '</tbody></table>';
        return htmlspecialchars_decode($html);
    
    }
    
    
     public function processAddsipoption($intro=null,$sect=null,$issue=null) {
      
      if($intro!=NULL && $sect!=null && $issue!=null){
          $unid = htmlspecialchars($intro);//($_POST['input-id'], ENT_QUOTES);
          $serv=htmlspecialchars($sect);
          $issue=htmlspecialchars($issue);
          
           $qr = $this->query("INSERT INTO `cap_sip_options`(`section_name`, `issue_relatedto`, `issues_remark`, `add_by`) VALUES ('" . htmlentities($serv) . "','" . htmlentities($issue) ."','" . htmlentities($unid) . "','" . $_SESSION['user']['email'] . "') ON DUPLICATE KEY UPDATE issues_remark=VALUES(issues_remark),add_time=CURRENT_TIMESTAMP,add_by=VALUES(add_by)") or die(mysql_error());
        return $qr;
          
          
          
        //  $qr = $this->query("UPDATE `cap_intro` SET `intro`='" . $unid . "', `add_by`='" . $_SESSION['user']['email'] . "'")  or die(mysql_error());
       // return $qr;
          
      }
    }
    
    
   /***************************END SIP Options ******************************/  
 
    /**********************************Business Scenarios ***********************/
//processAddmgmissues
//getmgmissues
//getmgmissues_dashboard
    
    
   public function getcurrserviceScenarios2() {
      
          
          
      $sql = "SELECT `servicetechno_id`, `service_name`, `remarks`, `add_by`, `add_time` FROM `cap_currservicesenario` where service_name='".htmlspecialchars($_POST['code'])."' ORDER by add_time DESC limit 1 ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }
        
        foreach ($result as $row) {
            
            $html=$row['remarks'];
        }
        return htmlspecialchars_decode($html);
        
        
        
    
    }
    
    public function processAddcurrserviceScenarios($intro=null,$serv=null) {
      
      if($intro!=NULL){
          $unid = htmlspecialchars($intro);//($_POST['input-id'], ENT_QUOTES);
          $serv=htmlspecialchars($serv);;
          
           $qr = $this->query("INSERT INTO `cap_currservicesenario`(`service_name`, `remarks`, `add_by`) VALUES ('" . htmlentities($serv) . "','" . htmlentities($unid) . "','" . $_SESSION['user']['email'] . "') ON DUPLICATE KEY UPDATE remarks=VALUES(remarks),add_time=CURRENT_TIMESTAMP,add_by=VALUES(add_by)") or die(mysql_error());
        return $qr;
          
          
          
        //  $qr = $this->query("UPDATE `cap_intro` SET `intro`='" . $unid . "', `add_by`='" . $_SESSION['user']['email'] . "'")  or die(mysql_error());
       // return $qr;
          
      }
    }
     
    
    public function processAddfuturserviceScenarios($intro=null,$serv=null) {
      
      if($intro!=NULL){
          $unid = htmlspecialchars($intro);//($_POST['input-id'], ENT_QUOTES);
          $serv=htmlspecialchars($serv);;
          
           $qr = $this->query("INSERT INTO `cap_futurservicesenario`(`service_name`, `remarks`, `add_by`) VALUES ('" . htmlentities($serv) . "','" . htmlentities($unid) . "','" . $_SESSION['user']['email'] . "') ON DUPLICATE KEY UPDATE remarks=VALUES(remarks),add_time=CURRENT_TIMESTAMP,add_by=VALUES(add_by)") or die(mysql_error());
        return $qr;
          
          
          
        //  $qr = $this->query("UPDATE `cap_intro` SET `intro`='" . $unid . "', `add_by`='" . $_SESSION['user']['email'] . "'")  or die(mysql_error());
       // return $qr;
          
      }
    }
    
         public function getcurrserviceScenarios() {
      
          
          
     // $sql = "SELECT `section_name`, `issue_relatedto`, `issues_remark`, `add_by`, `add_time` FROM `cap_mgm_mainissue` where section_name='".htmlspecialchars($_POST['code'])."' and issue_relatedto='".htmlspecialchars($_POST['code2'])."' ORDER by add_time DESC limit 1 ";
      $sql = "SELECT `servicetechno_id`, `service_name`, `remarks`, `add_by`, `add_time` FROM `cap_currservicesenario` where service_name='".htmlspecialchars($_POST['code'])."' ORDER by add_time DESC limit 1 ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }
        
        $html = '<table class="table table-striped table-condensed table-bordered" id="sample_1">
										<thead>
											<tr>
												
												
												<td align="center"><strong>Service Name</strong></td>
																								
												<td align="center"><strong>Service Remark</strong></td>
											
											</tr>
										</thead>
										<tbody>';
        
        foreach ($result as $row) {
            
           // $html=$row['issues_remark']; 
            $html .= '<tr class="odd gradeX" align="left">
							
												 <td align="center">' . $row['service_name'] . '</td>                                                                                                   
                                                                                                    <td>' . $row['remarks'] . '</td>
                                                                                                   
                                                                                               ';
        
            $html .= '</tr>';


            // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }




        $html .= '</tbody></table>';
        return htmlspecialchars_decode($html);
    
    }
    
       public function getfuturserviceScenarios() {
      
          
          
      $sql = "SELECT `servicetechno_id`, `service_name`, `remarks`, `add_by`, `add_time` FROM `cap_futurservicesenario` where service_name='".htmlspecialchars($_POST['code'])."' ORDER by add_time DESC limit 1 ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }
        
        $html = '<table class="table table-striped table-condensed table-bordered" id="sample_1">
										<thead>
											<tr>
												
												
												<td align="center"><strong>Service Name</strong></td>
																								
												<td align="center"><strong>Service Remark</strong></td>
											
											</tr>
										</thead>
										<tbody>';
        
        foreach ($result as $row) {
            
           // $html=$row['issues_remark']; 
            $html .= '<tr class="odd gradeX" align="left">
							
												 <td align="center">' . $row['service_name'] . '</td>                                                                                                   
                                                                                                    <td>' . $row['remarks'] . '</td>
                                                                                                   
                                                                                               ';
        
            $html .= '</tr>';


            // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }




        $html .= '</tbody></table>';
        return htmlspecialchars_decode($html);
    
    }
    
    
     public function getserviceScenarios_dashboard() {
      
          
          
      $sql = "SELECT distinct `service_name`, `remarks` FROM `cap_currservicesenario` ";
      //$sql2 = "SELECT distinct  `issue_relatedto`, `issues_remark` FROM `cap_mgm_mainissue` where section_name='".htmlspecialchars($_POST['code'])."'  ORDER by issue_relatedto ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }
        
        $html = '<table class="LightList1" style="margin-left: auto; margin-right: auto;" border="1" cellspacing="0" cellpadding="0">
	<tbody><tr>

<td valign="top" width="207">
<p><span style="font-size: medium;"><strong>Service Name</strong></span></p>
</td>
<td valign="top" width="209">
<p><span style="font-size: medium;"><strong>Remarks</strong></span></p>
</td>
</tr><tr><td colspan=2><strong>Existing Services</strong></td></tr>';
        
        foreach ($result as $row) {
            
           // $html=$row['issues_remark']; 
            $html .= '<tr class="odd gradeX" align="left">							
		<td align="center">' . $row['service_name'] . '</td> 
		<td align="left">' . $row['remarks'] . '</td> ';
         
            
            
        
            $html .= '</tr>';


            // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }
        
        
        
        $sql = "SELECT distinct `service_name`, `remarks` FROM `cap_futurservicesenario` ";
      //$sql2 = "SELECT distinct  `issue_relatedto`, `issues_remark` FROM `cap_mgm_mainissue` where section_name='".htmlspecialchars($_POST['code'])."'  ORDER by issue_relatedto ";
        
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }
        
        $html .= '<tr><td colspan=2><strong>NEW Services</strong></td></tr>';
        
        foreach ($result as $row) {
            
           // $html=$row['issues_remark']; 
            $html .= '<tr class="odd gradeX" align="left">							
		<td align="center">' . $row['service_name'] . '</td> 
		<td align="left">' . $row['remarks'] . '</td> ';
         
            
            
        
            $html .= '</tr>';


            // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }


        

        $html .= '<tr>

<td valign="top" width="207">
<p>For other potential services</p>
</td>
<td valign="top" width="209">
<p>Recommendations and forecast will be after the service is launched</p>
</td>
</tr></tbody></table>';
        return htmlspecialchars_decode($html);
    
    }
    
 
    
    /**********************************END Business Scenarios ***********************/
    
    /************************************SIP COST *******************************/
     public function processAddsipsectioncost($intro=null,$serv=null) {
      
      if($intro!=NULL){
          $unid = htmlspecialchars($intro);//($_POST['input-id'], ENT_QUOTES);
          $serv=htmlspecialchars($serv);;
          
           $qr = $this->query("INSERT INTO `cap_sipsectioncost`(`section_name`, `remarks`, `add_by`) VALUES ('" . htmlentities($serv) . "','" . htmlentities($unid) . "','" . $_SESSION['user']['email'] . "') ON DUPLICATE KEY UPDATE remarks=VALUES(remarks),add_time=CURRENT_TIMESTAMP,add_by=VALUES(add_by)") or die(mysql_error());
        return $qr;
          
          
          
        //  $qr = $this->query("UPDATE `cap_intro` SET `intro`='" . $unid . "', `add_by`='" . $_SESSION['user']['email'] . "'")  or die(mysql_error());
       // return $qr;
          
      }
    }
    
    
     public function getseipsectioncost() {
      
          
          
      $sql = "SELECT `servicetechno_id`, `section_name`, `remarks`, `add_by`, `add_time` FROM `cap_sipsectioncost` where section_name='".htmlspecialchars($_POST['code'])."' ORDER by add_time DESC limit 1 ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }
        
         $html = '<table class="table table-striped table-condensed table-bordered" id="sample_1">
										<thead>
											<tr>
												
												
												<td align="center"><strong>Section Name</strong></td>
																								
												<td align="center"><strong>Section Cost Remark</strong></td>
											
											</tr>
										</thead>
										<tbody>';
        
        foreach ($result as $row) {
            
           // $html=$row['issues_remark']; 
            $html .= '<tr class="odd gradeX" align="left">
							
												 <td align="center">' . $row['section_name'] . '</td>                                                                                                   
                                                                                                    <td>' . $row['remarks'] . '</td>
                                                                                                   
                                                                                               ';
        
            $html .= '</tr>';


            // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }




        $html .= '</tbody></table>';
        return htmlspecialchars_decode($html);
    
    }
    
    public function getseipsectioncost_dashboard() {
      
          
          
      $sql = "SELECT distinct `section_name`, `remarks` FROM `cap_sipsectioncost` ";
      //$sql2 = "SELECT distinct  `issue_relatedto`, `issues_remark` FROM `cap_mgm_mainissue` where section_name='".htmlspecialchars($_POST['code'])."'  ORDER by issue_relatedto ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }
        
        $html = '<table class="LightList1" style="margin-left: auto; margin-right: auto;" border="1" cellspacing="0" cellpadding="0">
	<tbody><tr>

<td valign="top" width="207">
<p><span style="font-size: medium;"><strong>Section Name</strong></span></p>
</td>
<td valign="top" width="209">
<p><span style="font-size: medium;"><strong>Cost Remarks</strong></span></p>
</td>
</tr>';
        
        foreach ($result as $row) {
            
           // $html=$row['issues_remark']; 
            $html .= '<tr class="odd gradeX" align="left">							
		<td align="center">' . $row['section_name'] . '</td> 
		<td align="left">' . $row['remarks'] . '</td> ';
         
            
            
        
            $html .= '</tr>';


            // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }
        
   


        

        $html .= '</tbody></table>';
        return htmlspecialchars_decode($html);
    
    }
    
        /************************************END  SIP COST *******************************/
    
      /************************************  SIP Recommendation *******************************/
    public function getseipsectionrecommend() {
      
          
          
      $sql = "SELECT `servicetechno_id`, `section_name`, `remarks`, `add_by`, `add_time` FROM `cap_sipsectionrecommend` where section_name='".htmlspecialchars($_POST['code'])."' ORDER by add_time DESC limit 1 ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }
        
          $html = '<table class="table table-striped table-condensed table-bordered" id="sample_1">
										<thead>
											<tr>
												
												
											<td valign="top" width="311">
<p align="center"><span style="font-size: medium;"><strong>Sections</strong></span></p>
</td>
<td valign="top" width="312">
<p align="center"><span style="font-size: medium;"><strong>Recommendations</strong></span></p>
</td>
											</tr>
										</thead>
										<tbody>';
        
        foreach ($result as $row) {
            
           // $html=$row['issues_remark']; 
            $html .= '<tr class="odd gradeX" align="left">
							
												 <td align="center">' . $row['section_name'] . '</td>                                                                                                   
                                                                                                    <td>' . $row['remarks'] . '</td>
                                                                                                   
                                                                                               ';
        
            $html .= '</tr>';


            // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }




        $html .= '</tbody></table>';
        return htmlspecialchars_decode($html);
    
    }
    
     public function processAddsipsectionrecommend($intro=null,$serv=null) {
      
      if($intro!=NULL){
          $unid = htmlspecialchars($intro);//($_POST['input-id'], ENT_QUOTES);
          $serv=htmlspecialchars($serv);;
          
           $qr = $this->query("INSERT INTO `cap_sipsectionrecommend`(`section_name`, `remarks`, `add_by`) VALUES ('" . htmlentities($serv) . "','" . htmlentities($unid) . "','" . $_SESSION['user']['email'] . "') ON DUPLICATE KEY UPDATE remarks=VALUES(remarks),add_time=CURRENT_TIMESTAMP,add_by=VALUES(add_by)") or die(mysql_error());
        return $qr;
          
          
          
        //  $qr = $this->query("UPDATE `cap_intro` SET `intro`='" . $unid . "', `add_by`='" . $_SESSION['user']['email'] . "'")  or die(mysql_error());
       // return $qr;
          
      }
    }
    
    
     public function processAddsipsectionrecommend_dashboard() {
      
          
          
      $sql = "SELECT distinct `section_name`, `remarks` FROM `cap_sipsectionrecommend` ";
      //$sql2 = "SELECT distinct  `issue_relatedto`, `issues_remark` FROM `cap_mgm_mainissue` where section_name='".htmlspecialchars($_POST['code'])."'  ORDER by issue_relatedto ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }
        
        $html = '<table class="LightList1" style="margin-left: auto; margin-right: auto;" border="1" cellspacing="0" cellpadding="0">
	<tbody><tr>

<td valign="top" width="311">
<p align="center"><span style="font-size: medium;"><strong>Sections</strong></span></p>
</td>
<td valign="top" width="312">
<p align="center"><span style="font-size: medium;"><strong>Recommendations</strong></span></p>
</td>
</tr>';
        
        foreach ($result as $row) {
            
           // $html=$row['issues_remark']; 
            $html .= '<tr class="odd gradeX" align="left">							
		<td align="center"><strong>' . $row['section_name'] . '</strong></td> 
		<td align="left">' . $row['remarks'] . '</td> ';
         
            
            
        
            $html .= '</tr>';


            // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }
        
   


        

        $html .= '</tbody></table>';
        return htmlspecialchars_decode($html);
    
    }
    
    
  
        /************************************END  SIP Recommendation *******************************/

    
     /********************************** Business Forcasting ***********************/     
    public function processAddcurrserviceforecast($intro=null,$serv=null) {
      
      if($intro!=NULL){
          $unid = htmlspecialchars($intro);//($_POST['input-id'], ENT_QUOTES);
          $serv=htmlspecialchars($serv);;
          
           $qr = $this->query("INSERT INTO `cap_currserviceforecast`(`service_name`, `remarks`, `add_by`) VALUES ('" . htmlentities($serv) . "','" . htmlentities($unid) . "','" . $_SESSION['user']['email'] . "') ON DUPLICATE KEY UPDATE remarks=VALUES(remarks),add_time=CURRENT_TIMESTAMP,add_by=VALUES(add_by)") or die(mysql_error());
        return $qr;
          
          
          
        //  $qr = $this->query("UPDATE `cap_intro` SET `intro`='" . $unid . "', `add_by`='" . $_SESSION['user']['email'] . "'")  or die(mysql_error());
       // return $qr;
          
      }
    }
     public function processAddfuturserviceforecast($intro=null,$serv=null) {
      
      if($intro!=NULL){
          $unid = htmlspecialchars($intro);//($_POST['input-id'], ENT_QUOTES);
          $serv=htmlspecialchars($serv);;
          
           $qr = $this->query("INSERT INTO `cap_futurserviceforecast`(`service_name`, `remarks`, `add_by`) VALUES ('" . htmlentities($serv) . "','" . htmlentities($unid) . "','" . $_SESSION['user']['email'] . "') ON DUPLICATE KEY UPDATE remarks=VALUES(remarks),add_time=CURRENT_TIMESTAMP,add_by=VALUES(add_by)") or die(mysql_error());
        return $qr;
          
          
          
        //  $qr = $this->query("UPDATE `cap_intro` SET `intro`='" . $unid . "', `add_by`='" . $_SESSION['user']['email'] . "'")  or die(mysql_error());
       // return $qr;
          
      }
    }
    
         public function getcurrserviceforecast() {
      
          
          
      $sql = "SELECT `servicetechno_id`, `service_name`, `remarks`, `add_by`, `add_time` FROM `cap_currserviceforecast` where service_name='".htmlspecialchars($_POST['code'])."' ORDER by add_time DESC limit 1 ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }
        
         $html = '<table class="table table-striped table-condensed table-bordered" id="sample_1">
										<thead>
											<tr>
												
												
												<td align="center"><strong>Service Name</strong></td>
																								
												<td align="center"><strong>Service Remark</strong></td>
											
											</tr>
										</thead>
										<tbody>';
        
        foreach ($result as $row) {
            
           // $html=$row['issues_remark']; 
            $html .= '<tr class="odd gradeX" align="left">
							
												 <td align="center">' . $row['service_name'] . '</td>                                                                                                   
                                                                                                    <td>' . $row['remarks'] . '</td>
                                                                                                   
                                                                                               ';
        
            $html .= '</tr>';


            // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }




        $html .= '</tbody></table>';
        return htmlspecialchars_decode($html);
    
    }
    
     public function getfuturserviceforecast() {
      
          
          
      $sql = "SELECT `servicetechno_id`, `service_name`, `remarks`, `add_by`, `add_time` FROM `cap_futurserviceforecast` where service_name='".htmlspecialchars($_POST['code'])."' ORDER by add_time DESC limit 1 ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }
        
         $html = '<table class="table table-striped table-condensed table-bordered" id="sample_1">
										<thead>
											<tr>
												
												
												<td align="center"><strong>Service Name</strong></td>
																								
												<td align="center"><strong>Service Remark</strong></td>
											
											</tr>
										</thead>
										<tbody>';
        
        foreach ($result as $row) {
            
           // $html=$row['issues_remark']; 
            $html .= '<tr class="odd gradeX" align="left">
							
												 <td align="center">' . $row['service_name'] . '</td>                                                                                                   
                                                                                                    <td>' . $row['remarks'] . '</td>
                                                                                                   
                                                                                               ';
        
            $html .= '</tr>';


            // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }




        $html .= '</tbody></table>';
        return htmlspecialchars_decode($html);
    
    }
    
    
      public function getserviceforecast_dashboard() {
      
          
          
      $sql = "SELECT distinct `service_name`, `remarks` FROM `cap_currserviceforecast` ";
      //$sql2 = "SELECT distinct  `issue_relatedto`, `issues_remark` FROM `cap_mgm_mainissue` where section_name='".htmlspecialchars($_POST['code'])."'  ORDER by issue_relatedto ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }
        
        $html = '<table class="LightList1" style="margin-left: auto; margin-right: auto;" border="1" cellspacing="0" cellpadding="0">
	<tbody><tr>

<td valign="top" width="207">
<p><span style="font-size: medium;"><strong>Service Name</strong></span></p>
</td>
<td valign="top" width="209">
<p><span style="font-size: medium;"><strong>Remarks</strong></span></p>
</td>
</tr><tr><td colspan=2><strong>Existing Services</strong></td></tr>';
        
        foreach ($result as $row) {
            
           // $html=$row['issues_remark']; 
            $html .= '<tr class="odd gradeX" align="left">							
		<td align="center">' . $row['service_name'] . '</td> 
		<td align="left">' . $row['remarks'] . '</td> ';
         
            
            
        
            $html .= '</tr>';


            // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }
        
        
        
        $sql = "SELECT distinct `service_name`, `remarks` FROM `cap_futurserviceforecast` ";
      //$sql2 = "SELECT distinct  `issue_relatedto`, `issues_remark` FROM `cap_mgm_mainissue` where section_name='".htmlspecialchars($_POST['code'])."'  ORDER by issue_relatedto ";
        
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }
        
        $html .= '<tr><td colspan=2><strong>NEW Services</strong></td></tr>';
        
        foreach ($result as $row) {
            
           // $html=$row['issues_remark']; 
            $html .= '<tr class="odd gradeX" align="left">							
		<td align="center">' . $row['service_name'] . '</td> 
		<td align="left">' . $row['remarks'] . '</td> ';
         
            
            
        
            $html .= '</tr>';


            // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }


        

        $html .= '<tr>

<td valign="top" width="207">
<p>For MSS services</p>
</td>
<td valign="top" width="209">
<p>Business will communicate to MSS on time to time basis</p>
</td>
</tr></tbody></table>';
        return htmlspecialchars_decode($html);
    
    }
    
 
    
    
    
     /**********************************END Business Forcasting ***********************/
    
    
    
   
    
    /*****************end utilization get ************/

    public function processDeleteCapTechno() {
        /*
         * Fails if the proper action was not submitted
         */
        // echo "<script>alert('".$_POST['input-username']."')</script>";
//        if ($_POST['action'] != 'bibZone_delete') {
//            return "Invalid action supplied for process Delete CNOC Handover Category.";
//        }
        if ($_POST['action'] != 'capTechno_delete') {
            return "Invalid action supplied for process Delete Capcity Technology.";
        }
        /*
         * Escapes the user input for security
         */
        $unid = htmlentities($_POST['input-id'], ENT_QUOTES);


        /*
         * Retrieves the matching info from the DB if it exists
         */
        $sql = "delete from cap_techno where techno_name=:id";
        try {
            $res = $this->query($sql, array("id" => $unid));
            $this->log->logActions($_SESSION['user']['email'], 'Delete Cap techno', 'Success Delete', 'techno ID :' . $unid . ' deleted');
        } catch (Exception $e) {
            // $this->db=null;
            return ($e->getMessage());
            $this->log->logActions($_SESSION['user']['email'], 'Delete Cap techno', 'Faild Delete due to ' . $e->getMessage(), 'Cap techno ID :' . $unid . ' deleted');
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
    
     
     public function processDeleteCapSpaceItem() {
       
        if ($_POST['action'] != 'capspaceitem_delete') {
            return "Invalid action supplied for process Delete Capcity spaceitem.";
        }
      
        $unid = htmlentities($_POST['input-id'], ENT_QUOTES);

        $sql = "delete from cap_spaceitem where resrc_name=:id";
        try {
            $res = $this->query($sql, array("id" => $unid));
            $this->log->logActions($_SESSION['user']['email'], 'Delete Cap spaceitem', 'Success Delete', 'infra item ID :' . $unid . ' deleted');
        } catch (Exception $e) {
            // $this->db=null;
            return ($e->getMessage());
            $this->log->logActions($_SESSION['user']['email'], 'Delete Cap spaceitem', 'Faild Delete due to ' . $e->getMessage(), 'Cap spaceitem ID :' . $unid . ' deleted');
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
     public function processDeleteCapPowerItem() {
       
        if ($_POST['action'] != 'cappoweritem_delete') {
            return "Invalid action supplied for process Delete Capcity poweritem item.";
        }
      
        $unid = htmlentities($_POST['input-id'], ENT_QUOTES);

        $sql = "delete from cap_poweritem where resrc_name=:id";
        try {
            $res = $this->query($sql, array("id" => $unid));
            $this->log->logActions($_SESSION['user']['email'], 'Delete Cap infra item', 'Success Delete', 'poweritem item ID :' . $unid . ' deleted');
        } catch (Exception $e) {
            // $this->db=null;
            return ($e->getMessage());
            $this->log->logActions($_SESSION['user']['email'], 'Delete Cap poweritem item', 'Faild Delete due to ' . $e->getMessage(), 'Cap poweritem item ID :' . $unid . ' deleted');
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
         public function processDeleteCapsectionItem() {
       
        if ($_POST['action'] != 'capsectionitem_delete') {
            return "Invalid action supplied for process Delete Capcity section item item.";
        }
      
        $unid = htmlentities($_POST['input-id'], ENT_QUOTES);

        $sql = "delete from cap_sectionitem where resrc_name=:id";
        try {
            $res = $this->query($sql, array("id" => $unid));
            $this->log->logActions($_SESSION['user']['email'], 'Delete Cap section item', 'Success Delete', 'section item ID :' . $unid . ' deleted');
        } catch (Exception $e) {
            // $this->db=null;
            return ($e->getMessage());
            $this->log->logActions($_SESSION['user']['email'], 'Delete Cap section item', 'Faild Delete due to ' . $e->getMessage(), 'Cap section item ID :' . $unid . ' deleted');
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
     public function processDeleteCapAssetItem() {
       
        if ($_POST['action'] != 'capassetitem_delete') {
            return "Invalid action supplied for process Delete Capcity assetsitem item.";
        }
      
        $unid = htmlentities($_POST['input-id'], ENT_QUOTES);

        $sql = "delete from cap_assetsitem where resrc_name=:id";
        try {
            $res = $this->query($sql, array("id" => $unid));
            $this->log->logActions($_SESSION['user']['email'], 'Delete Cap assetsitem item', 'Success Delete', 'assetsitem item ID :' . $unid . ' deleted');
        } catch (Exception $e) {
            // $this->db=null;
            return ($e->getMessage());
            $this->log->logActions($_SESSION['user']['email'], 'Delete Cap assetsitem item', 'Faild Delete due to ' . $e->getMessage(), 'Cap assetsitem item ID :' . $unid . ' deleted');
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
     public function processDeleteCapInfraItem() {
      
         
       
        if ($_POST['action'] != 'capinfraItem_delete') {
            return "Invalid action supplied for process Delete Capcity infra item.";
        }
      
        $unid = htmlentities($_POST['input-id'], ENT_QUOTES);

        $sql = "delete from cap_infraitem where resrc_name=:id";
        try {
            $res = $this->query($sql, array("id" => $unid));
            $this->log->logActions($_SESSION['user']['email'], 'Delete Cap infra item', 'Success Delete', 'infra item ID :' . $unid . ' deleted');
        } catch (Exception $e) {
            // $this->db=null;
            return ($e->getMessage());
            $this->log->logActions($_SESSION['user']['email'], 'Delete Cap infra item', 'Faild Delete due to ' . $e->getMessage(), 'Cap infra item ID :' . $unid . ' deleted');
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

    public function processDeleteCapServiceTechno() {
        /*
         * Fails if the proper action was not submitted
         */
        // echo "<script>alert('".$_POST['input-username']."')</script>";
//        if ($_POST['action'] != 'bibZone_delete') {
//            return "Invalid action supplied for process Delete CNOC Handover Category.";
//        }
        if ($_POST['action'] != 'capServiceTechno_delete') {
            return "Invalid action supplied for process Delete Capcity Service.";
        }
        /*
         * Escapes the user input for security
         */
        $unid = htmlentities($_POST['input-id'], ENT_QUOTES);


        /*
         * Retrieves the matching info from the DB if it exists
         */
        $sql = "delete from cap_servicetechno where servicetechno_id=:id";
        try {
            $res = $this->query($sql, array("id" => $unid));
            $this->log->logActions($_SESSION['user']['email'], 'Delete Cap servicetechno', 'Success Delete', 'servicetechno ID :' . $unid . ' deleted');
        } catch (Exception $e) {
            // $this->db=null;
            return ($e->getMessage());
            $this->log->logActions($_SESSION['user']['email'], 'Delete Cap servicetechno', 'Faild Delete due to ' . $e->getMessage(), 'Cap servicetechno ID :' . $unid . ' deleted');
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

    public function processDeleteCapfutureService() {
        /*
         * Fails if the proper action was not submitted
         */
        // echo "<script>alert('".$_POST['input-username']."')</script>";
//        if ($_POST['action'] != 'bibZone_delete') {
//            return "Invalid action supplied for process Delete CNOC Handover Category.";
//        }
        if ($_POST['action'] != 'CAPfutureService_delete') {
            return "Invalid action supplied for process Delete Capcity Service.";
        }
        /*
         * Escapes the user input for security
         */
        $unid = htmlentities($_POST['input-id'], ENT_QUOTES);


        /*
         * Retrieves the matching info from the DB if it exists
         */
        $sql = "delete from cap_futureservice where servicetechno_id=:id";
        try {
            $res = $this->query($sql, array("id" => $unid));
            $this->log->logActions($_SESSION['user']['email'], 'Delete Cap cap_futureservice', 'Success Delete', 'cap_futureservice ID :' . $unid . ' deleted');
        } catch (Exception $e) {
            // $this->db=null;
            return ($e->getMessage());
            $this->log->logActions($_SESSION['user']['email'], 'Delete Cap cap_futureservice', 'Faild Delete due to ' . $e->getMessage(), 'Cap cap_futureservice ID :' . $unid . ' deleted');
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
            $res = $this->query($sql, array("id" => $unid));
            $this->log->logActions($_SESSION['user']['email'], 'Delete BIB Zone', 'Success Delete', 'Zone ID :' . $unid . ' deleted');
        } catch (Exception $e) {
            // $this->db=null;
            return ($e->getMessage());
            $this->log->logActions($_SESSION['user']['email'], 'Delete BIB Zone', 'Faild Delete due to ' . $e->getMessage(), 'Zone ID :' . $unid . ' deleted');
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
    public function getAllCapServiceName() {// Connection data (server_address, database, name, poassword)
        $sql = "SELECT `service_name` FROM `cap_service` order by service_name ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }
        return $result; //customers":'.json_encode($result).'}';
    }

    public function getAllCaptechno_nameName() {// Connection data (server_address, database, name, poassword)
        $sql = "SELECT `techno_name` FROM `cap_techno` order by techno_name ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }
        return $result; //customers":'.json_encode($result).'}';
    }
    
     public function getAllCapinfraitem_nameName() {// Connection data (server_address, database, name, poassword)
        $sql = "SELECT `resrc_name` FROM `cap_infraitem` order by resrc_name ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }
        return $result; //customers":'.json_encode($result).'}';
    }
     public function getAllCapspacetem_nameName() {// Connection data (server_address, database, name, poassword)
        $sql = "SELECT `resrc_name` FROM `cap_spaceitem` order by resrc_name ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }
        return $result; //customers":'.json_encode($result).'}';
    }
         public function getAllCappowertem_nameName() {// Connection data (server_address, database, name, poassword)
        $sql = "SELECT `resrc_name` FROM `cap_poweritem` order by resrc_name ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }
        return $result; //customers":'.json_encode($result).'}';
    }
     public function getAllCapsectiontem_nameName() {// Connection data (server_address, database, name, poassword)
        $sql = "SELECT `resrc_name` FROM `cap_sectionitem` order by resrc_name ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }
        return $result; //customers":'.json_encode($result).'}';
    }
         public function getAllCapassetitem_nameName() {// Connection data (server_address, database, name, poassword)
        $sql = "SELECT `resrc_name` FROM `cap_assetsitem` order by resrc_name ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }
        return $result; //customers":'.json_encode($result).'}';
    }
       
    //==================END ZONE =================================//////
    /////////////--------------- OLT ------------------------------/////// 
    //AddNewCnocHandoverCategory
    public function AddNewBIBOLT() {

        if (!isset($_POST['input-catName']) || !isset($_POST['select-bib-zone_name'])) {


            return FALSE;
        }
        $Cat_name = $_POST['input-catName'];
        $Zone_name = $_POST['select-bib-zone_name'];

        $qr = $this->query("INSERT INTO bib_olt(`zone_name`,`olt_name`, `add_by`) values('" . htmlentities($Zone_name) . "','" . htmlentities($Cat_name) . "','" . $_SESSION['user']['email'] . "')") or die(mysql_error());
        return $qr;
    }

    /** get all Cncoc Categories for table view
     *
     * @return mixed TRUE on success, message on error
     */
    public function biuldOLTTable() {// Connection data (server_address, database, name, poassword)
        $sql = "SELECT zone_name,`olt_name`, `add_by`, `add_time` FROM `bib_olt` ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

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
            $rowKey = $row['zone_name'] . '__' . $row['olt_name'];
            $html .= '<tr class="odd gradeX">
												<td><input type="checkbox" class="checkboxes" value="' . $rowKey . '" /></td>
												 <td>' . $row['zone_name'] . '</td>
                                                                                                    <td>' . $row['olt_name'] . '</td>
												<td class="hidden-phone"><a href="mailto:' . $row['add_by'] . '@etisalat.ae">' . $row['add_by'] . '</a></td>
												<td class="hidden-phone">' . ($row['add_time'] == '' ? '-' : $row['add_time']) . '</td>
												
												';
            IF ((($_SESSION['currentRole'] == 'Admin')or ( $_SESSION['teamName'] == 'supperAdmins'))) {
                $html .= '<td class="center">
													
													<a href="#" class="icon huge"><i class="icon-remove" id="removeBIBCat_' . $rowKey . '" data-d="input-id=' . $rowKey . '&token=' . $_SESSION['token'] . '&action=bibOlt_delete"></i></a>&nbsp;		
												</td>';
            } else {
                $html .= '<td class="center">You don\'t have AUTH </td>';
            }
            $html .= '</tr>';


            // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }




        $html .= '</tbody></table>';

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

        $unid = explode('__', htmlentities($_POST['input-id'], ENT_QUOTES));


        /*
         * Retrieves the matching info from the DB if it exists
         */
        //  $sql = "delete from bib_subnets where zone_name=:zid and subnet_name=:sid";
        /*
         * Retrieves the matching info from the DB if it exists
         */
        $sql = "delete from bib_olt where zone_name=:zid and olt_name=:id";
        try {
            $res = $this->query($sql, array("zid" => $unid[0], "id" => $unid[1]));
            $this->log->logActions($_SESSION['user']['email'], 'Delete BIB OLT', 'Success Delete', 'OLT ID :' . $unid[0] . $unid[1] . ' deleted');
        } catch (Exception $e) {
            // $this->db=null;
            return ($e->getMessage());
            $this->log->logActions($_SESSION['user']['email'], 'Delete BIB OLT', 'Faild Delete due to ' . $e->getMessage(), 'OLT ID :' . $unid[0] . $unid[1] . ' deleted');
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
    public function getAllBIBOLTName($zonename = NULL) {// Connection data (server_address, database, name, poassword)
        $sql = "SELECT `olt_name` FROM `bib_olt`" . $zonename == NULL ? '' : " where zone_name:zid" . " order by olt_name ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql, array("zid" => $zonename));
        } catch (PDOException $e) {

            return($e->getMessage());
        }
        return $result; //customers":'.json_encode($result).'}';
    }

    public function getAllOLTPerZone($custname = null) {// Connection data (server_address, database, name, poassword)
        if ($custname != null or isset($_POST['code'])) {
            $custname = $custname == null ? htmlentities($_POST['code'], ENT_QUOTES) : htmlentities($custname, ENT_QUOTES);

            $sql = "SELECT `olt_name` FROM `bib_olt` where zone_name=:zid  order by olt_name"; //"SELECT  distinct t.`cmdb_host_name` from ( (select distinct `cmdb_host_name` FROM mss_db.`mss_cmdb` where `cmdb_customer_code`=:custcode) union (select distinct host_name from cnoc_handovers where Cust_code=:custcode1)  union (select distinct host_name from cnoc_activities where Cust_code=:custcode2)) as t  order by cmdb_host_name";//"SELECT distinct cmdb_host_name FROM `mss_cmdb` where `cmdb_customer_code`=:custcode order by cmdb_host_name ";

            $html = "";
            $result = array();
            try {
                $result = $this->query($sql, array('zid' => $custname), PDO::FETCH_OBJ);
            } catch (PDOException $e) {

                return($e->getMessage());
            }
            return json_encode($result);
        }
    }

    //==================END OTL =================================//////
    /////////////--------------- Subnet ------------------------------/////// 
    //AddNewCnocHandoverCategory
    public function AddNewBIBsubnet() {

        $Zone_name = $_POST['select-bib-zone_name'];
        $Olt_name = $_POST['select-bib-olt_name'];
        $subnet_name = $_POST['txt-bib-subnet'];
        $gateway_name = $_POST['txt-bib-gateway'];
        $subnet_from = $_POST['txt-bib-subnet_from'];
        $subnet_to = $_POST['txt-bib-subnet_to'];

        if (!isset($Zone_name) or empty($Zone_name) or ! isset($Olt_name) or empty($Olt_name) or ! isset($subnet_name) or empty($subnet_name)or ! isset($gateway_name) or empty($gateway_name)or ! isset($subnet_from) or empty($subnet_from)or ! isset($subnet_to) or empty($subnet_to)) {
            return FALSE;
        }

        $qr = $this->query("INSERT INTO bib_subnets(`zone_name`,`olt_name`,`subnet_name`, `Gateway`, `SubRange_from`, `SubRange_to`, `add_by`) values('" . htmlentities($Zone_name) . "','" . htmlentities($Olt_name) . "','" . htmlentities($subnet_name) . "','" . htmlentities($gateway_name) . "','" . htmlentities($subnet_from) . "','" . htmlentities($subnet_to) . "','" . $_SESSION['user']['email'] . "')") or die(mysql_error());
        return $qr;
    }

    /**
     *
     * @return mixed TRUE on success, message on error
     */
    public function biuldsubnetTable() {// Connection data (server_address, database, name, poassword)
        $sql = "SELECT `zone_name`,`olt_name`, `subnet_name`, `Gateway`, `SubRange_from`, `SubRange_to`, `add_by`, `add_time` FROM `bib_subnets`";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

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
            $rowKey = $row['zone_name'] . '__' . $row['subnet_name'];
            $html .= '<tr class="odd gradeX">
												<td><input type="checkbox" class="checkboxes" value="' . $rowKey . '" /></td>
												
                                                                                                    <td>' . $row['zone_name'] . '</td>
                                                                                                        <td>' . $row['olt_name'] . '</td>
                                                                                                    <td>' . $row['subnet_name'] . '</td>
                                                                                                    <td>' . $row['Gateway'] . '</td>
                                                                                                    <td>' . $row['SubRange_from'] . '</td>
                                                                                                    <td>' . $row['SubRange_to'] . '</td>
                                                                                                   
												<td class="hidden-phone"><a href="mailto:' . $row['add_by'] . '@etisalat.ae">' . $row['add_by'] . '</a></td>
												<td class="hidden-phone">' . ($row['add_time'] == '' ? '-' : $row['add_time']) . '</td>
												
												';
            IF ((($_SESSION['currentRole'] == 'Admin')or ( $_SESSION['teamName'] == 'supperAdmins'))) {
                $html .= '<td class="center">
													
													<a href="#" class="icon huge"><i class="icon-remove" id="removeBIBCat_' . $rowKey . '" data-d="input-id=' . $rowKey . '&token=' . $_SESSION['token'] . '&action=bibsubnet_delete"></i></a>&nbsp;		
												</td>';
            } else {
                $html .= '<td class="center">You don\'t have AUTH </td>';
            }
            $html .= '</tr>';


            // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }




        $html .= '</tbody></table>';

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
        $unid = explode('__', htmlentities($_POST['input-id'], ENT_QUOTES));


        /*
         * Retrieves the matching info from the DB if it exists
         */
        $sql = "delete from bib_subnets where zone_name=:zid and subnet_name=:sid";
        try {
            $res = $this->query($sql, array("zid" => $unid[0], "sid" => $unid[1]));
            $this->log->logActions($_SESSION['user']['email'], 'Delete BIB SUBNET', 'Success Delete', 'subnet ID :' . $_POST['input-id'] . ' deleted');
        } catch (Exception $e) {
            // $this->db=null;
            return ($e->getMessage());
            $this->log->logActions($_SESSION['user']['email'], 'Delete BIB SUBNET', 'Faild Delete due to ' . $e->getMessage(), 'subnet ID :' . $_POST['input-id'] . ' deleted');
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
    public function getAllBIBsubnetName($zonename = NULL) {// Connection data (server_address, database, name, poassword)
        $sql = "SELECT `subnet_name` FROM `bib_subnets`" . $zonename == NULL ? '' : " where zone_name:zid" . " order by olt_name ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql, array("zid" => $unid[0]));
        } catch (PDOException $e) {

            return($e->getMessage());
        }
        return $result; //customers":'.json_encode($result).'}';
    }

    public function getAllSubnetPerOLT($custname = null) {// Connection data (server_address, database, name, poassword)
        if ($custname != null or isset($_POST['code'])) {
            $custname = $custname == null ? htmlentities($_POST['code'], ENT_QUOTES) : htmlentities($custname, ENT_QUOTES);

            $sql = "SELECT `subnet_name` FROM `bib_subnets` where olt_name=:zid  order by subnet_name"; //"SELECT  distinct t.`cmdb_host_name` from ( (select distinct `cmdb_host_name` FROM mss_db.`mss_cmdb` where `cmdb_customer_code`=:custcode) union (select distinct host_name from cnoc_handovers where Cust_code=:custcode1)  union (select distinct host_name from cnoc_activities where Cust_code=:custcode2)) as t  order by cmdb_host_name";//"SELECT distinct cmdb_host_name FROM `mss_cmdb` where `cmdb_customer_code`=:custcode order by cmdb_host_name ";

            $html = "";
            $result = array();
            try {
                $result = $this->query($sql, array('zid' => $custname), PDO::FETCH_OBJ);
            } catch (PDOException $e) {

                return($e->getMessage());
            }
            return json_encode($result);
        }
    }

    public function get_subnetips($custname = null) {// Connection data (server_address, database, name, poassword)
        if ($custname != null or isset($_POST['code'])) {
            $custname = $custname == null ? htmlentities($_POST['code'], ENT_QUOTES) : htmlentities($custname, ENT_QUOTES);
            //SELECT `IP_address` FROM `bib_srs` WHERE `sr_status`!= 'Cancelled' and `Subnet-Mask` = '10.10.10.0/28' 
            $sql = "SELECT `IP_address` FROM `bib_srs` where `sr_status`!= 'Cancelled' and `Subnet-Mask` =:zid  order by IP_address"; //"SELECT  distinct t.`cmdb_host_name` from ( (select distinct `cmdb_host_name` FROM mss_db.`mss_cmdb` where `cmdb_customer_code`=:custcode) union (select distinct host_name from cnoc_handovers where Cust_code=:custcode1)  union (select distinct host_name from cnoc_activities where Cust_code=:custcode2)) as t  order by cmdb_host_name";//"SELECT distinct cmdb_host_name FROM `mss_cmdb` where `cmdb_customer_code`=:custcode order by cmdb_host_name ";

            $html = "";
            $result = array();
            try {
                $result = $this->query($sql, array('zid' => $custname));
            } catch (PDOException $e) {

                return($e->getMessage());
            }

            $usedips = null;
            foreach ($result as $row) {

                $usedips[] = $row['IP_address'];

                //print("\n");
            }


            //      $result2 = $result->db->fetch(PDO::FETCH_ASSOC);
//print_r($result2);
            // $newArray1 = array_values($result);
            // print_r($usedips);
            //  echo implode( ',', call_user_func_array('array_merge', $result ) );


            $subnetObj = new subnet();
            $arr = $subnetObj->subnet_ipRange($custname);
            //  print_r($arr);
            $resarr = array_diff($arr, $usedips);
            if ($usedips == null) {
                $resarr = $arr;
            }
            if ($resarr == null) {
                $resarr[] = 'No Avl Ips';
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

    public function getAllgetwayPerSubnet($custname = null) {// Connection data (server_address, database, name, poassword)
        if ($custname != null or isset($_POST['code'])) {
            $custname = $custname == null ? htmlentities($_POST['code'], ENT_QUOTES) : htmlentities($custname, ENT_QUOTES);

            $sql = "SELECT distinct `Gateway` FROM `bib_subnets` where subnet_name=:zid  order by Gateway"; //"SELECT  distinct t.`cmdb_host_name` from ( (select distinct `cmdb_host_name` FROM mss_db.`mss_cmdb` where `cmdb_customer_code`=:custcode) union (select distinct host_name from cnoc_handovers where Cust_code=:custcode1)  union (select distinct host_name from cnoc_activities where Cust_code=:custcode2)) as t  order by cmdb_host_name";//"SELECT distinct cmdb_host_name FROM `mss_cmdb` where `cmdb_customer_code`=:custcode order by cmdb_host_name ";

            $html = "";
            $result = array();
            try {
                $result = $this->query($sql, array('zid' => $custname), PDO::FETCH_OBJ);
            } catch (PDOException $e) {

                return($e->getMessage());
            }
            return json_encode($result);
        }
    }

    //==================END Subnet =================================//////
    /////////////--------------- Status ------------------------------/////// 
    public function AddNewBIBStatus() {

        $Cat_name = $_POST['input-catName'];
        $qr = $this->query("INSERT INTO bib_status(`status_name`, `add_by`) values('" . htmlentities($Cat_name) . "','" . $_SESSION['user']['email'] . "')") or die(mysql_error());
        return $qr;
    }

    /** get all Cncoc Categories for table view
     *
     * @return mixed TRUE on success, message on error
     */
    public function biuldStatusTable() {// Connection data (server_address, database, name, poassword)
        $sql = "SELECT `status_name`, `add_by`, `add_time` FROM `bib_status` ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

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

            $html .= '<tr class="odd gradeX">
												<td><input type="checkbox" class="checkboxes" value="' . $row['status_name'] . '" /></td>
												
                                                                                                    <td>' . $row['status_name'] . '</td>
												<td class="hidden-phone"><a href="mailto:' . $row['add_by'] . '@etisalat.ae">' . $row['add_by'] . '</a></td>
												<td class="hidden-phone">' . ($row['add_time'] == '' ? '-' : $row['add_time']) . '</td>
												
												';
            IF ((($_SESSION['currentRole'] == 'Admin')or ( $_SESSION['teamName'] == 'supperAdmins'))) {
                $html .= '<td class="center">
													
													<a href="#" class="icon huge"><i class="icon-remove" id="removeBIBCat_' . $row['status_name'] . '" data-d="input-id=' . $row['status_name'] . '&token=' . $_SESSION['token'] . '&action=bibStatus_delete"></i></a>&nbsp;		
												</td>';
            } else {
                $html .= '<td class="center">You don\'t have AUTH </td>';
            }
            $html .= '</tr>';


            // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }




        $html .= '</tbody></table>';

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
            $res = $this->query($sql, array("id" => $unid));
            $this->log->logActions($_SESSION['user']['email'], 'Delete BIB Status', 'Success Delete', 'Status ID :' . $unid . ' deleted');
        } catch (Exception $e) {
            // $this->db=null;
            return ($e->getMessage());
            $this->log->logActions($_SESSION['user']['email'], 'Delete BIB Status', 'Faild Delete due to ' . $e->getMessage(), 'Status ID :' . $unid . ' deleted');
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
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }
        return $result; //customers":'.json_encode($result).'}';
    }

    //==================END Status =================================//////
}

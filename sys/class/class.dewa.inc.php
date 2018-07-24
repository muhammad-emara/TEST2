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
class dewa extends DB_Connect {

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
     * Check If the site inserted before depending on its Spoke Lan host Name
     */

    private function dewa_HostExist($hostSer = null) {
        $found = false;

        // Fetching single value
        $hostID = $this->single("SELECT `Dewa_data_id` FROM `dewa_data` WHERE `Spoke_Lan_Sb_Hostname`= :id ", array('id' => $hostSer));
        if ($hostID > 0) {
            $found = TRUE;
        }

        return $found;
    }

    /*
     * Reading CSV as Assoicated Array
     */

    private function ImportCSV2Array($filename) {
        $row = 0;
        $col = 0;

        $handle = @fopen($filename, "r");
        if ($handle) {
            while (($row = fgetcsv($handle, 4096)) !== false) {
                if (empty($fields)) {
                    $fields = $row;
                    continue;
                }

                foreach ($row as $k => $value) {
                    $results[$col][$fields[$k]] = trim($value);
                }
                $col++;
                unset($row);
            }
            if (!feof($handle)) {
                echo "Error: unexpected fgets() failn";
            }
            fclose($handle);
        }

        return $results;
    }

//iportCSV2Array


    /*
     * Read the GPON Configuration file
     */

    public function dewa_GponConfig() {

        if ($_POST['action'] != 'Dewa_GponConfig') {
            return "Invalid action supplied for GPON Config";
        }
        $found = '';


        $file = trim('../comm/templates/DEWA/DEWA TEMPLATE_GPON.txt');

        $filestring = file_get_contents($file);
        $filearray = explode("\n", $filestring);

        while (list($var, $val) = each($filearray)) {
            ++$var;
            $val = trim($val);
            $found.= "Line $var: $val<br />";
            // echo $found;
        }
//$found=true;
        if ($found != '') {
            $found = str_replace("AAAAA", $_POST['Spoke_Lan_Sb_Management_vlan'], $found);
            $found = str_replace("BBBBB", $_POST['Spoke_Lan_Sb_Data_Vlan'], $found);
            $found = str_replace("CCCCC", $_POST['Data_Port_BW'], $found);
            $found = str_replace("DDDDD", $_POST['Access_SR'], $found);
            $found = str_replace("EEEEE", $_POST['Access_Account'], $found);
            $found = str_replace("FFFFF", $_POST['Spoke_Lan_Sb_Data_Wan_IP'], $found);
            $found = str_replace("VVVVV", $_POST['Spoke_Lan_Sb_Management_IP_CE31'], $found);
            $found = str_replace("GGGGG", $_POST['spoke_vlan_1'], $found);
            $found = str_replace("HHHHH", $_POST['spoke_vlan_2'], $found);
            $found = str_replace("IIIII", $_POST['spoke_vlan_3'], $found);
            $found = str_replace("JJJJJ", $_POST['spoke_vlan_4'], $found);
            $found = str_replace("KKKKK", $_POST['Spoke_Lan_Sb_Management_vlan'], $found);
            $found = str_replace("LLLLL", $_POST['Hub_Sec_GPRS_GPON_end2end_tunnel_source_ip'], $found);
            $found = str_replace("MMMMM", $_POST['Spoke_Lan_Sb_Tunnel_IP'], $found);
            $found = str_replace("NNNNN", $_POST['Hub_Pri_GPON_Tunnel_IP'], $found);
            $found = str_replace("OOOOO", $_POST['Hub_Pri_GPRS_GPON_end2end_tunnel_source_ip'], $found);
            $found = str_replace("PPPPP", $_POST['Hub_Sec_GPON_Tunnel_IP'], $found);
            $found = str_replace("QQQQQ", $_POST['Hub_Sec_GPRS_GPON_end2end_tunnel_source_ip'], $found);
            $found = str_replace("RRRRR", $_POST['Hub_Pri_Data_Wan_ip'], $found);
            $found = str_replace("SSSSS", $_POST['Hub_Sec_Data_Wan_ip'], $found);
            $found = str_replace("TTTTT", $_POST['Dewa_ref/location_ref'], $found);
            $found = str_replace("UUUUU", $_POST['Router_SN'], $found);
        }




        return $found;
    }

    public function forecast_insertupload() {// Connection data (server_address, database, name, poassword)
        if ($_POST['action'] != 'forecast_bulkupload') {
            return "Invalid action supplied for bulkupload.";
        }
        $fName = trim('../comm/fileuplaod_temp/' . $_POST['fileName']);
        if ($fName == NULL) {
            return "Invalid File Name supplied .";
        }
        //Party ID	Account Number	Customer Name	Section	Qty	Router Model	Prob. Of Closing	Requested By	Service
        // echo '<br/> fname : '.$fName.'<br/>';
        try {
            //$handle = fopen($fName, "r");

            $c = -1;
            $_sql = array();
            $file = fopen($fName, "r") or die("error open file " . $fName);

            while (!feof($file)) {
                $filesop = array();
                $filesop = fgetcsv($file);
                $c++;
                if ($c == 0) {

                    continue;
                }
                $Party_id = $filesop[0];
                $acc_num = $filesop[1];
                $acc_Name = $filesop[2];
                $Section = $filesop[3];
                $Qty = $filesop[4];
                $Router_model = $filesop[5];
                $Prob = $filesop[6];
                $service = $filesop[8];
                $req_by = $filesop[7];
                // check for cust name and qty and router
                if (($acc_Name != '' and ! (is_null($acc_Name)) and isset($acc_Name)) and ( $Qty >= 0 and ! (is_null($Qty)) and isset($Qty)) and ( $Router_model != '' and ! (is_null($Router_model)) and isset($Router_model))) {
                    //echo '<br/> loop#'.$c.'values are : '.'("'.mysql_real_escape_string($Party_id).'","'.mysql_real_escape_string($acc_num).'", "'.mysql_real_escape_string($acc_Name).'", "'.mysql_real_escape_string($service).'", "'.mysql_real_escape_string($Section).'", "'.mysql_real_escape_string($Router_model).'", '.mysql_real_escape_string($Qty).', "'.mysql_real_escape_string($Prob).'", "'.mysql_real_escape_string($req_by).'")<br/>-----------------------------------';
                    //  $_sql[] = '("' . mysql_real_escape_string($Party_id) . '","' . mysql_real_escape_string($acc_num) . '", "' . mysql_real_escape_string($acc_Name) . '", "' . mysql_real_escape_string($service) . '", "' . mysql_real_escape_string($Section) . '", "' . mysql_real_escape_string($Router_model) . '", ' . mysql_real_escape_string($Qty) . ', "' . mysql_real_escape_string($Prob) . '", "' . mysql_real_escape_string($req_by) . '")';
                    $_sql[] = '("' . mysql_real_escape_string($Device_Name, $this->pdo) . '","' . mysql_real_escape_string($Model, $this->pdo) . '", "' . mysql_real_escape_string($router_sr, $this->pdo) . '", "' . mysql_real_escape_string($vendor, $this->pdo) . '", "' . mysql_real_escape_string($LPO, $this->pdo) . '", "' . mysql_real_escape_string($cust_name, $this->pdo) . '", "' . mysql_real_escape_string($user, $this->pdo) . '")';
                }
            }
            fclose($file);
            // chmod('../comm/fileuplaod_temp/', 0777);



            $sql = 'INSERT INTO `stock_forecast`( `party_id`, `account_number`, `customer_name`, `service`, `section`, `router_model`, `quantity`, `prob_of_closeing`, `req_by`) VALUES ' . implode(',', $_sql);

            try {
                unlink($fName);
                $result = $this->query($sql);
                if ($result > 0) {

                    $this->log->logActions($_SESSION['user']['email'], 'Upload New Forecast', 'Success Delete', 'DATA :' . $sql . ' added');

                    return TRUE;
                } else {
                    return FALSE;
                }
            } catch (PDOException $e) {
                unlink($fName);
                $this->log->logActions($_SESSION['user']['email'], 'Upload New Forecast', 'error pdo ' . $e->getMessage(), 'DATA :' . $sql . ' Not added');
                return('error pdo ' . $e->getMessage());
            }
        } catch (Exception $ex) {
            unlink($fName);
            return('error opening ' . $ex->getMessage());
        }
    }

//end of forecast_insertupload


    /*
     * 
     * Add New Stock forecast
     * 
     */
    public function forecast_Addnew() {
        /*
         * Fails if the proper action was not submitted
         */
        // echo "<script>alert('".$_POST['input-username']."')</script>";
        if ($_POST['action'] != 'stock_AddForecast') {
            return "Invalid action supplied for process Add new Forecast.";
        }
        /*
         * Escapes the user input for security
         */
        //$unName = htmlentities($_POST['input-uName'], ENT_QUOTES);
        //$unPassword =  $this->getPasswordHash( htmlentities($_POST['input-pass'], ENT_QUOTES));
        // $teamID_fk = htmlentities($_POST['select_Teams'], ENT_QUOTES);
        $PartyID = htmlentities($_POST['txt-forecast-PartyId'], ENT_QUOTES);
        $AccountNumber = (htmlentities($_POST['txt-forecast-custNum'], ENT_QUOTES) == '' ? 'N/A' : htmlentities($_POST['txt-forecast-custNum'], ENT_QUOTES));
        $CustomerName = (strtolower(htmlentities($_POST['select-forecast-custName'], ENT_QUOTES)) == 'other' ? htmlentities($_POST['txt-forecast-custName'], ENT_QUOTES) : htmlentities($_POST['select-forecast-custName'], ENT_QUOTES));
        $Section = htmlentities($_POST['select-forecast-dept'], ENT_QUOTES);
        //  $Qty = htmlentities($_POST['txt-forecast-qty'], ENT_QUOTES);
        $RouterModel[] = htmlentities($_POST['select-forecast-deviceModel'], ENT_QUOTES);
        $Prob_Of_Closing = htmlentities($_POST['select-forecast-prob'], ENT_QUOTES);
        $Requested_By = htmlentities($_SESSION['user']['email'], ENT_QUOTES);
        $Service[] = htmlentities($_POST['select-forecast-serviceName'], ENT_QUOTES);

        // var_dump($Service);
        // var_dump($RouterModel);

        /*
         * Retrieves the matching info from the DB if it exists
         */
        $sql = "INSERT INTO stock_forecast( `party_id`, `account_number`, `customer_name`, `service`, `section`, `router_model`, `quantity`, `prob_of_closeing`, `req_by`) VALUES(:prtid,:acc_num,:acc_name,:serv,:section,:routmodel,:qty,:prob_close,:reqby)";
        //$this->lastInsertId();
        try {
            $res = $this->query($sql, array("prtid" => $PartyID, "acc_num" => $AccountNumber, "acc_name" => $CustomerName, "serv" => $Service, "section" => $Section, "routmodel" => $RouterModel, "qty" => $Qty, "prob_close" => $Prob_Of_Closing, "reqby" => $Requested_By));
            $this->log->logActions($_SESSION['user']['email'], ' New Forecast', 'Succes ad  ', 'DATA :prtid:' . $PartyID . "||acc_num" . $AccountNumber . "||acc_name" . $CustomerName . "||serv" . $Service . "||section" . $Section . "||routmodel" . $RouterModel . "||qty" . $Qty . "||prob_close" . $Prob_Of_Closing . 'added');
        } catch (Exception $e) {
            // $this->db=null;
            $this->log->logActions($_SESSION['user']['email'], 'Delete User', 'Faild Delete due to ' . $e->getMessage(), 'Data :');
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

    /** get all user for table view
     *
     * @return mixed TRUE on success, message on error
     */
    public function biuldforecastTable() {// Connection data (server_address, database, name, poassword)
        $sql = "SELECT * FROM `stock_forecast` ORDER BY `stock_forecast`.`fcst_id` DESC";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }


        /*
         * `fcst_id`, `party_id`, `account_number`, `customer_name`, `service`, `section`, `router_model`, `quantity`, `prob_of_closeing`, `req_by`, `req_time`
         */


        $html = '<table class="table table-striped table-bordered" id="sample_1">
										<thead>
											<tr>
												<th></th>
												<th>Forecast ID</th>
												<th>Party ID</th>
                                                                                                <th>Account Number</th>
												<th>Account Name</th>
                                                                                                <th>Service</th>
												<th>Section</th>
                                                                                                <th>Router Model</th>
												<th>QTY</th>
                                                                                                <th>Prob. of Closing</th>
												<th>Requested By</th>
                                                                                                <th>Request Time</th>';


        if (($_SESSION['teamName'] == 'Forecasting' and $_SESSION['currentRole'] == 'Admin')or ( $_SESSION['teamName'] == 'supperAdmins')) {
            $html.='<th>Actions</th>';
        }
        $html.= '</tr>
										</thead>
										<tbody>';
        foreach ($result as $row) {

            $html.='<tr class="odd gradeX"><td></td>
												
												<td>' . $row['fcst_id'] . '</td>
                                                                                                    <td>' . $row['party_id'] . '</td>
                                                                                                        <td>' . $row['account_number'] . '</td>
                                                                                                            <td>' . $row['customer_name'] . '</td>
                                                                                                                <td>' . $row['service'] . '</td>
												<td>' . $row['section'] . '</td>
                                                                                                    <td>' . $row['router_model'] . '</td>
                                                                                                        <td>' . $row['quantity'] . '</td>
                                                                                                            <td>' . $row['prob_of_closeing'] . '</td>
                                                                                                                <td>' . $row['req_by'] . '</td>
												 <td>' . $row['req_time'] . '</td>
												';
            if (($_SESSION['teamName'] == 'Forecasting' and $_SESSION['currentRole'] == 'Admin')or ( $_SESSION['teamName'] == 'supperAdmins')) {
                $html.='<td class="center">
													<!--<a href="#" class="icon huge"><i class="icon-zoom-in" id="viewForecast_' . $row['fcst_id'] . '" data-d="input-id=' . $row['fcst_id'] . '&token=' . $_SESSION['token'] . '&action=Stock_Forecast_view"></i></a>&nbsp;-->	
													<!--<a href="#" class="icon huge"><i class="icon-pencil" id="addForecast_' . $row['fcst_id'] . '" data-d="input-id=' . $row['fcst_id'] . '&token=' . $_SESSION['token'] . '&action=Stock_Forecast_edit"></i></a>&nbsp;-->
													<a href="#" class="icon huge"><i class="icon-remove" id="removeForecast_' . $row['fcst_id'] . '" data-d="input-id=' . $row['fcst_id'] . '&token=' . $_SESSION['token'] . '&action=Stock_Forecast_delete"></i></a>&nbsp;		
												</td>';
            }
            $html.='</tr>';


            // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }




        /* $html.='<tfoot><tr> <th></th>
          <th>Forecast ID</th>
          <th>Party ID</th>
          <th>Account Number</th>
          <th>Account Name</th>
          <th>Service</th>
          <th>Section</th>
          <th>Router Model</th>
          <th>QTY</th>
          <th>Prob. of Closing</th>
          <th>Requested By</th>
          <th>Request Time</th>


          <th>Actions</th></tr></tfoot></tbody></table>'; */

        return $html;
    }

    /*     * *******************************************************
     * 
     * Delete Forecast provided by his ID 
     * ************************************************
     */

    public function forecast_delete() {
        /*
         * Fails if the proper action was not submitted
         */
        // echo "<script>alert('".$_POST['input-username']."')</script>";
        if ($_POST['action'] != 'Stock_Forecast_delete') {
            return "Invalid action supplied for process Delete Forecast.";
        }
        /*
         * Escapes the user input for security
         */
        $unid = htmlentities($_POST['input-id'], ENT_QUOTES);


        /*
         * Retrieves the matching info from the DB if it exists
         */
        $sql = "DELETE FROM stock_forecast WHERE fcst_id=:id";
        try {
            $this->log->logActions($_SESSION['user']['email'], 'Delete Forecast', ' Delete success ', 'users fcst id :' . fcst_id . ' deleted');
            $res = $this->query($sql, array("id" => $unid));
        } catch (Exception $e) {
            // $this->db=null;
            $this->log->logActions($_SESSION['user']['email'], 'Delete User', 'Faild Delete due to ' . $e->getMessage(), 'fcst_id :' . fcst_id . ' ');
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

    /*     * *******************************************************
     * 
     * Delete Stock Data provided by his ID 
     * ************************************************
     */

    public function stock_delete() {
        /*
         * Fails if the proper action was not submitted
         */
        // echo "<script>alert('".$_POST['input-username']."')</script>";
        if ($_POST['action'] != 'Stock_Stock_delete') {
            return "Invalid action supplied for process Delete Stock.";
        }
        /*
         * Escapes the user input for security
         */
        $unid = htmlentities($_POST['input-id'], ENT_QUOTES);


        /*
         * Retrieves the matching info from the DB if it exists
         */
        $sql = "DELETE FROM stock_stockdata WHERE stock_ID=:id ";
        try {

            $res = $this->query($sql, array("id" => $unid));
            $this->query("DELETE FROM `stock_installation` WHERE `stock_ref`=:id", array("id" => $unid));
            ;
            $this->log->logActions($_SESSION['user']['email'], 'Delete Stock', ' Delete success ', 'Stock ID :' . $unid . ' deleted');
        } catch (Exception $e) {
            // $this->db=null;
            $this->log->logActions($_SESSION['user']['email'], 'Delete Stock', 'Faild Delete due to ' . $e->getMessage(), 'ss');
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

    /*     * *************End Delete stock ************ */

    /*     * ******************************************************************************order ****************************** */

    //////////////insert the uploaded sheet /////////////////////////////
    public function order_insertupload() {// Connection data (server_address, database, name, poassword)
        if ($_POST['action'] != 'order_bulkupload') {
            return "Invalid action supplied for bulkupload.";
        }
        $fName = trim('../comm/fileuplaod_temp/' . $_POST['fileName']);
        if ($fName == NULL) {
            return "Invalid File Name supplied .";
        }

        try {
            //$handle = fopen($fName, "r");

            $c = -1;
            $_sql = array();
            $file = fopen($fName, "r") or die("error open file " . $fName);

            while (!feof($file)) {
                $filesop = array();
                $filesop = fgetcsv($file);
                $c++;
                if ($c == 0) {

                    continue;
                }
                //INSERT INTO `stock_order`(`cear_id`, `router_model`, `description`, `po_qty`, `customer_name`, `req_by`, `Req_date`, `vendor`, `po_id`, `po_date`, `EDD`)
                //CEAR # //cear_id	Item Name //router_model	Description//description	PO Quantity//po_qty	Customer Name//customer_name	Requested By//req_by	ORIGINATION DATE//Req_date	Vendor//vendor	PO #//po_id	PO DATE//po_date	EDD//EDD
//( `customer_name`, `po_id`, `vendor`, `RouterClass`, `router_model`, `description`, `po_qty`, `cear_id`, `req_by`, `Req_date`, `po_date`, `EDD`, `user_add`)
                $cust_name = $filesop[0];
                $po_id = $filesop[1];
                $vendor = $filesop[2];
                $RouterClass = $filesop[3];
                $Item_Name = $filesop[4];
                $Description = $filesop[5];
                $PO_QTY = $filesop[6];
                $CEAR_id = $filesop[7];
                $Req_by = $filesop[8];





                $Req_date = ($filesop[9] == null or $filesop[9] == '') ? null : date("Y-m-d H:i:s", strtotime($filesop[9])); //$filesop[6];


                $po_date = ($filesop[10] == null or $filesop[10] == '') ? null : date("Y-m-d H:i:s", strtotime($filesop[10])); //$filesop[10];
                $EDD = $filesop[11];
                $user = $_SESSION['user']['email'];
                // check for cust name and qty and router
                if (($cust_name != '' and ! (is_null($cust_name)) and isset($cust_name)) and ( $PO_QTY >= 0 and ! (is_null($PO_QTY)) and isset($PO_QTY)) and ( $Item_Name != '' and ! (is_null($Item_Name)) and isset($Item_Name))) {
                    //echo '<br/> loop#'.$c.'values are : '.'("'.mysql_real_escape_string($Party_id).'","'.mysql_real_escape_string($acc_num).'", "'.mysql_real_escape_string($acc_Name).'", "'.mysql_real_escape_string($service).'", "'.mysql_real_escape_string($Section).'", "'.mysql_real_escape_string($Router_model).'", '.mysql_real_escape_string($Qty).', "'.mysql_real_escape_string($Prob).'", "'.mysql_real_escape_string($req_by).'")<br/>-----------------------------------';
                    $_sql[] = '("' . mysql_real_escape_string($cust_name) . '","' . mysql_real_escape_string($po_id) . '", "' . mysql_real_escape_string($vendor) . '", "' . mysql_real_escape_string($RouterClass) . '", "' . mysql_real_escape_string($Item_Name) . '", "' . mysql_real_escape_string($Description) . '", ' . mysql_real_escape_string($PO_QTY) . ', "' . mysql_real_escape_string($CEAR_id) . '", "' . mysql_real_escape_string($Req_by) . '", "' . mysql_real_escape_string($Req_date) . '", "' . mysql_real_escape_string($po_date) . '", "' . mysql_real_escape_string($EDD) . '", "' . $user . '")';
                }
            }
            fclose($file);
            // chmod('../comm/fileuplaod_temp/', 0777);



            $sql = 'INSERT INTO `stock_order`( `customer_name`, `po_id`, `vendor`, `RouterClass`, `router_model`, `description`, `po_qty`, `cear_id`, `req_by`, `Req_date`, `po_date`, `EDD`, `user_add`) VALUES ' . implode(',', $_sql);

            try {
                unlink($fName);
                $result = $this->query($sql);
                if ($result > 0) {

                    return TRUE;
                } else {
                    return FALSE;
                }
            } catch (PDOException $e) {
                unlink($fName);
                return('error pdo ' . $e->getMessage());
            }
        } catch (Exception $ex) {
            unlink($fName);
            return('error opening ' . $ex->getMessage());
        }
    }

//end of order_insertupload


    /*
     * 
     * Add New Stock forecast
     * 
     */
    public function order_Addnew() {
        /*
         * Fails if the proper action was not submitted
         */
        // echo "<script>alert('".$_POST['input-username']."')</script>";
        if ($_POST['action'] != 'stock_AddForecast') {
            return "Invalid action supplied for process Add new Forecast.";
        }
        /*
         * Escapes the user input for security
         */
        //$unName = htmlentities($_POST['input-uName'], ENT_QUOTES);
        //$unPassword =  $this->getPasswordHash( htmlentities($_POST['input-pass'], ENT_QUOTES));
        // $teamID_fk = htmlentities($_POST['select_Teams'], ENT_QUOTES);
        $PartyID = htmlentities($_POST['txt-forecast-PartyId'], ENT_QUOTES);
        $AccountNumber = (htmlentities($_POST['txt-forecast-custNum'], ENT_QUOTES) == '' ? 'N/A' : htmlentities($_POST['txt-forecast-custNum'], ENT_QUOTES));
        $CustomerName = (strtolower(htmlentities($_POST['select-forecast-custName'], ENT_QUOTES)) == 'other' ? htmlentities($_POST['txt-forecast-custName'], ENT_QUOTES) : htmlentities($_POST['select-forecast-custName'], ENT_QUOTES));
        $Section = htmlentities($_POST['select-forecast-dept'], ENT_QUOTES);
        $Qty = htmlentities($_POST['txt-forecast-qty'], ENT_QUOTES);
        $RouterModel = htmlentities($_POST['select-forecast-deviceModel'], ENT_QUOTES);
        $Prob_Of_Closing = htmlentities($_POST['select-forecast-prob'], ENT_QUOTES);
        $Requested_By = htmlentities($_SESSION['user']['email'], ENT_QUOTES);
        $Service = htmlentities($_POST['select-forecast-serviceName'], ENT_QUOTES);


        $CEAR_id = $filesop[0];
        $Item_Name = $filesop[1];
        $Description = $filesop[2];
        $PO_QTY = $filesop[3];
        $cust_name = $filesop[4];
        $Req_by = $filesop[5];
        $Req_date = $filesop[6];
        $vendor = $filesop[7];
        $po_id = $filesop[8];
        $po_date = $filesop[9];
        $EDD = $filesop[10];


        /*
         * Retrieves the matching info from the DB if it exists
         */
        $sql = "INSERT INTO stock_forecast( `party_id`, `account_number`, `customer_name`, `service`, `section`, `router_model`, `quantity`, `prob_of_closeing`, `req_by`) VALUES(:prtid,:acc_num,:acc_name,:serv,:section,:routmodel,:qty,:prob_close,:reqby)";

        try {
            $res = $this->query($sql, array("prtid" => $PartyID, "acc_num" => $AccountNumber, "acc_name" => $CustomerName, "serv" => $Service, "section" => $Section, "routmodel" => $RouterModel, "qty" => $Qty, "prob_close" => $Prob_Of_Closing, "reqby" => $Requested_By));
        } catch (Exception $e) {
            // $this->db=null;
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

    /*     * **********************biuld available  table ******************************* */

    public function biuldavailableStokcTable($cust = 'etisalat') {// Connection data (server_address, database, name, poassword)
        $condit = '';
        if ($cust != 'etisalat') {
            $cust = 'etisalat';
            $condit = 'lower(`Customer Name`)!=lower(:cust)';
        } else {
            $cust = 'etisalat';
            $condit = 'lower(`Customer Name`)=lower(:cust)';
        }
        $sql = "SELECT * FROM `avilable_stock` where " . $condit . " ORDER BY `avilable_stock`.`totalQTY` ASC";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql, array('cust' => $cust));
        } catch (PDOException $e) {

            return($e->getMessage());
        }


        /*
         * `fcst_id`, `party_id`, `account_number`, `customer_name`, `service`, `section`, `router_model`, `quantity`, `prob_of_closeing`, `req_by`, `req_time`
         */
//`Model`, `LPO`, `Vendor`, `Customer Name`, `totalQTY`, `TotlaInstalled`

        $html = '<table class="table table-striped table-bordered" id="sample_1">
										<thead>
											<tr>
												<th></th>
												<th>Model</th>
												<th>LPO</th>
                                                                                                <th>Vendor</th>
												<th>Customer Name</th>
                                                                                                <th>total QTY</th>
												<th>Totla Installed</th>
                                                                                                <th>Totla Available</th>
												
												
												
												
											</tr>
										</thead>
										<tbody>';
        foreach ($result as $row) {
//`Model`, `LPO`, `Vendor`, `Customer Name`, `totalQTY`, `TotlaInstalled`
            $html.='<tr class="odd gradeX getsubrouter" data-txt="' . $row['Model'] . '|||' . $row['LPO'] . '"   style="cursor:pointer;"><td></td>
                
												
												<td>' . $row['Model'] . '</td>
                                                                                                    <td>' . $row['LPO'] . '</td>
                                                                                                        <td>' . $row['Vendor'] . '</td>
                                                                                                        <td>' . $row['Customer Name'] . '</td>
                                                                                                            <td>' . $row['totalQTY'] . '</td>
                                                                                                                <td><span class="label label-important">' . $row['TotlaInstalled'] . '</span></td>
												<td><span class="label label-success">' . ($row['totalQTY'] - $row['TotlaInstalled']) . '</span></td>
                                                                                                   
												';

            $html.='</tr>';


            // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }




        $html.='</tbody></table>';

        return $html;
    }

    /*     * *********end available stokc Table ***************************** */
    /*     * **********************biuld routers  table ******************************* */

    public function biuldstockRouterTable($model = null, $lpo = null) {// Connection data (server_address, database, name, poassword)
        $condit = '';
        if ($model != null) {

            $condit = ' where Model=lower(:model) and LPO=:lpo ';
        } else {
            $condit = ' ';
        }
        $sql = "SELECT * FROM `routers_detailes` " . $condit . " ORDER BY `routers_detailes`.`installation_stat` DESC";
        $html = "";
        // var_dump($sql);
        $result = array();
        try {
            if ($model != null) {
                $result = $this->query($sql, array('model' => $model, 'lpo' => $lpo));
            } else {
                $result = $this->query($sql);
            }
        } catch (PDOException $e) {

            return($e->getMessage());
        }


        /*
         * `fcst_id`, `party_id`, `account_number`, `customer_name`, `service`, `section`, `router_model`, `quantity`, `prob_of_closeing`, `req_by`, `req_time`
         */
//`stock_ID`, `DeviceType`, `Model`, `device_Serial`, `Vendor`, `LPO`, `customerName`, `AddBy`, `AddDate`, `installation_stat`

        $html = '<table class="table table-striped table-bordered" id="sample_1">
										<thead>
											<tr>
												<th></th>
												<th>Device Type</th>
												<th>Model</th>
                                                                                                <th>device Serial</th>
												<th>Customer Name</th>
                                                                                                <th>Vendor</th>
												<th>LPO</th>
                                                                                                <th>Customer Name</th>
												
												<th>installation status</th>
												<th>Date time</th>
                                                                                                <th>Add By</th>';

        if (($_SESSION['teamName'] == 'MSS-Fulfullment' and $_SESSION['currentRole'] == 'Admin')or ( $_SESSION['teamName'] == 'supperAdmins')) {
            $html.='<th>Action</th>';
        }

        $html.='</tr>
										</thead>
										<tbody>';
        foreach ($result as $row) {
//`Model`, `LPO`, `Vendor`, `Customer Name`, `totalQTY`, `TotlaInstalled`
            // `DeviceType`, `Model`, `device_Serial`, `Vendor`, `LPO`, `customerName`, `AddBy`, `AddDate`, `installation_stat`
            $html.='<tr class="odd gradeX "  ><td></td>
                
												
												<td>' . $row['DeviceType'] . '</td>
                                                                                                    <td>' . $row['Model'] . '</td>
                                                                                                        <td>' . $row['device_Serial'] . '</td>
                                                                                                            <td>' . $row['customerName'] . '</td>
                                                                                                        <td>' . $row['Vendor'] . '</td>
                                                                                                            <td>' . $row['LPO'] . '</td>
                                                                                                                <td>' . $row['customerName'] . '</td>
												<td>' . ($row['installation_stat'] > 0 ? '<span class="label label-important">Installed</span> ' : '<span class="label label-success">Available</span>' ) . '</td>
                                                                                                    <td>' . $row['AddDate'] . '</td>
                                                                                                        <td>' . $row['AddBy'] . '</td>
                                                                                                   
												';
            if (($_SESSION['teamName'] == 'MSS-Fulfullment' and $_SESSION['currentRole'] == 'Admin')or ( $_SESSION['teamName'] == 'supperAdmins')) {
                $html.='<td class="center">
													<!--<a href="#" class="icon huge"><i class="icon-zoom-in" id="viewStock_' . $row['stock_ID'] . '" data-d="input-id=' . $row['stock_ID'] . '&token=' . $_SESSION['token'] . '&action=Stock_Stock_view"></i></a>&nbsp;-->	
													<!--<a href="#" class="icon huge"><i class="icon-pencil" id="addStock_' . $row['stock_ID'] . '" data-d="input-id=' . $row['stock_ID'] . '&token=' . $_SESSION['token'] . '&action=Stock_Stock_edit"></i></a>&nbsp;-->
													<a href="#" class="icon huge"><i class="icon-remove" id="removeStock__' . $row['stock_ID'] . '" data-d="input-id=' . $row['stock_ID'] . '&token=' . $_SESSION['token'] . '&action=Stock_Stock_delete"></i></a>&nbsp;		
												</td>';
            }

            $html.='</tr>';


            // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }




        $html.='</tbody></table>';

        return $html;
    }

    /*     * *********end routers detials Table ***************************** */

    /** get all user for table view
     *
     * @return mixed TRUE on success, message on error
     */
    public function biuldorderTable() {// Connection data (server_address, database, name, poassword)
        $sql = "SELECT * FROM `stock_order` ORDER BY `stock_order`.`po_id` DESC";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }


        /*
         * orderID	customer_name	po_id	vendor	RouterClass	router_model	description	po_qty	cear_id	req_by	Req_date	po_date	EDD	user_add

         */

//table table-condensed table-striped
        $html = '<table class="table table-condensed table-striped table-bordered" id="sample_1">
										<thead>
											<tr>
												<th></th>
												<th>Customer Name</th>
												<th>PO#</th>
                                                                                                <th>Vendor</th>
												<th>Router Class</th>
                                                                                                <th>Router Model</th>
												<th>Description</th>
                                                                                             <th>QTY</th>
                                                                                                <th>Cear#</th>
												<th>Requested By</th>
                                                                                                <th>Request Time</th>
                                                                                                
<th>PO Time</th>
<th>EDD</th>
';


        if (($_SESSION['teamName'] == 'Marketing' and $_SESSION['currentRole'] == 'Admin')or ( $_SESSION['teamName'] == 'supperAdmins')) {
            $html.='<th>Created By</th><th>Actions</th>';
        }
        $html.= '</tr>
										</thead>
										<tbody>';
        foreach ($result as $row) {

            $html.='<tr class="odd gradeX">
												
												<td id="orderID_' . $row['orderID'] . '">' . $row['orderID'] . '</td>
                                                                                                    <td id="customer_name_' . $row['orderID'] . '">' . $row['customer_name'] . '</td>
                                                                                                        <td id="po_id_' . $row['orderID'] . '">' . $row['po_id'] . '</td>
                                                                                                            <td id="vendor_' . $row['orderID'] . '">' . $row['vendor'] . '</td>
                                                                                                                <td id="RouterClass_' . $row['orderID'] . '">' . $row['RouterClass'] . '</td>
												<td id="router_model_' . $row['orderID'] . '">' . $row['router_model'] . '</td>
                                                                                                    <td id="description_' . $row['orderID'] . '">' . $row['description'] . '</td>
                                                                                                        <td id="po_qty_' . $row['orderID'] . '">' . $row['po_qty'] . '</td>
                                                                                                            <td id="cear_id_' . $row['orderID'] . '">' . $row['cear_id'] . '</td>
                                                                                                                <td id="req_by_' . $row['orderID'] . '">' . $row['req_by'] . '</td>
												 <td id="Req_date_' . $row['orderID'] . '">' . $row['Req_date'] . '</td>
                                                                                                      <td id="po_date_' . $row['orderID'] . '">' . $row['po_date'] . '</td>
                                                                                                           <td id="EDD_' . $row['orderID'] . '">' . $row['EDD'] . '</td>
												';

            if (($_SESSION['teamName'] == 'Marketing' and $_SESSION['currentRole'] == 'Admin')or ( $_SESSION['teamName'] == 'supperAdmins')) {

                $html.=' <td>' . $row['user_add'] . '</td>';

                $html.='<td class="center">
													
                                                                                                             <a class="icon huge view-order" href="javascript:;" data-id="' . $row['orderID'] . '"><i class="icon-zoom-in"></i></a>&nbsp;
													<a href="#" class="icon huge"><i class="icon-remove" id="removeForecast_' . $row['orderID'] . '" data-d="input-id=' . $row['orderID'] . '&token=' . $_SESSION['token'] . '&action=Stock_order_delete"></i></a>&nbsp;		
												</td>';
            }
            $html.='</tr>';


            // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }





        $html.='</tbody></table>';
        return $html;
    }

    /*
     * Edit Stock Order
     */

    public function stockOrderEdit_form() {

        //return $_POST['action'];
        if ($_POST['action'] != 'stock_EditOrder') {
            return FALSE; //se;//"Invalid action supplied for forget password Form.";
        }
        $uname = htmlentities($_SESSION['user']['email'], ENT_QUOTES);
        // $close_Time = htmlentities($_POST['select-cnoc-activityStatus'], ENT_QUOTES) == 'Completed' ? date('Y-m-d G:i:s') : NULL;




        $orderID = htmlentities($_SESSION['Orderid_sess'], ENT_QUOTES); //was set when click view Order Data To edit
        $customer_name = htmlentities($_POST['select-order-CustomerName'], ENT_QUOTES);
        $po_id = htmlentities($_POST['txt-order-PO'], ENT_QUOTES);
        $vendor = htmlentities($_POST['select-order-Vendor'], ENT_QUOTES);
        $RouterClass = htmlentities($_POST['select-order-RouterClassification'], ENT_QUOTES);
        $router_model = htmlentities($_POST['select-order-RouterModel'], ENT_QUOTES);
        $description = htmlentities($_POST['txt-order-Description'], ENT_QUOTES);
        $po_qty = htmlentities($_POST['txt-order-POQuantity'], ENT_QUOTES);
        $cear_id = htmlentities($_POST['txt-order-CEARID'], ENT_QUOTES);
        $req_by = htmlentities($_POST['select-order-Requestedby'], ENT_QUOTES);
        $Req_date = htmlentities($_POST['txt-order-RequestedDate'], ENT_QUOTES);
        $po_date = htmlentities($_POST['txt-order-PODate'], ENT_QUOTES);
        $EDD = htmlentities($_POST['txt-order-ExpectedDeliveryDate'], ENT_QUOTES);
        // $user_add = htmlentities($_POST['select-cnoc-activityReason'], ENT_QUOTES);
        // $Create_time = htmlentities($_POST['select-cnoc-activityReason'], ENT_QUOTES);


        $sql = "UPDATE cnoc_activities set `activity_reason`=:reason,`activity_date`=:Act_date,`realted_team`=:rel_Team,`close_action`=:closeact,`activity_status`=:status,Closed_by=:closer,complete_time=:compeletTime,activity_Startdate=:startTime WHERE activity_id=:id";
        $sql = "UPDATE 
	`stock_order` 
SET 
	`customer_name` =:customer_name , 
	`po_id` = :po_id, 
	`vendor` =:vendor , 
	`RouterClass` = :RouterClass, 
	`router_model` =:router_model , 
	`description` =:description , 
	`po_qty` =:po_qty , 
	`cear_id` =:cear_id , 
	`req_by` =:req_by , 
	`Req_date` = :Req_date, 
	`po_date` = :po_date, 
	`EDD` =:EDD 
	
WHERE 
	`orderID`=:orderID";
        try {

            $countRows = $this->query($sql, array("customer_name" => $customer_name, "po_id" => $po_id, "vendor" => $vendor, "RouterClass" => $RouterClass, "router_model" => $router_model, "description" => $description, "po_qty" => $po_qty, "cear_id" => $cear_id, "req_by" => $req_by, "Req_date" => $Req_date, "po_date" => $po_date, "EDD" => $EDD, "orderID" => $orderID));
            //var_dump($countRows);
            if ($countRows > 0) {

                // $this->alertObj->setAlert('UPDATE', 'cnoc_activities', $id, 'Activity ID  :' . $id . ' Edit values :' + implode(', ', array("id" => $id, "reason" => $activity_reason, "Act_date" => $activity_date, "rel_Team" => $realted_team, "closeact" => $close_action, "status" => $activity_status)));
                $this->log->logActions($_SESSION['user']['email'], 'Edit Order', 'Success Edit', 'Order ID  :' . $orderID . ' Edit values :' + implode(', ', array("customer_name" => $customer_name, "po_id" => $po_id, "vendor" => $vendor, "RouterClass" => $RouterClass, "router_model" => $router_model, "description" => $description, "po_qty" => $po_qty, "cear_id" => $cear_id, "req_by" => $req_by, "Req_date" => $Req_date, "po_date" => $po_date, "EDD" => $EDD, "orderID" => $orderID)));

                return true; //$_SESSION['user']['email'].'<a href="javascript:;" class="btn unpick-activity"  data-id="'.$id.'">unPick It</a>';;
            } else {
                return FALSE;
            }
            //success
        } catch (Exception $e) {

            $this->log->logActions($_SESSION['user']['email'], 'Edit Order', 'Faild Edit', 'Order ID  :' . $orderID . ' Edit values :' + implode(', ', array("customer_name" => $customer_name, "po_id" => $po_id, "vendor" => $vendor, "RouterClass" => $RouterClass, "router_model" => $router_model, "description" => $description, "po_qty" => $po_qty, "cear_id" => $cear_id, "req_by" => $req_by, "Req_date" => $Req_date, "po_date" => $po_date, "EDD" => $EDD, "orderID" => $orderID)));
            return (FALSE);
        }
    }

    /*     * ****************
     * cnocGetActivityDetails
     * to get Order details using its id
     * retrun json data
     */

    public function stockGetOrderDetails() {
        $serial = $_POST['Orderid'];
        if ($_POST['action'] != 'Stock_getOrderDetails') {
            return false; //"Invalid action supplied for retrive Activity Data.";
        }
        $sql = 'SELECT 	`orderID`,`customer_name`,`po_id`,`vendor`,`RouterClass`,`router_model`, `description`,	`po_qty`,`cear_id`,`req_by`, 
	`Req_date`,	`po_date`,	`EDD`,	`user_add`,	`Create_time` FROM 
	`stock_order` 
WHERE 	`orderID` =:ser';
        $msg = '';
        try {

            $result = $this->row($sql, array('ser' => $serial));
            if ($result > 0) {
                $_SESSION['Orderid_sess'] = $serial;

                // $msg= 'true ';// Items inserted Before'.implode(' || ',$_srInserted);//array("phone"=>'123-12313',"email"=>'test@test.com','city'=>'Medicine Hat','address'=>'556 19th Street NE')
                echo json_encode(array("orid" => $result['orderID'], "custname" => $result['customer_name'], "po" => $result['po_id'], "vendor" => $result['vendor'], "router_class" => $result['RouterClass'], "router_model" => $result['router_model'], "description" => $result['description'], "qty" => $result['po_qty'], "cear" => $result['cear_id'], "req_by" => $result['req_by'], "req_date" => $result['Req_date'], "po_date" => $result['po_date'], "edd" => $result['EDD'], "user_add" => $result['user_add'], "create_time" => $result['Create_time']));
            } else {
                return FALSE;
            }
        } catch (PDOException $e) {

            return( 'error pdo ' . $e->getMessage());
        }
        //echo json_encode(array("phone"=>'123-12313',"email"=>'test@test.com','city'=>'Medicine Hat','address'=>'556 19th Street NE'));
        //return;
    }

    /*     * *******************************************************
     * 
     * Delete Forecast provided by his ID 
     * ************************************************
     */

    public function order_delete() {
        /*
         * Fails if the proper action was not submitted
         */
        // echo "<script>alert('".$_POST['input-username']."')</script>";
        if ($_POST['action'] != 'Stock_order_delete') {
            return "Invalid action supplied for process Delete Order.";
        }
        /*
         * Escapes the user input for security
         */
        $unid = htmlentities($_POST['input-id'], ENT_QUOTES);


        /*
         * Retrieves the matching info from the DB if it exists
         */
        $sql = "DELETE FROM stock_order WHERE orderID=:id";
        try {
            $res = $this->query($sql, array("id" => $unid));
        } catch (Exception $e) {
            // $this->db=null;
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

    /*     * ************************************end of order ********************************************* */



    /*     * ***********************stock and installation *********************** */

    //////////////insert the uploaded sheet /////////////////////////////
    public function dewa_data_upload() {// Connection data (server_address, database, name, poassword)
        if ($_POST['action'] != 'DEWA_bulkupload') {
            return "Invalid action supplied for DEWA bulkupload.";
        }
        $fName = trim('../comm/fileuplaod_temp/' . $_POST['fileName']);
        if ($fName == NULL) {
            return "Invalid File Name supplied .";
        }
        $user = $_SESSION['user']['email'];
        try {
            //$handle = fopen($fName, "r");


            $csvArray = $this->ImportCSV2Array($fName);
            $c = -1;
            $_sql = array();
            $_srInserted = array();

            foreach ($csvArray as $row) {

                if (trim($row['Spoke_Lan_Sb_Hostname']) == '' || trim($row['Spoke_Lan_Sb_Hostname']) == NULL) {

                    continue;
                }
                if ($this->dewa_HostExist(htmlentities(trim($row['Spoke_Lan_Sb_Hostname'])))) {

                    $_srInserted[] = htmlentities(trim($row['Spoke_Lan_Sb_Hostname']));
                    continue;
                }
                /* echo $row['MSS PORT AC#'];
                  echo '<br/>';
                  echo $row['Dewa ref/location ref'];
                  echo '<br/>------------------<br/>'; */

                if ((htmlentities(trim($row['Spoke_Lan_Sb_Hostname'])) != '' and ! (is_null(htmlentities(trim($row['Spoke_Lan_Sb_Hostname'])))) and isset($row['Spoke_Lan_Sb_Hostname']))) {

                    $_sql[] = '("' . htmlentities(trim($row['Site_Status'])) . '",	"' . htmlentities(trim($row['Dewa_ref/location_ref'])) . '",	"' . htmlentities(trim($row['Location_name'])) . '",	"' . htmlentities(trim($row['Site_Type'])) . '",	"' . htmlentities(trim($row['DEWA_address_(LL+Building_name+Street)'])) . '",	"' . htmlentities(trim($row['GNID_as_per_OLT'])) . '",	"' . htmlentities(trim($row['Access_SR'])) . '",	"' . htmlentities(trim($row['Access_Account'])) . '",	"' . htmlentities(trim($row['Access_B.W'])) . '",	"' . htmlentities(trim($row['Data_Port_B.W'])) . '",	"' . htmlentities(trim($row['Data_PORT_SR'])) . '",	"' . htmlentities(trim($row['Data_PORT_AC#'])) . '",	"' . htmlentities(trim($row['MSS_PORT_SR'])) . '",	"' . htmlentities(trim($row['MSS_PORT_AC#'])) . '",	"' . htmlentities(trim($row['Customer_Site_Contact'])) . '",	"' . htmlentities(trim($row['Geographical_coordinates'])) . '",	"' . htmlentities(trim($row['Geographical_coordinates'])) . '",	"' . htmlentities(trim($row['MRWAN_SR'])) . '",	"' . htmlentities(trim($row['MRWAN_SR_AC#'])) . '",	"' . htmlentities(trim($row['Router_Model'])) . '",	"' . htmlentities(trim($row['Router_SN'])) . '",	"' . htmlentities(trim($row['IOS_Version'])) . '",	"' . htmlentities(trim($row['OBN_(SIM_AC_No)'])) . '",	"' . htmlentities(trim($row['OBN_(Ser_No)'])) . '",	"' . htmlentities(trim($row['OBN_IP'])) . '",	"' . htmlentities(trim($row['Site_readiness(Rack)'])) . '",	"' . htmlentities(trim($row['Power_Supply_Status(Router)'])) . '",	"' . htmlentities(trim($row['Power_Supply_Status(ONT)'])) . '",	"' . htmlentities(trim($row['Router_Installation_Date'])) . '",	"' . htmlentities(trim($row['Spoke_Lan_Sb_Hostname'])) . '",	"' . htmlentities(trim($row['Spoke_Lan_Sb_Data_Wan_IP'])) . '",	"' . htmlentities(trim($row['Spoke_Lan_Sb_Data_Vlan'])) . '",	"' . htmlentities(trim($row['Spoke_Lan_Sb_Management_vlan'])) . '",	"' . htmlentities(trim($row['Spoke_Lan_Sb_Management_IP_CE/31'])) . '",	"' . htmlentities(trim($row['Spoke_Lan_Sb_Management_IP_PE/31'])) . '",	"' . htmlentities(trim($row['Spoke_Lan_Sb_Tunnel_IP'])) . '",	"' . htmlentities(trim($row['spoke_vlan_1'])) . '",	"' . htmlentities(trim($row['spoke_vlan_2'])) . '",	"' . htmlentities(trim($row['spoke_vlan_3'])) . '",	"' . htmlentities(trim($row['spoke_vlan_4'])) . '",	"' . htmlentities(trim($row['Spoke_Lan_Sb_Aggregate_subnet'])) . '",	"' . htmlentities(trim($row['Spoke_Lan_Sb_Tunnel_Primary_NBMA'])) . '",	"' . htmlentities(trim($row['Spoke_Lan_Sb_Tunnel_Secondary_NBMA'])) . '",	"' . htmlentities(trim($row['Hub_Pri_Hostname'])) . '",	"' . htmlentities(trim($row['Hub_Pri_Data_Vlan'])) . '",	"' . htmlentities(trim($row['Hub_Pri_Data_Wan_ip'])) . '",	"' . htmlentities(trim($row['Hub_Pri_GPON_Tunnel_IP'])) . '",	"' . htmlentities(trim($row['Hub_Pri_X-connect_wan_ip'])) . '",	"' . htmlentities(trim($row['Hub_Pri_GPRS_primary_tunnel'])) . '",	"' . htmlentities(trim($row['Hub_Pri_GPRS_Secondary_tunnel'])) . '",	"' . htmlentities(trim($row['Hub_Pri_ugw_primary'])) . '",	"' . htmlentities(trim($row['Hub_Pri_ugw_secondary'])) . '",	"' . htmlentities(trim($row['Hub_Pri_GPRS/GPON_end2end_tunnel_source_ip'])) . '",	"' . htmlentities(trim($row['Hub_Sec_Hostname'])) . '",	"' . htmlentities(trim($row['Hub_Sec_Data_Vlan'])) . '",	"' . htmlentities(trim($row['Hub_Sec_Data_Wan_ip'])) . '",	"' . htmlentities(trim($row['Hub_Sec_GPON_Tunnel_IP'])) . '",	"' . htmlentities(trim($row['Hub_Sec_X-connect_wan_ip'])) . '",	"' . htmlentities(trim($row['Hub_Sec_GPRS_primary_tunnel'])) . '",	"' . htmlentities(trim($row['Hub_Sec_GPRS_Secondary_tunnel'])) . '",	"' . htmlentities(trim($row['Hub_Sec_ugw_primary'])) . '",	"' . htmlentities(trim($row['Hub_Sec_ugw_secondary'])) . '",	"' . htmlentities(trim($row['Hub_Sec_GPRS/GPON_end2end_tunnel_source_ip'])) . '",	"' . htmlentities(trim($row['Hub_Sec_Server_subnet'])) . '",	"' . htmlentities(trim($row['Hub_Sec_Hub_Pair'])) . '",	"' . htmlentities(trim($row['MSS_Chk_lst_Router_Configuration'])) . '",	"' . htmlentities(trim($row['MSS_Chk_lst_CI_Creation'])) . '",	"' . htmlentities(trim($row['MSS_Chk_lst_Backup_Ticket'])) . '",	"' . htmlentities(trim($row['MSS_Chk_lst_RFS_Ticket'])) . '",	"' . htmlentities(trim($row['NNM_Discovery'])) . '",	"' . htmlentities(trim($row['MSS_Chk_lst_Router_RFS_Date'])) . '",	"' . htmlentities(trim($row['MSS_Chk_lst_Acceptance_letter'])) . '",	"' . htmlentities(trim($row['MSS_Chk_lst_Fulfillment_Engineer_Name'])) . '",	"' . htmlentities(trim($row['MSS_Chk_lst_MSS_Fulfillment_Remarks'])) . '",	"' . htmlentities(trim($row['MSS_Chk_lst_MSS-FM_Remarks'])) . '")';
                }
            }




            $sql = 'INSERT INTO `dewa_data`( `Site_Status`, `Dewa_ref_location_ref`, `Location_name`, `Site_Type`, `DEWA_address_LL+Building_name+Street`, `GNID_as_per_OLT`, `Access_SR`, `Access_Account`, `Access_B.W`, `Data_Port_B.W`, `Data_PORT_SR`, `Data_PORT_AC`, `MSS_PORT_SR`, `MSS_PORT_AC`, `Customer_Site_Contact`, `Geographical_coordinates`, `Geographical_coordinates_2`, `MRWAN_SR`, `MRWAN_SR_AC`, `Router_Model`, `Router_SN`, `IOS_Version`, `OBN_SIM_AC_No_`, `OBN_Ser_No`, `OBN_IP`, `Site_readiness__Rack`, `Power_Supply_Status__Router`, `Power_Supply_Status_ONT`, `Router_Installation_Date`, `Spoke_Lan_Sb_Hostname`, `Spoke_Lan_Sb_Data_Wan_IP`, `Spoke_Lan_Sb_Data_Vlan`, `Spoke_Lan_Sb_Management_vlan`, `Spoke_Lan_Sb_Management_IP_CE_31`, `Spoke_Lan_Sb_Management_IP_PE_31`, `Spoke_Lan_Sb_Tunnel_IP`, `spoke_vlan_1`, `spoke_vlan_2`, `spoke_vlan_3`, `spoke_vlan_4`, `Spoke_Lan_Sb_Aggregate_subnet`, `Spoke_Lan_Sb_Tunnel_Primary_NBMA`, `Spoke_Lan_Sb_Tunnel_Secondary_NBMA`, `Hub_Pri_Hostname`, `Hub_Pri_Data_Vlan`, `Hub_Pri_Data_Wan_ip`, `Hub_Pri_GPON_Tunnel_IP`, `Hub_Pri_X-connect_wan_ip`, `Hub_Pri_GPRS_primary_tunnel`, `Hub_Pri_GPRS_Secondary_tunnel`, `Hub_Pri_ugw_primary`, `Hub_Pri_ugw_secondary`, `Hub_Pri_GPRS/GPON_end2end_tunnel_source_ip`, `Hub_Sec_Hostname`, `Hub_Sec_Data_Vlan`, `Hub_Sec_Data_Wan_ip`, `Hub_Sec_GPON_Tunnel_IP`, `Hub_Sec_X-connect_wan_ip`, `Hub_Sec_GPRS_primary_tunnel`, `Hub_Sec_GPRS_Secondary_tunnel`, `Hub_Sec_ugw_primary`, `Hub_Sec_ugw_secondary`, `Hub_Sec_GPRS/GPON_end2end_tunnel_source_ip`, `Hub_Sec_Server_subnet`, `Hub_Sec_Hub_Pair`, `MSS_Chk_lst_Router_Configuration`, `MSS_Chk_lst_CI_Creation`, `MSS_Chk_lst_Backup_Ticket`, `MSS_Chk_lst_RFS_Ticket`, `NNM_Discovery`, `MSS_Chk_lst_Router_RFS_Date`, `MSS_Chk_lst_Acceptance_letter`, `MSS_Chk_lst_Fulfillment_Engineer_Name`, `MSS_Chk_lst_MSS_Fulfillment_Remarks`, `MSS_Chk_lst_MSS-FM_Remarks`) VALUES ' . implode(',', $_sql);
            $msg = '';
            try {
                unlink($fName);
                if (sizeof($_sql) > 0) {
                    $result = $this->query($sql);
                    if ($result > 0) {
                        $this->log->logActions($_SESSION['user']['email'], 'DEWA upload', 'Success upload', 'data :' . $sql . ' ');
                        $msg = 'true '; // Items inserted Before'.implode(' || ',$_srInserted);
                    } else {

                        $this->log->logActions($_SESSION['user']['email'], 'DEWA upload', 'Faild upload', 'data :' . $sql . ' ');
                        $msg = FALSE;
                    }
                }//empty sheet
            } catch (PDOException $e) {
                unlink($fName);
                $this->log->logActions($_SESSION['user']['email'], 'DEWA upload', 'error pdo ' . $e->getMessage(), 'data :' . $sql . ' ');
                $msg = 'error pdo ' . $e->getMessage();
            }
        } catch (Exception $ex) {
            unlink($fName);
            $msg = 'error opening ' . $ex->getMessage();
        }

        if (sizeof($_srInserted) > 0) {
            $msg.= 'Hosts inserted Before :<br/>' . implode(' || ', $_srInserted);
        }

        return $msg;
    }

//end of stock_insertupload



    public function stockInstallationGetData() {

        $serial = $_POST['serial'];

        if ($_POST['action'] != 'getDeviceData') {
            return "Invalid action supplied for retrive Device Data.";
        }
        $sql = 'SELECT `stock_ID`, `Model`, `Vendor`, `LPO` FROM `stock_stockdata` WHERE device_Serial=:ser';
        $msg = '';
        try {

            $result = $this->row($sql, array('ser' => $serial));
            if ($result > 0) {

                // $msg= 'true ';// Items inserted Before'.implode(' || ',$_srInserted);//array("phone"=>'123-12313',"email"=>'test@test.com','city'=>'Medicine Hat','address'=>'556 19th Street NE')
                echo json_encode(array("stockID" => $result['stock_ID'], "Model" => $result['Model'], "Vendor" => $result['Vendor'], "LPO" => $result['LPO']));
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
     * 
     * Add New Stock forecast
     * 
     */

    public function stockInstallationAddNew() {
        /*
         * Fails if the proper action was not submitted
         */
        // echo "<script>alert('".$_POST['input-username']."')</script>";
        if ($_POST['action'] != 'stock_addInstallation') {
            return "Invalid action supplied for process Add new Installation.";
        }
        /*
         * Escapes the user input for security
         */
        //$unName = htmlentities($_POST['input-uName'], ENT_QUOTES);
        $stock_ref = htmlentities($_POST['StockRef'], ENT_QUOTES);
        $MARWAN_acc = htmlentities($_POST['txt_Marwan_Acc'], ENT_QUOTES);
        $MARWAN_SR = htmlentities($_POST['txt_MARWAN_SR'], ENT_QUOTES);
        $cust_name = htmlentities($_POST['txt_cust_name'], ENT_QUOTES);
        $Party_id = htmlentities($_POST['txt_PartyID'], ENT_QUOTES);
        $link_acc = htmlentities($_POST['txt_Linked_Acc'], ENT_QUOTES);
        $Requested_By = htmlentities($_SESSION['user']['email'], ENT_QUOTES);




        /*
         * Retrieves the matching info from the DB if it exists
         */
        $sql = "INSERT INTO `stock_installation`( `stock_ref`, `add_by`, `MARWAN_SR`, `cust_name`, `Party_id`, `link_acc`, `MARWAN_acc`) VALUES (:stock_ref ,:add_by ,:MARWAN_SR ,:cust_name ,:Party_id ,:link_acc ,:MARWAN_acc) ON DUPLICATE KEY UPDATE  add_by=:add_by1 ,MARWAN_SR=:MARWAN_SR1 ,cust_name=:cust_name1 ,Party_id=:Party_id1 ,link_acc=:link_acc1 ,MARWAN_acc=:MARWAN_acc1";
        //$this->lastInsertId();
        if ($stock_ref != '' and ! (is_null($stock_ref)) and isset($stock_ref)) {
            $params = array("stock_ref" => $stock_ref, "add_by" => $Requested_By, "cust_name" => $cust_name, "MARWAN_SR" => $MARWAN_SR, "Party_id" => $Party_id, "link_acc" => $link_acc, "MARWAN_acc" => $MARWAN_acc, "add_by1" => $Requested_By, "cust_name1" => $cust_name, "MARWAN_SR1" => $MARWAN_SR, "Party_id1" => $Party_id, "link_acc1" => $link_acc, "MARWAN_acc1" => $MARWAN_acc);

            try {
                $res = $this->query($sql, $params); //, "add_by1" => $Requested_By, "MARWAN_SR1" => $MARWAN_SR, "Party_id1" => $Party_id, "link_acc1" => $link_acc, "MARWAN_acc1" => $MARWAN_acc
                // $rows=  $this->db rowCount();

                $this->log->logActions($_SESSION['user']['email'], 'stock installation', 'Success installation', ' stock_ref :' . $stock_ref . ' ');
            } catch (Exception $e) {
                // $this->db=null;

                $this->log->logActions($_SESSION['user']['email'], 'stock installation', 'Faild installation' . $e->getMessage(), ' stock_ref :' . $stock_ref . ' ');
                return ($e->getMessage());
            }
        } else {
            return "Stock device serial is not entered";
        }

        /*
         * Fails if username doesn't match a DB entry
         */
        if ($res <= -1) {
            return false; //"Your username or password is invalid.";
        } else {
            return TRUE; //success
        }
    }

    /*     * *********************END OF STOCK AND INSTALLATION */


    /*
     * 
     * add new order using form
     * @takes form data fields 
     */

    public function stockOrderAddNew_form() {
        /*
         * Fails if the proper action was not submitted
         */
        // echo "<script>alert('".$_POST['input-username']."')</script>";
        if ($_POST['action'] != 'stock_AddOrder') {
            return "Invalid action supplied for process Add new Order.";
        }
        /*
         * Escapes the user input for security
         */
        //$unName = htmlentities($_POST['input-uName'], ENT_QUOTES);
        $CEARID = htmlentities($_POST['txt-order-CEARID'], ENT_QUOTES);
        $PO = htmlentities($_POST['txt-order-PO'], ENT_QUOTES);
        //  $CustomerName = htmlentities($_POST['select-order-CustomerName'], ENT_QUOTES);
        $CustomerName = (strtolower(htmlentities($_POST['select-order-CustomerName'], ENT_QUOTES))) == 'other' ? htmlentities($_POST['txt-order-CustomerName'], ENT_QUOTES) : htmlentities($_POST['select-order-CustomerName'], ENT_QUOTES);
        $Requestedby = (strtolower(htmlentities($_POST['select-order-Requestedby'], ENT_QUOTES))) == 'other' ? htmlentities($_POST['txt-order-Requestedby'], ENT_QUOTES) : htmlentities($_POST['select-order-Requestedby'], ENT_QUOTES);
        $RouterClassification = htmlentities($_POST['select-order-RouterClassification'], ENT_QUOTES);
        $RouterModel = (strtolower(htmlentities($_POST['select-order-Requestedby'], ENT_QUOTES))) == 'other' ? htmlentities($_POST['txt-order-Requestedby'], ENT_QUOTES) : htmlentities($_POST['select-order-Requestedby'], ENT_QUOTES); //htmlentities($_POST['select-order-RouterModel'], ENT_QUOTES);
        $POQuantity = htmlentities($_POST['txt-order-POQuantity'], ENT_QUOTES);
        $Vendor = (strtolower(htmlentities($_POST['select-order-Vendor'], ENT_QUOTES))) == 'other' ? htmlentities($_POST['txt-order-Vendor'], ENT_QUOTES) : htmlentities($_POST['select-order-Vendor'], ENT_QUOTES); //htmlentities($_POST['select-order-Vendor'], ENT_QUOTES);
        $Description = htmlentities($_POST['txt-order-Description'], ENT_QUOTES);
        $RequestedDate = date("Y-m-d  H:i:s", strtotime(htmlentities($_POST['txt-order-RequestedDate'], ENT_QUOTES))); //  htmlentities($_POST['txt-order-RequestedDate'], ENT_QUOTES);
        $PODate = date("Y-m-d  H:i:s", strtotime(htmlentities($_POST['txt-order-PODate'], ENT_QUOTES))); // htmlentities($_POST['txt-order-PODate'], ENT_QUOTES);
        $ExpectedDeliveryDate = htmlentities($_POST['txt-order-ExpectedDeliveryDate'], ENT_QUOTES);


        $user_add = $_SESSION['user']['email'];





        $sql = "INSERT INTO `stock_order`( `customer_name`, `po_id`, `vendor`, `RouterClass`, `router_model`, `description`, `po_qty`, `cear_id`, `req_by`, `Req_date`, `po_date`, `EDD`, `user_add`) VALUES (:customer_name, :po_id, :vendor, :RouterClass, :router_model, :description, :po_qty, :cear_id, :req_by, :Req_date, :po_date, :EDD, :user_add)";
        //$this->lastInsertId();
        try {

            $Parms = array("customer_name" => $CustomerName, "po_id" => $PO, "vendor" => $Vendor, "RouterClass" => $RouterClassification, "router_model" => $RouterModel, "description" => $Description, "po_qty" => $POQuantity, "cear_id" => $CEARID, "req_by" => $Requestedby, "Req_date" => $RequestedDate, "po_date" => $PODate, "EDD" => $ExpectedDeliveryDate, "user_add" => $user_add);
            $res = $this->query($sql, $Parms);
            $this->alertObj->setAlert('INSERT', 'Stock_Order', null, 'DATA :' + implode(', ', $Parms));
            $this->log->logActions($_SESSION['user']['email'], ' New Stock_Order', 'Succes add  ', 'DATA :' + implode(', ', $Parms));
        } catch (Exception $e) {
            // $this->db=null;
            $this->log->logActions($_SESSION['user']['email'], 'Add New stock Order', 'Faild Add due to ' . $e->getMessage(), 'Data :' + implode(', ', $Parms));
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

//add new order


    /*     * *********get all report stock order json ****** */

    //($_REQUEST['from'],$_REQUEST['to'],$_REQUEST['FilterName'],$_REQUEST['Operator'],$_REQUEST['FilterValue']));
    public function stockGetOrderStockJson($filterName = null) {// Connection data (server_address, database, name, poassword)
        $option = '';
        if ($filterName == null) {
            //$option = " WHERE STR_TO_DATE(`activity_date`, '%d/%m/%Y')='" . date('Y-m-d') . "'";
            return false;
        } else {
            //  $option = " WHERE STR_TO_DATE(replace(activity_date,'/',','),'%d,%m,%Y %T')>= STR_TO_DATE(replace('" . $from . "','/',','),'%m,%d,%Y %T') and STR_TO_DATE(replace(activity_date,'/',','),'%d,%m,%Y %T')<= STR_TO_DATE(replace('" . $to . "','/',','),'%m,%d,%Y %T')";

            $orderFilters = '';
            $stockFilters1 = '';

            switch ($filterName) {
                case 'Customer':
                    //   $coulmnName = '';
                    $orderFilters = 'customer_name';
                    $stockFilters1 = 'customerName';

                    break;
                case 'Router Model':
                    // $coulmnName = 'Cust_code';
                    $orderFilters = 'router_model';
                    $stockFilters1 = 'Model';

                    break;
                case 'LPO':
                    $orderFilters = 'po_id';
                    $stockFilters1 = 'LPO';

                    break;
                case 'Vendor':
                    $orderFilters = 'vendor';
                    $stockFilters1 = 'Vendor';

                    break;
            }//end filter name
        }


        $sql = "SELECT sum(po_qty) as ordered,count(DISTINCT rd1.device_Serial) as installed,count(DISTINCT rd2.device_Serial) as recieved,stock_order." . $orderFilters . " as filtered FROM `stock_order`
left join
routers_detailes as rd1
on
rd1." . $stockFilters1 . "=stock_order." . $orderFilters . " 
and
rd1.installation_stat!=0
left join
routers_detailes as rd2
on
rd2." . $stockFilters1 . "=stock_order." . $orderFilters . " 


 GROUP BY stock_order." . $orderFilters . "";
        $html = "";
        //var_dump($sql);
        $result = array();
        try {
            $result = $this->query($sql, array(), PDO::FETCH_OBJ);
        } catch (PDOException $e) {

            return($e->getMessage());
        }
        return '{"orders":' . json_encode($result) . '}'; //json_encode($result);
    }

}

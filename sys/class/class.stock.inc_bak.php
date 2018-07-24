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
class stock extends DB_Connect {

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
     * build user selections options to be selected
     */

    private function stock_routerExist($routerSer = null) {
        $found = false;

        // Fetching single value
        $stockID = $this->single("SELECT stock_ID FROM stock_stockdata WHERE device_Serial = :id ", array('id' => $routerSer));
        if ($stockID > 0) {
            $found = TRUE;
        }

        return $found;
    }

    public function stock_routerINstalled() {

        if ($_POST['action'] != 'checkDeviceinstallation') {
            return "Invalid action supplied for Check installation";
        }
        $found = false;

        $routerSer = $_POST['serial'];
        // Fetching single value
        $stockID = $this->single("SELECT stock_ref FROM stock_installation WHERE stock_ref = :id ", array('id' => $routerSer));
        //return $this->sQuery->rowcount();
        if ($this->sQuery->rowcount() > 0) {
            $found = TRUE;
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
 $_sql[] = '("' . mysql_real_escape_string($Device_Name,  $this->pdo) . '","' . mysql_real_escape_string($Model,  $this->pdo) . '", "' . mysql_real_escape_string($router_sr,  $this->pdo) . '", "' . mysql_real_escape_string($vendor,  $this->pdo) . '", "' . mysql_real_escape_string($LPO,  $this->pdo) . '", "' . mysql_real_escape_string($cust_name,  $this->pdo) . '", "' . mysql_real_escape_string($user,  $this->pdo) . '")';
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





                $Req_date = date("Y-m-d H:i:s", strtotime($filesop[9])); //$filesop[6];


                $po_date = $filesop[10];
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


        $html = '<table class="table table-striped table-bordered" id="sample_1">
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
												
												<td>' . $row['orderID'] . '</td>
                                                                                                    <td>' . $row['customer_name'] . '</td>
                                                                                                        <td>' . $row['po_id'] . '</td>
                                                                                                            <td>' . $row['vendor'] . '</td>
                                                                                                                <td>' . $row['RouterClass'] . '</td>
												<td>' . $row['router_model'] . '</td>
                                                                                                    <td>' . $row['description'] . '</td>
                                                                                                        <td>' . $row['po_qty'] . '</td>
                                                                                                            <td>' . $row['cear_id'] . '</td>
                                                                                                                <td>' . $row['req_by'] . '</td>
												 <td>' . $row['Req_date'] . '</td>
                                                                                                      <td>' . $row['po_date'] . '</td>
                                                                                                           <td>' . $row['EDD'] . '</td>
												';

            if (($_SESSION['teamName'] == 'Marketing' and $_SESSION['currentRole'] == 'Admin')or ( $_SESSION['teamName'] == 'supperAdmins')) {

                $html.=' <td>' . $row['user_add'] . '</td>';
                $html.='<td class="center">
													<!--<a href="#" class="icon huge"><i class="icon-zoom-in" id="viewForecast_' . $row['orderID'] . '" data-d="input-id=' . $row['orderID'] . '&token=' . $_SESSION['token'] . '&action=Stock_Forecast_view"></i></a>&nbsp;-->	
													<!--<a href="#" class="icon huge"><i class="icon-pencil" id="addForecast_' . $row['orderID'] . '" data-d="input-id=' . $row['orderID'] . '&token=' . $_SESSION['token'] . '&action=Stock_Forecast_edit"></i></a>&nbsp;-->
													<a href="#" class="icon huge"><i class="icon-remove" id="removeForecast_' . $row['orderID'] . '" data-d="input-id=' . $row['orderID'] . '&token=' . $_SESSION['token'] . '&action=Stock_Forecast_delete"></i></a>&nbsp;		
												</td>';
            }
            $html.='</tr>';


            // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }






        return $html;
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
    public function stock_insertupload() {// Connection data (server_address, database, name, poassword)
        if ($_POST['action'] != 'stock_bulkupload') {
            return "Invalid action supplied for Stock bulkupload.";
        }
        $fName = trim('../comm/fileuplaod_temp/' . $_POST['fileName']);
        if ($fName == NULL) {
            return "Invalid File Name supplied .";
        }

        try {
            //$handle = fopen($fName, "r");

            $c = -1;
            $_sql = array();
            $_srInserted = array();
            $file = fopen($fName, "r") or die("error open file " . $fName);

            while (!feof($file)) {
                $filesop = array();
                $filesop = fgetcsv($file);
                $c++;
                if ($c == 0) {

                    continue;
                }

                $Model = str_replace(' ', '', strtolower(trim($filesop[0])));
                $Device_Name = $filesop[1];
                $router_sr = $filesop[2];
                $router_sr = $filesop[2];
                 if (trim($router_sr) == '' || trim($router_sr) ==NULL) {

                    continue;
                }
                
                $vendor = $filesop[3];
                $LPO = str_replace(' ', '', strtolower(trim($filesop[4])));
                $cust_name = $filesop[5];
                
                $Router_Class=htmlentities( trim($filesop[6]))==''?NUll:htmlentities( trim($filesop[6]));
                $Descrp=htmlentities( trim($filesop[7]))==''?NUll:htmlentities( trim($filesop[7]));
                //INSERT INTO `stock_order`(`cear_id`, `router_model`, `description`, `po_qty`, `customer_name`, `req_by`, `Req_date`, `vendor`, `po_id`, `po_date`, `EDD`)
                //CEAR # //cear_id	Item Name //router_model	Description//description	PO Quantity//po_qty	Customer Name//customer_name	Requested By//req_by	ORIGINATION DATE//Req_date	Vendor//vendor	PO #//po_id	PO DATE//po_date	EDD//EDD
                if ($this->stock_routerExist($router_sr)) {

                    $_srInserted[] = $router_sr;
                    continue;
                }



                $user = $_SESSION['user']['email'];
                // check for cust name and qty and router
                if (($router_sr != '' and ! (is_null($router_sr)) and isset($router_sr))) {
                    //echo '<br/> loop#'.$c.'values are : '.'("'.mysql_real_escape_string($Party_id).'","'.mysql_real_escape_string($acc_num).'", "'.mysql_real_escape_string($acc_Name).'", "'.mysql_real_escape_string($service).'", "'.mysql_real_escape_string($Section).'", "'.mysql_real_escape_string($Router_model).'", '.mysql_real_escape_string($Qty).', "'.mysql_real_escape_string($Prob).'", "'.mysql_real_escape_string($req_by).'")<br/>-----------------------------------';
                  //  $_sql[] = '("' . mysql_real_escape_string($Device_Name) . '","' . mysql_real_escape_string($Model) . '", "' . mysql_real_escape_string($router_sr) . '", "' . mysql_real_escape_string($vendor) . '", "' . mysql_real_escape_string($LPO) . '", "' . mysql_real_escape_string($cust_name) . '", "' . mysql_real_escape_string($user) . '")';
                    
               //     $_sql[] = '("' . $this->pdo->quote($Device_Name) . '","' . $this->pdo->quote($Model) . '", "' . $this->pdo->quote($router_sr) . '", "' . $this->pdo->quote($vendor) . '", "' . $this->pdo->quote($LPO) . '", "' . $this->pdo->quote($cust_name) . '", "' . $this->pdo->quote($user) . '")';
               //     
                 //   $_sql[] = '("' . mysqli_real_escape_string($this->pdo,$Device_Name) . '","' . mysqli_real_escape_string($this->pdo,$Model) . '", "' . mysqli_real_escape_string($this->pdo,$router_sr) . '", "' . mysqli_real_escape_string($this->pdo,$vendor) . '", "' . mysqli_real_escape_string($this->pdo,$LPO) . '", "' . mysqli_real_escape_string($this->pdo,$cust_name) . '", "' . mysqli_real_escape_string($this->pdo,$user) . '")';
                 //   
                      $_sql[] = '("' . htmlentities($Device_Name) . '","' . htmlentities($Model) . '", "' . htmlentities($router_sr) . '", "' . htmlentities($vendor) . '", "' . htmlentities($LPO) . '", "' . htmlentities($cust_name) . '", "' . htmlentities($user) . '", "' . htmlentities($Router_Class) . '", "' . htmlentities($Descrp) . '")';
                 // $this->pdo->quote($Device_Name);
                }
            }
            fclose($file);
          //  chmod('../comm/fileuplaod_temp/', 0777);



            $sql = 'INSERT INTO `stock_stockdata`( `DeviceType`, `Model`, `device_Serial`, `Vendor`, `LPO`, `customerName`, `AddBy`,`Router_Class`, `Descrp`) VALUES ' . implode(',', $_sql);
            $msg = '';
            try {
                unlink($fName);
                if (sizeof($_sql) > 0) {
                    $result = $this->query($sql);
                    if ($result > 0) {
                        $this->log->logActions($_SESSION['user']['email'], 'Stock upload', 'Success upload', 'data :' . $sql . ' ');
                        $msg = 'true '; // Items inserted Before'.implode(' || ',$_srInserted);
                    } else {
                        
                        $this->log->logActions($_SESSION['user']['email'], 'Stock upload', 'Faild upload', 'data :' . $sql . ' ');
                        $msg = FALSE;
                    }
                }//empty sheet
            } catch (PDOException $e) {
                unlink($fName);
                $this->log->logActions($_SESSION['user']['email'], 'Stock upload', 'error pdo ' . $e->getMessage(), 'data :' . $sql . ' ');
                $msg = 'error pdo ' . $e->getMessage();
            }
        } catch (Exception $ex) {
            unlink($fName);
            $msg = 'error opening ' . $ex->getMessage();
        }

        if (sizeof($_srInserted) > 0) {
            $msg.= 'Serials inserted Before :<br/>' . implode(' || ', $_srInserted);
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
        $CustomerName =(strtolower(htmlentities($_POST['select-order-CustomerName'], ENT_QUOTES))) == 'other' ? htmlentities($_POST['txt-order-CustomerName'], ENT_QUOTES):htmlentities($_POST['select-order-CustomerName'], ENT_QUOTES);
        $Requestedby=htmlentities($_POST['select-order-Requestedby'], ENT_QUOTES);
        $RouterClassification = (strtolower(htmlentities($_POST['select-order-Requestedby'], ENT_QUOTES))) == 'sa' ?  htmlentities($_POST['select-order-RouterClassification'], ENT_QUOTES):NULL;
        $RouterModel = htmlentities($_POST['select-order-RouterModel'], ENT_QUOTES);
        $POQuantity = htmlentities($_POST['txt-order-POQuantity'], ENT_QUOTES);
        $Vendor = htmlentities($_POST['select-order-Vendor'], ENT_QUOTES);
        $Description = htmlentities($_POST['txt-order-Description'], ENT_QUOTES);
        $RequestedDate = htmlentities($_POST['txt-order-RequestedDate'], ENT_QUOTES);
        $PODate = htmlentities($_POST['txt-order-PODate'], ENT_QUOTES);
        $ExpectedDeliveryDate = htmlentities($_POST['txt-order-ExpectedDeliveryDate'], ENT_QUOTES);
        
        
              $user_add = $_SESSION['user']['email'];





$sql = "INSERT INTO `stock_order`( `customer_name`, `po_id`, `vendor`, `RouterClass`, `router_model`, `description`, `po_qty`, `cear_id`, `req_by`, `Req_date`, `po_date`, `EDD`, `user_add`) VALUES (:customer_name, :po_id, :vendor, :RouterClass, :router_model, :description, :po_qty, :cear_id, :req_by, :Req_date, :po_date, :EDD, :user_add)";
        //$this->lastInsertId();
        try {
           
              $Parms=   array("customer_name" => $CustomerName, "po_id" => $PO, "vendor" => $Vendor, "RouterClass" => $RouterClassification, "router_model" => $RouterModel, "description" => $Description, "po_qty" => $POQuantity, "cear_id" => $CEARID, "req_by" => $Requestedby, "Req_date" => $RequestedDate, "po_date" => $PODate, "EDD" => $ExpectedDeliveryDate, "user_add" => $user_add);
            $res = $this->query($sql,$Parms);
            $this->alertObj->setAlert('INSERT', 'Stock_Order', null, 'DATA :'+implode(', ',$Parms));
            $this->log->logActions($_SESSION['user']['email'], ' New Stock_Order', 'Succes add  ', 'DATA :'+implode(', ',$Parms));
        } catch (Exception $e) {
            // $this->db=null;
            $this->log->logActions($_SESSION['user']['email'], 'Add New stock Order', 'Faild Add due to ' . $e->getMessage(), 'Data :'+implode(', ',$Parms));
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
    }//add new order

}

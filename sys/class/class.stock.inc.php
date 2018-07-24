<?php

if (!isset($_SESSION)) {
    session_start();
}
include_once 'DB_Connect.inc.php';

/**
 * Description of classstock_bulkupload
 *
 * @author muham_000stock_bulkupload
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

    private function stock_routerOrderModelExist($po = null, $router = null) {
        // $start = microtime(true);
        $found = 0;
        $overqty = 1;
        $result = array();

// Fetching single value
        //$stockID = $this->single("SELECT orderID FROM `stock_order` WHERE po_id = :po and router_model=:routr ", array('po' => $po, 'routr' => $router));
        //$stockID = $this->single("SELECT LPO,`Order QTY` as orderd,`total Stock QTY` as stocked FROM `avilable_stock` WHERE LPO = :po and Model=:routr ", array('po' => $po, 'routr' => $router));
        //$sql = "SELECT * FROM `stock_forecast` ORDER BY `stock_forecast`.`fcst_id` DESC";
        // $result = array();
        try {
            $result = $this->query("SELECT distinct LPO,`Order QTY` as orderd,`total Stock QTY` as stocked FROM `avilable_stock` WHERE LPO = :po and Model=:routr ", array('po' => $po, 'routr' => $router));
            // $result = $this->single("SELECT LPO,`Order QTY` as orderd,`total Stock QTY` as stocked FROM `avilable_stock` WHERE LPO = :po and Model=:routr ", array('po' => $po, 'routr' => $router));
        } catch (PDOException $e) {

            return($e->getMessage());
        }


        foreach ($result as $row) {
            $found = 1;
            if ($row['stocked'] < $row['orderd']) {
                $overqty = 0;
            }
        }
        //print_r($stockID);die;



        $result['found'] = $found;
        $result['overqty'] = $overqty;
        // $time_elapsed_secs = microtime(true) - $start;
        // print_r($time_elapsed_secs);die;
        // print_r($result);        die;
        return $result;
    }

    private function stock_orderExist($po = null, $router = null) {
        $found = false;

// Fetching single value
        $stockID = $this->single("SELECT orderID FROM `stock_order` WHERE po_id = :po and router_model=:routr ", array('po' => $po, 'routr' => $router));
        if ($stockID > 0) {
            $found = TRUE;
        }


        return $found;
    }

    private function stock_invoiceExist($Invoice_Number = null) {
        $found = false;

// Fetching single value
        $stockID = $this->single("SELECT Invoice_Number FROM stock_invoicedata WHERE Invoice_Number = :id ", array('id' => $Invoice_Number));
        if ($stockID > 0) {
            $found = TRUE;
        }

        return $found;
    }

    private function stock_docExist($tb_name, $cond, $arr_val) {
        // var_dump($arr_val);die;
        $found = false;

// Fetching single value
        $counts = $this->single("SELECT count(*) FROM " . $tb_name . " WHERE " . $cond, $arr_val);
        if ($counts > 0) {
            $found = TRUE;
        }

        return $found;
    }

    private function stock_authcodeExist($tb_name, $cond, $arr_val) {
        $found = false;

//        print_r($arr_val);
//        echo "SELECT count(*) FROM " . $tb_name . " WHERE " . $cond;
//        die;
// Fetching single value
        //select count(distinct auth_code) from stock_hwp_invoice where `auth_code`='12345678901234567890' or(`doc_number`='hwinv100002' and `auth_code` is not null)
        // $counts = $this->single("SELECT count(*) FROM " . $tb_name . " WHERE " . $cond, $arr_val);
        $counts = $this->single("SELECT count(*) FROM " . $tb_name . " WHERE " . $cond, $arr_val);
        if ($counts > 0) {
            $found = TRUE;
        }

        return $found;
    }

    public function stock_routerDnDoc() {

        if ($_POST['action'] != 'checkDevicednDoc') {
            return "Invalid action supplied for Check DN Docs";
        }
        $found = false;

        $routerSer = $_POST['serial'];
// Fetching single value
        // $stockID = $this->single("SELECT stock_ID FROM stockdo_stockdata WHERE doc_type='DN' and stock_ID = :id ", array('id' => $routerSer));
        $stockID = $this->single("SELECT* from stockdo_stockdata sdsd left JOIN stock_stockdata ssd ON (ssd.LPO=sdsd.LPO and sdsd.model LIKE CONCAT('%',ssd.Model, '%') and sdsd.devices='all') or (sdsd.stock_ID=ssd.stock_ID) where ssd.stock_ID=:id and doc_type='DN' ", array('id' => $routerSer));
//return $this->sQuery->rowcount();
        if ($this->sQuery->rowcount() > 0) {
            $found = TRUE;
        }

        return $found;
    }

    public function stock_routerMarwansr() {

        if ($_POST['action'] != 'checkInstallMarwansr') {
            return "Invalid action supplied for Check MarwanaSR";
        }
        $found = false;

        $routerSer = $_POST['serial'];
// Fetching single value
        $stockID = $this->single("select `MARWAN_SR` from stock_installation where `MARWAN_SR`= :id ", array('id' => $routerSer));
        echo $this->sQuery->rowcount();
        die;
        if ($this->sQuery->rowcount() > 0) {
            $found = TRUE;
        }

        return $found;
    }

    public function stock_routerMarwanacc() {

        if ($_POST['action'] != 'checkInstallMarwanacc') {
            return "Invalid action supplied for Check Marwanacc";
        }
        $found = false;

        $routerSer = $_POST['serial'];
// Fetching single value
        $stockID = $this->single("select `MARWAN_acc` from stock_installation where `MARWAN_acc`= :id ", array('id' => $routerSer));
//return $this->sQuery->rowcount();
        if ($this->sQuery->rowcount() > 0) {
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
// unlink($fName);
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
            $html .= '<th>Actions</th>';
        }
        $html .= '</tr>
										</thead>
										<tbody>';
        foreach ($result as $row) {

            $html .= '<tr class="odd gradeX"><td></td>
												
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
                $html .= '<td class="center">
													<!--<a href="#" class="icon huge"><i class="icon-zoom-in" id="viewForecast_' . $row['fcst_id'] . '" data-d="input-id=' . $row['fcst_id'] . '&token=' . $_SESSION['token'] . '&action=Stock_Forecast_view"></i></a>&nbsp;-->	
													<!--<a href="#" class="icon huge"><i class="icon-pencil" id="addForecast_' . $row['fcst_id'] . '" data-d="input-id=' . $row['fcst_id'] . '&token=' . $_SESSION['token'] . '&action=Stock_Forecast_edit"></i></a>&nbsp;-->
													<a href="#" class="icon huge"><i class="icon-remove" id="removeForecast_' . $row['fcst_id'] . '" data-d="input-id=' . $row['fcst_id'] . '&token=' . $_SESSION['token'] . '&action=Stock_Forecast_delete"></i></a>&nbsp;		
												</td>';
            }
            $html .= '</tr>';


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
     * Delete Forecast provided by his ID 
     * ************************************************
     */

    public function stockInvoiceAddAuth() {
        /*
         * Fails if the proper action was not submitted
         */
// echo "<script>alert('".$_POST['input-username']."')</script>";
        if ($_POST['action'] != 'stock_AddInvoiceAuth') {
            return "Invalid action supplied for process Add New  Invoice Auth Code.";
        }
        // var_dump($_POST);die;
        // return true;
        /*
         * Escapes the user input for security
         */
        $unid = htmlentities($_POST['txt-doc-AuthCode'], ENT_QUOTES);
        $invID = htmlentities($_POST['select-stock-invoice'], ENT_QUOTES);
        $authtype = htmlentities($_POST['authtype'], ENT_QUOTES);
        $tb_name = NULL;
        $cond = NULL;
        $arr_val = NULL;
        $found = true;
        $fieldname = null;
        
        $Docfound = $this->stock_authcodeExist("stock_docs", " doc_number=:doc_number and verify_status='Accepted' ", array( "doc_number" => $invID)); //check if exist before
        if (!$Docfound) {
            return ' The invoice Copy Doc Did not be uploaded or Not accepted Yet ';
        }
        

        //if ($this->stock_docExist("stock_qp_renew_invoice", " device_sr=:device_Serial and `year`=:year and q_num=:q_num", array("device_Serial" => $router_sr, "year" => $year, "q_num" => $Q)))
//ref1
        switch ($authtype) {
            case "hw":
                $tb_name = 'stock_hwp_invoice';
                $cond = ' auth_code=:auth_code';
                $cond_find = " auth_code=:auth_code or(`doc_number`=:doc_number and `auth_code` is not null and `auth_code`!='')";
                $arr_val = array("auth_code" => $unid, "doc_number" => $invID); //array("auth_code" => $unid);
                $arr_updateval = array("auth_code" => $unid, "doc_number" => $invID);
                $fieldname = 'doc_number';


                break;
            case "rfs":
                $tb_name = 'stock_rfsp_invoice';
                $cond = ' auth_code=:auth_code';
                // $arr_val = array("auth_code" => $unid);
                $cond_find = " auth_code=:auth_code or(`doc_number`=:doc_number and `auth_code` is not null and `auth_code`!='')";
                $arr_val = array("auth_code" => $unid, "doc_number" => $invID); //array("auth_code" => $unid);
                $arr_updateval = array("auth_code" => $unid, "doc_number" => $invID);
                $fieldname = 'doc_number';


                break;
            case "pac":
                $tb_name = 'stock_pacp_invoice';
                $cond = ' auth_code=:auth_code';
                //$arr_val = array("auth_code" => $unid);
                $cond_find = " auth_code=:auth_code or(`doc_number`=:doc_number and `auth_code` is not null and `auth_code`!='')";
                $arr_val = array("auth_code" => $unid, "doc_number" => $invID); //array("auth_code" => $unid);
                $arr_updateval = array("auth_code" => $unid, "doc_number" => $invID);
                $fieldname = 'doc_number';


                break;
            case "support":
                $tb_name = 'stock_qp_invoice';
                $cond = ' auth_code=:auth_code';
                // $arr_val = array("auth_code" => $unid);
                $cond_find = " auth_code=:auth_code or(`doc_number`=:doc_number and `auth_code` is not null and `auth_code`!='')";
                $arr_val = array("auth_code" => $unid, "doc_number" => $invID); //array("auth_code" => $unid);
                $arr_updateval = array("auth_code" => $unid, "doc_number" => $invID);
                $fieldname = 'doc_number';


                break;


            default:
                break;
        }

        $found = $this->stock_authcodeExist($tb_name, $cond_find, $arr_val); //check if exist before
        if (!$found) {
            $sql = "UPDATE " . $tb_name . " SET " . $cond . " where " . $fieldname . "=:doc_number";
            // `LPO`, `model`,`doc_number`, `                                      auth_code`, `manual_amount`, `add_by`

            $countRows = $this->query($sql, $arr_updateval);
//var_dump($countRows);die;
            if ($countRows > 0) {
                return TRUE; //success

                return true; //$_SESSION['user']['email'].'<a href="javascript:;" class="btn unpick-activity"  data-id="'.$id.'">unPick It</a>';;
            } else {
                //  return FALSE;
                $this->log->logActions($_SESSION['user']['email'], 'Edit' . $_POST['action'] . ' ' . $tb_name, 'Faild Edit', 'DOC ID  :' . $authtype);
                return TRUE;
            }
        } else {
            return 'This Auth code is already exist or the invoice already has Auth Code';
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

        $Array = $this->analyse_file($fName, 100);
// print_r($Array);
// print(trim($Array['delimiter']['value']));
//        $row = 1;
//if (($handle = fopen($fName, "r")) !== FALSE) {
//    while (($data = fgetcsv($handle, 1000, $Array['delimiter']['value'])) !== FALSE) {
//        $num = count($data);
//        echo "<p> $num fields in line $row: <br /></p>\n";
//        $row++;
//        for ($c=0; $c < $num; $c++) {
//            echo $data[$c] . "<br />\n";
//        }
//    }
//    fclose($handle);
//}
//die;



        try {
//$handle = fopen($fName, "r");
            $_orInserted = array();

            $msg = '';

            $c = -1;
            $_sql = array();
            $file = fopen($fName, "r") or die("error open file " . $fName);

            $row = 1;
            if (($handle = fopen($fName, "r")) !== FALSE) {
                while (($filesop = fgetcsv($handle, 1000, $Array['delimiter']['value'])) !== FALSE) {
                    $num = count($filesop);
// echo "<p> $num fields in line $row: <br /></p>\n";
                    $row++;
                    $c++;
                    if ($c == 0) {

                        continue;
                    }
                    $cust_name = $filesop[0];
                    $po_id = $filesop[1];
                    $vendor = $filesop[2];
                    $RouterClass = $filesop[3];
                    $Item_Name = strtolower(str_replace(' ', '', trim($filesop[4])));
                    $Description = $filesop[5];
                    $PO_QTY = $filesop[6];
                    $CEAR_id = $filesop[7];
                    $Req_by = $filesop[8];
                    $DeviceType = $filesop[12];
                    $HW_Price = $filesop[13];
                    $installation_charge = $filesop[14];
                    $support_charge = $filesop[15];
                    $SICETClassification = $filesop[16];


                    // var_dump($filesop);die;


                    $Req_date = ($filesop[9] == null or $filesop[9] == '') ? null : date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $filesop[9]))); //$filesop[6];


                    $po_date = ($filesop[10] == null or $filesop[10] == '') ? null : date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $filesop[10]))); //$filesop[10];
                    $EDD = $filesop[11];
                    $user = $_SESSION['user']['email'];
// check for cust name and qty and router
                    if (($cust_name != '' and ! (is_null($cust_name)) and isset($cust_name)) and ( $PO_QTY >= 0 and ! (is_null($PO_QTY)) and isset($PO_QTY)) and ( $Item_Name != '' and ! (is_null($Item_Name)) and isset($Item_Name))) {
//echo '<br/> loop#'.$c.'values are : '.'("'.mysql_real_escape_string($Party_id).'","'.mysql_real_escape_string($acc_num).'", "'.mysql_real_escape_string($acc_Name).'", "'.mysql_real_escape_string($service).'", "'.mysql_real_escape_string($Section).'", "'.mysql_real_escape_string($Router_model).'", '.mysql_real_escape_string($Qty).', "'.mysql_real_escape_string($Prob).'", "'.mysql_real_escape_string($req_by).'")<br/>-----------------------------------';

                        if ($this->stock_orderExist($po_id, $Item_Name)) {

                            $_orInserted[] = $po_id . '-' . $Item_Name;


                            continue;
                        }

                        $_sql[] = '("' . htmlentities($DeviceType) . '","' . htmlentities($cust_name) . '","' . htmlentities($po_id) . '", "' . htmlentities($vendor) . '", "' . htmlentities($RouterClass) . '", "' . htmlentities($Item_Name) . '", "' . htmlentities($Description) . '", ' . htmlentities($PO_QTY) . ', "' . htmlentities($CEAR_id) . '", "' . htmlentities($Req_by) . '", "' . htmlentities($Req_date) . '", "' . htmlentities($po_date) . '", "' . htmlentities($EDD) . '", "' . htmlentities($SICETClassification) . '", "' . htmlentities($HW_Price) . '", "' . htmlentities($installation_charge) . '", "' . htmlentities($support_charge) . '", "' . $user . '")';
                    }
//   print_r($_sql);
                }
                fclose($handle);
//  chmod('../comm/fileuplaod_temp/', 0777);
                @unlink($fName);
            }
//die;
// chmod('../comm/fileuplaod_temp/', 0777);
            //  var_dump($_orInserted);
            // die();

            $sql = 'INSERT INTO `stock_order`( DeviceType,`customer_name`, `po_id`, `vendor`, `RouterClass`, `router_model`, `description`, `po_qty`, `cear_id`, `req_by`, `Req_date`, `po_date`, `EDD`,`sicet_type`, `hw_price`, `install_charge`, `support_charge`, `user_add`) VALUES ' . implode(',', $_sql);





            try {
                //  unlink($fName);
                if (sizeof($_sql) > 0) {
                    $result = $this->query($sql);
                    if ($result > 0) {
                        $this->log->logActions($_SESSION['user']['email'], 'Order upload', 'Success upload', 'data :' . $sql . ' ');
                        $msg = 'true '; // Items inserted Before'.implode(' || ',$_srInserted);
                    } else {

                        $this->log->logActions($_SESSION['user']['email'], 'Order upload', 'Faild upload', 'data :' . $sql . ' ');
                        $msg = 'FALSE ';
                    }
                }//empty sheet
            } catch (PDOException $e) {
                unlink($fName);
                $this->log->logActions($_SESSION['user']['email'], 'Order upload', 'error pdo ' . $e->getMessage(), 'data :' . $sql . ' ');
                $msg = 'error pdo ' . $e->getMessage();
            }
        } catch (Exception $ex) {
            //@unlink($fName);
            $msg = 'error opening ' . $ex->getMessage();
        }

        if (sizeof($_orInserted) > 0) {
            $msg .= 'Orders inserted Before :<br/>' . implode(' || ', $_orInserted);
        }

        return $msg;
    }

    public function order_insertRFSupload() {// Connection data (server_address, database, name, poassword)
        if ($_POST['action'] != 'order_RFSbulkupload') {
            return "Invalid action supplied for RFS bulkupload.";
        }
        $fName = trim('../comm/fileuplaod_temp/' . $_POST['fileName']);
        if ($fName == NULL) {
            return "Invalid File Name supplied .";
        }

        $Array = $this->analyse_file($fName, 100);

        $_srInstalled = array();
        $_srexist = array();
        $_srInserted = array();
        $_rfsInserted = array();
        $_hostnameInserted = array();
        $_rfssameLPOInserted = array();
        $error_msg = '';





        try {





            $c = -1;
            $_sql = array();
            $file = fopen($fName, "r") or die("error open file " . $fName);




            $row = 1;
            if (($handle = fopen($fName, "r")) !== FALSE) {
                while (($filesop = fgetcsv($handle, 1000, $Array['delimiter']['value'])) !== FALSE) {
                    $num = count($filesop);

                    $row++;
                    $c++;
                    if ($c == 0) {

                        continue;
                    }
                    $LPO = strtolower(str_replace(' ', '', $filesop[0]));
                    $Model = strtolower(str_replace(' ', '', $filesop[1]));
                    $Device_sr = strtolower(str_replace(' ', '', $filesop[2]));
                    $hostname = strtolower(str_replace(' ', '', $filesop[3]));




                    $rfs_date = ($filesop[4] == null or $filesop[4] == '') ? null : date("Y-m-d", strtotime(str_replace('/', '-', $filesop[4]))); // strtolower(str_replace(' ', '', trim($filesop[4])));
                    $SuportEnrollment_date = '';
                    //  print_r($rfs_date);
                    $d = date_parse_from_format("Y-m-d", $rfs_date);
                    $d["month"];
                    switch ($d["month"]) {
                        case 1:
                        case 2:
                        case 3:
                            // Prints something like: 2006-04-05T01:02:03+00:00
                            $SuportEnrollment_date = ( date('Y-m-d', mktime(0, 0, 0, 4, 1, $d["year"])));
                            break;

                        case 4:
                        case 5:
                        case 6:
                            // Prints something like: 2006-04-05T01:02:03+00:00
                            $SuportEnrollment_date = ( date('Y-m-d', mktime(0, 0, 0, 7, 1, $d["year"])));
                            break;
                        case 7:
                        case 8:
                        case 9:
                            // Prints something like: 2006-04-05T01:02:03+00:00
                            $SuportEnrollment_date = ( date('Y-m-d', mktime(0, 0, 0, 10, 1, $d["year"])));
                            break;
                        case 10:
                        case 11:
                        case 12:
                            // Prints something like: 2006-04-05T01:02:03+00:00
                            $SuportEnrollment_date = ( date('Y-m-d', mktime(0, 0, 0, 1, 1, $d["year"] + 1)));


                            break;

                        default:
                            break;
                    }


                    $account_status = $filesop[5];
                    $contrc_date = ($filesop[6] == null or $filesop[6] == '') ? null : date("Y-m-d", strtotime(str_replace('/', '-', $filesop[6]))); //$filesop[6];
                    // print_r($filesop[6]);
                    $rfs_number = $filesop[7];
                    $Req_by = $_SESSION['user']['email'];

                    $user = $_SESSION['user']['email'];

                    if (($LPO != '' and ! (is_null($LPO)) and isset($LPO)) and ( !(is_null($Model)) and isset($Model)) and ( $Device_sr != '' and ! (is_null($Device_sr)) and isset($Device_sr))) {
                        $AddNew_flag=TRUE;
                        //convert that to be switch case is better to validate all
                        if (!$this->stock_docExist("routers_detailes", " device_Serial=:device_Serial ", array("device_Serial" => $Device_sr))) {// installed or not
                            $_srexist[] = $Device_sr;
                           // continue;
                            $AddNew_flag=FALSE;
                        }
                        if ($this->stock_docExist("routers_detailes", " device_Serial=:device_Serial and installation_stat=0", array("device_Serial" => $Device_sr))) {// installed or not
                            $_srInstalled[] = $Device_sr;
                            // continue;
                            $AddNew_flag=FALSE;
                        }
                        if ($this->stock_docExist("stock_rfs_data", " devices_serial=:device_Serial", array("device_Serial" => $Device_sr))) {//did not RFSed before
                            $_srInserted[] = $Device_sr;
                            // continue;
                            $AddNew_flag=FALSE;
                        }

                        if ($this->stock_docExist("stock_rfs_data", " host_name=:host_name", array("host_name" => $hostname))) {//did not RFSed before
                            $_hostnameInserted[] = $hostname;
                           // continue;
                            $AddNew_flag=FALSE;
                        }

                        if ($this->stock_docExist("stock_rfs_data", " RFS_Request_Number=:RFS_Request_Number and LPO=:LPO", array("RFS_Request_Number" => $rfs_number,"LPO" => $LPO))) {//did not RFSed before
                            $_rfssameLPOInserted[] ='LPO#'.$LPO.' with RFS#'.$rfs_number;
                           // continue;
                            $AddNew_flag=FALSE;
                        }

                        if ($AddNew_flag) {
                            $_sql[] = '("' . htmlentities($LPO) . '","' . htmlentities($Model) . '","' . htmlentities($Device_sr) . '", "' . htmlentities($hostname) . '", "' . htmlentities($rfs_date) . '", "' . htmlentities($SuportEnrollment_date) . '", "' . htmlentities($account_status) . '", "' . htmlentities($contrc_date) . '", "' . htmlentities($rfs_number) . '", "' . htmlentities($Req_by) . '")';
                        }

                        
                    }
//   print_r($_sql);
                }
                fclose($handle);
//  chmod('../comm/fileuplaod_temp/', 0777);
                @unlink($fName);
            }
//die;
// chmod('../comm/fileuplaod_temp/', 0777);
            if (!empty($_sql) && count($_sql) > 0) {


                $sql = 'INSERT INTO `stock_rfs_data`(`LPO`, `model`, `devices_serial`, `host_name`, `RFS_date`,enroll_date, `account_status`, `contracutal_RFS_Date`, `RFS_Request_Number`, `add_by`) VALUES ' . implode(',', $_sql);
                // print_r($_POST);
                //  print_r($sql);die;
                try {
//    unlink($fName);

                    $result = $this->query($sql);
                    if ($result > 0) {
                        if (sizeof($_srInserted) > 0) {
                            $error_msg .= '<br/><br/>devices updated But there are Serials already have RFS Request Before :<br/>' . implode(' || ', $_srInserted);
                        }
                        if (sizeof($_srInstalled) > 0) {
                            $error_msg .= ' <br/><br/>devices updated But there are Serials already Did installed Yet:<br/>' . implode(' || ', $_srInstalled);
                            // return TRUE;
                        }

                        if (sizeof($_srexist) > 0) {
                            $error_msg .= ' <br/><br/>devices updated But there are Serials already DID NOT DELIVERED Yet:<br/>' . implode(' || ', $_srexist);
                            // return TRUE;
                        }
        
                        if (sizeof($_hostnameInserted) > 0) {
                            $error_msg .= ' <br/><br/>devices updated But there are HOSTNAMES ALREADY EXIST:<br/>' . implode(' || ', $_hostnameInserted);
                            // return TRUE;
                        }

                        if (sizeof($_rfssameLPOInserted) > 0) {
                            $error_msg .= ' <br/><br/>devices updated But there are RFS NUMBER ALREADY EXIST:<br/>' . implode(' || ', $_rfssameLPOInserted);
                            // return TRUE;
                        }

                        $error_msg = 'true ' . $error_msg;

                        return $error_msg;
                    } else {
                        $error_msg = 'false ' . $error_msg;
                        return $error_msg;
                    }
                } catch (PDOException $e) {
//   unlink($fName);
                    return('error pdo ' . $e->getMessage());
                }
            } else {
                // return TRUE;
                $error_msg .= ' RFS did not inserted due to  :<br/>';
                if (sizeof($_srInserted) > 0) {
                    $error_msg .= ' <br/><br/>RFS did not inserted due to there are Serials already have RFS Request Before :<br/>' . implode(' || ', $_srInserted);
                }
                if (sizeof($_srInstalled) > 0) {
                    $error_msg .= ' <br/><br/>RFS did not inserted due to there are Serials already Did installed Yet:<br/>' . implode(' || ', $_srInstalled);
                    // return TRUE;
                }

                if (sizeof($_srexist) > 0) {
                    $error_msg .= ' <br/><br/>RFS did not inserted due to there are Serials  Did not Delivered Yet:<br/>' . implode(' || ', $_srexist);
                    // return TRUE;
                }
                  if (sizeof($_hostnameInserted) > 0) {
                            $error_msg .= ' <br/><br/>RFS did not inserted due to there are HOSTNAMES ALREADY EXIST:<br/>' . implode(' || ', $_hostnameInserted);
                            // return TRUE;
                        }

                        if (sizeof($_rfssameLPOInserted) > 0) {
                            $error_msg .= ' <br/><br/>RFS did not inserted due to there are RFS NUMBER ALREADY EXIST:<br/>' . implode(' || ', $_rfssameLPOInserted);
                            // return TRUE;
                        }
                
                
                $error_msg = 'false ' . $error_msg;
                return $error_msg;
            }
        } catch (Exception $ex) {
//  unlink($fName);
            return('error opening ' . $ex->getMessage());
        }
    }

   public function order_insertRFSdeviceupload() {// Connection data (server_address, database, name, poassword)
        if ($_POST['action'] != 'order_RFSdeviceupload') {
            return "Invalid action supplied for RFS Device upload.";
        }
        $fName = trim('../comm/fileuplaod_temp/' . $_POST['fileName']);
        if ($fName == NULL) {
            return "Invalid File Name supplied .";
        }

        $Array = $this->analyse_file($fName, 100);

        $_srInstalled = array();
        $_srexist = array();
        $_srInserted = array();
        $_rfsInserted = array();
        $_hostnameInserted = array();
        $_rfssameLPOInserted = array();
        $error_msg = '';





        try {





            $c = -1;
            $_sql = array();
            $file = fopen($fName, "r") or die("error open file " . $fName);




            $row = 1;
            if (($handle = fopen($fName, "r")) !== FALSE) {
                while (($filesop = fgetcsv($handle, 1000, $Array['delimiter']['value'])) !== FALSE) {
                    $num = count($filesop);

                    $row++;
                    $c++;
                    if ($c == 0) {

                        continue;
                    }
                    $Device_sr = strtolower(str_replace(' ', '', $filesop[0]));
                    $hostname = strtolower(str_replace(' ', '', $filesop[1]));
                   // $Device_sr = strtolower(str_replace(' ', '', $filesop[2]));
                   // $hostname = strtolower(str_replace(' ', '', $filesop[3]));




                    $rfs_date = ($filesop[2] == null or $filesop[2] == '') ? null : date("Y-m-d", strtotime(str_replace('/', '-', $filesop[2]))); // strtolower(str_replace(' ', '', trim($filesop[4])));
                    $SuportEnrollment_date = '';
                    //  print_r($rfs_date);
                    $d = date_parse_from_format("Y-m-d", $rfs_date);
                    $d["month"];
                    switch ($d["month"]) {
                        case 1:
                        case 2:
                        case 3:
                            // Prints something like: 2006-04-05T01:02:03+00:00
                            $SuportEnrollment_date = ( date('Y-m-d', mktime(0, 0, 0, 4, 1, $d["year"])));
                            break;

                        case 4:
                        case 5:
                        case 6:
                            // Prints something like: 2006-04-05T01:02:03+00:00
                            $SuportEnrollment_date = ( date('Y-m-d', mktime(0, 0, 0, 7, 1, $d["year"])));
                            break;
                        case 7:
                        case 8:
                        case 9:
                            // Prints something like: 2006-04-05T01:02:03+00:00
                            $SuportEnrollment_date = ( date('Y-m-d', mktime(0, 0, 0, 10, 1, $d["year"])));
                            break;
                        case 10:
                        case 11:
                        case 12:
                            // Prints something like: 2006-04-05T01:02:03+00:00
                            $SuportEnrollment_date = ( date('Y-m-d', mktime(0, 0, 0, 1, 1, $d["year"] + 1)));


                            break;

                        default:
                            break;
                    }


                 //   $account_status = $filesop[5];
                 //   $contrc_date = ($filesop[6] == null or $filesop[6] == '') ? null : date("Y-m-d", strtotime(str_replace('/', '-', $filesop[6]))); //$filesop[6];
                    // print_r($filesop[6]);
                 //   $rfs_number = $filesop[7];
                    $Req_by = $_SESSION['user']['email'];

                    $user = $_SESSION['user']['email'];

                    if (( $Device_sr != '' and ! (is_null($Device_sr)) and isset($Device_sr))) {
                        $AddNew_flag=TRUE;
                        //convert that to be switch case is better to validate all
                        if (!$this->stock_docExist("routers_detailes", " device_Serial=:device_Serial ", array("device_Serial" => $Device_sr))) {// installed or not
                            $_srexist[] = $Device_sr;
                           // continue;
                            $AddNew_flag=FALSE;
                        }
                        if ($this->stock_docExist("routers_detailes", " device_Serial=:device_Serial and installation_stat=0", array("device_Serial" => $Device_sr))) {// installed or not
                            $_srInstalled[] = $Device_sr;
                            // continue;
                            $AddNew_flag=FALSE;
                        }
                        if ($this->stock_docExist("stock_rfs_devices_tbl", " devices_serial=:device_Serial", array("device_Serial" => $Device_sr))) {//did not RFSed before
                            $_srInserted[] = $Device_sr;
                            // continue;
                            $AddNew_flag=FALSE;
                        }

                        if ($this->stock_docExist("stock_rfs_devices_tbl", " host_name=:host_name", array("host_name" => $hostname))) {//did not RFSed before
                            $_hostnameInserted[] = $hostname;
                           // continue;
                            $AddNew_flag=FALSE;
                        }

//                        if ($this->stock_docExist("stock_rfs_data", " RFS_Request_Number=:RFS_Request_Number and LPO=:LPO", array("RFS_Request_Number" => $rfs_number,"LPO" => $LPO))) {//did not RFSed before
//                            $_rfssameLPOInserted[] ='LPO#'.$LPO.' with RFS#'.$rfs_number;
//                           // continue;
//                            $AddNew_flag=FALSE;
//                        }

                        if ($AddNew_flag) {
                            $_sql[] = '("' . htmlentities($Device_sr) . '", "' . htmlentities($hostname) . '", "' . htmlentities($rfs_date) . '", "' . htmlentities($SuportEnrollment_date) . '", "' . htmlentities($Req_by) . '")';
                        }

                        
                    }
//   print_r($_sql);
                }
                fclose($handle);
//  chmod('../comm/fileuplaod_temp/', 0777);
                @unlink($fName);
            }
//die;
// chmod('../comm/fileuplaod_temp/', 0777);
            if (!empty($_sql) && count($_sql) > 0) {


                $sql = 'INSERT INTO `stock_rfs_devices_tbl`(`devices_serial`, `host_name`, `RFS_date`,enroll_date, `add_by`) VALUES ' . implode(',', $_sql);
                // print_r($_POST);
                //  print_r($sql);die;
                try {
//    unlink($fName);

                    $result = $this->query($sql);
                    if ($result > 0) {
                        if (sizeof($_srInserted) > 0) {
                            $error_msg .= '<br/><br/>devices updated But there are Serials already RFSED Before :<br/>' . implode(' || ', $_srInserted);
                        }
                        if (sizeof($_srInstalled) > 0) {
                            $error_msg .= ' <br/><br/>devices updated But there are Serials already DIDNOT BE INSTALLED Yet:<br/>' . implode(' || ', $_srInstalled);
                            // return TRUE;
                        }

                        if (sizeof($_srexist) > 0) {
                            $error_msg .= ' <br/><br/>devices updated But there are Serials already DID NOT DELIVERED Yet:<br/>' . implode(' || ', $_srexist);
                            // return TRUE;
                        }
        
                        if (sizeof($_hostnameInserted) > 0) {
                            $error_msg .= ' <br/><br/>devices updated But there are HOSTNAMES ALREADY EXIST:<br/>' . implode(' || ', $_hostnameInserted);
                            // return TRUE;
                        }

//                        if (sizeof($_rfssameLPOInserted) > 0) {
//                            $error_msg .= ' <br/><br/>devices updated But there are RFS NUMBER ALREADY EXIST:<br/>' . implode(' || ', $_rfssameLPOInserted);
//                            // return TRUE;
//                        }

                        $error_msg = 'true ' . $error_msg;

                        return $error_msg;
                    } else {
                        $error_msg = 'false ' . $error_msg;
                        return $error_msg;
                    }
                } catch (PDOException $e) {
//   unlink($fName);
                    return('error pdo ' . $e->getMessage());
                }
            } else {
                // return TRUE;
                $error_msg .= ' RFS did not inserted due to  :<br/>';
                if (sizeof($_srInserted) > 0) {
                    $error_msg .= ' <br/><br/>RFS did not inserted due to there are Serials already RFSED Before Before :<br/>' . implode(' || ', $_srInserted);
                }
                if (sizeof($_srInstalled) > 0) {
                    $error_msg .= ' <br/><br/>RFS did not inserted due to there are Serials already DIDNOT BE INSTALLED yet:<br/>' . implode(' || ', $_srInstalled);
                    // return TRUE;
                }

                if (sizeof($_srexist) > 0) {
                    $error_msg .= ' <br/><br/>RFS did not inserted due to there are Serials  Did not Delivered Yet:<br/>' . implode(' || ', $_srexist);
                    // return TRUE;
                }
                  if (sizeof($_hostnameInserted) > 0) {
                            $error_msg .= ' <br/><br/>RFS did not inserted due to there are HOSTNAMES ALREADY EXIST:<br/>' . implode(' || ', $_hostnameInserted);
                            // return TRUE;
                        }

//                        if (sizeof($_rfssameLPOInserted) > 0) {
//                            $error_msg .= ' <br/><br/>RFS did not inserted due to there are RFS NUMBER ALREADY EXIST:<br/>' . implode(' || ', $_rfssameLPOInserted);
//                            // return TRUE;
//                        }
                
                
                $error_msg = 'false ' . $error_msg;
                return $error_msg;
            }
        } catch (Exception $ex) {
//  unlink($fName);
            return('error opening ' . $ex->getMessage());
        }
    } 
    
    
    public function order_insertPACupload() {// Connection data (server_address, database, name, poassword)
        if ($_POST['action'] != 'order_PACbulkupload') {
            return "Invalid action supplied for PAC bulkupload.";
        }
        $fName = trim('../comm/fileuplaod_temp/' . $_POST['fileName']);
        if ($fName == NULL) {
            return "Invalid File Name supplied .";
        }

        $Array = $this->analyse_file($fName, 100);

        $_srInstalled = array();
        $_srexist = array();
        $_srInserted = array();
        $_rfsInserted = array();
        $_hostnameInserted = array();
        $_PACsameLPOInserted = array();
        $error_msg = '';





        try {





            $c = -1;
            $_sql = array();
            $file = fopen($fName, "r") or die("error open file " . $fName);




            $row = 1;
            if (($handle = fopen($fName, "r")) !== FALSE) {
                while (($filesop = fgetcsv($handle, 1000, $Array['delimiter']['value'])) !== FALSE) {
                    $num = count($filesop);

                    $row++;
                    $c++;
                    if ($c == 0) {

                        continue;
                    }
                    $LPO = strtolower(str_replace(' ', '', $filesop[0]));
                    $Model = strtolower(str_replace(' ', '', $filesop[1]));
                    $Device_sr = strtolower(str_replace(' ', '', $filesop[2]));
                    $hostname = strtolower(str_replace(' ', '', $filesop[3]));




                    $rfs_date = ($filesop[4] == null or $filesop[4] == '') ? null : date("Y-m-d", strtotime(str_replace('/', '-', $filesop[4]))); // strtolower(str_replace(' ', '', trim($filesop[4])));
                    $SuportEnrollment_date = '';
                    //  print_r($rfs_date);
                    $d = date_parse_from_format("Y-m-d", $rfs_date);
                    $d["month"];
                    switch ($d["month"]) {
                        case 1:
                        case 2:
                        case 3:
                            // Prints something like: 2006-04-05T01:02:03+00:00
                            $SuportEnrollment_date = ( date('Y-m-d', mktime(0, 0, 0, 4, 1, $d["year"])));
                            break;

                        case 4:
                        case 5:
                        case 6:
                            // Prints something like: 2006-04-05T01:02:03+00:00
                            $SuportEnrollment_date = ( date('Y-m-d', mktime(0, 0, 0, 7, 1, $d["year"])));
                            break;
                        case 7:
                        case 8:
                        case 9:
                            // Prints something like: 2006-04-05T01:02:03+00:00
                            $SuportEnrollment_date = ( date('Y-m-d', mktime(0, 0, 0, 10, 1, $d["year"])));
                            break;
                        case 10:
                        case 11:
                        case 12:
                            // Prints something like: 2006-04-05T01:02:03+00:00
                            $SuportEnrollment_date = ( date('Y-m-d', mktime(0, 0, 0, 1, 1, $d["year"] + 1)));


                            break;

                        default:
                            break;
                    }


                    $account_status = $filesop[5];
                  //  $contrc_date = ($filesop[6] == null or $filesop[6] == '') ? null : date("Y-m-d", strtotime(str_replace('/', '-', $filesop[6]))); //$filesop[6];
                    // print_r($filesop[6]);
                    $rfs_number = $filesop[6];
                    $Req_by = $_SESSION['user']['email'];

                    $user = $_SESSION['user']['email'];

                    if (($LPO != '' and ! (is_null($LPO)) and isset($LPO)) and ( !(is_null($Model)) and isset($Model)) and ( $Device_sr != '' and ! (is_null($Device_sr)) and isset($Device_sr))) {

                        if (!$this->stock_docExist("stock_stockdata", " device_Serial=:device_Serial ", array("device_Serial" => $Device_sr))) {// installed or not
                            $_srexist[] = $Device_sr;
                            continue;
                        }
                        if ($this->stock_docExist("stock_stockdata", " device_Serial=:device_Serial and rfs_paid!=1", array("device_Serial" => $Device_sr))) {// installed or not
                            $_srInstalled[] = $Device_sr;
                            continue;
                        }
                        if ($this->stock_docExist("stock_pac_data", " devices_serial=:device_Serial", array("device_Serial" => $Device_sr))) {//did not RFSed before
                            $_srInserted[] = $Device_sr;
                            continue;
                        }



                        $_sql[] = '("' . htmlentities($LPO) . '","' . htmlentities($Model) . '","' . htmlentities($Device_sr) . '", "' . htmlentities($hostname) . '", "' . htmlentities($rfs_date) . '", "' . htmlentities($SuportEnrollment_date) . '", "' . htmlentities($account_status) . '", "' . htmlentities($rfs_number) . '", "' . htmlentities($Req_by) . '")';
                    }
//   print_r($_sql);
                }
                fclose($handle);
//  chmod('../comm/fileuplaod_temp/', 0777);
                @unlink($fName);
            }
//die;
// chmod('../comm/fileuplaod_temp/', 0777);
            if (!empty($_sql) && count($_sql) > 0) {


                $sql = 'INSERT INTO `stock_pac_data`(`LPO`, `model`, `devices_serial`, `host_name`, `PAC_date`,enroll_date, `account_status`, `PAC_Request_Number`, `add_by`) VALUES ' . implode(',', $_sql);
                // print_r($_POST);
                //  print_r($sql);die;
                try {
//    unlink($fName);

                    $result = $this->query($sql);
                    if ($result > 0) {
                        if (sizeof($_srInserted) > 0) {
                            $error_msg .= '<br/><br/>devices updated But there are Serials already have PAC Request Before :<br/>' . implode(' || ', $_srInserted);
                        }
                        if (sizeof($_srInstalled) > 0) {
                            $error_msg .= ' <br/><br/>devices updated But there are Serials already Did not RFS-ed Yet:<br/>' . implode(' || ', $_srInstalled);
                            // return TRUE;
                        }

                        if (sizeof($_srexist) > 0) {
                            $error_msg .= ' <br/><br/>devices updated But there are Serials already Did Deliveried Yet:<br/>' . implode(' || ', $_srexist);
                            // return TRUE;
                        }
                        $error_msg = 'true ' . $error_msg;

                        return $error_msg;
                    } else {
                        $error_msg = 'false ' . $error_msg;
                        return $error_msg;
                    }
                } catch (PDOException $e) {
//   unlink($fName);
                    return('error pdo ' . $e->getMessage());
                }
            } else {
                // return TRUE;
                $error_msg .= ' PAC did not inserted due to  :<br/>';
                if (sizeof($_srInserted) > 0) {
                    $error_msg .= ' <br/><br/>PAC did not inserted due to there are Serials already have PAC Request Before :<br/>' . implode(' || ', $_srInserted);
                }
                if (sizeof($_srInstalled) > 0) {
                    $error_msg .= ' <br/><br/>PAC did not inserted due to there are Serials already Did not RFS Yet:<br/>' . implode(' || ', $_srInstalled);
                    // return TRUE;
                }

                if (sizeof($_srexist) > 0) {
                    $error_msg .= ' <br/><br/>PAC did not inserted due to there are Serials  Did not Delivered Yet:<br/>' . implode(' || ', $_srexist);
                    // return TRUE;
                }
                $error_msg = 'false ' . $error_msg;
                return $error_msg;
            }
        } catch (Exception $ex) {
//  unlink($fName);
            return('error opening ' . $ex->getMessage());
        }
    }

    public function getAllModelsPerLPO($custname = null) {// Connection data (server_address, database, name, poassword)
        if ($custname != null or isset($_POST['code'])) {
            $custname = $custname == null ? htmlentities($_POST['code'], ENT_QUOTES) : htmlentities($custname, ENT_QUOTES);

            $sql = "SELECT  distinct router_model from stock_order where po_id=:po_id order by router_model"; //"SELECT  distinct t.`cmdb_host_name` from ( (select distinct `cmdb_host_name` FROM mss_db.`mss_cmdb` where `cmdb_customer_code`=:custcode) union (select distinct host_name from cnoc_handovers where Cust_code=:custcode1)  union (select distinct host_name from cnoc_activities where Cust_code=:custcode2)) as t  order by cmdb_host_name";//"SELECT distinct cmdb_host_name FROM `mss_cmdb` where `cmdb_customer_code`=:custcode order by cmdb_host_name ";

            $html = "";
            $result = array();
            try {
                $result = $this->query($sql, array('po_id' => $custname), PDO::FETCH_OBJ);
            } catch (PDOException $e) {

                return($e->getMessage());
            }
            return json_encode($result);
        }
    }

    public function getAllDevicesPerModel($lpo = null, $model = null) {// Connection data (server_address, database, name, poassword)
        if ($lpo != null or $model != null or isset($_POST['$lpo']) or isset($_POST['model'])) {
            $lpo = $lpo == null ? htmlentities($_POST['lpo'], ENT_QUOTES) : htmlentities($lpo, ENT_QUOTES);
            $model = $model == null ? htmlentities($_POST['model'], ENT_QUOTES) : htmlentities($model, ENT_QUOTES);
            $model = "'" . str_replace(",", "','", $model) . "'";


            //$sql = "SELECT distinct device_Serial from stock_stockdata where LPO=:po_id and Model=:model order by device_Serial"; //"SELECT  distinct t.`cmdb_host_name` from ( (select distinct `cmdb_host_name` FROM mss_db.`mss_cmdb` where `cmdb_customer_code`=:custcode) union (select distinct host_name from cnoc_handovers where Cust_code=:custcode1)  union (select distinct host_name from cnoc_activities where Cust_code=:custcode2)) as t  order by cmdb_host_name";//"SELECT distinct cmdb_host_name FROM `mss_cmdb` where `cmdb_customer_code`=:custcode order by cmdb_host_name ";
            $sql = "SELECT distinct device_Serial from stock_stockdata where LPO=:po_id and Model in($model) order by device_Serial"; //"SELECT  distinct t.`cmdb_host_name` from ( (select distinct `cmdb_host_name` FROM mss_db.`mss_cmdb` where `cmdb_customer_code`=:custcode) union (select distinct host_name from cnoc_handovers where Cust_code=:custcode1)  union (select distinct host_name from cnoc_activities where Cust_code=:custcode2)) as t  order by cmdb_host_name";//"SELECT distinct cmdb_host_name FROM `mss_cmdb` where `cmdb_customer_code`=:custcode order by cmdb_host_name ";

            $html = "";
            $result = array();
            try {
                //$result = $this->query($sql, array('po_id' => $lpo, 'model' => $model), PDO::FETCH_OBJ);
                $result = $this->query($sql, array('po_id' => $lpo), PDO::FETCH_OBJ);
            } catch (PDOException $e) {

                return($e->getMessage());
            }
            return json_encode($result);
        }
    }

//    public function getAllRFSPerModel($lpo = null, $model = null) {// Connection data (server_address, database, name, poassword)
//        if ($lpo != null or $model != null or isset($_POST['$lpo']) or isset($_POST['model'])) {
//            $lpo = $lpo == null ? htmlentities($_POST['lpo'], ENT_QUOTES) : htmlentities($lpo, ENT_QUOTES);
//            $model = $model == null ? htmlentities($_POST['model'], ENT_QUOTES) : htmlentities($model, ENT_QUOTES);
//
//            $sql = "SELECT distinct RFS_Request_Number from stock_rfs_data where LPO=:po_id and Model=:model order by RFS_Request_Number"; //"SELECT  distinct t.`cmdb_host_name` from ( (select distinct `cmdb_host_name` FROM mss_db.`mss_cmdb` where `cmdb_customer_code`=:custcode) union (select distinct host_name from cnoc_handovers where Cust_code=:custcode1)  union (select distinct host_name from cnoc_activities where Cust_code=:custcode2)) as t  order by cmdb_host_name";//"SELECT distinct cmdb_host_name FROM `mss_cmdb` where `cmdb_customer_code`=:custcode order by cmdb_host_name ";
//
//            $html = "";
//            $result = array();
//            try {
//                $result = $this->query($sql, array('po_id' => $lpo, 'model' => $model), PDO::FETCH_OBJ);
//            } catch (PDOException $e) {
//
//                return($e->getMessage());
//            }
//            return json_encode($result);
//        }
//    }

    public function getAllRFSPerModel($lpo = null, $model = null) {// Connection data (server_address, database, name, poassword)
        //   echo 'i am here';
        //  var_dump($_POST);
        if ($lpo != null or isset($_POST['lpo'])) {
            //  echo 'i am here2';
            $lpo = $lpo == null ? htmlentities($_POST['lpo'], ENT_QUOTES) : htmlentities($lpo, ENT_QUOTES);
            // $model = $model == null ? htmlentities($_POST['model'], ENT_QUOTES) : htmlentities($model, ENT_QUOTES);

            $sql = "SELECT distinct RFS_Request_Number from stock_rfs_data where LPO=:po_id order by RFS_Request_Number"; //"SELECT  distinct t.`cmdb_host_name` from ( (select distinct `cmdb_host_name` FROM mss_db.`mss_cmdb` where `cmdb_customer_code`=:custcode) union (select distinct host_name from cnoc_handovers where Cust_code=:custcode1)  union (select distinct host_name from cnoc_activities where Cust_code=:custcode2)) as t  order by cmdb_host_name";//"SELECT distinct cmdb_host_name FROM `mss_cmdb` where `cmdb_customer_code`=:custcode order by cmdb_host_name ";

            $html = "";
            $result = array();
            try {
                $result = $this->query($sql, array('po_id' => $lpo), PDO::FETCH_OBJ);
            } catch (PDOException $e) {

                return($e->getMessage());
            }
            //    var_dump($result);
            return json_encode($result);
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

    public function addNewDoc() {
        /*
         * Fails if the proper action was not submitted
         */
// echo "<script>alert('".$_POST['input-username']."')</script>";
        if ($_POST['action'] != 'stock_AddDoc') {
            return "Invalid action supplied for process Add new Doc.";
        }



//    print_r($_POST);
//echo $_FILES["file_doc"]["name"] . " <span id='invalid'><b>already exists.</b></span> ";     
//      
//        $PartyID = htmlentities($_POST['txt-forecast-PartyId'], ENT_QUOTES);
//        $AccountNumber = (htmlentities($_POST['txt-forecast-custNum'], ENT_QUOTES) == '' ? 'N/A' : htmlentities($_POST['txt-forecast-custNum'], ENT_QUOTES));
//        $CustomerName = (strtolower(htmlentities($_POST['select-forecast-custName'], ENT_QUOTES)) == 'other' ? htmlentities($_POST['txt-forecast-custName'], ENT_QUOTES) : htmlentities($_POST['select-forecast-custName'], ENT_QUOTES));
//        $Section = htmlentities($_POST['select-forecast-dept'], ENT_QUOTES);
//        $Qty = htmlentities($_POST['txt-forecast-qty'], ENT_QUOTES);
//        $RouterModel = htmlentities($_POST['select-forecast-deviceModel'], ENT_QUOTES);
//        $Prob_Of_Closing = htmlentities($_POST['select-forecast-prob'], ENT_QUOTES);
        $Requested_By = htmlentities($_SESSION['user']['email'], ENT_QUOTES);
//        $Service = htmlentities($_POST['select-forecast-serviceName'], ENT_QUOTES);
//
//
//        $CEAR_id = $filesop[0];
//        $Item_Name = $filesop[1];
//        $Description = $filesop[2];
//        $PO_QTY = $filesop[3];
//        $cust_name = $filesop[4];
//        $Req_by = $filesop[5];
//        $Req_date = $filesop[6];
//        $vendor = $filesop[7];
//        $po_id = $filesop[8];
//        $po_date = $filesop[9];
//        $EDD = $filesop[10];
        $output_dir = realpath(dirname(__FILE__) . '/../..') . "/public/assets/comm/uploads/stock/";
        $LPO = htmlentities($_POST['select-order-LPO'], ENT_QUOTES);
        //$model = htmlentities($_POST['select-order-model'], ENT_QUOTES);
        $model = htmlentities(implode(',', $_POST['select-order-model']), ENT_QUOTES);
        $devices = htmlentities(implode(',', $_POST['select-devices']), ENT_QUOTES);
        $DocType = htmlentities($_POST['select-order-DocType'], ENT_QUOTES);
        $DocName = htmlentities($_FILES["file_doc"]["name"], ENT_QUOTES);
        $DocNumber = htmlentities($_POST['txt-doc-number'], ENT_QUOTES); //documnet number 
        $DocNewName = '';



        $found = FALSE;

        $tb_name = "stock_docs";

        //  $cond = ' doc_number=:doc_number or auth_code=:auth_code';
        $cond = ' doc_number=:doc_number ';
        // $arr_val = array("doc_number" => $DocNumber, "auth_code" => $AuthCode);
        $arr_val = array("doc_number" => $DocNumber);
        $found = $this->stock_docExist($tb_name, $cond, $arr_val);

//var_dump($found);
//print_r($_POST);die;

        if (!$found) {// if this is new invoice with a new DocNumber
//$upOne = realpath(dirname(__FILE__) . '/../..');
//print(__FILE__);
//print_r($_FILES);
//   print_r($_FILES["file_doc"]["name"]);
            if (isset($_FILES["file_doc"])) {
                $ret = array();

                $error = $_FILES["file_doc"]["error"];
//You need to handle  both cases
//If Any browser does not support serializing of multiple files using FormData() 
                if (!is_array($_FILES["file_doc"]["name"])) { //single file
                    $temp = explode(".", $_FILES["file_doc"]["name"]);
                    $newfilename = microtime(true) . '.' . end($temp);
//$fileName = $_FILES["myfile"]["name"];
                    move_uploaded_file($_FILES["file_doc"]["tmp_name"], $output_dir . $newfilename);
                    $ret[] = $newfilename;
                    $DocNewName = $newfilename;
//  echo $newfilename;
                } else {  //Multiple files, file[]
                    $fileCount = count($_FILES["file_doc"]["name"]);
                    for ($i = 0; $i < $fileCount; $i++) {
                        $fileName = $_FILES["file_doc"]["name"][$i];
                        move_uploaded_file($_FILES["file_doc"]["tmp_name"][$i], $output_dir . $fileName);
                        $ret[] = $fileName;
                    }
                }

//   var_dump($ret);
//echo json_encode($ret);
            }





            /*
             * Retrieves the matching info from the DB if it exists
             */
            $sql = "INSERT INTO `stock_docs`(`LPO`, `model`, `doc_type`, `devices`, `file_path`, `file_name`,doc_number, `upload_by`) VALUES (:LPO, :model, :doc_type, :devices, :file_path, :file_name,:doc_number, :upload_by)";

            try {
                $res = $this->query($sql, array("LPO" => $LPO, "model" => $model, "doc_type" => $DocType, "devices" => $devices, "file_path" => $DocNewName, "file_name" => $DocName, "doc_number" => $DocNumber, "upload_by" => $Requested_By));
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
        } else {
            echo '/The Documnet Number is already there';
            return FALSE;
        }//Found it 
    }

    /*     * *************************** add new HWP payment devices *********** */

    public function addNewHWPDoc() {
        /*
         * Fails if the proper action was not submitted
         */
// echo "<script>alert('".$_POST['input-username']."')</script>";
        if ($_POST['action'] != 'stock_AddHWPDoc') {
            return "Invalid action supplied for process Add new HWP Doc.";
        }


        $Requested_By = htmlentities($_SESSION['user']['email'], ENT_QUOTES);

        $output_dir = realpath(dirname(__FILE__) . '/../..') . "/public/assets/comm/fileuplaod_temp/";
        $LPO = htmlentities($_POST['select-order-LPO'], ENT_QUOTES);
        //   $model = htmlentities($_POST['select-order-model'], ENT_QUOTES); // we don't need to use router as invoice may contain more than lpo
//$devices = htmlentities(implode(',', $_POST['select-devices']), ENT_QUOTES);
//$DocType = htmlentities($_POST['select-order-DocType'], ENT_QUOTES);
//$DocName = htmlentities($_FILES["file_doc"]["name"], ENT_QUOTES);
        $DocNumber = htmlentities($_POST['txt-doc-number'], ENT_QUOTES); //documnet number 
        $m_docamount = htmlentities($_POST['txt-order-m_docamount'], ENT_QUOTES); //documnet number 
        //   $AuthCode = htmlentities($_POST['txt-doc-AuthCode'], ENT_QUOTES); //Do un update function for this 
        $DocNewName = '';
        $found = FALSE;

        $tb_name = "stock_HWP_invoice";

        //  $cond = ' doc_number=:doc_number or auth_code=:auth_code';
        $cond = ' doc_number=:doc_number ';
        // $arr_val = array("doc_number" => $DocNumber, "auth_code" => $AuthCode);
        $arr_val = array("doc_number" => $DocNumber);
        $found = $this->stock_docExist($tb_name, $cond, $arr_val);

//var_dump($found);
//print_r($_POST);die;

        if (!$found) {// if this is new invoice with a new Auth Code
            if (isset($_FILES["file_HWPdoc"])) {
                $ret = array();

                $error = $_FILES["file_HWPdoc"]["error"];
//You need to handle  both cases
//If Any browser does not support serializing of multiple files using FormData() 
                if (!is_array($_FILES["file_HWPdoc"]["name"])) { //single file
                    $temp = explode(".", $_FILES["file_HWPdoc"]["name"]);
                    $newfilename = microtime(true) . '.' . end($temp);
//$fileName = $_FILES["myfile"]["name"];
                    move_uploaded_file($_FILES["file_HWPdoc"]["tmp_name"], $output_dir . $newfilename);
                    $ret[] = $newfilename;
                    $DocNewName = $newfilename;
//  echo $newfilename;
                } else {  //Multiple files, file[]
                    $fileCount = count($_FILES["file_HWPdoc"]["name"]);
                    for ($i = 0; $i < $fileCount; $i++) {
                        $fileName = $_FILES["file_HWPdoc"]["name"][$i];
                        move_uploaded_file($_FILES["file_HWPdoc"]["tmp_name"][$i], $output_dir . $fileName);
                        $ret[] = $fileName;
                    }
                }

//   var_dump($ret);
//echo json_encode($ret);
            }

            $c = -1;
            $_sql = array();
            $_srInserted = array();

            try {


                $file = fopen($output_dir . $DocNewName, "r") or die("error open file " . $DocNewName);

                while (!feof($file)) {
                    $filesop = array();
                    $filesop = fgetcsv($file);
                    $c++;
                    if ($c == 0) {

                        continue;
                    }

                    $router_sr = htmlentities(str_replace(' ', '', strtolower(trim($filesop[0])))); //LPO Numbers


                    if (trim($router_sr) == '' || trim($router_sr) == NULL) {

                        continue;
                    }




//INSERT INTO `stock_order`(`cear_id`, `router_model`, `description`, `po_qty`, `customer_name`, `req_by`, `Req_date`, `vendor`, `po_id`, `po_date`, `EDD`)
//CEAR # //cear_id	Item Name //router_model	Description//description	PO Quantity//po_qty	Customer Name//customer_name	Requested By//req_by	ORIGINATION DATE//Req_date	Vendor//vendor	PO #//po_id	PO DATE//po_date	EDD//EDD
                    if ($this->stock_docExist("stock_stockdata", " device_Serial=:device_Serial and hw_paid=1", array("device_Serial" => $router_sr))) {

                        $_srInserted[] = $router_sr;
                        continue;
                    }



                    $user = $_SESSION['user']['email'];

                    if (($router_sr != '' and ! (is_null($router_sr)) and isset($router_sr))) {

                        $sql = "UPDATE `stock_stockdata` SET hw_paid=1,updated_by=:updated_by ,update_time=CURRENT_TIMESTAMP where device_Serial=:device_Serial";
                        // `LPO`, `model`,`doc_number`, `                                      auth_code`, `manual_amount`, `add_by`
                        $_sql[] = '("' . $LPO . '", "' . $DocNumber . '","' . $m_docamount . '", "' . $router_sr . '", "' . $Requested_By . '")';
                        $countRows = $this->query($sql, array("device_Serial" => $router_sr, "updated_by" => $Requested_By));
//var_dump($countRows);die;
                        if ($countRows > 0) {

// $this->alertObj->setAlert('UPDATE', 'cnoc_activities', $id, 'Activity ID  :' . $id . ' Edit values :' + implode(', ', array("id" => $id, "reason" => $activity_reason, "Act_date" => $activity_date, "rel_Team" => $realted_team, "closeact" => $close_action, "status" => $activity_status)));
                            //  $this->log->logActions($_SESSION['user']['email'], 'Edit Doc', 'Success Edit', 'Doc ID  :' . $docID . ' Edit values :' + implode(', ', array("upload_by" => $uname, "verify_status" => $verify_status, "doc_id" => $docID)));
                            //  return true; //$_SESSION['user']['email'].'<a href="javascript:;" class="btn unpick-activity"  data-id="'.$id.'">unPick It</a>';;
                        } else {
                            //  return FALSE;
                            $this->log->logActions($_SESSION['user']['email'], 'Edit HW Paid', 'Faild Edit', 'Device ID  :' . $router_sr);
                        }
                    }
                }
                fclose($file);
                @unlink($output_dir . $DocNewName);

                if (!empty($_sql) && count($_sql) > 0) {

                    $sql = "INSERT INTO `stock_hwp_invoice`(`LPO`,`doc_number`, `manual_amount`,device_sr, `add_by`) VALUES " . implode(',', $_sql);
                    ;

                    try {
                        //  unlink($output_dir . $DocNewName);
                        $res = $this->query($sql); //, array("LPO" => $LPO, "model" => $model, "manual_amount" => $m_docamount, "auth_code" => $AuthCode, "doc_number" => $DocNumber, "add_by" => $Requested_By)
                    } catch (Exception $e) {
// $this->db=null;
                        //   unlink($output_dir . $DocNewName);
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
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }

            print "devices updated";
            if (sizeof($_srInserted) > 0) {
                print 'Serials already Paid Before :<br/>' . implode(' || ', $_srInserted);
            }





            //  print_r($DocNewName);die;
        } else {
            echo 'The Invoice is there or the AUTH code is already there';
            return FALSE;
        }//Found it 
//$upOne = realpath(dirname(__FILE__) . '/../..');
//print(__FILE__);
//print_r($_FILES);
//   print_r($_FILES["file_doc"]["name"]);
    }

    /*     * *************************** add new RFS Certificate devices *********** */

    public function addNewRFSCert() {
        /*
         * Fails if the proper action was not submitted
         */
// echo "<script>alert('".$_POST['input-username']."')</script>";
        if ($_POST['action'] != 'stock_AddRFSCert') {
            return "Invalid action supplied for process Add new RFS Cert Doc.";
        }


        $Requested_By = htmlentities($_SESSION['user']['email'], ENT_QUOTES);

        $output_dir = realpath(dirname(__FILE__) . '/../..') . "/public/assets/comm/fileuplaod_temp/";
        // $LPO = htmlentities($_POST['select-order-LPO'], ENT_QUOTES);
        //   $model = htmlentities($_POST['select-order-model'], ENT_QUOTES); // we don't need to use router as invoice may contain more than lpo
//$devices = htmlentities(implode(',', $_POST['select-devices']), ENT_QUOTES);
//$DocType = htmlentities($_POST['select-order-DocType'], ENT_QUOTES);
//$DocName = htmlentities($_FILES["file_doc"]["name"], ENT_QUOTES);
        $DocNumber = htmlentities($_POST['txt-doc-number'], ENT_QUOTES); //Cert  number 
        //   $AuthCode = htmlentities($_POST['txt-doc-AuthCode'], ENT_QUOTES); //Do un update function for this 
        $DocNewName = '';
        $found = FALSE;

        $tb_name = "stock_rfs_data";

        //  $cond = ' doc_number=:doc_number or auth_code=:auth_code';
        $cond = ' RFS_cert=:doc_number ';
        // $arr_val = array("doc_number" => $DocNumber, "auth_code" => $AuthCode);
        $arr_val = array("doc_number" => $DocNumber);
        $found = $this->stock_docExist($tb_name, $cond, $arr_val);

//var_dump($found);
//print_r($_POST);die;

        if (!$found) {// if this is new invoice with a new Auth Code
            if (isset($_FILES["file_HWPdoc"])) {
                $ret = array();

                $error = $_FILES["file_HWPdoc"]["error"];
//You need to handle  both cases
//If Any browser does not support serializing of multiple files using FormData() 
                if (!is_array($_FILES["file_HWPdoc"]["name"])) { //single file
                    $temp = explode(".", $_FILES["file_HWPdoc"]["name"]);
                    $newfilename = microtime(true) . '.' . end($temp);
//$fileName = $_FILES["myfile"]["name"];
                    move_uploaded_file($_FILES["file_HWPdoc"]["tmp_name"], $output_dir . $newfilename);
                    $ret[] = $newfilename;
                    $DocNewName = $newfilename;
//  echo $newfilename;
                } else {  //Multiple files, file[]
                    $fileCount = count($_FILES["file_HWPdoc"]["name"]);
                    for ($i = 0; $i < $fileCount; $i++) {
                        $fileName = $_FILES["file_HWPdoc"]["name"][$i];
                        move_uploaded_file($_FILES["file_HWPdoc"]["tmp_name"][$i], $output_dir . $fileName);
                        $ret[] = $fileName;
                    }
                }

//   var_dump($ret);
//echo json_encode($ret);
            }

            $c = -1;
            $_sql = array();
            $_srInserted = array();

            try {


                $file = fopen($output_dir . $DocNewName, "r") or die("error open file " . $DocNewName);

                while (!feof($file)) {
                    $filesop = array();
                    $filesop = fgetcsv($file);
                    $c++;

                    if ($c == 0) {

                        continue;
                    }
                    //echo strtolower(trim($filesop[0]));Die;

                    $router_sr = htmlentities(str_replace(' ', '', strtolower(trim($filesop[0])))); //LPO Numbers


                    if (trim($router_sr) == '' || trim($router_sr) == NULL) {

                        continue;
                    }




//INSERT INTO `stock_order`(`cear_id`, `router_model`, `description`, `po_qty`, `customer_name`, `req_by`, `Req_date`, `vendor`, `po_id`, `po_date`, `EDD`)
//CEAR # //cear_id	Item Name //router_model	Description//description	PO Quantity//po_qty	Customer Name//customer_name	Requested By//req_by	ORIGINATION DATE//Req_date	Vendor//vendor	PO #//po_id	PO DATE//po_date	EDD//EDD
                    if ($this->stock_docExist("stock_rfs_data", " devices_serial=:device_Serial and RFS_cert is not null", array("device_Serial" => $router_sr))) {

                        $_srInserted[] = $router_sr;
                        continue;
                    }



                    $user = $_SESSION['user']['email'];

                    if (($router_sr != '' and ! (is_null($router_sr)) and isset($router_sr))) {

                        $sql = "UPDATE `stock_rfs_data` SET RFS_cert=:RFS_cert,add_cert_by=:updated_by where devices_serial=:device_Serial";
                        // `LPO`, `model`,`doc_number`, `                                      auth_code`, `manual_amount`, `add_by`
                        // $_sql[] = '("' . $LPO . '", "' . $DocNumber . '","' . $m_docamount . '", "' . $router_sr . '", "' . $Requested_By . '")';
                        $countRows = $this->query($sql, array("RFS_cert" => $DocNumber, "device_Serial" => $router_sr, "updated_by" => $Requested_By));
//var_dump($countRows);die;
                        if ($countRows > 0) {

// $this->alertObj->setAlert('UPDATE', 'cnoc_activities', $id, 'Activity ID  :' . $id . ' Edit values :' + implode(', ', array("id" => $id, "reason" => $activity_reason, "Act_date" => $activity_date, "rel_Team" => $realted_team, "closeact" => $close_action, "status" => $activity_status)));
                            //  $this->log->logActions($_SESSION['user']['email'], 'Edit Doc', 'Success Edit', 'Doc ID  :' . $docID . ' Edit values :' + implode(', ', array("upload_by" => $uname, "verify_status" => $verify_status, "doc_id" => $docID)));
                            //  return true; //$_SESSION['user']['email'].'<a href="javascript:;" class="btn unpick-activity"  data-id="'.$id.'">unPick It</a>';;
                        } else {
                            //  return FALSE;
                            $this->log->logActions($_SESSION['user']['email'], 'Edit RFS Certificate', 'Faild Edit', 'Device ID  :' . $router_sr);
                        }
                    }
                }
                fclose($file);
                @unlink($output_dir . $DocNewName);
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }

            //print "devices updated";
            if (sizeof($_srInserted) > 0) {
                print 'devices updated But there are Serials already has Certificate Before :<br/>' . implode(' || ', $_srInserted);
                return TRUE;
            } else {
                return TRUE;
            }






            //  print_r($DocNewName);die;
        } else {
            echo 'The Certificate number is already there';
            return FALSE;
        }//Found it 
//$upOne = realpath(dirname(__FILE__) . '/../..');
//print(__FILE__);
//print_r($_FILES);
//   print_r($_FILES["file_doc"]["name"]);
    }

    public function addNewPACCert() {
        /*
         * Fails if the proper action was not submitted
         */
// echo "<script>alert('".$_POST['input-username']."')</script>";
        if ($_POST['action'] != 'stock_AddPACCert') {
            return "Invalid action supplied for process Add new PAC Cert Doc.";
        }


        $Requested_By = htmlentities($_SESSION['user']['email'], ENT_QUOTES);

        $output_dir = realpath(dirname(__FILE__) . '/../..') . "/public/assets/comm/fileuplaod_temp/";
        // $LPO = htmlentities($_POST['select-order-LPO'], ENT_QUOTES);
        //   $model = htmlentities($_POST['select-order-model'], ENT_QUOTES); // we don't need to use router as invoice may contain more than lpo
//$devices = htmlentities(implode(',', $_POST['select-devices']), ENT_QUOTES);
//$DocType = htmlentities($_POST['select-order-DocType'], ENT_QUOTES);
//$DocName = htmlentities($_FILES["file_doc"]["name"], ENT_QUOTES);
        $DocNumber = htmlentities($_POST['txt-doc-number'], ENT_QUOTES); //Cert  number 
        //   $AuthCode = htmlentities($_POST['txt-doc-AuthCode'], ENT_QUOTES); //Do un update function for this 
        $DocNewName = '';
        $found = FALSE;

        $tb_name = "stock_pac_data";

        //  $cond = ' doc_number=:doc_number or auth_code=:auth_code';
        $cond = ' PAC_cert=:doc_number ';
        // $arr_val = array("doc_number" => $DocNumber, "auth_code" => $AuthCode);
        $arr_val = array("doc_number" => $DocNumber);
        $found = $this->stock_docExist($tb_name, $cond, $arr_val);

//var_dump($found);
//print_r($_POST);die;

        if (!$found) {// if this is new invoice with a new Auth Code
            if (isset($_FILES["file_HWPdoc"])) {
                $ret = array();

                $error = $_FILES["file_HWPdoc"]["error"];
//You need to handle  both cases
//If Any browser does not support serializing of multiple files using FormData() 
                if (!is_array($_FILES["file_HWPdoc"]["name"])) { //single file
                    $temp = explode(".", $_FILES["file_HWPdoc"]["name"]);
                    $newfilename = microtime(true) . '.' . end($temp);
//$fileName = $_FILES["myfile"]["name"];
                    move_uploaded_file($_FILES["file_HWPdoc"]["tmp_name"], $output_dir . $newfilename);
                    $ret[] = $newfilename;
                    $DocNewName = $newfilename;
//  echo $newfilename;
                } else {  //Multiple files, file[]
                    $fileCount = count($_FILES["file_HWPdoc"]["name"]);
                    for ($i = 0; $i < $fileCount; $i++) {
                        $fileName = $_FILES["file_HWPdoc"]["name"][$i];
                        move_uploaded_file($_FILES["file_HWPdoc"]["tmp_name"][$i], $output_dir . $fileName);
                        $ret[] = $fileName;
                    }
                }

//   var_dump($ret);
//echo json_encode($ret);
            }

            $c = -1;
            $_sql = array();
            $_srInserted = array();

            try {


                $file = fopen($output_dir . $DocNewName, "r") or die("error open file " . $DocNewName);

                while (!feof($file)) {
                    $filesop = array();
                    $filesop = fgetcsv($file);
                    $c++;
                    if ($c == 0) {

                        continue;
                    }

                    $router_sr = htmlentities(str_replace(' ', '', strtolower(trim($filesop[0])))); //LPO Numbers


                    if (trim($router_sr) == '' || trim($router_sr) == NULL) {

                        continue;
                    }




//INSERT INTO `stock_order`(`cear_id`, `router_model`, `description`, `po_qty`, `customer_name`, `req_by`, `Req_date`, `vendor`, `po_id`, `po_date`, `EDD`)
//CEAR # //cear_id	Item Name //router_model	Description//description	PO Quantity//po_qty	Customer Name//customer_name	Requested By//req_by	ORIGINATION DATE//Req_date	Vendor//vendor	PO #//po_id	PO DATE//po_date	EDD//EDD
                    if ($this->stock_docExist("stock_pac_data", " devices_serial=:device_Serial and PAC_cert is not null", array("device_Serial" => $router_sr))) {

                        $_srInserted[] = $router_sr;
                        continue;
                    }



                    $user = $_SESSION['user']['email'];

                    if (($router_sr != '' and ! (is_null($router_sr)) and isset($router_sr))) {

                        $sql = "UPDATE `stock_pac_data` SET PAC_cert=:PAC_cert,add_cert_by=:updated_by where devices_serial=:device_Serial";
                        // `LPO`, `model`,`doc_number`, `                                      auth_code`, `manual_amount`, `add_by`
                        // $_sql[] = '("' . $LPO . '", "' . $DocNumber . '","' . $m_docamount . '", "' . $router_sr . '", "' . $Requested_By . '")';
                        $countRows = $this->query($sql, array("PAC_cert" => $DocNumber, "device_Serial" => $router_sr, "updated_by" => $Requested_By));
//var_dump($countRows);die;
                        if ($countRows > 0) {

// $this->alertObj->setAlert('UPDATE', 'cnoc_activities', $id, 'Activity ID  :' . $id . ' Edit values :' + implode(', ', array("id" => $id, "reason" => $activity_reason, "Act_date" => $activity_date, "rel_Team" => $realted_team, "closeact" => $close_action, "status" => $activity_status)));
                            //  $this->log->logActions($_SESSION['user']['email'], 'Edit Doc', 'Success Edit', 'Doc ID  :' . $docID . ' Edit values :' + implode(', ', array("upload_by" => $uname, "verify_status" => $verify_status, "doc_id" => $docID)));
                            //  return true; //$_SESSION['user']['email'].'<a href="javascript:;" class="btn unpick-activity"  data-id="'.$id.'">unPick It</a>';;
                        } else {
                            //  return FALSE;
                            $this->log->logActions($_SESSION['user']['email'], 'Edit PAC Certificate', 'Faild Edit', 'Device ID  :' . $router_sr);
                        }
                    }
                }
                fclose($file);
                @unlink($output_dir . $DocNewName);
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }

            //print "devices updated";
            if (sizeof($_srInserted) > 0) {
                print 'PAC devices updated But there are Serials already has Certificate Before :<br/>' . implode(' || ', $_srInserted) . "<br/>";
                return TRUE;
            } else {
                return TRUE;
            }






            //  print_r($DocNewName);die;
        } else {
            echo 'The PAC Certificate number is already there';
            return FALSE;
        }//Found it 
//$upOne = realpath(dirname(__FILE__) . '/../..');
//print(__FILE__);
//print_r($_FILES);
//   print_r($_FILES["file_doc"]["name"]);
    }

    /*     * *************************** add new HWP payment devices *********** */

    public function addNewRFSPDoc() {
        /*
         * Fails if the proper action was not submitted
         */
// echo "<script>alert('".$_POST['input-username']."')</script>";
        if ($_POST['action'] != 'stock_AddRFSPDoc') {
            return "Invalid action supplied for process Add new RFSP Doc.";
        }


        $Requested_By = htmlentities($_SESSION['user']['email'], ENT_QUOTES);

        $output_dir = realpath(dirname(__FILE__) . '/../..') . "/public/assets/comm/fileuplaod_temp/";
        $LPO = 'noneed'; //htmlentities($_POST['select-order-RFSLPO'], ENT_QUOTES);
        //    $model = htmlentities($_POST['select-order-RFSmodel'], ENT_QUOTES);//we reomved it as we don't need it 
        $RFSReqNum = 'noneed'; // htmlentities($_POST['select-order-RFSReqNum'], ENT_QUOTES);
//$devices = htmlentities(implode(',', $_POST['select-devices']), ENT_QUOTES);
//$DocType = htmlentities($_POST['select-order-DocType'], ENT_QUOTES);
//$DocName = htmlentities($_FILES["file_doc"]["name"], ENT_QUOTES);
        $DocNumber = htmlentities($_POST['txt-doc-number'], ENT_QUOTES); //documnet number 
        $RFSCertNumber = NULL; // htmlentities($_POST['txt-doc-RFSCertnumber'], ENT_QUOTES); //documnet number 
        $m_docamount = htmlentities($_POST['txt-order-m_docamount'], ENT_QUOTES); //documnet number 
        //  $AuthCode = htmlentities($_POST['txt-doc-AuthCode'], ENT_QUOTES); //we will add it later
        $DocNewName = '';
        $found = FALSE;

        $tb_name = "stock_RFSP_invoice";

        $cond = ' doc_number=:doc_number ';
        $arr_val = array("doc_number" => $DocNumber,);
        $found = $this->stock_docExist($tb_name, $cond, $arr_val);

//var_dump($found);
//print_r($_POST);die;

        if (!$found) {// if this is new invoice with a new Auth Code
            if (isset($_FILES["file_HWPdoc"])) {
                $ret = array();

                $error = $_FILES["file_HWPdoc"]["error"];
//You need to handle  both cases
//If Any browser does not support serializing of multiple files using FormData() 
                if (!is_array($_FILES["file_HWPdoc"]["name"])) { //single file
                    $temp = explode(".", $_FILES["file_HWPdoc"]["name"]);
                    $newfilename = microtime(true) . '.' . end($temp);
//$fileName = $_FILES["myfile"]["name"];
                    move_uploaded_file($_FILES["file_HWPdoc"]["tmp_name"], $output_dir . $newfilename);
                    $ret[] = $newfilename;
                    $DocNewName = $newfilename;
//  echo $newfilename;
                } else {  //Multiple files, file[]
                    $fileCount = count($_FILES["file_HWPdoc"]["name"]);
                    for ($i = 0; $i < $fileCount; $i++) {
                        $fileName = $_FILES["file_HWPdoc"]["name"][$i];
                        move_uploaded_file($_FILES["file_HWPdoc"]["tmp_name"][$i], $output_dir . $fileName);
                        $ret[] = $fileName;
                    }
                }

//   var_dump($ret);
//echo json_encode($ret);
            }

            $c = -1;
            $_sql = array();
            $_srInserted = array();
            $_srexist = array();
            $_srRfs = array();
            $_srRfsCert = array();
            $errormsg = '';

            try {


                $file = fopen($output_dir . $DocNewName, "r") or die("error open file " . $DocNewName);

                while (!feof($file)) {
                    $filesop = array();
                    $filesop = fgetcsv($file);
                    $c++;
                    if ($c == 0) {

                        continue;
                    }

                    $router_sr = htmlentities(str_replace(' ', '', strtolower(trim($filesop[0])))); //LPO Numbers


                    if (trim($router_sr) == '' || trim($router_sr) == NULL) {

                        continue;
                    }




//INSERT INTO `stock_order`(`cear_id`, `router_model`, `description`, `po_qty`, `customer_name`, `req_by`, `Req_date`, `vendor`, `po_id`, `po_date`, `EDD`)
//CEAR # //cear_id	Item Name //router_model	Description//description	PO Quantity//po_qty	Customer Name//customer_name	Requested By//req_by	ORIGINATION DATE//Req_date	Vendor//vendor	PO #//po_id	PO DATE//po_date	EDD//EDD
                    //stop
                    if ($this->stock_docExist("stock_stockdata", " device_Serial=:device_Serial and rfs_paid=1", array("device_Serial" => $router_sr))) {

                        $_srInserted[] = $router_sr;
                        continue;
                    }
                    if (!$this->stock_docExist("stock_stockdata", " device_Serial=:device_Serial ", array("device_Serial" => $router_sr))) {// installed or not
                        $_srexist[] = $router_sr;
                        continue;
                    }

                    if (!$this->stock_docExist("stock_rfs_data", " devices_serial=:device_Serial", array("device_Serial" => $router_sr))) {//did not RFSed before
                        $_srRfs[] = $router_sr;
                        continue;
                    } elseif ($this->stock_docExist("stock_rfs_data", " devices_serial=:device_Serial and RFS_cert is null ", array("device_Serial" => $router_sr))) {//did not RFSed before
                        $_srRfsCert[] = $router_sr;
                        continue;
                    }



                    $user = $_SESSION['user']['email'];

                    if (($router_sr != '' and ! (is_null($router_sr)) and isset($router_sr))) {

                        $sql = "UPDATE `stock_stockdata` SET rfs_paid=1,updated_by=:updated_by ,update_time=CURRENT_TIMESTAMP where device_Serial=:device_Serial";
                        //`LPO`, `model`, `RFS_cert`, `RFS_req_num`,`doc_number`,                                            `auth_code`, `manual_amount`,device_sr, `add_by`
                        $_sql[] = '("' . $LPO . '", "' . $RFSCertNumber . '", "' . $RFSReqNum . '", "' . $DocNumber . '", "' . $m_docamount . '", "' . $router_sr . '", "' . $Requested_By . '")';
                        $countRows = $this->query($sql, array("device_Serial" => $router_sr, "updated_by" => $Requested_By));
//var_dump($countRows);die;
                        if ($countRows > 0) {

// $this->alertObj->setAlert('UPDATE', 'cnoc_activities', $id, 'Activity ID  :' . $id . ' Edit values :' + implode(', ', array("id" => $id, "reason" => $activity_reason, "Act_date" => $activity_date, "rel_Team" => $realted_team, "closeact" => $close_action, "status" => $activity_status)));
                            //  $this->log->logActions($_SESSION['user']['email'], 'Edit Doc', 'Success Edit', 'Doc ID  :' . $docID . ' Edit values :' + implode(', ', array("upload_by" => $uname, "verify_status" => $verify_status, "doc_id" => $docID)));
                            //  return true; //$_SESSION['user']['email'].'<a href="javascript:;" class="btn unpick-activity"  data-id="'.$id.'">unPick It</a>';;
                        } else {
                            //  return FALSE;
                            $this->log->logActions($_SESSION['user']['email'], 'Edit RFS Paid', 'Faild Edit', 'Device ID  :' . $router_sr);
                        }
                    }
                }
                fclose($file);

                @unlink($output_dir . $DocNewName);
                if (sizeof($_srexist) > 0) {
                    $errormsg .= '<br/><br/>Serials Did not delivered Yet :<br/>' . implode(' || ', $_srexist);
                }
                if (sizeof($_srInserted) > 0) {
                    $errormsg .= '<br/><br/>Serials already Paid Before :<br/>' . implode(' || ', $_srInserted);
                }
                if (sizeof($_srRfs) > 0) {
                    $errormsg .= '<br/><br/>Serials did RFS yet :<br/>' . implode(' || ', $_srRfs);
                }
                if (sizeof($_srRfsCert) > 0) {
                    $errormsg .= '<br/><br/>Serials RFS but without Certificate :<br/>' . implode(' || ', $_srRfsCert);
                }



                if (!empty($_sql) && count($_sql) > 0) {
                    //  $sql = "INSERT INTO `stock_rfsp_invoice`(`LPO`, `model`, `RFS_cert`, `RFS_req_num`,`doc_number`, `auth_code`, `manual_amount`,device_sr, `add_by`) VALUES ". implode(',', $_sql);
                    $sql = "INSERT INTO `stock_rfsp_invoice`(`LPO`,  `RFS_cert`, `RFS_req_num`,`doc_number`,  `manual_amount`,device_sr, `add_by`) VALUES " . implode(',', $_sql);

                    try {
                        //  unlink($output_dir . $DocNewName);
                        $res = $this->query($sql); //array("LPO" => $LPO, "model" => $model, "manual_amount" => $m_docamount, "auth_code" => $AuthCode, "doc_number" => $DocNumber, "add_by" => $Requested_By, "RFSCertNumber" => $RFSCertNumber, "RFSReqNum" => $RFSReqNum)
                    } catch (Exception $e) {
// $this->db=null;
                        //  unlink($output_dir . $DocNewName);
                        return ($e->getMessage());
                    }

                    /*
                     * Fails if username doesn't match a DB entry
                     */
                    if (!$res) {
                        $errormsg = 'false ' . $errormsg;
                        return $errormsg; //"Your username or password is invalid.";
                    } else {
                        $errormsg = 'true ' . $errormsg;
                        return $errormsg;
                    }
                } else {
                    $errormsg = '<u>RFS Payment Failed due to</u> <BR/> ' . $errormsg;
                    return $errormsg;
                }
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }

//            // print "devices updated";
//            if (sizeof($_srInserted) > 0) {
//                print 'Serials already Paid Before :<br/>' . implode(' || ', $_srInserted);
//            }
            //  print_r($DocNewName);die;
        } else {
            echo 'The Invoice is there or the AUTH code is already there';
            return FALSE;
        }//Found it 
//$upOne = realpath(dirname(__FILE__) . '/../..');
//print(__FILE__);
//print_r($_FILES);
//   print_r($_FILES["file_doc"]["name"]);
    }

    /*     * *************************** add new HWP payment devices *********** */

    public function addNewPACPDoc() {
        /*
         * Fails if the proper action was not submitted
         */
// echo "<script>alert('".$_POST['input-username']."')</script>";
        if ($_POST['action'] != 'stock_AddPACPDoc') {
            return "Invalid action supplied for process Add new PACP Doc.";
        }


        $Requested_By = htmlentities($_SESSION['user']['email'], ENT_QUOTES);

        $output_dir = realpath(dirname(__FILE__) . '/../..') . "/public/assets/comm/fileuplaod_temp/";
        $LPO = htmlentities($_POST['select-order-RFSLPO'], ENT_QUOTES);
        //   $model = htmlentities($_POST['select-order-RFSmodel'], ENT_QUOTES);
        // $RFSReqNum = htmlentities($_POST['select-order-RFSReqNum'], ENT_QUOTES);
        $PACReqNum = htmlentities($_POST['txt-doc-PACReqnumber'], ENT_QUOTES);

        $DocNumber = htmlentities($_POST['txt-doc-number'], ENT_QUOTES); //documnet number 
        $PACCertNumber = htmlentities($_POST['txt-doc-PACRefnumber'], ENT_QUOTES); //documnet number 
        $m_docamount = htmlentities($_POST['txt-order-m_docamount'], ENT_QUOTES); //documnet number 
        //   $AuthCode = htmlentities($_POST['txt-doc-AuthCode'], ENT_QUOTES); //documnet number 
        $DocNewName = '';
        $found = FALSE;

        $tb_name = "stock_PACP_invoice";

        //    $cond = ' doc_number=:doc_number or auth_code=:auth_code';
        $cond = ' doc_number=:doc_number ';
        // $arr_val = array("doc_number" => $DocNumber, "auth_code" => $AuthCode);
        $arr_val = array("doc_number" => $DocNumber);
        $found = $this->stock_docExist($tb_name, $cond, $arr_val);

//var_dump($found);
//print_r($_POST);die;

        if (!$found) {// if this is new invoice with a new Auth Code
            if (isset($_FILES["file_HWPdoc"])) {
                $ret = array();

                $error = $_FILES["file_HWPdoc"]["error"];
//You need to handle  both cases
//If Any browser does not support serializing of multiple files using FormData() 
                if (!is_array($_FILES["file_HWPdoc"]["name"])) { //single file
                    $temp = explode(".", $_FILES["file_HWPdoc"]["name"]);
                    $newfilename = microtime(true) . '.' . end($temp);
//$fileName = $_FILES["myfile"]["name"];
                    move_uploaded_file($_FILES["file_HWPdoc"]["tmp_name"], $output_dir . $newfilename);
                    $ret[] = $newfilename;
                    $DocNewName = $newfilename;
//  echo $newfilename;
                } else {  //Multiple files, file[]
                    $fileCount = count($_FILES["file_HWPdoc"]["name"]);
                    for ($i = 0; $i < $fileCount; $i++) {
                        $fileName = $_FILES["file_HWPdoc"]["name"][$i];
                        move_uploaded_file($_FILES["file_HWPdoc"]["tmp_name"][$i], $output_dir . $fileName);
                        $ret[] = $fileName;
                    }
                }

//   var_dump($ret);
//echo json_encode($ret);
            }

            $c = -1;
            $_sql = array();
            $_srInserted = array();

            try {


                $file = fopen($output_dir . $DocNewName, "r") or die("error open file " . $DocNewName);

                while (!feof($file)) {
                    $filesop = array();
                    $filesop = fgetcsv($file);
                    $c++;
                    if ($c == 0) {

                        continue;
                    }

                    $router_sr = htmlentities(str_replace(' ', '', strtolower(trim($filesop[0])))); //LPO Numbers


                    if (trim($router_sr) == '' || trim($router_sr) == NULL) {

                        continue;
                    }




//INSERT INTO `stock_order`(`cear_id`, `router_model`, `description`, `po_qty`, `customer_name`, `req_by`, `Req_date`, `vendor`, `po_id`, `po_date`, `EDD`)
//CEAR # //cear_id	Item Name //router_model	Description//description	PO Quantity//po_qty	Customer Name//customer_name	Requested By//req_by	ORIGINATION DATE//Req_date	Vendor//vendor	PO #//po_id	PO DATE//po_date	EDD//EDD
                    if ($this->stock_docExist("stock_stockdata", " device_Serial=:device_Serial and pac_paid=1", array("device_Serial" => $router_sr))) {

                        $_srInserted[] = $router_sr;
                        continue;
                    }



                    $user = $_SESSION['user']['email'];

                    if (($router_sr != '' and ! (is_null($router_sr)) and isset($router_sr))) {

                        $sql = "UPDATE `stock_stockdata` SET pac_paid=1,updated_by=:updated_by ,update_time=CURRENT_TIMESTAMP where device_Serial=:device_Serial";
                        //                                                                                                                                                                                                                                                                              `LPO`, `model`, `PAC_ref`, `PAC_req_num`, `RFS_req_num`, `doc_number`, `auth_code`, `manual_amount`, device_sr, `add_by`
                        $_sql[] = '("' . $LPO . '","' . $PACCertNumber . '", "' . $PACReqNum . '","' . $DocNumber . '", "' . $m_docamount . '", "' . $router_sr . '", "' . $Requested_By . '")';
                        //  print_r($_sql);

                        $countRows = $this->query($sql, array("device_Serial" => $router_sr, "updated_by" => $Requested_By));
//var_dump($countRows);die;
                        if ($countRows > 0) {

// $this->alertObj->setAlert('UPDATE', 'cnoc_activities', $id, 'Activity ID  :' . $id . ' Edit values :' + implode(', ', array("id" => $id, "reason" => $activity_reason, "Act_date" => $activity_date, "rel_Team" => $realted_team, "closeact" => $close_action, "status" => $activity_status)));
                            //  $this->log->logActions($_SESSION['user']['email'], 'Edit Doc', 'Success Edit', 'Doc ID  :' . $docID . ' Edit values :' + implode(', ', array("upload_by" => $uname, "verify_status" => $verify_status, "doc_id" => $docID)));
                            //  return true; //$_SESSION['user']['email'].'<a href="javascript:;" class="btn unpick-activity"  data-id="'.$id.'">unPick It</a>';;
                        } else {
                            //  return FALSE;
                            $this->log->logActions($_SESSION['user']['email'], 'Edit RFS Paid', 'Faild Edit', 'Device ID  :' . $router_sr);
                        }
                    }
                }
                fclose($file);

                @unlink($output_dir . $DocNewName);

                if (!empty($_sql) && count($_sql) > 0) {
                    $sql = "INSERT INTO `stock_pacp_invoice`(`LPO`,`PAC_ref`, `PAC_req_num`,`doc_number`, `manual_amount`,device_sr, `add_by`) VALUES " . implode(',', $_sql);


                    try {
                        //  @unlink($output_dir . $DocNewName);
                        $res = $this->query($sql); //, array("LPO" => $LPO, "model" => $model, "manual_amount" => $m_docamount, "auth_code" => $AuthCode, "doc_number" => $DocNumber, "add_by" => $Requested_By, "PAC_ref" => $PACCertNumber, "RFS_req_num" => $RFSReqNum, 'PAC_req_num' => $PACReqNum)
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
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }

            print "devices updated";
            if (sizeof($_srInserted) > 0) {
                print 'Serials already Paid Before :<br/>' . implode(' || ', $_srInserted);
            }





            //  print_r($DocNewName);die;
        } else {
            echo 'The Invoice is there  is already there';

            return FALSE;
        }//Found it 
    }

    /*     * *************************** add new HWP payment devices *********** */

    public function addNewQPDoc() {
        /*
         * Fails if the proper action was not submitted
         */
// echo "<script>alert('".$_POST['input-username']."')</script>";
        if ($_POST['action'] != 'stock_AddQPDoc') {
            return "Invalid action supplied for process Add new QP Doc.";
        }


        $Requested_By = htmlentities($_SESSION['user']['email'], ENT_QUOTES);

        $output_dir = realpath(dirname(__FILE__) . '/../..') . "/public/assets/comm/fileuplaod_temp/";
        $Q = htmlentities($_POST['select-invoice-q'], ENT_QUOTES);

        $DocNumber = htmlentities($_POST['txt-doc-number'], ENT_QUOTES); //documnet number 

        $m_docamount = htmlentities($_POST['txt-order-m_docamount'], ENT_QUOTES); //documnet number 
        //  $AuthCode = htmlentities($_POST['txt-doc-AuthCode'], ENT_QUOTES); //documnet number 
        $DocNewName = '';
        $found = FALSE;

        $tb_name = "stock_qp_invoice";

        // $cond = ' doc_number=:doc_number or auth_code=:auth_code';
        $cond = ' doc_number=:doc_number ';
        $arr_val = array("doc_number" => $DocNumber);
        $found = $this->stock_docExist($tb_name, $cond, $arr_val);

//var_dump($found);
//print_r($_POST);die;

        if (!$found) {// if this is new invoice with a new Auth Code
            if (isset($_FILES["file_HWPdoc"])) {
                $ret = array();

                $error = $_FILES["file_HWPdoc"]["error"];
//You need to handle  both cases
//If Any browser does not support serializing of multiple files using FormData() 
                if (!is_array($_FILES["file_HWPdoc"]["name"])) { //single file
                    $temp = explode(".", $_FILES["file_HWPdoc"]["name"]);
                    $newfilename = microtime(true) . '.' . end($temp);
//$fileName = $_FILES["myfile"]["name"];
                    move_uploaded_file($_FILES["file_HWPdoc"]["tmp_name"], $output_dir . $newfilename);
                    $ret[] = $newfilename;
                    $DocNewName = $newfilename;
//  echo $newfilename;
                } else {  //Multiple files, file[]
                    $fileCount = count($_FILES["file_HWPdoc"]["name"]);
                    for ($i = 0; $i < $fileCount; $i++) {
                        $fileName = $_FILES["file_HWPdoc"]["name"][$i];
                        move_uploaded_file($_FILES["file_HWPdoc"]["tmp_name"][$i], $output_dir . $fileName);
                        $ret[] = $fileName;
                    }
                }

//   var_dump($ret);
//echo json_encode($ret);
            }

            $c = -1;
            $_sql = array();
            $q_support = 'Q0';
            $_srInserted = array();
            switch ($Q) {
                case "Q1":
                    $q_support = "q1_support";


                    break;
                case "Q2":
                    $q_support = "q2_support";


                    break;
                case "Q3":
                    $q_support = "q3_support";


                    break;
                case "Q4":
                    $q_support = "q4_support";


                    break;
            }

            //q1_support



            try {


                $file = fopen($output_dir . $DocNewName, "r") or die("error open file " . $DocNewName);

                while (!feof($file)) {
                    $filesop = array();
                    $filesop = fgetcsv($file);
                    $c++;
                    if ($c == 0) {

                        continue;
                    }

                    $router_sr = htmlentities(str_replace(' ', '', strtolower(trim($filesop[0])))); //LPO Numbers


                    if (trim($router_sr) == '' || trim($router_sr) == NULL) {

                        continue;
                    }




//INSERT INTO `stock_order`(`cear_id`, `router_model`, `description`, `po_qty`, `customer_name`, `req_by`, `Req_date`, `vendor`, `po_id`, `po_date`, `EDD`)
//CEAR # //cear_id	Item Name //router_model	Description//description	PO Quantity//po_qty	Customer Name//customer_name	Requested By//req_by	ORIGINATION DATE//Req_date	Vendor//vendor	PO #//po_id	PO DATE//po_date	EDD//EDD
                    if ($this->stock_docExist("stock_stockdata", " device_Serial=:device_Serial and " . $q_support . "=1", array("device_Serial" => $router_sr))) {

                        $_srInserted[] = $router_sr;
                        continue;
                    }



                    $user = $_SESSION['user']['email'];

                    if (($router_sr != '' and ! (is_null($router_sr)) and isset($router_sr))) {

                        $sql = "UPDATE `stock_stockdata` SET " . $q_support . "=1,updated_by=:updated_by ,update_time=CURRENT_TIMESTAMP where device_Serial=:device_Serial";
                        //            ( `q_num`, `doc_number`, `auth_code`,  `manual_amount`, `device_sr`, `add_by`)                                                                                                                                                                                                                                                                  `LPO`, `model`, `PAC_ref`, `PAC_req_num`, `RFS_req_num`, `doc_number`, `auth_code`, `manual_amount`, device_sr, `add_by`
                        $_sql[] = '("' . $Q . '","' . $DocNumber . '", "' . $m_docamount . '", "' . $router_sr . '", "' . $Requested_By . '")';
                        //  print_r($_sql);

                        $countRows = $this->query($sql, array("device_Serial" => $router_sr, "updated_by" => $Requested_By));
//var_dump($countRows);die;
                        if ($countRows > 0) {

// $this->alertObj->setAlert('UPDATE', 'cnoc_activities', $id, 'Activity ID  :' . $id . ' Edit values :' + implode(', ', array("id" => $id, "reason" => $activity_reason, "Act_date" => $activity_date, "rel_Team" => $realted_team, "closeact" => $close_action, "status" => $activity_status)));
                            //  $this->log->logActions($_SESSION['user']['email'], 'Edit Doc', 'Success Edit', 'Doc ID  :' . $docID . ' Edit values :' + implode(', ', array("upload_by" => $uname, "verify_status" => $verify_status, "doc_id" => $docID)));
                            //  return true; //$_SESSION['user']['email'].'<a href="javascript:;" class="btn unpick-activity"  data-id="'.$id.'">unPick It</a>';;
                        } else {
                            //  return FALSE;
                            $this->log->logActions($_SESSION['user']['email'], 'Edit Q Paid', 'Faild Edit', 'Device ID  :' . $router_sr);
                        }
                    }
                }
                fclose($file);

                @unlink($output_dir . $DocNewName);

                if (!empty($_sql) && count($_sql) > 0) {
                    $sql = "INSERT INTO `stock_qp_invoice`(`q_num`, `doc_number`, `manual_amount`, `device_sr`, `add_by`) VALUES " . implode(',', $_sql);


                    try {
                        //  @unlink($output_dir . $DocNewName);
                        $res = $this->query($sql); //, array("LPO" => $LPO, "model" => $model, "manual_amount" => $m_docamount, "auth_code" => $AuthCode, "doc_number" => $DocNumber, "add_by" => $Requested_By, "PAC_ref" => $PACCertNumber, "RFS_req_num" => $RFSReqNum, 'PAC_req_num' => $PACReqNum)
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
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }

            print "devices updated";
            if (sizeof($_srInserted) > 0) {
                print 'Serials already Paid Before :<br/>' . implode(' || ', $_srInserted);
            }





            //  print_r($DocNewName);die;
        } else {
            echo 'The Invoice is there  is already there';

            return FALSE;
        }//Found it 
    }

    /*     * *************************** add new HWP payment devices *********** */

    public function addNewQrenewPDoc() {// Connection data (server_address, database, name, poassword)
        if ($_POST['action'] != 'stock_AddQrnewPDoc') {
            return "Invalid action supplied for Rew support bulkupload.";
        }


        $Requested_By = htmlentities($_SESSION['user']['email'], ENT_QUOTES);

        $output_dir = realpath(dirname(__FILE__) . '/../..') . "/public/assets/comm/fileuplaod_temp/";
        $Q = htmlentities($_POST['select-invoice-q'], ENT_QUOTES);
        $year = htmlentities($_POST['select-invoice-year'], ENT_QUOTES);

        $DocNumber = htmlentities($_POST['txt-doc-number'], ENT_QUOTES); //documnet number 

        $m_docamount = htmlentities($_POST['txt-order-m_docamount'], ENT_QUOTES); //documnet number 
        $AuthCode = htmlentities($_POST['txt-doc-AuthCode'], ENT_QUOTES); //documnet number 
        $DocNewName = '';
        $found = FALSE;

        //  print_r($_POST);die;

        $tb_name = "stock_qp_renew_invoice";

        $cond = ' doc_number=:doc_number or auth_code=:auth_code';
        $arr_val = array("doc_number" => $DocNumber, "auth_code" => $AuthCode);
        $found = $this->stock_docExist($tb_name, $cond, $arr_val);

        $Array = '';

        if (!$found) {// if this is new invoice with a new Auth Code
            if (isset($_FILES["file_HWPdoc"])) {

                $ret = array();

                $error = $_FILES["file_HWPdoc"]["error"];
//You need to handle  both cases
//If Any browser does not support serializing of multiple files using FormData() 
                if (!is_array($_FILES["file_HWPdoc"]["name"])) { //single file
                    $temp = explode(".", $_FILES["file_HWPdoc"]["name"]);
                    $newfilename = microtime(true) . '.' . end($temp);
//$fileName = $_FILES["myfile"]["name"];
                    move_uploaded_file($_FILES["file_HWPdoc"]["tmp_name"], $output_dir . $newfilename);
                    $ret[] = $newfilename;
                    $DocNewName = $newfilename;


//  echo $newfilename;
                } else {  //Multiple files, file[]
                    $fileCount = count($_FILES["file_HWPdoc"]["name"]);
                    for ($i = 0; $i < $fileCount; $i++) {
                        $fileName = $_FILES["file_HWPdoc"]["name"][$i];
                        move_uploaded_file($_FILES["file_HWPdoc"]["tmp_name"][$i], $output_dir . $fileName);
                        $ret[] = $fileName;
                    }
                }

//   var_dump($ret);
//echo json_encode($ret);
            }
            $Array = $this->analyse_file($output_dir . $DocNewName, 100);
            $fName = $output_dir . $DocNewName;
            // print_r($Array);die;
            // print_r($Array);
// print(trim($Array['delimiter']['value']));
//        $row = 1;
//if (($handle = fopen($fName, "r")) !== FALSE) {
//    while (($data = fgetcsv($handle, 1000, $Array['delimiter']['value'])) !== FALSE) {
//        $num = count($data);
//        echo "<p> $num fields in line $row: <br /></p>\n";
//        $row++;
//        for ($c=0; $c < $num; $c++) {
//            echo $data[$c] . "<br />\n";
//        }
//    }
//    fclose($handle);
//}
//die;


            $c = -1;
            $_sql = array();
            $q_support = 'Q0';
            $_srInserted = array();


            try {

                $c = -1;
                $_sql = array();
                $file = fopen($output_dir . $DocNewName, "r") or die("error open file " . $DocNewName);




                $row = 1;
                if (($handle = fopen($output_dir . $DocNewName, "r")) !== FALSE) {
                    while (($filesop = fgetcsv($handle, 1000, $Array['delimiter']['value'])) !== FALSE) {
                        $num = count($filesop);

                        $row++;
                        $c++;
                        if ($c == 0) {

                            continue;
                        }
                        $hostname = htmlentities(str_replace(' ', '', strtolower(trim($filesop[0])))); //LPO Numbers
                        $cust_name = htmlentities(str_replace(' ', '', strtolower(trim($filesop[1])))); //LPO Numbers
                        $account_number = htmlentities(str_replace(' ', '', strtolower(trim($filesop[2])))); //LPO Numbers
                        $account_status = htmlentities(str_replace(' ', '', strtolower(trim($filesop[3])))); //LPO Numbers
                        $model = htmlentities(str_replace(' ', '', strtolower(trim($filesop[4])))); //LPO Numbers
                        $router_sr = htmlentities(str_replace(' ', '', strtolower(trim($filesop[5])))); //LPO Numbers
                        $service = htmlentities(str_replace(' ', '', strtolower(trim($filesop[6])))); //LPO Numbers
                        $sla = htmlentities(str_replace(' ', '', strtolower(trim($filesop[7])))); //LPO Numbers
                        $location = htmlentities(str_replace(' ', '', strtolower(trim($filesop[8])))); //LPO Numbers
                        $Device_type = htmlentities(str_replace(' ', '', strtolower(trim($filesop[10])))); //LPO Numbers
                        $rfs_date = ($filesop[9] == null or $filesop[9] == '') ? null : date("Y-m-d", strtotime(str_replace('/', '-', $filesop[9]))); // strtolower(str_replace(' ', '', trim($filesop[4])));
                        $SuportEnrollment_date = '';
                        //  print_r($rfs_date);
                        $d = date_parse_from_format("Y-m-d", $rfs_date);
                        $d["month"];
                        switch ($d["month"]) {
                            case 1:
                            case 2:
                            case 3:
                                // Prints something like: 2006-04-05T01:02:03+00:00
                                $SuportEnrollment_date = ( date('Y-m-d', mktime(0, 0, 0, 4, 1, $d["year"])));
                                break;

                            case 4:
                            case 5:
                            case 6:
                                // Prints something like: 2006-04-05T01:02:03+00:00
                                $SuportEnrollment_date = ( date('Y-m-d', mktime(0, 0, 0, 7, 1, $d["year"])));
                                break;
                            case 7:
                            case 8:
                            case 9:
                                // Prints something like: 2006-04-05T01:02:03+00:00
                                $SuportEnrollment_date = ( date('Y-m-d', mktime(0, 0, 0, 10, 1, $d["year"])));
                                break;
                            case 10:
                            case 11:
                            case 12:
                                // Prints something like: 2006-04-05T01:02:03+00:00
                                $SuportEnrollment_date = ( date('Y-m-d', mktime(0, 0, 0, 1, 1, $d["year"] + 1)));


                                break;

                            default:
                                break;
                        }


                        if (trim($router_sr) == '' || trim($router_sr) == NULL) {

                            continue;
                        }

                        if ($this->stock_docExist("stock_qp_renew_invoice", " device_sr=:device_Serial and `year`=:year and q_num=:q_num", array("device_Serial" => $router_sr, "year" => $year, "q_num" => $Q))) {

                            $_srInserted[] = $router_sr;
                            continue;
                        }



                        $user = $_SESSION['user']['email'];

                        if (($router_sr != '' and ! (is_null($router_sr)) and isset($router_sr))) {
//                                `q_num`, `year`, `doc_number`, `auth_code`, `manual_amount`, `device_sr`, `host_name`, `mss_accountNum`, `Account_status`, `model`, `service`, `SLA`, `cust_name`, `loaction`, `RFS_date`, `enroll_date`, `add_by`
                            $_sql[] = '("' . $Q . '","' . $year . '", "' . $DocNumber . '", "' . $AuthCode . '", "' . $m_docamount . '", "' . $router_sr . '", "' . $hostname . '", "' . $account_number . '", "' . $account_status . '", "' . $model . '", "' . $service . '", "' . $sla . '", "' . $cust_name . '", "' . $location . '", "' . $rfs_date . '", "' . $SuportEnrollment_date . '", "' . $Requested_By . '")';
                        }
//   print_r($_sql);
                    }
                    fclose($file);

                    @unlink($output_dir . $DocNewName);
                }
//die;


                if (!empty($_sql) && count($_sql) > 0) {
                    $sql = "INSERT INTO `stock_qp_renew_invoice`(`q_num`, `year`, `doc_number`, `auth_code`, `manual_amount`, `device_sr`, `host_name`, `mss_accountNum`, `Account_status`, `model`, `service`, `SLA`, `cust_name`, `loaction`, `RFS_date`, `enroll_date`, `add_by`) VALUES " . implode(',', $_sql);


                    try {
                        //  @unlink($output_dir . $DocNewName);
                        $res = $this->query($sql); //, array("LPO" => $LPO, "model" => $model, "manual_amount" => $m_docamount, "auth_code" => $AuthCode, "doc_number" => $DocNumber, "add_by" => $Requested_By, "PAC_ref" => $PACCertNumber, "RFS_req_num" => $RFSReqNum, 'PAC_req_num' => $PACReqNum)
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
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }

            print "Support Added updated";
            if (sizeof($_srInserted) > 0) {
                print 'Serials already Paid Before :<br/>' . implode(' || ', $_srInserted);
            }





            //  print_r($DocNewName);die;
        } else {
            echo 'The Invoice is there or the AUTH code is already there';

            return FALSE;
        }//Found it 
    }

    /*     * **********************biuld available  table ******************************* */

    public function biuldavailableStokcTable($cust = 'etisalat') {// Connection data (server_address, database, name, poassword)
        $condit = '';
        $from = '';
        if ($cust == 'order') {
            $cust = 'order';
            $from = 'avilable_stock';
// $condit = ' `avilable_stock`.`Order QTY` >`avilable_stock`.`total Stock QTY`  ';
            $condit = 1;
        } else if ($cust == 'etisalat') {
            $cust = 'etisalat';
            $from = 'avilable_stock_router';
            $condit = 'lower(`RouterClass`)=lower(\'Standard\') and `' . $from . '`.`Order QTY` >0 ';
        } else {
            $cust = 'etisalat';
            $from = 'avilable_stock_router';
            $condit = 'lower(`RouterClass`)=lower(\'Non standard\') and `' . $from . '`.`Order QTY` >0 ';
        }
        $sql = "SELECT * FROM " . $from . " where " . $condit . "  ORDER BY `" . $from . "`.`Order QTY` ASC";
        $html = "";
//var_dump($sql);
        $result = array();
        try {
//, array('cust' => $cust)
            $result = $this->query($sql);
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
												<th>Order Id</th>
												<th>Model</th>';

        if ($cust == 'order') {
            $html .= '<th>LPO</th><th>Classification</th>
                                                                                                <th>Vendor</th>';
        }
        $html .= '<th>Customer Name</th>
                                                                                                <th>Order QTY</th>
                                                                                                <th>Stock QTY</th>
												<th>Totla Installed</th>
                                                                                                <th>Totla Available</th>
												
												
												
												
											</tr>
										</thead>
										<tbody>';
        $src = '';
        if ($cust == 'order') {
            $src = 'order';
        } else {
            $src = 'stock';
        }
        foreach ($result as $row) {
//`Model`, `LPO`, `Vendor`, `Customer Name`, `totalQTY`, `TotlaInstalled`

            $html .= '<tr class="odd gradeX getsubrouter" data-txt="' . $row['Model'] . '|||' . $row['LPO'] . '|||' . $src . '"   style="cursor:pointer;"><td>' . $row['orderID'] . '</td>
                
												
												<td>' . $row['Model'] . '</td>';
            if ($cust == 'order') {
                $html .= '<td>' . $row['LPO'] . '</td><td>' . $row['RouterClass'] . '</td> 
                                                                                                        <td>' . $row['Vendor'] . '</td>';
            }
            $html .= '<td>' . $row['Customer Name'] . '</td>
                                                                                                              <td>' . $row['Order QTY'] . '</td>
                                                                                                            <td>' . $row['total Stock QTY'] . '</td>
                                                                                                                <td><span class="label label-important">' . $row['TotlaInstalled'] . '</span></td>
												<td><span class="label label-success">' . ($row['total Stock QTY'] - $row['TotlaInstalled']) . '</span></td>
                                                                                                   
												';

            $html .= '</tr>';


// echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }




        $html .= '</tbody></table>';

        return $html;
    }

    public function build_invoice($fitler = null) {
        $html = '';
        $msg = '';
        $title = '';
        $header1 = '  <table cellpadding="0" cellspacing="0">
            
            
   
            <tr class="heading">
                <td>';

        $header2 = ' </td>
                
                <td>
                    <span class="invoice-result" id="searchky"></span>
                </td>
            </tr>
            
           
            
        </table>';

        //  print_r($_POST);die;
        switch (htmlspecialchars($_POST['select-invoice-filterName'])) {
            case "LPO":
                $html = $this->biuldordrTableInvoice(" LPO='" . htmlspecialchars($_POST['txt-invoice-invoice_filterValue']) . "'");
                $html .= $header1;
                $html .= 'Invoices Information ';
                $html .= $header2;
                $html .= $this->biuldinvoiceTableInvoice(" po_id='" . htmlspecialchars($_POST['txt-invoice-invoice_filterValue']) . "'");

                $html .= $header1;
                $html .= 'Devices Status Per Model ';
                $html .= $header2;
                $html .= $this->biuldinv_countTableInvoice(" LPO='" . htmlspecialchars($_POST['txt-invoice-invoice_filterValue']) . "'");
                //$html .= $header1;
                //$html .= ' Devices Per Model Details2';
                //$html .= $header2;
                // $html .= $this->biuldinv_HWTableInvoice(" LPO='" . htmlspecialchars($_POST['txt-invoice-invoice_filterValue']) . "' and (`hw_paid`=1 or `rfs_paid`=1 or `pac_paid`=1 or `q1_support`=1 or `q2_support`=1 or `q3_support`=1 or `q4_support`=1)");

                $html .= $header1;
                $html .= ' Devices Per Model Details';
                $html .= $header2;
                $html .= $this->biuldinv_HWTableInvoice(" LPO='" . htmlspecialchars($_POST['txt-invoice-invoice_filterValue']) . "' and (`hw_paid`=1 or `rfs_paid`=1 or `pac_paid`=1 or `q1_support`=1 or `q2_support`=1 or `q3_support`=1 or `q4_support`=1)");

                $html .= $header1;
                $html .= ' H/W Invoices Per Model';
                $html .= $header2;
                $html .= $this->biuldinv_hwpTableInvoice(" stock_hwp_invoice.LPO='" . htmlspecialchars($_POST['txt-invoice-invoice_filterValue']) . "' GROUP BY
    stock_hwp_invoice.LPO,
    stock_hwp_invoice.model , stock_hwp_invoice.doc_number");


                $html .= $header1;
                $html .= ' RFS Invoices Per Model';
                $html .= $header2;
                $html .= $this->biuldinv_rfspTableInvoice(" invoice_order_device_v.LPO='" . htmlspecialchars($_POST['txt-invoice-invoice_filterValue']) . "' GROUP BY
    stock_rfsp_invoice.LPO,
    stock_rfsp_invoice.model ,stock_rfsp_invoice.doc_number");



                $html .= $header1;
                $html .= ' PAC Invoices Per Model';
                $html .= $header2;
                $html .= $this->biuldinv_pacpTableInvoice(" stock_pacp_invoice.LPO='" . htmlspecialchars($_POST['txt-invoice-invoice_filterValue']) . "' GROUP BY
    stock_pacp_invoice.LPO,
    stock_pacp_invoice.model ,stock_pacp_invoice.doc_number");



                $html .= $header1;
                $html .= ' All Docs Related TO This';
                $html .= $header2;
                $html .= $this->biuldInvoiceDocTable(" where LPO='" . htmlspecialchars($_POST['txt-invoice-invoice_filterValue']) . "' ");

                break;
            case "docx":
                $html = $this->biuldinvoiceTableInvoice(" LPO='" . htmlspecialchars($_POST['txt-invoice-invoice_filterValue']) . "'");


                break;

            case "docx":
                $html = $this->biuldinvoiceTableInvoice(" LPO='" . htmlspecialchars($_POST['txt-invoice-invoice_filterValue']) . "'");

                break;

            default:
                break;
        }


        echo $html;
    }

    public function biuldordrTableInvoice($condit = 1) {// Connection data (server_address, database, name, poassword)
        // $condit = '';
        $from = '';


        $cust = 'order';
        $from = 'avilable_stock';
// $condit = ' `avilable_stock`.`Order QTY` >`avilable_stock`.`total Stock QTY`  ';


        $sql = "SELECT * FROM " . $from . " where " . $condit . "  ORDER BY `" . $from . "`.`Order QTY` ASC";


        $html = "";
//var_dump($sql);
        $result = array();
        try {
//, array('cust' => $cust)
            $result = $this->query($sql);
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
											
												<th>Model</th>';

        if ($cust == 'order') {
            $html .= '<th>LPO</th>
                                                                                                <th>Vendor</th>';
        }
        $html .= '<th>Customer Name</th>
                                                                                                <th>Order QTY</th>
                                                                                                <th>Stock QTY</th>
												<th>Totla Installed</th>
                                                                                                <th>Totla Available</th>
												
												
												
												
											</tr>
										</thead>
										<tbody>';
        $src = '';
        if ($cust == 'order') {
            $src = 'order';
        } else {
            $src = 'stock';
        }
        foreach ($result as $row) {
//`Model`, `LPO`, `Vendor`, `Customer Name`, `totalQTY`, `TotlaInstalled`

            $html .= '<tr class="odd gradeX getsubrouter" data-txt="' . $row['Model'] . '|||' . $row['LPO'] . '|||' . $src . '"   style="cursor:pointer;">
                
												
												<td>' . $row['Model'] . '</td>';
            if ($cust == 'order') {
                $html .= '<td>' . $row['LPO'] . '</td>
                                                                                                        <td>' . $row['Vendor'] . '</td>';
            }
            $html .= '<td>' . $row['Customer Name'] . '</td>
                                                                                                              <td>' . $row['Order QTY'] . '</td>
                                                                                                            <td>' . $row['total Stock QTY'] . '</td>
                                                                                                                <td><span class="label label-important">' . $row['TotlaInstalled'] . '</span></td>
												<td><span class="label label-success">' . ($row['total Stock QTY'] - $row['TotlaInstalled']) . '</span></td>
                                                                                                   
												';

            $html .= '</tr>';


// echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }




        $html .= '</tbody></table>';

        return $html;
    }

    public function biuldinvoiceTableInvoice($condit = 1) {// Connection data (server_address, database, name, poassword)
        // $condit = '';
        $from = '';


        $cust = 'order';
        $from = 'lpo_model_invoice_paid_v';
// $condit = ' `avilable_stock`.`Order QTY` >`avilable_stock`.`total Stock QTY`  ';


        $sql = "SELECT * FROM " . $from . " where " . $condit . " ";


        $html = "";
//var_dump($sql);
        $result = array();
        try {
//, array('cust' => $cust)
            $result = $this->query($sql);
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
											
												<th>Model</th>';


        $html .= '<th>LPO</th>
                                                                                                <th>H/W price</th>
<th>Invoiced hw/price</th>                                                                                                
';

        $html .= '<th>Install Charge</th>
                                                                                                <th>Support Charge</th>
                                                                                                <th>Total PO</th>
												<th>Total Invoiced</th>
                                                                                                <th>Total Balance</th>
												
												
												
												
											</tr>
										</thead>
										<tbody>';
        $src = '';
        if ($cust == 'order') {
            $src = 'order';
        } else {
            $src = 'stock';
        }

        //  var_dump($result);
        foreach ($result as $row) {
//`Model`, `LPO`, `Vendor`, `Customer Name`, `totalQTY`, `TotlaInstalled`
            //    print_r($row);die;

            $html .= '<tr class="odd gradeX getsubrouter" data-txt="' . $row['router_model'] . '|||' . $row['po_id'] . '|||' . $src . '"   style="cursor:pointer;">
                
												
												<td>' . $row['router_model'] . '</td>';

            $html .= '<td>' . $row['po_id'] . '</td>
                                                                                                        <td>' . floatval($row['hw_price']) . '</td>';

            $html .= '<td>' . floatval($row['hw_paid']) . '</td>
                                                                                                              <td>' . floatval($row['install_charge']) . '</td>
                                                                                                            <td>' . floatval($row['support_charge']) . '</td>
                                                                                                                <td>' . floatval(floatval($row['support_charge']) + floatval($row['install_charge']) + floatval($row['hw_price'])) . '</td>
                                                                                                                <td><span class="label label-success">' . floatval(floatval($row['hw_paid']) + floatval($row['rfs_paid']) + floatval($row['pac_paid']) + floatval($row['Q1_paid']) + floatval($row['Q2_paid']) + floatval($row['Q3_paid']) + floatval($row['Q4_paid'])) . '</span></td>
												<td><span class="label label-important">' . floatval(floatval(floatval($row['support_charge']) + floatval($row['install_charge']) + floatval($row['hw_price'])) - floatval(floatval($row['hw_paid']) + floatval($row['rfs_paid']) + floatval($row['pac_paid']) + floatval($row['Q1_paid']) + floatval($row['Q2_paid']) + floatval($row['Q3_paid']) + floatval($row['Q4_paid']))) . '</span></td>
                                                                                                   
												';

            $html .= '</tr>';


// echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }




        $html .= '</tbody></table>';

        return $html;
    }

    public function biuldinv_countTableInvoice($condit = 1) {// Connection data (server_address, database, name, poassword)
        // $condit = '';
        $from = '';


        $cust = 'order';
        $from = 'lpo_model_invoice_count_v';
// $condit = ' `avilable_stock`.`Order QTY` >`avilable_stock`.`total Stock QTY`  ';


        $sql = "SELECT * FROM " . $from . " where " . $condit . " ";


        $html = "";
//var_dump($sql);
        $result = array();
        try {
//, array('cust' => $cust)
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }


        /*
         * `fcst_id`, `party_id`, `account_number`, `customer_name`, `service`, `section`, `router_model`, `quantity`, `prob_of_closeing`, `req_by`, `req_time`
         */
//`Model`, `LPO`, `Vendor`, `Customer Name`, `totalQTY`, `TotlaInstalled`
//SELECT `LPO`, `Model`, `total_count`, `hw_paid`, `rfs_paid`, `pac_paid`, `q1_support`, `q2_support`, `q3_support`, `q4_support` FROM `lpo_model_invoice_count_v`
        $html = '<table class="table table-striped table-bordered" id="sample_1">
										<thead>
											<tr>
											
												<th>Model</th>';


        $html .= '<th>LPO</th>
                                                                                                <th>Total</th>
<th>Total H/W Paid</th>                                                                                                
';

        $html .= '<th>Total RFS</th>
                                                                                                <th>Total PAC </th>
                                                                                                <th>Total Q1 SUpported</th>
                                                                                                <th>Total Q2 SUpported</th>
                                                                                                <th>Total Q3 SUpported</th>
                                                                                                <th>Total Q4 SUpported</th>
												
												
												
												
											</tr>
										</thead>
										<tbody>';
        $src = '';
        if ($cust == 'order') {
            $src = 'order';
        } else {
            $src = 'stock';
        }

        //  var_dump($result);
        foreach ($result as $row) {
//`Model`, `LPO`, `Vendor`, `Customer Name`, `totalQTY`, `TotlaInstalled`
            //    print_r($row);die;
//////////////////////////////////////////////////////LPO`, `Model`, `total_count`, `hw_paid`, `rfs_paid`, `pac_paid`, `q1_support`
            $html .= '<tr class="odd gradeX getsubrouter" data-txt="' . $row['Model'] . '|||' . $row['LPO'] . '|||' . $src . '"   style="cursor:pointer;">
                
												
												<td>' . $row['Model'] . '</td>';

            $html .= '<td>' . $row['LPO'] . '</td>
                                                                                                        <td>' . floatval($row['total_count']) . '</td>';

            $html .= '<td>' . floatval($row['hw_paid']) . '</td>
                                                                                                              <td>' . floatval($row['rfs_paid']) . '</td>
                                                                                                            <td>' . floatval($row['pac_paid']) . '</td>
                                                                                                            <td>' . floatval($row['q1_support']) . '</td>
                                                                                                            <td>' . floatval($row['q2_support']) . '</td>
                                                                                                            <td>' . floatval($row['q3_support']) . '</td>
                                                                                                            <td>' . floatval($row['q4_support']) . '</td>
                                                                                                        
                                                                                                   
												';

            $html .= '</tr>';


// echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }




        $html .= '</tbody></table>';

        return $html;
    }

    public function biuldinv_HWTableInvoice($condit = 1) {// Connection data (server_address, database, name, poassword)
        // $condit = '';
        $from = '';


        $cust = 'order';
        $from = 'invoice_order_device_v';
// $condit = ' `avilable_stock`.`Order QTY` >`avilable_stock`.`total Stock QTY`  ';


        $sql = "SELECT * FROM " . $from . " where " . $condit . " ";
        // var_dump($sql);die;


        $html = "";
//var_dump($sql);
        $result = array();
        try {
//, array('cust' => $cust)
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }


        /*
         * `fcst_id`, `party_id`, `account_number`, `customer_name`, `service`, `section`, `router_model`, `quantity`, `prob_of_closeing`, `req_by`, `req_time`
         */
//`Model`, `LPO`, `Vendor`, `Customer Name`, `totalQTY`, `TotlaInstalled`
//SELECT//` `hw_paid`, `rfs_paid`, `pac_paid`, `q1_support`, `q2_support`, `q3_support`, `q4_support`,  `hw_price`, `hw_invoice`, `d_install_charge`, `rfs`

        $html = '<table class="table table-striped table-bordered" id="sample_1">
										<thead>
											<tr>
											
												<th>Model</th>';


        $html .= '<th>LPO</th>
                                                                                                <th>Device Serial</th>
<th>Total H/W Paid</th>                                                                                                
';

        $html .= '<th>RFS PAid</th>
                                                                                                <th>PAC Paid</th>
                                                                                                <th>Q1 SUpported</th>
                                                                                                <th>Q2 SUpported</th>
                                                                                                <th>Q3 SUpported</th>
                                                                                                <th>Q4 SUpported</th>
                                                                                                <th>H/W price</th>
                                                                                                <th>H/W Invoice</th>
                                                                                                <th>Install Charge</th>
                                                                                                <th>RFS</th>
												
												
												
												
											</tr>
										</thead>
										<tbody>';
        $src = '';
        if ($cust == 'order') {
            $src = 'order';
        } else {
            $src = 'stock';
        }

        //  var_dump($result);
        foreach ($result as $row) {
//`Model`, `LPO`, `Vendor`, `Customer Name`, `totalQTY`, `TotlaInstalled`
            //    print_r($row);die;
// `hw_price`, `hw_invoice`, `d_install_charge`, `rfs`
            $html .= '<tr class="odd gradeX getsubrouter" data-txt="' . $row['Model'] . '|||' . $row['LPO'] . '|||' . $src . '"   style="cursor:pointer;">
                
												
												<td>' . $row['Model'] . '</td>';

            $html .= '<td>' . $row['LPO'] . '</td>
                                                                                                        <td>' . ($row['device_Serial']) . '</td>';

            $html .= '<td>' . boolval($row['hw_paid']) . '</td>
                                                                                                              <td>' . boolval($row['rfs_paid']) . '</td>
                                                                                                            <td>' . boolval($row['pac_paid']) . '</td>
                                                                                                            <td>' . boolval($row['q1_support']) . '</td>
                                                                                                            <td>' . boolval($row['q2_support']) . '</td>
                                                                                                            <td>' . boolval($row['q3_support']) . '</td>
                                                                                                            <td>' . boolval($row['q4_support']) . '</td>
                                                                                                            <td>' . floatval($row['hw_price']) . '</td>
                                                                                                            <td>' . floatval($row['hw_invoice']) . '</td>
                                                                                                            <td>' . floatval($row['d_install_charge']) . '</td>
                                                                                                            <td>' . floatval($row['rfs']) . '</td>
                                                                                                        
                                                                                                   
												';

            $html .= '</tr>';


// echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }




        $html .= '</tbody></table>';

        return $html;
    }

    public function biuldinv_hwpTableInvoice($condit = 1) {// Connection data (server_address, database, name, poassword)
        // $condit = '';
        $from = '';
        /*
          SELECT DISTINCT
          `stock_hwp_invoice`.`LPO`,
          stock_hwp_invoice.`model`,
          stock_hwp_invoice.`doc_number`,
          stock_hwp_invoice.`auth_code`,
          stock_hwp_invoice.`manual_amount`,
          SUM(
          invoice_order_device_v.hw_invoice
          ) auto_amount
          FROM
          `stock_hwp_invoice`
          LEFT JOIN invoice_order_device_v ON invoice_order_device_v.device_Serial = stock_hwp_invoice.device_sr
          GROUP BY
          stock_hwp_invoice.LPO,
          stock_hwp_invoice.model
         */

        $cust = 'order';
        $from = 'stock_hwp_invoice LEFT JOIN invoice_order_device_v ON invoice_order_device_v.device_Serial = stock_hwp_invoice.device_sr ';
// $condit = ' `avilable_stock`.`Order QTY` >`avilable_stock`.`total Stock QTY`  ';


        $sql = "SELECT DISTINCT
    `stock_hwp_invoice`.`LPO`,
    stock_hwp_invoice.`model`,
    stock_hwp_invoice.`doc_number`,
    stock_hwp_invoice.`auth_code`,
    stock_hwp_invoice.`manual_amount`,
    SUM(
        invoice_order_device_v.hw_invoice
    ) auto_amount FROM " . $from . " where " . $condit . " ";


        $html = "";
//var_dump($sql);die;
        $result = array();
        try {
//, array('cust' => $cust)
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }


        /*
         * `fcst_id`, `party_id`, `account_number`, `customer_name`, `service`, `section`, `router_model`, `quantity`, `prob_of_closeing`, `req_by`, `req_time`
         */
//`Model`, `LPO`, `Vendor`, `Customer Name`, `totalQTY`, `TotlaInstalled`
//SELECT//` `hw_paid`, `rfs_paid`, `pac_paid`, `q1_support`, `q2_support`, `q3_support`, `q4_support`,  `hw_price`, `hw_invoice`, `d_install_charge`, `rfs`
        /*
         * `stock_hwp_invoice`.`LPO`,
          stock_hwp_invoice.`model`,
          stock_hwp_invoice.`doc_number`,
          stock_hwp_invoice.`auth_code`,
          stock_hwp_invoice.`manual_amount`,
          SUM(
          invoice_order_device_v.hw_invoice
          ) auto_amount
         */
        $html = '<table class="table table-striped table-bordered" id="sample_1">
										<thead>
											<tr>
											
												<th>Model</th>';


        $html .= '<th>LPO</th>
                                                                                                <th>Invoice Number</th>
<th>Auth Code</th>                                                                                                
';

        $html .= '<th>Manual Amount</th>
                                                                                                <th>System Amount</th>
                                                                                                
												
												
												
												
											</tr>
										</thead>
										<tbody>';
        $src = '';
        if ($cust == 'order') {
            $src = 'order';
        } else {
            $src = 'stock';
        }

        //  var_dump($result);
        foreach ($result as $row) {
//`Model`, `LPO`, `Vendor`, `Customer Name`, `totalQTY`, `TotlaInstalled`
            //    print_r($row);die;
// `hw_price`, `hw_invoice`, `d_install_charge`, `rfs`
            /*
             * `stock_hwp_invoice`.`LPO`,
              stock_hwp_invoice.`model`,
              stock_hwp_invoice.`doc_number`,
              stock_hwp_invoice.`auth_code`,
              stock_hwp_invoice.`manual_amount`,
              SUM(
              invoice_order_device_v.hw_invoice
              ) auto_amount
             */
            $html .= '<tr class="odd gradeX getsubrouter" data-txt="' . $row['model'] . '|||' . $row['LPO'] . '|||' . $src . '"   style="cursor:pointer;">
                
												
												<td>' . $row['model'] . '</td>';

            $html .= '<td>' . $row['LPO'] . '</td>
                                                                                                        <td>' . ($row['doc_number']) . '</td>';

            $html .= '<td>' . ($row['auth_code']) . '</td>
                                                                                                              <td>' . floatval($row['manual_amount']) . '</td>
                                                                                                            <td>' . floatval($row['auto_amount']) . '</td>
                                                                                                            
                                                                                                        
                                                                                                   
												';

            $html .= '</tr>';


// echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }




        $html .= '</tbody></table>';

        return $html;
    }

    public function biuldinv_rfspTableInvoice($condit = 1) {// Connection data (server_address, database, name, poassword)
        // $condit = '';
        $from = '';

        $cust = 'order';
        $from = '`stock_rfsp_invoice`
LEFT JOIN invoice_order_device_v ON invoice_order_device_v.device_Serial = stock_rfsp_invoice.device_sr left JOIN
stock_rfs_data
ON
stock_rfs_data.devices_serial=stock_rfsp_invoice.device_sr ';
// $condit = ' `avilable_stock`.`Order QTY` >`avilable_stock`.`total Stock QTY`  ';


        $sql = "SELECT DISTINCT
     stock_rfsp_invoice.`LPO`,
    stock_rfsp_invoice.`model`,
    stock_rfs_data.RFS_cert as `RFS_cert`,
    `stock_rfs_data`.`RFS_Request_Number` as `RFS_req_num`,
    stock_rfsp_invoice.`doc_number`,
    stock_rfsp_invoice.`auth_code`,
    stock_rfsp_invoice.`manual_amount`,
    SUM(invoice_order_device_v.rfs) auto_amount FROM " . $from . " where " . $condit . " ";


        $html = "";
//var_dump($sql);
        $result = array();
        try {
//, array('cust' => $cust)
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }


        /*
         * `fcst_id`, `party_id`, `account_number`, `customer_name`, `service`, `section`, `router_model`, `quantity`, `prob_of_closeing`, `req_by`, `req_time`
         */
//`Model`, `LPO`, `Vendor`, `Customer Name`, `totalQTY`, `TotlaInstalled`
//SELECT//` `hw_paid`, `rfs_paid`, `pac_paid`, `q1_support`, `q2_support`, `q3_support`, `q4_support`,  `hw_price`, `hw_invoice`, `d_install_charge`, `rfs`
        /*
         *    stock_rfsp_invoice.`LPO`,
          stock_rfsp_invoice.`model`,
          stock_rfsp_invoice.`RFS_cert`,
          stock_rfsp_invoice.`RFS_req_num`,
          stock_rfsp_invoice.`doc_number`,
          stock_rfsp_invoice.`auth_code`,
          stock_rfsp_invoice.`manual_amount`,
          SUM(invoice_order_device_v.rfs) auto_amount
         */
        $html = '<table class="table table-striped table-bordered" id="sample_1">
										<thead>
											<tr>
											
												<th>Model</th>';


        $html .= '<th>LPO</th>
                                                                                                <th>Invoice Number</th>
<th>Auth Code</th>                                                                                                
<th>RFS Cert</th>                                                                                                
<th>RFS Req Num</th>                                                                                                
';

        $html .= '<th>Manual Amount</th>
                                                                                                <th>System Amount</th>
                                                                                                
												
												
												
												
											</tr>
										</thead>
										<tbody>';
        $src = '';
        if ($cust == 'order') {
            $src = 'order';
        } else {
            $src = 'stock';
        }

        //  var_dump($result);
        foreach ($result as $row) {
//`Model`, `LPO`, `Vendor`, `Customer Name`, `totalQTY`, `TotlaInstalled`
            //    print_r($row);die;
// `hw_price`, `hw_invoice`, `d_install_charge`, `rfs`
            /*
             *  stock_rfsp_invoice.`LPO`,
              stock_rfsp_invoice.`model`,
              stock_rfsp_invoice.`RFS_cert`,
              stock_rfsp_invoice.`RFS_req_num`,
              stock_rfsp_invoice.`doc_number`,
              stock_rfsp_invoice.`auth_code`,
              stock_rfsp_invoice.`manual_amount`,
              SUM(invoice_order_device_v.rfs) auto_amount
             */
            $html .= '<tr class="odd gradeX getsubrouter" data-txt="' . $row['model'] . '|||' . $row['LPO'] . '|||' . $src . '"   style="cursor:pointer;">
                
												
												<td>' . $row['model'] . '</td>';

            $html .= '<td>' . $row['LPO'] . '</td>
                                                                                                        <td>' . ($row['doc_number']) . '</td>';

            $html .= '<td>' . ($row['auth_code']) . '</td>
                                                                                                              <td>' . ($row['RFS_cert']) . '</td>
                                                                                                              <td>' . ($row['RFS_req_num']) . '</td>
                                                                                                              <td>' . floatval($row['manual_amount']) . '</td>
                                                                                                            <td>' . floatval($row['auto_amount']) . '</td>
                                                                                                            
                                                                                                        
                                                                                                   
												';

            $html .= '</tr>';


// echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }




        $html .= '</tbody></table>';

        return $html;
    }

    public function biuldinv_pacpTableInvoice($condit = 1) {// Connection data (server_address, database, name, poassword)
        // $condit = '';
        $from = '';

        $cust = 'order';
        $from = '`stock_pacp_invoice`
LEFT JOIN invoice_order_device_v ON invoice_order_device_v.device_Serial = stock_pacp_invoice.device_sr ';
// $condit = ' `avilable_stock`.`Order QTY` >`avilable_stock`.`total Stock QTY`  ';


        $sql = "SELECT DISTINCT
     stock_pacp_invoice.`LPO`,
    stock_pacp_invoice.`model`,
    stock_pacp_invoice.`PAC_ref`,
    stock_pacp_invoice.`PAC_req_num`,
    stock_pacp_invoice.`RFS_req_num`,
    stock_pacp_invoice.`doc_number`,
    stock_pacp_invoice.`auth_code`,
    stock_pacp_invoice.`manual_amount`,
    SUM(
        invoice_order_device_v.hw_price
    ) / 4 auto_amount FROM " . $from . " where " . $condit . " ";


        $html = "";
//var_dump($sql);
        $result = array();
        try {
//, array('cust' => $cust)
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }


        /*
         * `fcst_id`, `party_id`, `account_number`, `customer_name`, `service`, `section`, `router_model`, `quantity`, `prob_of_closeing`, `req_by`, `req_time`
         */
//`Model`, `LPO`, `Vendor`, `Customer Name`, `totalQTY`, `TotlaInstalled`
//SELECT//` `hw_paid`, `rfs_paid`, `pac_paid`, `q1_support`, `q2_support`, `q3_support`, `q4_support`,  `hw_price`, `hw_invoice`, `d_install_charge`, `rfs`
        /*
         *   stock_pacp_invoice.`LPO`,
          stock_pacp_invoice.`model`,
          stock_pacp_invoice.`PAC_ref`,
          stock_pacp_invoice.`PAC_req_num`,
          stock_pacp_invoice.`RFS_req_num`,
          stock_pacp_invoice.`doc_number`,
          stock_pacp_invoice.`auth_code`,
          stock_pacp_invoice.`manual_amount`,
          SUM(
          invoice_order_device_v.hw_price
          ) / 4 auto_amount
         */
        $html = '<table class="table table-striped table-bordered" id="sample_1">
										<thead>
											<tr>
											
												<th>Model</th>';


        $html .= '<th>LPO</th>
                                                                                                <th>Invoice Number</th>
<th>Auth Code</th>                                                                                                
<th>PAC Ref</th>                                                                                                
<th>RFS Req Num</th>                                                                                                
<th>PAC Req Num</th>                                                                                                
';

        $html .= '<th>Manual Amount</th>
                                                                                                <th>System Amount</th>
                                                                                                
												
												
												
												
											</tr>
										</thead>
										<tbody>';
        $src = '';
        if ($cust == 'order') {
            $src = 'order';
        } else {
            $src = 'stock';
        }

        //  var_dump($result);
        foreach ($result as $row) {
//`Model`, `LPO`, `Vendor`, `Customer Name`, `totalQTY`, `TotlaInstalled`
            //    print_r($row);die;
// `hw_price`, `hw_invoice`, `d_install_charge`, `rfs`
            /*
             *  stock_pacp_invoice.`LPO`,
              stock_pacp_invoice.`model`,
              stock_pacp_invoice.`PAC_ref`,
              stock_pacp_invoice.`PAC_req_num`,
              stock_pacp_invoice.`RFS_req_num`,
              stock_pacp_invoice.`doc_number`,
              stock_pacp_invoice.`auth_code`,
              stock_pacp_invoice.`manual_amount`,
              SUM(
              invoice_order_device_v.hw_price
              ) / 4 auto_amount
             */
            $html .= '<tr class="odd gradeX getsubrouter" data-txt="' . $row['model'] . '|||' . $row['LPO'] . '|||' . $src . '"   style="cursor:pointer;">
                
												
												<td>' . $row['model'] . '</td>';

            $html .= '<td>' . $row['LPO'] . '</td>
                                                                                                        <td>' . ($row['doc_number']) . '</td>';

            $html .= '<td>' . ($row['auth_code']) . '</td>
                                                                                                              <td>' . ($row['PAC_ref']) . '</td>
                                                                                                              <td>' . ($row['RFS_req_num']) . '</td>
                                                                                                              <td>' . ($row['PAC_req_num']) . '</td>
                                                                                                              <td>' . floatval($row['manual_amount']) . '</td>
                                                                                                            <td>' . floatval($row['auto_amount']) . '</td>
                                                                                                            
                                                                                                        
                                                                                                   
												';

            $html .= '</tr>';


// echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }




        $html .= '</tbody></table>';

        return $html;
    }

    /*     * *********end available stokc Table ***************************** */
    /*     * **********************biuld routers  table ******************************* */

    public function biuldstockRouterTable($model = null, $lpo = null, $src = null) {// Connection data (server_address, database, name, poassword)
        $condit = '';
// var_dump($src);
        if ($model != null) {
            if ($src != 'stock') {
                $condit = ' where Model=lower(:model) and LPO=:lpo and device_Serial is not null ';
            } else {
                $condit = ' where Model=lower(:model) and device_Serial is not null ';
            }
        } else {
            $condit = ' where device_Serial is not null ';
        }
        $sql = "SELECT * FROM `routers_detailes` " . $condit . " ORDER BY `routers_detailes`.`installation_stat` DESC";
        $html = "";
// var_dump($sql);
        $result = array();
        try {
            if ($model != null) {
                if ($src != 'stock') {

                    $result = $this->query($sql, array('model' => $model, 'lpo' => $lpo));
                } else {
                    $result = $this->query($sql, array('model' => $model));
                }
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
                                                                                                <!--<th>Customer Name</th>-->
												<th>MARWAN ACC</th>
												<th>installation status</th>
												<th>Date time</th>
                                                                                                <th>Add By</th>';

        if (($_SESSION['teamName'] == 'MSS-Fulfullment' or $_SESSION['currentRole'] == 'Admin')or ( $_SESSION['teamName'] == 'supperAdmins')) {
            $html .= '<th>Action</th>';
        }

        $html .= '</tr>
										</thead>
										<tbody>';
        foreach ($result as $row) {
//`Model`, `LPO`, `Vendor`, `Customer Name`, `totalQTY`, `TotlaInstalled`
// `DeviceType`, `Model`, `device_Serial`, `Vendor`, `LPO`, `customerName`, `AddBy`, `AddDate`, `installation_stat`
            $html .= '<tr class="odd gradeX "  ><td></td>
                
												
												<td>' . $row['DeviceType'] . '</td>
                                                                                                    <td>' . $row['Model'] . '</td>
                                                                                                        <td>' . $row['device_Serial'] . '</td>
                                                                                                            <td>' . $row['customerName'] . '</td>
                                                                                                        <td>' . $row['Vendor'] . '</td>
                                                                                                            <td>' . $row['LPO'] . '</td>
                                                                                                               <!-- <td>' . $row['customerName'] . '</td>-->
<td>' . $row['MARWAN_acc'] . '</td>
												<td>' . ($row['installation_stat'] > 0 ? '<span class="label label-important">Installed</span> ' : '<span class="label label-success">Available</span>' ) . '</td>
                                                                                                    <td>' . $row['AddDate'] . '</td>
                                                                                                        <td>' . $row['AddBy'] . '</td>
                                                                                                   
												';
// if (($_SESSION['teamName'] == 'MSS-Fulfullment' and $_SESSION['currentRole'] == 'Admin') or ( $_SESSION['teamName'] == 'supperAdmins')) {
            if (( $_SESSION['currentRole'] == 'Admin') or ( $_SESSION['teamName'] == 'supperAdmins')) {
                $html .= '<td class="center">
												
													<a href="#" class="icon huge"><i class="icon-remove" id="removeStock__' . $row['stock_ID'] . '" data-d="input-id=' . $row['stock_ID'] . '&token=' . $_SESSION['token'] . '&action=Stock_Stock_delete"></i></a>&nbsp;		
												</td>';
            }

            $html .= '</tr>';


// echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }




        $html .= '</tbody></table>';

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


        if ((($_SESSION['teamName'] == 'Marketing' or $_SESSION['teamName'] == 'MSS-Fulfullment') and $_SESSION['currentRole'] == 'Admin')or ( $_SESSION['teamName'] == 'supperAdmins')) {
            $html .= '<th>Created By</th><th>Actions</th>';
        }
        $html .= '</tr>
										</thead>
										<tbody>';
        foreach ($result as $row) {

            $html .= '<tr class="odd gradeX">
												
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

            if ((($_SESSION['teamName'] == 'Marketing' or $_SESSION['teamName'] == 'MSS-Fulfullment') and $_SESSION['currentRole'] == 'Admin')or ( $_SESSION['teamName'] == 'supperAdmins')) {

                $html .= ' <td>' . $row['user_add'] . '</td>';

                $html .= '<td class="center">
													
                                                                                                             <a class="icon huge view-order" href="javascript:;" data-id="' . $row['orderID'] . '"><i class="icon-zoom-in"></i></a>&nbsp;
													<a href="#" class="icon huge"><i class="icon-remove" id="removeForecast_' . $row['orderID'] . '" data-d="input-id=' . $row['orderID'] . '&token=' . $_SESSION['token'] . '&action=Stock_order_delete"></i></a>&nbsp;		
												</td>';
            }
            $html .= '</tr>';


// echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }





        $html .= '</tbody></table>';
        return $html;
    }

    //build the installation  table
    public function biuldInstallationTable() {// Connection data (server_address, database, name, poassword)
        $sql = "SELECT si.inst_id,ssd.device_Serial,ssd.LPO,ssd.Model,`MARWAN_SR`,`MARWAN_acc`,`cust_name`,`Party_id`,`installation_date` FROM `stock_installation` si left JOIN stock_stockdata ssd ON ssd.stock_ID=si.stock_ref ORDER BY `si`.`installation_date` DESC ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }


        /*
         * inst_id ssd.device_Serial,ssd.LPO,ssd.LPO,`MARWAN_SR`,`MARWAN_acc`,`cust_name`,`Party_id`,`installation_date`	po_date	EDD	user_add

         */

//table table-condensed table-striped
        $html = '<table class="table table-condensed table-striped table-bordered" id="sample_1">
										<thead>
											<tr>
												<th></th>
												<th>device Serial</th>
												<th>PO#</th>
                                                                                                <th>Model</th>
												<th>MARWAN SR</th>
                                                                                                <th>MARWAN Acc</th>
												<th>cus. name</th>
                                                                                             <th>Party ID</th>
                                                                                                <th>Installation Date</th>
						
';


        $html .= '</tr>
										</thead>
										<tbody>';
        foreach ($result as $row) {
//inst_id ssd.device_Serial,ssd.LPO,ssd.LPO,`MARWAN_SR`,`MARWAN_acc`,`cust_name`,`Party_id`,`installation_date`
            $html .= '<tr class="odd gradeX">
												
												<td id="inst_id' . $row['inst_id'] . '">' . $row['inst_id'] . '</td>
                                                                                                    <td id="device_Serial_' . $row['inst_id'] . '">' . $row['device_Serial'] . '</td>
                                                                                                        <td id="LPO_' . $row['inst_id'] . '">' . $row['LPO'] . '</td>
                                                                                                            <td id="Model_' . $row['inst_id'] . '">' . $row['Model'] . '</td>
                                                                                                                <td id="MARWAN_SR_' . $row['inst_id'] . '">' . $row['MARWAN_SR'] . '</td>
												<td id="MARWAN_acc_' . $row['inst_id'] . '">' . $row['MARWAN_acc'] . '</td>
                                                                                                    <td id="cust_name_' . $row['inst_id'] . '">' . $row['cust_name'] . '</td>
                                                                                                        <td id="Party_id_' . $row['inst_id'] . '">' . $row['Party_id'] . '</td>
                                                                                                            <td id="installation_date_' . $row['inst_id'] . '">' . $row['installation_date'] . '</td>
                                                                                                               
												';

            $html .= '</tr>';


// echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }





        $html .= '</tbody></table>';
        return $html;
    }

    public function biuldRfsRequestsTable() {// Connection data (server_address, database, name, poassword)
        $sql = "select Distinct `RFS_id`, `LPO`, `model`, `devices_serial`, `host_name`, `RFS_date`, `enroll_date`, `account_status`, `contracutal_RFS_Date`, `RFS_Request_Number` from stock_rfs_data ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }


        /*
         * `RFS_id`, `LPO`, `model`, `devices_serial`, `host_name`, `RFS_date`, `enroll_date`, `account_status`, `contracutal_RFS_Date`, `RFS_Request_Number`	po_date	EDD	user_add
          RFS_id,devices_serial ,LPO,model,RFS_Request_Number,RFS_date,enroll_date,contracutal_RFS_Date,host_name,account_status
         */

//table table-condensed table-striped,
        $html = '<table class="table table-condensed table-striped table-bordered" id="sample_1">
										<thead>
											<tr>
												<th></th>
												<th>device Serial</th>
												<th>PO#</th>
                                                                                                <th>Model</th>
												<th>RFS Request Number</th>
                                                                                                <th>RFS Date</th>
												<th>Enroll Date</th>
                                                                                             <th>Contracutal Date</th>
                                                                                                <th>Host Name</th>
                                                                                                <th>Account Status</th>
						
';


        $html .= '</tr>
										</thead>
										<tbody>';
        foreach ($result as $row) {
//RFS_id,devices_serial ,LPO,model,RFS_Request_Number,RFS_date,enroll_date,contracutal_RFS_Date,host_name,account_status
            $html .= '<tr class="odd gradeX">												
			<td id="inst_id' . $row['RFS_id'] . '">' . $row['RFS_id'] . '</td>
                        <td id="device_Serial_' . $row['RFS_id'] . '">' . $row['devices_serial'] . '</td>
                        <td id="LPO_' . $row['RFS_id'] . '">' . $row['LPO'] . '</td>
                        <td id="Model_' . $row['RFS_id'] . '">' . $row['model'] . '</td>
                        <td id="MARWAN_SR_' . $row['RFS_id'] . '">' . $row['RFS_Request_Number'] . '</td>
			<td id="MARWAN_acc_' . $row['RFS_id'] . '">' . $row['RFS_date'] . '</td>
                        <td id="cust_name_' . $row['RFS_id'] . '">' . $row['enroll_date'] . '</td>
                        <td id="Party_id_' . $row['RFS_id'] . '">' . $row['contracutal_RFS_Date'] . '</td>
                        <td id="installation_date_' . $row['RFS_id'] . '">' . $row['host_name'] . '</td>
                         <td id="installation_date_' . $row['RFS_id'] . '">' . $row['account_status'] . '</td>
                                                                                                               
												';

            $html .= '</tr>';


// echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }





        $html .= '</tbody></table>';
        return $html;
    }

     public function biuldRfsDevicessTable() {// Connection data (server_address, database, name, poassword)
        $sql = "select Distinct  `devices_serial`, `host_name`, `RFS_date`, `enroll_date` from stock_rfs_devices_tbl ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }


        /*
         * `RFS_id`, `LPO`, `model`, `devices_serial`, `host_name`, `RFS_date`, `enroll_date`, `account_status`, `contracutal_RFS_Date`, `RFS_Request_Number`	po_date	EDD	user_add
          RFS_id,devices_serial ,LPO,model,RFS_Request_Number,RFS_date,enroll_date,contracutal_RFS_Date,host_name,account_status
         */

//table table-condensed table-striped,
        $html = '<table class="table table-condensed table-striped table-bordered" id="sample_1">
										<thead>
											<tr>
												<th></th>
												<th>device Serial</th>
												                                                                                              
                                                                                                <th>RFS Date</th>
												<th>Enroll Date</th>
                                                                                             
                                                                                                <th>Host Name</th>
                                                                                                
						
';


        $html .= '</tr>
										</thead>
										<tbody>';
        foreach ($result as $row) {
//RFS_id,devices_serial ,LPO,model,RFS_Request_Number,RFS_date,enroll_date,contracutal_RFS_Date,host_name,account_status
            $html .= '<tr class="odd gradeX">												
			
                        <td id="device_Serial_' . $row['devices_serial'] . '">' . $row['devices_serial'] . '</td>
                     
			<td id="MARWAN_acc_' . $row['devices_serial'] . '">' . $row['RFS_date'] . '</td>
                        <td id="cust_name_' . $row['devices_serial'] . '">' . $row['enroll_date'] . '</td>
                      
                        <td id="installation_date_' . $row['devices_serial'] . '">' . $row['host_name'] . '</td>
                        
                                                                                                               
												';

            $html .= '</tr>';


// echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }





        $html .= '</tbody></table>';
        return $html;
    }

    
    
    public function biuldPacRequestsTable() {// Connection data (server_address, database, name, poassword)
        $sql = "select Distinct `PAC_id`, `LPO`, `model`, `devices_serial`, `host_name`, `PAC_date`, `enroll_date`, `account_status`, `contracutal_PAC_Date`, `PAC_Request_Number` from stock_pac_data ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }


        /*
         * `RFS_id`, `LPO`, `model`, `devices_serial`, `host_name`, `RFS_date`, `enroll_date`, `account_status`, `contracutal_RFS_Date`, `RFS_Request_Number`	po_date	EDD	user_add
          RFS_id,devices_serial ,LPO,model,RFS_Request_Number,RFS_date,enroll_date,contracutal_RFS_Date,host_name,account_status
         */

//table table-condensed table-striped,
        $html = '<table class="table table-condensed table-striped table-bordered" id="sample_1">
										<thead>
											<tr>
												<th></th>
												<th>device Serial</th>
												<th>PO#</th>
                                                                                                <th>Model</th>
												<th>PAC Request Number</th>
                                                                                                <th>PAC Date</th>
												<th>Enroll Date</th>
                                                                                             <th>Contracutal Date</th>
                                                                                                <th>Host Name</th>
                                                                                                <th>Account Status</th>
						
';


        $html .= '</tr>
										</thead>
										<tbody>';
        foreach ($result as $row) {
//RFS_id,devices_serial ,LPO,model,RFS_Request_Number,RFS_date,enroll_date,contracutal_RFS_Date,host_name,account_status
            $html .= '<tr class="odd gradeX">												
			<td id="inst_id' . $row['PAC_id'] . '">' . $row['PAC_id'] . '</td>
                        <td id="device_Serial_' . $row['PAC_id'] . '">' . $row['devices_serial'] . '</td>
                        <td id="LPO_' . $row['PAC_id'] . '">' . $row['LPO'] . '</td>
                        <td id="Model_' . $row['PAC_id'] . '">' . $row['model'] . '</td>
                        <td id="MARWAN_SR_' . $row['PAC_id'] . '">' . $row['PAC_Request_Number'] . '</td>
			<td id="MARWAN_acc_' . $row['PAC_id'] . '">' . $row['PAC_date'] . '</td>
                        <td id="cust_name_' . $row['PAC_id'] . '">' . $row['enroll_date'] . '</td>
                        <td id="Party_id_' . $row['PAC_id'] . '">' . $row['contracutal_PAC_Date'] . '</td>
                        <td id="installation_date_' . $row['PAC_id'] . '">' . $row['host_name'] . '</td>
                         <td id="installation_date_' . $row['PAC_id'] . '">' . $row['account_status'] . '</td>
                                                                                                               
												';

            $html .= '</tr>';


// echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }





        $html .= '</tbody></table>';
        return $html;
    }

    /** get all user for table view
     *
     * @return mixed TRUE on success, message on error
     */
    public function biuldOrderDocTable($show = 'my', $cat = 'all') {// Connection data (server_address, database, name, poassword)
        $condit = '';
        $arr = array();

//print_r($cat);die;
        if ($show == 'my') {

            $condit = ' WHERE upload_by=:user ';
            $arr = array('user' => $_SESSION['user']['email']);
        } elseif ($show == 'all') {




            if ($cat != 'all') {
                $condit = " where verify_status ='" . $cat . "'";
            } else {
                
            }
        }

        /* if(isset($_REQUEST['show']) && $_REQUEST['show']=='all'){$condit=' ';}
          elseif(isset($_REQUEST['show']) && $_REQUEST['show']=='today'){$condit=" WHERE STR_TO_DATE(`activity_date`, '%d/%m/%Y')='".date('Y-m-d')."' ";}
          else {$condit=' WHERE assigned_to=:user or created_by=:user1 ';} */
//$sql = "SELECT `handover_id`, `SM_ticket`, `Cust_code`, `host_name`, `handover_Category`, `CMS/DKT`, `Last_update`, `close_action`, `create_time`, `complete_time`, `last_assign_time`, `created_by`, `assigned_to`, `handover_status`, `Closed_by`, `IM_Startdate` FROM `cnoc_handovers` " . $condit . " ORDER BY STR_TO_DATE(replace(IM_Startdate,'/',','),'%d,%m,%Y %T') ASC";
        $sql = "SELECT `doc_id`, `LPO`, `model`, `doc_type`, `devices`, `file_path`, `file_name`, `doc_number`, `upload_time`, `upload_by`, `verify_status`, `verify_by`, `verify_time` FROM `stock_docs` " . $condit . " ORDER BY LPO ASC";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql, $arr);
        } catch (PDOException $e) {

            return($e->getMessage());
        }


///public/assets/comm/uploads/stock/

        $html = '<table class="table table-striped table-bordered" id="sample_1">
										<thead>
											<tr>
												<th>LPO</th>
												<th>Model</th>
												<th>Doc Type</th>
                                                                                                <th>Devices</th>
                                                                                                <th>File Name</th>
												 <th>Doc Number</th>
                                                                                                <th>Upload By</th>
												<th>upload Time</th>
                                                                                                <th>verify status</th>
                                                                                                <th>verify by</th>
                                                                                                <th>verify Time</th>
                                                                                                
                                                                                                
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


            switch ($row['verify_status']) {
                case "Pending":
                    $statusLable = 'label-important';
                    break;
                case "In Progress":
                    $statusLable = 'label-info';
                    break;
                case "Completed":
                    $statusLable = 'label-success';
                    break;
                case "Rejected":
                    $statusLable = 'label-warning';
                    break;
                case "Rejected":
                    $statusLable = 'label-primary'; //FA0DFE
                    $MGM_esclat = 'bgcolor="#F0FFFF"';
                    break;

                case "Canceled":
                    $statusLable = ''; //#d3d3d3
                    break;
            }//set status color
//
//
            switch ($row['verify_status']) {
                case "Rejected":
                    $statusLable = 'label-important';
                    break;
                case "Accepted":
                    $statusLable = 'label-success';
                    break;
                case "Pending":
                    $statusLable = 'label-warning';
                    break;
                case "BGP Flap":
                    $statusLable = 'label-warning';
                    break;
                case "Canceled":
                    $statusLable = 'label-warning'; //#d3d3d3
                    break;
            }//set status color
//`doc_id`, `LPO`, `model`, `doc_type`, `devices`, `file_path`, `file_name`, `doc_number`, `upload_time`, `upload_by`, `verify_status`, `verify_by`, `verify_time`

            $html .= '<tr class="odd gradeX " data-txt="' . $row['doc_id'] . '" ' . '   style="cursor:pointer;"><td>' . $row['LPO'] . '</td>
               
												
												<td>' . $row['model'] . '</td>
                                                                                                    <td>' . $row['doc_type'] . '</td>
                                                                                                        <td style="word-wrap: break-word">' . str_replace(',', ',' . PHP_EOL, $row['devices']) . '</td>
<td><a href="assets/comm/uploads/stock/' . $row['file_path'] . '" >' . $row['file_name'] . '</a></td>
                                                                                                       <td>' . $row['doc_number'] . '</td>
                                                                                                       <td>' . $row['upload_by'] . '</td>
                                                                                                       <td>' . $row['upload_time'] . '</td>
                                                                                                        
                                                                                                                <td id=verify_status_' . $row['doc_id'] . '><span class="label ' . $statusLable . '">' . $row['verify_status'] . '</span></td>
												<td id=verifyLastUpdate_' . $row['doc_id'] . '>' . $row['verify_by'] . '</td>
												<td id=verifyLastUpdate_' . $row['doc_id'] . '>' . $row['verify_time'] . '</td>
                                                                                                  
                                                                               <td id=order_docaction_' . $row['doc_id'] . '>';

            if (isset($_SESSION['user']['email']) && ($_SESSION['user']['email'] == 'mohemara' or $_SESSION['user']['email'] == 'sojohn')) {
                $html .= '  <a class="icon huge stockdoc-verify" href="javascript:;" data-docstatus="Rejected" data-action="stock_updatestatusDoc" data-id="' . $row['doc_id'] . '"><i class="icon-remove-circle"></i></a>&nbsp;';
                $html .= '  <a class="icon huge stockdoc-verify" href="javascript:;" data-docstatus="Accepted" data-action="stock_updatestatusDoc" data-id="' . $row['doc_id'] . '"><i class="icon-ok-circle"></i></a>&nbsp;';

                if ((htmlentities($_SESSION['user']['email'], ENT_QUOTES)) == "mohemara") {
                    $html .= '<a href="javascript:;" class="icon huge delete-stockdoc" data-id="' . $row['doc_id'] . '" data-action="Stock_orderdoc_delete"><i class="icon-remove"></i></a>';
                }
            }
//if ($show == 'my' and $row['handover_status'] != 'Completed') {
//
//
//if (true) {//date('d-m-Y',strtotime(str_replace('/', '-',$row['activity_date'])))<= date('d-m-Y'))
//$html .= '<a class="icon huge view-handover" href="javascript:;" data-id="' . $row['handover_id'] . '"><i class="icon-zoom-in"></i></a>&nbsp;';
//}
//if((htmlentities($_SESSION['user']['email'], ENT_QUOTES)) != "dsm_r"){$html .= '<a href="javascript:;" class="icon huge delete-handover" data-id="' . $row['handover_id'] . '"><i class="icon-remove"></i></a>';
//}
//} elseif ($show == 'all' and ( ($_SESSION['currentRole'] == 'Admin')or ( $_SESSION['teamName'] == 'supperAdmins'))) {
//$html .= ' <a class="icon huge view-handover" href="javascript:;" data-id="' . $row['handover_id'] . '"><i class="icon-zoom-in"></i></a>&nbsp;';
//
//if ((htmlentities($_SESSION['user']['email'], ENT_QUOTES)) != "dsm_r") {
//$html .= '<a href="javascript:;" class="icon huge delete-handover" data-id="' . $row['handover_id'] . '"><i class="icon-remove"></i></a>';
//}
//} elseif ($show == 'today') {
//
//
//
//if ($assignedUser == $_SESSION['user']['email'] and $row['handover_status'] != 'Completed') {
//$html .= '  <a class="icon huge view-handover" href="javascript:;" data-id="' . $row['handover_id'] . '"><i class="icon-zoom-in"></i></a>&nbsp;';
//
//if((htmlentities($_SESSION['user']['email'], ENT_QUOTES)) != "dsm_r"){$html .= '<a href="javascript:;" class="icon huge delete-handover" data-id="' . $row['handover_id'] . '"><i class="icon-remove"></i></a>';
//}
//}
//}




            $html .= '</td></tr>';
        }




        $html .= '</tbody></table>';

        return $html;
    }

    public function biuldInvoiceDocTable($cond = ' where 1') {// Connection data (server_address, database, name, poassword)
        $condit = '';
        $arr = array();
        $sql = "SELECT `doc_id`, `LPO`, `model`, `doc_type`, `devices`, `file_path`, `file_name`, `doc_number`, `upload_time`, `upload_by`, `verify_status`, `verify_by`, `verify_time` FROM `stock_docs` " . $cond . " ORDER BY LPO ASC";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql, $arr);
        } catch (PDOException $e) {

            return($e->getMessage());
        }


///public/assets/comm/uploads/stock/

        $html = '<table class="table table-striped table-bordered" id="sample_1">
										<thead>
											<tr>
												<th>LPO</th>
												<th>Model</th>
												<th>Doc Type</th>
                                                                                                <th>Devices</th>
                                                                                                <th>File Name</th>
												 <th>Doc Number</th>
                                                                                                <th>Upload By</th>
												<th>upload Time</th>
                                                                                                <th>verify status</th>
                                                                                                <th>verify by</th>
                                                                                                <th>verify Time</th>
                                                                                                
												
												
												
												
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


            switch ($row['verify_status']) {
                case "Pending":
                    $statusLable = 'label-important';
                    break;
                case "In Progress":
                    $statusLable = 'label-info';
                    break;
                case "Completed":
                    $statusLable = 'label-success';
                    break;
                case "Rejected":
                    $statusLable = 'label-warning';
                    break;
                case "Rejected":
                    $statusLable = 'label-primary'; //FA0DFE
                    $MGM_esclat = 'bgcolor="#F0FFFF"';
                    break;

                case "Canceled":
                    $statusLable = ''; //#d3d3d3
                    break;
            }//set status color
//
//
            switch ($row['verify_status']) {
                case "Rejected":
                    $statusLable = 'label-important';
                    break;
                case "Accepted":
                    $statusLable = 'label-success';
                    break;
                case "Pending":
                    $statusLable = 'label-warning';
                    break;
                case "BGP Flap":
                    $statusLable = 'label-warning';
                    break;
                case "Canceled":
                    $statusLable = 'label-warning'; //#d3d3d3
                    break;
            }//set status color
//`doc_id`, `LPO`, `model`, `doc_type`, `devices`, `file_path`, `file_name`, `doc_number`, `upload_time`, `upload_by`, `verify_status`, `verify_by`, `verify_time`

            $html .= '<tr class="odd gradeX " data-txt="' . $row['doc_id'] . '" ' . '   style="cursor:pointer;"><td>' . $row['LPO'] . '</td>
               
												
												<td>' . $row['model'] . '</td>
                                                                                                    <td>' . $row['doc_type'] . '</td>
                                                                                                        <td style="word-wrap: break-word">' . str_replace(',', ',' . PHP_EOL, $row['devices']) . '</td>
<td><a href="assets/comm/uploads/stock/' . $row['file_path'] . '" >' . $row['file_name'] . '</a></td>
                                                                                                       <td>' . $row['doc_number'] . '</td>
                                                                                                       <td>' . $row['upload_by'] . '</td>
                                                                                                       <td>' . $row['upload_time'] . '</td>
                                                                                                        
                                                                                                                <td id=verify_status_' . $row['doc_id'] . '><span class="label ' . $statusLable . '">' . $row['verify_status'] . '</span></td>
												<td id=verifyLastUpdate_' . $row['doc_id'] . '>' . $row['verify_by'] . '</td>
												<td id=verifyLastUpdate_' . $row['doc_id'] . '>' . $row['verify_time'] . '</td>
                                                                                                  
                                                                               <td id=order_docaction_' . $row['doc_id'] . '>';






            $html .= '</td></tr>';
        }




        $html .= '</tbody></table>';

        return $html;
    }

    /*
     * Edit Stock Order Doc
     */

    public function stock_updateDocStatus() {

        // print_r($_POST);die;
//return $_POST['action'];
        if ($_POST['action'] != 'stock_updatestatusDoc') {
            return FALSE; //se;//"Invalid action supplied for forget password Form.";
        }
        $uname = htmlentities($_SESSION['user']['email'], ENT_QUOTES);
// $close_Time = htmlentities($_POST['select-cnoc-activityStatus'], ENT_QUOTES) == 'Completed' ? date('Y-m-d G:i:s') : NULL;




        $docID = htmlentities($_POST['docID'], ENT_QUOTES); //was set when click view Order Data To edit
        $verify_status = htmlentities($_POST['verify_status'], ENT_QUOTES); //was set when click view Order Data To edit
//$docID = htmlentities($_SESSION['Orderid_sess'], ENT_QUOTES); //was set when click view Order Data To edit
// $user_add = htmlentities($_POST['select-cnoc-activityReason'], ENT_QUOTES);
// $Create_time = htmlentities($_POST['select-cnoc-activityReason'], ENT_QUOTES);



        $sql = "update 
    `stock_docs`
	set verify_status=:verify_status,
        verify_by=:verify_by,
        verify_time=CURRENT_TIMESTAMP
WHERE 
	`doc_id`=:doc_id";
        try {

            $countRows = $this->query($sql, array("verify_by" => $uname, "verify_status" => $verify_status, "doc_id" => $docID));
//var_dump($countRows);die;
            if ($countRows > 0) {

// $this->alertObj->setAlert('UPDATE', 'cnoc_activities', $id, 'Activity ID  :' . $id . ' Edit values :' + implode(', ', array("id" => $id, "reason" => $activity_reason, "Act_date" => $activity_date, "rel_Team" => $realted_team, "closeact" => $close_action, "status" => $activity_status)));
                $this->log->logActions($_SESSION['user']['email'], 'Edit Doc', 'Success Edit', 'Doc ID  :' . $docID . ' Edit values :' + implode(', ', array("upload_by" => $uname, "verify_status" => $verify_status, "doc_id" => $docID)));

                return true; //$_SESSION['user']['email'].'<a href="javascript:;" class="btn unpick-activity"  data-id="'.$id.'">unPick It</a>';;
            } else {
                return FALSE;
            }
//success
        } catch (Exception $e) {

            $this->log->logActions($_SESSION['user']['email'], 'Edit Order', 'Faild Doc', 'Doc ID  :' . $docID . ' Edit values :' + implode(', ', array("upload_by" => $uname, "verify_status" => $verify_status, "doc_id" => $docID)));
            return (FALSE);
        }
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
        $Device_Type = htmlentities($_POST['txt-order-DeviceType'], ENT_QUOTES);
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
        `DeviceType`=:DeviceType,
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

            $countRows = $this->query($sql, array("customer_name" => $customer_name, "po_id" => $po_id, "vendor" => $vendor, "RouterClass" => $RouterClass, "DeviceType" => $Device_Type, "router_model" => $router_model, "description" => $description, "po_qty" => $po_qty, "cear_id" => $cear_id, "req_by" => $req_by, "Req_date" => $Req_date, "po_date" => $po_date, "EDD" => $EDD, "orderID" => $orderID));
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
        // $_SESSION['Orderid_sess'] = $serial;

        if ($_POST['action'] != 'Stock_getOrderDetails') {
            return false; //"Invalid action supplied for retrive Activity Data.";
        }
        $sql = 'SELECT 	`orderID`,`customer_name`,DeviceType,`po_id`,`vendor`,`RouterClass`,`router_model`, `description`,	`po_qty`,`cear_id`,`req_by`, 
	`Req_date`,	`po_date`,	`EDD`,	`user_add`,	`Create_time` FROM 
	`stock_order` 
WHERE 	`orderID` =:ser';
        $msg = '';
        try {

            $result = $this->row($sql, array('ser' => $serial));
            if ($result > 0) {
                $_SESSION['Orderid_sess'] = $serial;

// $msg= 'true ';// Items inserted Before'.implode(' || ',$_srInserted);//array("phone"=>'123-12313',"email"=>'test@test.com','city'=>'Medicine Hat','address'=>'556 19th Street NE')
                echo json_encode(array("orid" => $result['orderID'], "DevTyp" => $result['DeviceType'], "custname" => $result['customer_name'], "po" => $result['po_id'], "vendor" => $result['vendor'], "router_class" => $result['RouterClass'], "router_model" => $result['router_model'], "description" => $result['description'], "qty" => $result['po_qty'], "cear" => $result['cear_id'], "req_by" => $result['req_by'], "req_date" => $result['Req_date'], "po_date" => $result['po_date'], "edd" => $result['EDD'], "user_add" => $result['user_add'], "create_time" => $result['Create_time']));
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

    public function delete_stockdoc() {
        /*
         * Fails if the proper action was not submitted
         */
// echo "<script>alert('".$_POST['input-username']."')</script>";
        if ($_POST['action'] != 'Stock_orderdoc_delete') {
            return "Invalid action supplied for process Delete Order Doc.";
        }
        /*
         * Escapes the user input for security
         */
        $unid = htmlentities($_POST['doc-id'], ENT_QUOTES);


        /*
         * Retrieves the matching info from the DB if it exists
         */
        $sql = "DELETE FROM `stock_docs` WHERE `doc_id`=:id";
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


    /*     * *******************************************************
     * 
     * Delete Forecast provided by his ID 
     * ************************************************
     */

    public function invoice_delete() {
        /*
         * Fails if the proper action was not submitted
         */
// echo "<script>alert('".$_POST['input-username']."')</script>";
        if ($_POST['action'] != 'Stock_invoice_delete') {
            return "Invalid action supplied for process Delete invoice.";
        }
        /*
         * Escapes the user input for security
         */
        $unid = htmlentities($_POST['input-id'], ENT_QUOTES);


        /*
         * Retrieves the matching info from the DB if it exists
         */
        $sql = "DELETE FROM stock_invoicedata WHERE invoice_ID=:id";
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

    /*     * ************************************end of Invoice delete ********************************************* */



    /*     * ***********************stock and installation *********************** */

    public function stock_insertupload() {
        if ($_POST['action'] != 'stock_bulkupload') {
            return "Invalid action supplied for Stock bulkupload.";
        }
        $fName = trim('../comm/fileuplaod_temp/' . $_POST['fileName']);
        if ($fName == NULL) {
            return "Invalid File Name supplied .";
        }

        $_orderModelNotexist = array(); // holding nin existed order/model
        $_orderModelOverQty = array(); //holding the overQTY
        $_deveiceSerialOverQty = array(); //deviceSerial overATY holding
        $msg = '';

        try {
            $databasehost = "localhost";
            $databasename = "mss_life_db";
            $databasetable = "stock_data_temp";
            $databaseusername = "root";
            $databasepassword = "";
            $fieldseparator = ",";
            $lineseparator = "\n";
            $csvfile = $fName;
            if (!file_exists($csvfile)) {
                die("File not found. Make sure you specified the correct path.");
            }
            try {

                $pdo = new PDO(
                        "mysql:host=$databasehost;dbname=$databasename", $databaseusername, $databasepassword, array
                    (
                    PDO::MYSQL_ATTR_LOCAL_INFILE => true,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                        )
                );
            } catch (PDOException $e) {
                die("database connection failed: " . $e->getMessage());
            }
            $affectedRows = $pdo->exec("TRUNCATE TABLE `stock_data_temp`");
            $affectedRows = $pdo->exec
                    (
                    "LOAD DATA LOCAL INFILE "
                    . $pdo->quote($csvfile)
                    . " INTO TABLE `$databasetable` FIELDS TERMINATED BY "
                    . $pdo->quote($fieldseparator)
                    . "LINES TERMINATED BY "
                    . $pdo->quote($lineseparator)
                    . " IGNORE 1 LINES (LPO, Model, device_Serial)"
            );
            // clean the data from \r , \n and space
            $affectedRows = $pdo->exec("UPDATE `stock_data_temp` SET `Model`=LOWER(replace(replace(replace(Model,' ',''),'\r',''),'\n','')) ,`device_Serial`=LOWER(replace(replace(replace(device_Serial,' ',''),'\r',''),'\n','')) ,`LPO`=LOWER(replace(replace(replace(LPO,' ',''),'\r',''),'\n',''))");
            // echo "Loaded a total of $affectedRows records from this csv file.\n";

            /*             * *************if there is non iserted data ************** */

            $sql2 = "select sdt.`LPO`,sdt.Model,sdt.device_Serial,IFNULL(ome_v.reason, 'Duplicated')Reason from stock_data_temp as sdt left join order_model_exclude_v as ome_v ON sdt.LPO=ome_v.LPO and sdt.Model=ome_v.Model left join stock_stockdata ssd ON sdt.device_Serial=ssd.device_Serial where ome_v.LPO is not Null or ssd.device_Serial is not null";
            $html = "";
            $result2 = array();
            try {
                $result2 = $this->query($sql2);
                if ($result2 > 0) {
                    $html = '<br/><u><h4>Data Did not be inserted</h4></u><table class="table table-striped table-bordered" id="sample_1"><thead><tr>												
												<th>LPO</th>
												<th>Model</th>
                                                                                                <th>Deveice Serial</th>
												<th>Reason</th>                                                                                                
                                                                                                ';



                    $html .= '</tr></thead><tbody>';

                    foreach ($result2 as $row) {

                        $html .= '<tr class="odd gradeX"><td>' . $row['LPO'] . '</td><td>' . $row['Model'] . '</td><td>' . $row['device_Serial'] . '</td><td>' . $row['Reason'] . '</td></tr>';
                    }
                    $html .= '</tbody></table>';
                    $msg .= $html;
                }
            } catch (PDOException $e) {

                return($e->getMessage());
            }



            /*             * ********************end of non insrted *************** */

            /*             * *********insert the unique data **************

             */
            $sql = 'INSERT INTO `stock_stockdata`(  `LPO`,`Model`, `device_Serial`,`AddBy`) select sdt.`LPO`,sdt.Model,sdt.device_Serial,"' . $_SESSION['user']['email'] . '" from stock_data_temp as sdt left join order_model_exclude_v as ome_v ON sdt.LPO=ome_v.LPO and sdt.Model=ome_v.Model left join stock_stockdata ssd ON sdt.device_Serial=ssd.device_Serial where ome_v.LPO is Null and ssd.device_Serial is null';

            //  print_r($sql);die;

            try {


                $result = $this->query($sql);
                if ($result > 0) {
                    $this->log->logActions($_SESSION['user']['email'], 'Stock upload', 'Success upload', 'data :' . $sql . ' ');
                    $msg = 'true ' . ' ' . $msg; // Items inserted Before'.implode(' || ',$_srInserted);
                } else {

                    $this->log->logActions($_SESSION['user']['email'], 'Stock upload', 'Faild upload', 'data :' . $sql . ' ');
                    $msg = FALSE . '' . $msg;
                }
            } catch (PDOException $e) {
                unlink($fName);
                $this->log->logActions($_SESSION['user']['email'], 'Stock upload', 'error pdo ' . $e->getMessage(), 'data :' . $sql . ' ');
                $msg = 'error pdo ' . $e->getMessage();
            }






            /*             * *******end of insertion new data **** */

            // echo $msg;

            /*             * ***stock_routerOrderModelExist** */
            //SELECT t.`LPO`,t.`Model`,t.`device_Serial`,so.orderID FROM `stock_data_temp` as t left join stock_order as so ON t.LPO=so.po_id and t.Model=so.router_model where so.orderID IS Null 

            /*             * ***stock_routerOrderModelOverQty** */
            //SELECT t.`LPO`,t.`Model`,count(t.`device_Serial`) as insert_qty,omq_v.`Order QTY` as orderd_qty,omq_v.`total Stock QTY` as stocked_qty FROM `stock_data_temp` as t left join order_model_qty_v as omq_v ON t.LPO=omq_v.LPO and t.Model=omq_v.Model group by t.Model,t.LPO HAVING ( insert_qty+stocked_qty ) >orderd_qty
            //select overqty.LPO,overqty.Model,"overQty" as reason from ( SELECT t.`LPO`,t.`Model`,count(t.`device_Serial`) as insert_qty,omq_v.`Order QTY` as orderd_qty,omq_v.`total Stock QTY` as stocked_qty FROM `stock_data_temp` as t left join order_model_qty_v as omq_v ON t.LPO=omq_v.LPO and t.Model=omq_v.Model group by t.Model,t.LPO HAVING ( insert_qty+stocked_qty ) >orderd_qty) overqty UNION select noexist.LPO,noexist.Model,"NonExist" as reason from (SELECT t.`LPO`,t.`Model`,t.`device_Serial`,so.orderID FROM `stock_data_temp` as t left join stock_order as so ON t.LPO=so.po_id and t.Model=so.router_model where so.orderID IS Null )as noexist
            /* select sdt.`LPO`,sdt.Model,sdt.device_Serial from stock_data_temp as sdt
              left join order_model_exclude_v as ome_v
              ON
              sdt.LPO=ome_v.LPO
              and sdt.Model=ome_v.Model
              where ome_v.LPO is Null
             * 
             */

            /*             * ***********final insert *******
              select sdt.`LPO`,sdt.Model,sdt.device_Serial from stock_data_temp as sdt
              left join order_model_exclude_v as ome_v
              ON
              sdt.LPO=ome_v.LPO
              and sdt.Model=ome_v.Model
              left join stock_stockdata ssd
              ON
              sdt.device_Serial=ssd.device_Serial
              where ome_v.LPO is Null and ssd.device_Serial is null
             * ******************* */

            /*
              select sdt.`LPO`,sdt.Model,sdt.device_Serial,ssd.device_Serial from stock_data_temp as sdt
              left join order_model_exclude_v as ome_v
              ON
              sdt.LPO=ome_v.LPO
              and sdt.Model=ome_v.Model
              left join stock_stockdata ssd
              ON
              sdt.device_Serial<>ssd.device_Serial
              where ome_v.LPO is Null and ssd.device_Serial is null
             */
            /*             * ***********insert unqiue values ***********
             * select sdt.`LPO`,sdt.Model,sdt.device_Serial,ssd.device_Serial from stock_data_temp as sdt left join order_model_exclude_v as ome_v ON sdt.LPO=ome_v.LPO and sdt.Model=ome_v.Model left join stock_stockdata ssd ON sdt.device_Serial=ssd.device_Serial where ome_v.LPO is Null and ssd.device_Serial is null 
             */
            /*             * * did not inserted ****
             * select sdt.`LPO`,sdt.Model,sdt.device_Serial,IFNULL(ome_v.reason, 'Duplicated')Reason from stock_data_temp as sdt left join order_model_exclude_v as ome_v ON sdt.LPO=ome_v.LPO and sdt.Model=ome_v.Model left join stock_stockdata ssd ON sdt.device_Serial=ssd.device_Serial where ome_v.LPO is not Null or ssd.device_Serial is not null 
             */
        } catch (Exception $ex) {

            $msg = 'error opening ' . $ex->getMessage();
        } finally {
            unlink($fName);
            $pdo = null;
            unset($pdo);
        }

        return $msg;
    }

//////////////insert the uploaded sheet /////////////////////////////
    public function stock_insertupload2() {// Connection data (server_address, database, name, poassword)
        $insertedbefore_count = 0;
        $_orderModelNotexist = array();
        $_orderModelOverQty = array();
        $_deveiceSerialOverQty = array();


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
//print 'inside loop ..';

                $filesop = array();
                $filesop = fgetcsv($file);
                $c++;
                if ($c == 0) {

                    continue;
                }

                // print_r($filesop);

                $LPO = htmlentities(str_replace(' ', '', strtolower(trim($filesop[0])))); //LPO Numbers
                $Model = htmlentities(str_replace(' ', '', strtolower(trim($filesop[1])))); //Router Model	
                $router_sr = htmlentities(str_replace(' ', '', strtolower(trim($filesop[2])))); //Serial Numbers
                //  print_r($router_sr);
                if (trim($router_sr) == '' || trim($router_sr) == NULL) {

                    continue;
                } else {
                    if (in_array($LPO . '-' . $Model, $_orderModelNotexist)) {//check if aleasdry cheked existance
                        $_orderModelNotexist[] = $LPO . '-' . $Model;
                        continue;
                    } elseif (in_array($LPO . '-' . $Model, $_deveiceSerialOverQty)) {
                        $_orderModelOverQty[] = $LPO . '-' . $Model; //count the duplicates will give you how many
                        $_deveiceSerialOverQty[] = $router_sr; // take the additional Srials to display
                        continue;
                    } else {
                        //check and set the orderd qty and existing of the order itself
                        $order_ceck = $this->stock_routerOrderModelExist($LPO, $Model);
                        if ($order_ceck['found'] == TRUE) {
                            if ($order_ceck['overqty'] == TRUE) {// if not over qnatity still less than the ordered
                                //QTY is now over
                                $_orderModelOverQty[] = $LPO . '-' . $Model; //count the duplicates will give you how many
//                             $array = array(12,43,66,21,56,43,43,78,78,100,43,43,43,21);
//                              $vals = array_count_values($array);
//                              echo 'No. of NON Duplicate Items: '.count($vals).'<br><br>';
//                              print_r($vals);
//                              foreach($page as $key => $value) {
//                              echo "$key is at $value";
//                              }

                                $_deveiceSerialOverQty[] = $router_sr; // take the additional Srials to display
                                continue;
                            }
                        } else {
                            //doesnot exist
                            $_orderModelNotexist[] = $LPO . '-' . $Model;
                            continue;
                        }
                    }

                    //  $result['found']=$found;
                    //$result['overqty']=$overqty;
                }






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
                    $_sql[] = '("' . htmlentities($Model) . '","' . htmlentities($router_sr) . '", "' . htmlentities($LPO) . '", "' . htmlentities($user) . '")';
// $this->pdo->quote($Device_Name);
                }
            }
            fclose($file);
//  chmod('../comm/fileuplaod_temp/', 0777);



            $sql = 'INSERT INTO `stock_stockdata`(  `Model`, `device_Serial`, `LPO`, `AddBy`) VALUES ' . implode(',', $_sql);

            //  print_r($sql);die;
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
        $insertedbefore_count = sizeof($_srInserted);

        if (sizeof($_srInserted) > 0) {
            $msg .= '<B><u>' . $insertedbefore_count . '</u></B> Serials inserted Before :<br/>' . implode(' || ', $_srInserted);
        }
        //check non existed reouters models
        if (sizeof($_orderModelNotexist) > 0) {
            $msg .= '<br><br><B><u>' . sizeof($_orderModelNotexist) . '</u></B> Order/models do not existed in system:<br/>';
            $vals = array_count_values($_orderModelNotexist);
            foreach ($vals as $key => $value) {
                $msg .= "<br><B><u>$key </u></B> has : $value device(s)<br/>";
            }
        }
        if (sizeof($_orderModelOverQty) > 0) {
            $vals = array_count_values($_orderModelOverQty);
            $msg .= '<br><br><B><u>' . count($vals) . '</u></B> Order/models Have over-stock amount<br/>';

            foreach ($vals as $key => $value) {
                $msg .= "<br><B><u>$key </u></B>has : $value device(s) over order QTY<br/>";
            }
        }
        if (sizeof($_deveiceSerialOverQty) > 0) {
            $msg .= '<br><br><B><u>' . sizeof($_deveiceSerialOverQty) . '</u></B> Device did not inserted as thier Order/models Have over-stock amount<br/>' . implode(' || ', $_deveiceSerialOverQty);
        }


        return $msg;
    }

//end of stock_insertupload
//////////////insert the uploaded sheet /////////////////////////////
    public function invoice_insertupload() {// Connection data (server_address, database, name, poassword)
        if ($_POST['action'] != 'invoice_bulkupload') {
            return "Invalid action supplied for invoice bulkupload.";
        }
        $fName = trim('../comm/fileuplaod_temp/' . $_POST['fileName']);
        if ($fName == NULL) {
            return "Invalid File Name supplied .";
        }
//echo $fName;
//die;
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

                /*
                 * 

                  PO	Number      0
                  PO Type	Free Text	1
                  Vendor	Free Text	2
                  Invoice Number	Free Text	Unique  3
                  Invoice Type	Free Text	4
                  Invoice Amount	money/number	5
                  Total PO Amount	Number          6
                  Invoice Received Date	DD/MM/YYYY	7
                  Status		8
                  Remark	free text	9
                  Authorization Code	free text	10
                  AC Date	DD/MM/YYYY	11

                 * 
                 * 
                 * 
                 */

                $LPO = htmlentities(str_replace(' ', '', strtolower(trim($filesop[0])))); //LPO Numbers
                $PO_Type = htmlentities(str_replace(' ', '', strtolower(trim($filesop[1])))); //Router Model	
                $Vendor = htmlentities(str_replace(' ', '', strtolower(trim($filesop[2])))); //Serial Numbers
                $Invoice_Number = htmlentities(str_replace(' ', '', strtolower(trim($filesop[3])))); //LPO Numbers
                $Invoice_Type = htmlentities(str_replace(' ', '', strtolower(trim($filesop[4])))); //Router Model	
                $Invoice_Amount = htmlentities(str_replace(' ', '', strtolower(trim($filesop[5])))); //Serial Numbers
                $Total_PO_Amount = htmlentities(str_replace(' ', '', strtolower(trim($filesop[6])))); //Serial Numbers
                $Invoice_Received = date("Y-m-d", strtotime(htmlentities(str_replace(' ', '', strtolower(trim($filesop[7])))))); //date("Y-m-d", strtotime(htmlentities(str_replace(' ', '', strtolower(trim($filesop[7])))))); //LPO Numbers
                $Status = htmlentities(str_replace(' ', '', strtolower(trim($filesop[8])))); //Router Model	
                $Remark = htmlentities(str_replace(' ', '', strtolower(trim($filesop[9])))); //Serial Numbers
                $Authorization_Code = htmlentities(str_replace(' ', '', strtolower(trim($filesop[10])))); //Serial Numbers
                $AC_Date = date("Y-m-d", strtotime(htmlentities(str_replace(' ', '', strtolower(trim($filesop[11])))))); //Serial Numbers
//  return strtotime(htmlentities(str_replace(' ', '', strtolower(trim($filesop[7])))));
//   die;

                if (trim($Invoice_Number) == '' || trim($Invoice_Number) == NULL) {

                    continue;
                }




//INSERT INTO `stock_order`(`cear_id`, `router_model`, `description`, `po_qty`, `customer_name`, `req_by`, `Req_date`, `vendor`, `po_id`, `po_date`, `EDD`)
//CEAR # //cear_id	Item Name //router_model	Description//description	PO Quantity//po_qty	Customer Name//customer_name	Requested By//req_by	ORIGINATION DATE//Req_date	Vendor//vendor	PO #//po_id	PO DATE//po_date	EDD//EDD
                if ($this->stock_invoiceExist($Invoice_Number)) {

                    $_srInserted[] = $Invoice_Number;
                    continue;
                }





                $user = $_SESSION['user']['email'];
// check for cust name and qty and router
                if (($Invoice_Number != '' and ! (is_null($Invoice_Number)) and isset($Invoice_Number))) {
//echo '<br/> loop#'.$c.'values are : '.'("'.mysql_real_escape_string($Party_id).'","'.mysql_real_escape_string($acc_num).'", "'.mysql_real_escape_string($acc_Name).'", "'.mysql_real_escape_string($service).'", "'.mysql_real_escape_string($Section).'", "'.mysql_real_escape_string($Router_model).'", '.mysql_real_escape_string($Qty).', "'.mysql_real_escape_string($Prob).'", "'.mysql_real_escape_string($req_by).'")<br/>-----------------------------------';
//  $_sql[] = '("' . mysql_real_escape_string($Device_Name) . '","' . mysql_real_escape_string($Model) . '", "' . mysql_real_escape_string($router_sr) . '", "' . mysql_real_escape_string($vendor) . '", "' . mysql_real_escape_string($LPO) . '", "' . mysql_real_escape_string($cust_name) . '", "' . mysql_real_escape_string($user) . '")';
//     $_sql[] = '("' . $this->pdo->quote($Device_Name) . '","' . $this->pdo->quote($Model) . '", "' . $this->pdo->quote($router_sr) . '", "' . $this->pdo->quote($vendor) . '", "' . $this->pdo->quote($LPO) . '", "' . $this->pdo->quote($cust_name) . '", "' . $this->pdo->quote($user) . '")';
//     
//   $_sql[] = '("' . mysqli_real_escape_string($this->pdo,$Device_Name) . '","' . mysqli_real_escape_string($this->pdo,$Model) . '", "' . mysqli_real_escape_string($this->pdo,$router_sr) . '", "' . mysqli_real_escape_string($this->pdo,$vendor) . '", "' . mysqli_real_escape_string($this->pdo,$LPO) . '", "' . mysqli_real_escape_string($this->pdo,$cust_name) . '", "' . mysqli_real_escape_string($this->pdo,$user) . '")';
//   
                    $_sql[] = '("' . htmlentities($LPO) . '","' . htmlentities($PO_Type) . '", "' . htmlentities($Vendor) . '", "' . htmlentities($Invoice_Number) . '", "' . htmlentities($Invoice_Type) . '", "' . htmlentities($Invoice_Amount) . '", "' . htmlentities($Total_PO_Amount) . '", "' . htmlentities($Invoice_Received) . '", "' . htmlentities($Status) . '", "' . htmlentities($Remark) . '", "' . htmlentities($Authorization_Code) . '", "' . htmlentities($AC_Date) . '", "' . htmlentities($user) . '")';
// $this->pdo->quote($Device_Name);
                }
            }
            fclose($file);
//  chmod('../comm/fileuplaod_temp/', 0777);



            $sql = 'INSERT INTO `stock_invoicedata`(`LPO`, `PO_Type`, `Vendor`, `Invoice_Number`, `Invoice_Type`, `Invoice_Amount`, `Total_PO_Amount`, `Invoice_Received-Date`, `Status`, `Remark`, `Authorization_Code`, `AC_Date`, `AddBy`) VALUES  ' . implode(',', $_sql);
            $msg = '';
            try {
                unlink($fName);
                if (sizeof($_sql) > 0) {
                    $result = $this->query($sql);
                    if ($result > 0) {
                        $this->log->logActions($_SESSION['user']['email'], 'Invoice upload', 'Success upload', 'data :' . $sql . ' ');
                        $msg = TRUE; // Items inserted Before'.implode(' || ',$_srInserted);
                    } else {

                        $this->log->logActions($_SESSION['user']['email'], 'Invoice upload', 'Faild upload', 'data :' . $sql . ' ');
                        $msg = FALSE;
                    }
                }//empty sheet
            } catch (PDOException $e) {
                unlink($fName);
                $this->log->logActions($_SESSION['user']['email'], 'Invoice upload', 'error pdo ' . $e->getMessage(), 'data :' . $sql . ' ');
                $msg = 'error pdo ' . $e->getMessage();
            }
        } catch (Exception $ex) {
            unlink($fName);
            $msg = 'error opening ' . $ex->getMessage();
        }

        if (sizeof($_srInserted) > 0) {
            $msg .= 'Invoices Number inserted Before :<br/>' . implode(' || ', $_srInserted);
        }

        return $msg;
    }

//end of stock_insertupload

    /** get all user for table view
     *
     * @return mixed TRUE on success, message on error
     */
    public function biuldinvoiceTable($cond = ' where 1 ') {// Connection data (server_address, database, name, poassword)
        $sql = "SELECT `invoice_ID`, `LPO`, `PO_Type`, `Vendor`, `Invoice_Number`, `Invoice_Type`, `Invoice_Amount`, `Total_PO_Amount`, `Invoice_Received-Date`, `Status`, `Remark`, `Authorization_Code`, `AC_Date`, `AddBy`, `AddDate` FROM `stock_invoicedata` " . $cond . " ORDER BY `stock_invoicedata`.`LPO` DESC";
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
												<th>PO</th>
												<th>PO Type</th>
                                                                                                <th>Vendor</th>
												<th>Invoice Number</th>
                                                                                                <th>Invoice Type</th>
												<th>Invoice Amount</th>
                                                                                                <th>Total PO Amount</th>
                                                                                                <th>Invoice Received Date</th>
                                                                                                <th>Status</th>
                                                                                                <th>Remark</th>
                                                                                                <th>Authorization Code</th>
                                                                                                <th>AC Date</th>
												<th>Created By</th>
                                                                                                <th>Created Time</th>
                                                                                                <th>Action</th>
                                                                                                ';



        $html .= '</tr>
										</thead>
										<tbody>';
        foreach ($result as $row) {

            $html .= '<tr class="odd gradeX">
												
												<td>' . $row['invoice_ID'] . '</td>
                                                                                                    <td>' . $row['LPO'] . '</td>
                                                                                                        <td>' . $row['PO_Type'] . '</td>
                                                                                                            <td>' . $row['Vendor'] . '</td>
                                                                                                                <td>' . $row['Invoice_Number'] . '</td>
												<td>' . $row['Invoice_Type'] . '</td>
                                                                                                    <td>' . $row['Invoice_Amount'] . '</td>
                                                                                                        <td>' . $row['Total_PO_Amount'] . '</td>
                                                                                                            <td>' . $row['Invoice_Received-Date'] . '</td>
                                                                                                                <td>' . $row['Status'] . '</td>
												 <td>' . $row['Remark'] . '</td>
                                                                                                      <td>' . $row['Authorization_Code'] . '</td>
                                                                                                           <td>' . $row['AC_Date'] . '</td>
                                                                                                                <td>' . $row['AddBy'] . '</td>
                                                                                                                     <td>' . $row['AddDate'] . '</td>
												';

            if (($_SESSION['teamName'] == 'Marketing' and $_SESSION['currentRole'] == 'Admin')or ( $_SESSION['teamName'] == 'supperAdmins' or TRUE)) {


                $html .= '<td class="center">
													<!--<a href="#" class="icon huge"><i class="icon-zoom-in" id="viewInvoice_' . $row['invoice_ID'] . '" data-d="input-id=' . $row['invoice_ID'] . '&token=' . $_SESSION['token'] . '&action=Stock_invoice_view"></i></a>&nbsp;-->	
													<!--<a href="#" class="icon huge"><i class="icon-pencil" id="addInvoice_' . $row['invoice_ID'] . '" data-d="input-id=' . $row['invoice_ID'] . '&token=' . $_SESSION['token'] . '&action=Stock_invoice_edit"></i></a>&nbsp;-->
													<a href="#" class="icon huge"><i class="icon-remove" id="removeInvoice_' . $row['invoice_ID'] . '" data-d="input-id=' . $row['invoice_ID'] . '&token=' . $_SESSION['token'] . '&action=Stock_invoice_delete"></i></a>&nbsp;		
												</td>';
            }
            $html .= '</tr>';


// echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }






        return $html;
    }

    public function stockInstallationGetData() {

        $serial = $_POST['serial'];

        if ($_POST['action'] != 'getDeviceData') {
            return "Invalid action supplied for retrive Device Data.";
        }
        // $sql = 'SELECT `stock_ID`, `router_model`, `vendor`, `po_id` FROM `stock_stockdata_v` WHERE device_Serial=:ser';
        $sql = 'SELECT ssd_v.`stock_ID`, ssd_v.`router_model`, ssd_v.`vendor`, ssd_v.`po_id`, CASE WHEN si.stock_ref IS NULL THEN "Available" ELSE "Al ready Installed" END AS stus FROM `stock_stockdata_v` as ssd_v left join stock_installation si ON si.stock_ref=ssd_v.stock_ID WHERE ssd_v.device_Serial=:ser';
        $msg = '';
        try {

            $result = $this->row($sql, array('ser' => $serial));
            if ($result > 0) {

// $msg= 'true ';// Items inserted Before'.implode(' || ',$_srInserted);//array("phone"=>'123-12313',"email"=>'test@test.com','city'=>'Medicine Hat','address'=>'556 19th Street NE')
                echo json_encode(array("stockID" => $result['stock_ID'], "Model" => $result['router_model'], "vendor" => $result['vendor'], "LPO" => $result['po_id'], "stus" => $result['stus']));
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
        $installdate = htmlentities($_POST['txt-device-installdate'], ENT_QUOTES);




        /*
         * Retrieves the matching info from the DB if it exists
         */
        $sql = "INSERT INTO `stock_installation`( `stock_ref`, `add_by`, `MARWAN_SR`, `cust_name`, `Party_id`, `link_acc`, `MARWAN_acc`,installation_date) VALUES (:stock_ref ,:add_by ,:MARWAN_SR ,:cust_name ,:Party_id ,:link_acc ,:MARWAN_acc,:installation_date) ON DUPLICATE KEY UPDATE  add_by=:add_by1 ,MARWAN_SR=:MARWAN_SR1 ,cust_name=:cust_name1 ,Party_id=:Party_id1 ,link_acc=:link_acc1 ,MARWAN_acc=:MARWAN_acc1,installation_date=:installation_date1";
//$this->lastInsertId();
        if ($stock_ref != '' and ! (is_null($stock_ref)) and isset($stock_ref)) {
            $params = array("stock_ref" => $stock_ref, "add_by" => $Requested_By, "cust_name" => $cust_name, "MARWAN_SR" => $MARWAN_SR, "Party_id" => $Party_id, "link_acc" => $link_acc, "MARWAN_acc" => $MARWAN_acc, "add_by1" => $Requested_By, "cust_name1" => $cust_name, "MARWAN_SR1" => $MARWAN_SR, "Party_id1" => $Party_id, "link_acc1" => $link_acc, "MARWAN_acc1" => $MARWAN_acc, "installation_date" => $installdate, "installation_date1" => $installdate);

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
        $SICETClassification = htmlentities($_POST['select-order-SICETClassification'], ENT_QUOTES);
        $HW_Price = htmlentities($_POST['txt-order-HW_Price'], ENT_QUOTES);
        $installation_charge = htmlentities($_POST['txt-order-installation_charge'], ENT_QUOTES);
        $support_charge = htmlentities($_POST['txt-order-support_charge'], ENT_QUOTES);
        $PO = htmlentities($_POST['txt-order-PO'], ENT_QUOTES);
        $DeviceType = htmlentities($_POST['txt-order-DeviceType'], ENT_QUOTES);
//  $CustomerName = htmlentities($_POST['select-order-CustomerName'], ENT_QUOTES);
        $CustomerName = (strtolower(htmlentities($_POST['select-order-CustomerName'], ENT_QUOTES))) == 'other' ? htmlentities($_POST['txt-order-CustomerName'], ENT_QUOTES) : htmlentities($_POST['select-order-CustomerName'], ENT_QUOTES);
        $Requestedby = (strtolower(htmlentities($_POST['select-order-Requestedby'], ENT_QUOTES))) == 'other' ? htmlentities($_POST['txt-order-Requestedby'], ENT_QUOTES) : htmlentities($_POST['select-order-Requestedby'], ENT_QUOTES);
        $RouterClassification = htmlentities($_POST['select-order-RouterClassification'], ENT_QUOTES);
        $RouterModel = (strtolower(htmlentities($_POST['select-order-RouterModel'], ENT_QUOTES))) == 'other' ? htmlentities($_POST['txt-order-RouterModel'], ENT_QUOTES) : htmlentities($_POST['select-order-RouterModel'], ENT_QUOTES); //htmlentities($_POST['select-order-RouterModel'], ENT_QUOTES);
        $POQuantity = htmlentities($_POST['txt-order-POQuantity'], ENT_QUOTES);
        $Vendor = (strtolower(htmlentities($_POST['select-order-Vendor'], ENT_QUOTES))) == 'other' ? htmlentities($_POST['txt-order-Vendor'], ENT_QUOTES) : htmlentities($_POST['select-order-Vendor'], ENT_QUOTES); //htmlentities($_POST['select-order-Vendor'], ENT_QUOTES);
        $Description = htmlentities($_POST['txt-order-Description'], ENT_QUOTES);
        $RequestedDate = date("Y-m-d  H:i:s", strtotime(htmlentities($_POST['txt-order-RequestedDate'], ENT_QUOTES))); //  htmlentities($_POST['txt-order-RequestedDate'], ENT_QUOTES);
        $PODate = date("Y-m-d  H:i:s", strtotime(htmlentities($_POST['txt-order-PODate'], ENT_QUOTES))); // htmlentities($_POST['txt-order-PODate'], ENT_QUOTES);
        $ExpectedDeliveryDate = htmlentities($_POST['txt-order-ExpectedDeliveryDate'], ENT_QUOTES);


        $user_add = $_SESSION['user']['email'];





        $sql = "INSERT INTO `stock_order`( `customer_name`, `po_id`, `vendor`, `RouterClass`, `router_model`,DeviceType, `description`, `po_qty`, `cear_id`, `req_by`, `Req_date`, `po_date`, `EDD`,`sicet_type`, `hw_price`, `install_charge`, `support_charge`, `user_add`) VALUES (:customer_name, :po_id, :vendor, :RouterClass, :router_model,:DeviceType, :description, :po_qty, :cear_id, :req_by, :Req_date, :po_date, :EDD,:sicet_type, :hw_price, :install_charge, :support_charge, :user_add)";
//$this->lastInsertId();
        try {

            $Parms = array("customer_name" => $CustomerName, "po_id" => $PO, "vendor" => $Vendor, "RouterClass" => $RouterClassification, "router_model" => $RouterModel, "DeviceType" => $DeviceType, "description" => $Description, "po_qty" => $POQuantity, "cear_id" => $CEARID, "req_by" => $Requestedby, "Req_date" => $RequestedDate, "po_date" => $PODate, "EDD" => $ExpectedDeliveryDate, "sicet_type" => $SICETClassification, "hw_price" => $HW_Price, "install_charge" => $installation_charge, "support_charge" => $support_charge, "user_add" => $user_add);
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
    public function stockAddOrderRemarks() {// Connection data (server_address, database, name, poassword)
        /*
         * inproper action call
         */
        if ($_POST['action'] != 'Stock_AddOrderRemark' or ( htmlentities($_SESSION['user']['email'], ENT_QUOTES)) == "dsm_r" OR ! isset($_SESSION['user']['email'])) {
            return "Invalid action supplied for process Add new Order Remarks.";
        }

        $OrderRemarks = nl2br(htmlentities($_POST['OrderRemarks'], ENT_QUOTES));

        $OrderID = (htmlentities($_SESSION['Orderid_sess'], ENT_QUOTES));
        $ReqBy = (htmlentities($_SESSION['user']['email'], ENT_QUOTES));

        $sql = "INSERT INTO `stock_ordersremarks`( order_id,`Remark_TXT`, `Editor`) VALUES (:OrderId,:remark,:by)";
        //$this->lastInsertId();
        try {
            $res = $this->query($sql, array("remark" => $OrderRemarks, "by" => $ReqBy, "OrderId" => $OrderID));
            $this->log->logActions($_SESSION['user']['email'], ' New Stock Order Remark', 'Succes ad d ', 'DATA :remark =>' . $OrderRemarks . ', by => ' . $ReqBy . ',  for activityId =>' . $OrderID);
        } catch (Exception $e) {
            // $this->db=null;
            $this->log->logActions($_SESSION['user']['email'], 'Add New Stock Order Remarks', 'Faild Delete due to ' . $e->getMessage(), 'DATA :remark =>' . $OrderRemarks . ', by => ' . $ReqBy . ',  for HandoverId =>' . $OrderID);
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

//add new Order remarkS

    public function stockGetOrderRemarks() {// Connection data (server_address, database, name, poassword)
        if ($_POST['action'] != 'STOCK_getOrderRemarks' OR ! isset($_SESSION['user']['email'])) {
            return false; //"Invalid action supplied for retrive Activity assign History Data.";
        }
        if (isset($_POST['Orderid']) or isset($_SESSION['Orderid_sess'])) {
            $Orderid = htmlentities($_POST['Orderid'], ENT_QUOTES) == null ? $_SESSION['Orderid_sess'] : htmlentities($_POST['Orderid'], ENT_QUOTES);

            $sql = "SELECT  `Remark_TXT`, `Editor`, `Remark_tTme` FROM `stock_ordersremarks` WHERE order_id=:Orderid order by Remark_tTme desc ";
            $html = "";
            $result = array();
            try {
                $result = $this->query($sql, array('Orderid' => $Orderid), PDO::FETCH_OBJ);
            } catch (PDOException $e) {

                return($e->getMessage());
            }
            return json_encode($result);
        }
    }

    /*
     * 
     * add new invoice using form
     * @takes form data fields 
     */

    public function stockInvoiceAddNew_form() {
        /*
         * Fails if the proper action was not submitted
         */
// echo "<script>alert('".$_POST['input-username']."')</script>";
        if ($_POST['action'] != 'stock_AddInvoice') {
            return "Invalid action supplied for process Add new Invoice.";
        }
        /*
         * Escapes the user input for security
         */





        $LPO = htmlentities($_POST['txt-invoice-PO'], ENT_QUOTES);
        $PO_Type = htmlentities($_POST['select-invoice-PO_Type'], ENT_QUOTES);
        $Vendor = htmlentities($_POST['select-invoice-vendor'], ENT_QUOTES);
        $Invoice_Number = htmlentities($_POST['txt-invoice-Number'], ENT_QUOTES);
        $Invoice_Type = htmlentities($_POST['select-invoice-Invoice_Type'], ENT_QUOTES);
        $Invoice_Amount = htmlentities($_POST['txt-invoice-Amount'], ENT_QUOTES);
        $Total_PO_Amount = htmlentities($_POST['txt-invoice-TotalPoAmount'], ENT_QUOTES);
        $Invoice_Received = date("Y-m-d", strtotime(htmlentities($_POST['txt-invoice-Invoice_Received_Date'], ENT_QUOTES)));
        $Status = htmlentities($_POST['select-invoice-Invoice_Status'], ENT_QUOTES);
        $Remark = htmlentities($_POST['txt-invoice-Remark'], ENT_QUOTES);
        $Authorization_Code = htmlentities($_POST['txt-invoice-Authorization_Code'], ENT_QUOTES);
        $AC_Date = date("Y-m-d", strtotime(htmlentities($_POST['txt-invoice-AC_Date'], ENT_QUOTES)));





        $user_add = $_SESSION['user']['email'];






//$this->lastInsertId();                                                                                                                                                                                                                              `LPO`, `PO_Type`, `Vendor`, `Invoice_Number`, `Invoice_Type`, `Invoice_Amount`, `Total_PO_Amount`, `Invoice_Received-Date`, `Status`, `Remark`, `Authorization_Code`, `AC_Date`, `AddBy`                                          
        $sql = 'INSERT INTO `stock_invoicedata`(`LPO`, `PO_Type`, `Vendor`, `Invoice_Number`, `Invoice_Type`, `Invoice_Amount`, `Total_PO_Amount`, `Invoice_Received-Date`, `Status`, `Remark`, `Authorization_Code`, `AC_Date`, `AddBy`) VALUES  (:LPO, :PO_Type, :Vendor, :Invoice_Number,:Invoice_Type, :Invoice_Amount, :Total_PO_Amount, :Invoice_Received, :Status, :Remark, :Authorization_Code, :AC_Date, :user_add)';
        try {
//   :LPO, :PO_Type, :Vendor, :Invoice_Number,:Invoice_Type, :Invoice_Amount, :Total_PO_Amount, :Invoice_Received, :Status, :Remark, :Authorization_Code, :AC_Date, :user_add
            $Parms = array("LPO" => $LPO, "PO_Type" => $PO_Type, "Vendor" => $Vendor, "Invoice_Number" => $Invoice_Number, "Invoice_Type" => $Invoice_Type, "Invoice_Amount" => $Invoice_Amount, "Total_PO_Amount" => $Total_PO_Amount, "Invoice_Received" => $Invoice_Received, "Status" => $Status, "Remark" => $Remark, "Authorization_Code" => $Authorization_Code, "AC_Date" => $AC_Date, "user_add" => $user_add);
            $res = $this->query($sql, $Parms);
            $this->alertObj->setAlert('INSERT', 'Stock_Invoice', null, 'DATA :' + implode(', ', $Parms));
            $this->log->logActions($_SESSION['user']['email'], ' New Stock_Invoice', 'Succes add  ', 'DATA :' + implode(', ', $Parms));
        } catch (Exception $e) {
// $this->db=null;
            $this->log->logActions($_SESSION['user']['email'], 'Add New stock Invoice', 'Faild Add due to ' . $e->getMessage(), 'Data :' + implode(', ', $Parms));
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

//add new invoice



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
                    $stockFilters1 = 'customer_name';

                    break;
                case 'Router Model':
// $coulmnName = 'Cust_code';
                    $orderFilters = 'router_model';
                    $stockFilters1 = 'router_model';

                    break;
                case 'LPO':
                    $orderFilters = 'po_id';
                    $stockFilters1 = 'po_id';

                    break;
                case 'Vendor':
                    $orderFilters = 'vendor';
                    $stockFilters1 = 'vendor';

                    break;
            }//end filter name
        }

        /*
         * SELECT sum(po_qty) as ordered
          ,count(DISTINCT rd1.stock_ref) as installed
          ,count(DISTINCT rd2.device_Serial) as recieved
          ,stock_order.router_model as filtered
          FROM `stock_order`
          left join stock_stockdata_v as rd2
          on rd2.router_model=stock_order.router_model
          and rd2.device_Serial!= null
          left join stock_installation as rd1
          on rd1.stock_ref=rd2.stock_ID
          GROUP BY stock_order.router_model
         * */


        $sql = "SELECT (" . $orderFilters . ".po_qty) as ordered,count(DISTINCT rd1.stock_ref) as installed,count(DISTINCT rd2.device_Serial) as recieved," . $orderFilters . "." . $orderFilters . " as filtered FROM `" . $orderFilters . "`
left join
 stock_stockdata_v as rd2
      on rd2." . $orderFilters . " =" . $orderFilters . "." . $orderFilters . "  

left join stock_installation as rd1 
on rd1.stock_ref=rd2.stock_ID 
GROUP BY " . $orderFilters . "." . $orderFilters . "";


        $html = "";
// var_dump($sql);
        $result = array();
        try {
            $result = $this->query($sql, array(), PDO::FETCH_OBJ);
// var_dump($result);
        } catch (PDOException $e) {

            return($e->getMessage());
        }
        return '{"orders":' . json_encode($result) . '}'; //json_encode($result);
    }

    function analyse_file($file, $capture_limit_in_kb = 10) {
// capture starting memory usage
        $output['peak_mem']['start'] = memory_get_peak_usage(true);

// log the limit how much of the file was sampled (in Kb)
        $output['read_kb'] = $capture_limit_in_kb;

// read in file
        $fh = fopen($file, 'r');
        $contents = fread($fh, ($capture_limit_in_kb * 1024)); // in KB
        fclose($fh);

// specify allowed field delimiters
        $delimiters = array(
            'comma' => ',',
            'semicolon' => ';',
            'tab' => "\t",
            'pipe' => '|',
            'colon' => ':'
        );

// specify allowed line endings
        $line_endings = array(
            'rn' => "\r\n",
            'n' => "\n",
            'r' => "\r",
            'nr' => "\n\r"
        );

// loop and count each line ending instance
        foreach ($line_endings as $key => $value) {
            $line_result[$key] = substr_count($contents, $value);
        }

// sort by largest array value
        asort($line_result);

// log to output array
        $output['line_ending']['results'] = $line_result;
        $output['line_ending']['count'] = end($line_result);
        $output['line_ending']['key'] = key($line_result);
        $output['line_ending']['value'] = $line_endings[$output['line_ending']['key']];
        $lines = explode($output['line_ending']['value'], $contents);

// remove last line of array, as this maybe incomplete?
        array_pop($lines);

// create a string from the legal lines
        $complete_lines = implode(' ', $lines);

// log statistics to output array
        $output['lines']['count'] = count($lines);
        $output['lines']['length'] = strlen($complete_lines);

// loop and count each delimiter instance
        foreach ($delimiters as $delimiter_key => $delimiter) {
            $delimiter_result[$delimiter_key] = substr_count($complete_lines, $delimiter);
        }

// sort by largest array value
        asort($delimiter_result);

// log statistics to output array with largest counts as the value
        $output['delimiter']['results'] = $delimiter_result;
        $output['delimiter']['count'] = end($delimiter_result);
        $output['delimiter']['key'] = key($delimiter_result);
        $output['delimiter']['value'] = $delimiters[$output['delimiter']['key']];

// capture ending memory usage
        $output['peak_mem']['end'] = memory_get_peak_usage(true);
        return $output;
    }

}

//if we started convert Stock MGM new codeigniter 

/*
 * pages:
 *  1-profile
 *  2-modules how it is work
 *  3- sidebar builder
 *  4- stock submenus
 *      4.1 order
 *          4.1.1 insert new
 *          4.1.2 view
 *      4.2 stock
 *          4.2.1 all stock
 *          4.2.2 pending stock
 *          4.2.3 upload stock
 *      4.3 Device Operation
 *          4.3.1 device history
 *          4.3.2 dvice isntallation
 *          4.3.3 serach by serial, LPO, hostname
 *      4.4 Invoices
 *          4.4.1   SICT B
 *          4.4.2   SICT A
 *      4.5 Documents
 *          4.5.1 Search By Invoice Number, Vendor, Date
 *          4.5.2 Add new Invoices or incpection
 * 
 * 
 *      4.6 
 * 
 * 
 * 
 * 
 * 
 * 
 */
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
class users extends DB_Connect {

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
    public function UserRegister($username, $emailid, $password) {
        $password = md5($password);
        $qr = mysql_query("INSERT INTO users(username, emailid, password) values('" . $username . "','" . $emailid . "','" . $password . "')") or die(mysql_error());
        return $qr;
    }

    /** get all user for table view
     *
     * @return mixed TRUE on success, message on error
     */
    public function biuldUsersTable() {// Connection data (server_address, database, name, poassword)
        $sql = "select distinct `Members_id` ,`Members_Email` ,`Team_Name` from `memberdata` ";
        $html = "";
        $result = array();
        try {
            $result = $this->query($sql);
        } catch (PDOException $e) {

            return($e->getMessage());
        }





        $html = '<table class="table table-striped table-bordered" id="sample_1">
										<thead>
											<tr>
												<th style="width:8px"><input type="checkbox" class="group-checkable" data-set=".checkboxes" /></th>
												<th>User ID</th>
												<th class="hidden-phone">User Name</th>
												<th class="hidden-phone">User Team</th>
												
												<th>Actions</th>
											</tr>
										</thead>
										<tbody>';
        foreach ($result as $row) {

            $html.='<tr class="odd gradeX">
												<td><input type="checkbox" class="checkboxes" value="' . $row['Members_id'] . '" /></td>
												<td>' . $row['Members_id'] . '</td>
												<td class="hidden-phone"><a href="mailto:' . $row['Members_Email'] . '@etisalat.ae">' . $row['Members_Email'] . '</a></td>
												<td class="hidden-phone">' . ($row['Team_Name'] == '' ? '-' : $row['Team_Name']) . '</td>
												
												';
            IF (TRUE) {
                $html.='<td class="center">
													<a href="#" class="icon huge"><i class="icon-zoom-in" id="viewUser_' . $row['Members_id'] . '" data-d="input-id=' . $row['Members_id'] . '&token=' . $_SESSION['token'] . '&action=user_view"></i></a>&nbsp;	
													<!--<a href="#" class="icon huge"><i class="icon-pencil" id="addUser_' . $row['Members_id'] . '" data-d="input-id=' . $row['Members_id'] . '&token=' . $_SESSION['token'] . '&action=user_edit"></i></a>-->&nbsp;
													<a href="#" class="icon huge"><i class="icon-remove" id="removeUser_' . $row['Members_id'] . '" data-d="input-id=' . $row['Members_id'] . '&token=' . $_SESSION['token'] . '&action=user_delete"></i></a>&nbsp;		
												</td>';
            } else {
                $html.='<td class="center">You don\'t have AUTH </td>';
            }
            $html.='</tr>';


            // echo $row['Members_id']. ' - '. $row['Members_Email']. ' - '. ($row['Team_Name']==''? '***':$row['Team_Name']). '<br />';
        }




        $html.='</tbody></table>';

        return $html;
    }

    /** Checks login credentials for a valid user
     *
     * @return mixed TRUE on success, message on error
     */
    public function processLoginForm() {
        /*
         * Fails if the proper action was not submitted
         */
        // echo "<script>alert('".$_POST['input-username']."')</script>";
        if ($_POST['action'] != 'user_login') {
            return "Invalid action supplied for processLoginForm.";
        }
        /*
         * Escapes the user input for security
         */
        $uname = htmlentities($_POST['input-username'], ENT_QUOTES);
        $pword = $this->getPasswordHash(htmlentities($_POST['input-password'], ENT_QUOTES));
//var_dump($uname);
        /* var_dump($_POST);
          echo '\n<br/>----<br/>';
          var_dump($pword);
          echo '\n----<br/>'; */

        /*
         * Retrieves the matching info from the DB if it exists
         */


        // Single Row

        $user = array();
        //$sql = "call sp_UserLoginCheck(:uname,:pass)";
        $sql = "select distinct Members_Email,Members_id,Members_websites,Members_Team_FK from members where Members_Email= :uname and Members_Password=md5(sha1(:pass))";
        // $sql="select distinct Members_Email,Members_id from members where Members_Email='".$uname."' and Members_Password='""'";
//$sql="select distinct Members_Email,Members_id,Members_websites,Members_Team_FK from members limit 1";
        try {


            $user = $this->query($sql, array("uname" => $uname, "pass" => $pword));
            //$user  =  $this->query($sql);
            // $this->CloseConnection();
        } catch (Exception $e) {
            die($e->getMessage());
        }
//var_dump(array("uname"=>$uname,"pass"=>$pword));
        //      var_dump($user);

        /*
         * Fails if username doesn't match a DB entry
         */
        if (!isset($user) or count($user) <= 0) {

            $this->log->logActions($_POST['input-username'], 'Try to login', 'Faild login', 'users Class');
            return FALSE; //"Your username or password is invalid.";
        } else {
            /*
             * Stores user info in the session as an array
             */
            //  echo "<script>alert('".$user['Members_Email']."$".$user['Members_id']."')</script>";
            $_SESSION['user'] = array(
                'id' => $user[0]['Members_id'],
                'email' => $user[0]['Members_Email'],
                'webSites' => $user[0]['Members_websites'],
                'team' => $user[0]['Members_Team_FK']
            );
            //loglogActions($_POST['input-username'], 'Try to login', 'Success login', 'users Class');

            $this->log->logActions($_POST['input-username'], 'Try to login', 'Success login', 'users Class');

            return TRUE; //success
        }
    }
    
    
     /** Checks login credentials for a valid user
     *
     * @return mixed TRUE on success, message on error
     */
    public function processLogin_macro($user=null) {
        
    
        $uname = htmlentities($user, ENT_QUOTES);
        

        $user = array();
        //$sql = "call sp_UserLoginCheck(:uname,:pass)";
        $sql = "select distinct Members_Email from members where Members_Email= :uname ";
  
        try {


            $user = $this->query($sql, array("uname" => $uname));
            //$user  =  $this->query($sql);
            // $this->CloseConnection();
        } catch (Exception $e) {
            die($e->getMessage());
        }
//var_dump(array("uname"=>$uname,"pass"=>$pword));
        //      var_dump($user);

        /*
         * Fails if username doesn't match a DB entry
         */
        if (!isset($user) or count($user) <= 0) {

          
            return FALSE; //"Your username or password is invalid.";
        } else {
            

           

            return TRUE; //success
        }
    }
    

    /*
     * 
     * Delete user provided by his ID 
     * 
     */

    public function processDeleteUser() {
        /*
         * Fails if the proper action was not submitted
         */
        // echo "<script>alert('".$_POST['input-username']."')</script>";
        if ($_POST['action'] != 'user_delete') {
            return "Invalid action supplied for process Delete User.";
        }
        /*
         * Escapes the user input for security
         */
        $unid = htmlentities($_POST['input-id'], ENT_QUOTES);


        /*
         * Retrieves the matching info from the DB if it exists
         */
        $sql = "delete from members where Members_id=:id";
        try {
            $res = $this->query($sql, array("id" => $unid));
            $this->log->logActions($_SESSION['user']['email'], 'Delete User', 'Success Delete', 'users ID :' . $unid . ' deleted');
        } catch (Exception $e) {
            // $this->db=null;
            return ($e->getMessage());
            $this->log->logActions($_SESSION['user']['email'], 'Delete User', 'Faild Delete due to ' . $e->getMessage(), 'users ID :' . $unid . ' deleted');
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

    /*     * ************************************************************
     * Edit users depending on the selected user
     * 
     * **************************
     */

    public function processEditUsers() {
        /*
         * Fails if the proper action was not submitted
         */
        // echo "<script>alert('".$_POST['input-username']."')</script>";
        if ($_POST['action'] != 'user_EditUser') {
            return "Invalid action supplied for process Edit User.";
        }
        /*
         * Escapes the user input for security
         */
        $unId = htmlentities($_POST['select-user'], ENT_QUOTES);
        $uTeam = htmlentities($_POST['select_team'], ENT_QUOTES);
        $uWebsites = htmlentities($_POST['_websites'], ENT_QUOTES);



        /*
         * Retrieves the matching info from the DB if it exists
         */
        $sql = "update members set Members_Team_FK=:team,Members_websites=:websites where  Members_id=:id";
        try {
            $res = $this->query($sql, array("id" => $unId, "team" => $uTeam, "websites" => $uWebsites));
        } catch (Exception $e) {
            // $this->db=null;
            return ($e->getMessage());
        }
        // return 'result is '.$res;

        /*
         * Fails if username doesn't match a DB entry
         */
        if (!$res) {
            return FALSE; //"Your username or password is invalid.";
        } else {

            return TRUE; //success}
        }
    }

    /*     * ****************************************
     * end edit user
     * ****************************************
     */
    /*     * ************************************************************
     * Edit users Role depending on the selected user
     * 
     * **************************
     */

    public function processEditUsersRole() {
        /*
         * Fails if the proper action was not submitted
         */
        // echo "<script>alert('".$_POST['input-username']."')</script>";
        if ($_POST['action'] != 'user_EditUserRole') {
            return "Invalid action supplied for process Edit User.";
        }
        /*
         * Escapes the user input for security
         */

        $unId = htmlentities($_POST['select-user'], ENT_QUOTES);
        $uweb = htmlentities($_POST['select-websites'], ENT_QUOTES);
        $uRole = htmlentities($_POST['select-websitesRole'], ENT_QUOTES);



        /*
         * Retrieves the matching info from the DB if it exists
         */
        /*
         * * 
         * 
         */

        $sql = "INSERT INTO member_website_role (Member_Id_FK,Website_Id_FK,Role_Id_FK) VALUES (:uid , :webid, :roleid) ON DUPLICATE KEY UPDATE  Role_Id_FK=:roleid1 ";

        try {
            $res = $this->query($sql, array("uid" => $unId, "webid" => $uweb, "roleid" => $uRole, "roleid1" => $uRole));
            $this->log->logActions($_SESSION['user']['email'], 'Edit User', 'Success Edit', 'users ID :' . $unId . ' Edit with values webid=' . $uweb . '& role : ' . $uRole);
        } catch (Exception $e) {
            // $this->db=null;
            $this->log->logActions($_SESSION['user']['email'], 'Delete User', 'Faild Edit due to ' . $e->getMessage(), 'users ID :' . $unId . ' Edit');
            return ($e->getMessage());
        }
        // return 'result is '.$res;

        /*
         * Fails if username doesn't match a DB entry
         */
        if (!$res) {
            return FALSE; //"Your username or password is invalid.";
        } else {

            return TRUE; //success}
        }
    }

    /*     * ****************************************
     * end edit user Role
     * ****************************************
     */

    /*
     * 
     * Add New USers
     * 
     */

    public function processAddUser() {
        /*
         * Fails if the proper action was not submitted
         */
        // echo "<script>alert('".$_POST['input-username']."')</script>";
        if ($_POST['action'] != 'user_add') {
            return "Invalid action supplied for process Delete User.";
        }
        /*
         * Escapes the user input for security
         */
        $unName = htmlentities($_POST['input-uName'], ENT_QUOTES);
        $unPassword = $this->getPasswordHash(htmlentities($_POST['input-pass'], ENT_QUOTES));
        $teamID_fk = htmlentities($_POST['select_Teams'], ENT_QUOTES);


        /*
         * Retrieves the matching info from the DB if it exists
         */
        $sql = "insert into members (Members_Email, Members_Password ,Members_Team_FK) VALUES(:name,md5(sha1(:pass)),:team)";
        try {

            $res = $this->query($sql, array("name" => $unName, "pass" => $unPassword, "team" => $teamID_fk));
            $this->log->logActions($_SESSION['user']['email'], 'Add User', 'Success Add', 'users Name :' . $unName . '');
        } catch (Exception $e) {
            // $this->db=null;

            $this->log->logActions($_SESSION['user']['email'], 'Delete User', 'Faild Add due to ' . $e->getMessage(), 'users Name :' . name . '');
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

//another login function but simpl
    public function Login($emailid, $password) {
        $res = mysql_query("SELECT * FROM users WHERE emailid = '" . $emailid . "' AND password = '" . md5($password) . "'");
        $user_data = mysql_fetch_array($res);
        //print_r($user_data);
        $no_rows = mysql_num_rows($res);

        if ($no_rows == 1) {

            $_SESSION['login'] = true;
            $_SESSION['uid'] = $user_data['id'];
            $_SESSION['username'] = $user_data['username'];
            $_SESSION['email'] = $user_data['emailid'];
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /*
     * To restet the password and send email for user with the new password
     * 
     */

    public function processForgetPasswordForm() {
        /*
         * Fails if the proper action was not submitted
         */
        //echo "<script>alert('".$_POST['input-captcha']."$$".$_SESSION['captcha']['code']."')</script>";
        if ($_POST['action'] != 'user_forgetPassword') {
            return FALSE; //se;//"Invalid action supplied for forget password Form.";
        }
        /*
         * Escapes the user input for security
         */
        $uname = htmlentities($_POST['input-email'], ENT_QUOTES);
        $pword = htmlentities($_POST['input-captcha'], ENT_QUOTES);
        //    if ($pword != $_SESSION['captcha']['code']) {
        if ($pword != '12345678') {
            return FALSE; //"Your username or captcha  is invalid.";
        }

        /*
         * Retrieves the matching info from the DB if it exists
         */
        $pass = $this->getPasswordHash($this->randomPassword());
        //$sql = "call sp_UserResetPassword(:uname,:pass)";
        $sql = "update members set Members_Password=md5(sha1(:pass)) where Members_Email=:uname";
        try {

            $countRows = $this->query($sql, array("pass" => $pass, "uname" => $uname));
            //var_dump($countRows);
            if ($countRows > 0) {


                $this->log->logActions($_SESSION['user']['email'], 'Change Pass random User', 'Success change', 'users Name :' . $uname . ' Chnaged');

                return $_SESSION['NEWPASS'];
            } else {
                return FALSE;
            }
            //success
        } catch (Exception $e) {

            $this->log->logActions($_SESSION['user']['email'], 'Change Pass random User', 'Faild change due to ' . $e->getMessage(), 'users Name :' . $uname . ' deleted');
            return ($e->getMessage());
        }
    }

    /*     * ********************
     * 
     * 
     *    get web sites and team for a user
     * 
     * ***************************** */

    public function webSitesAndTeam($userID) {
        /*
         * Fails if the proper action was not submitted
         */
        // echo "<script>alert('".$_POST['input-username']."')</script>";
        if ($_POST['action'] != 'user_getTeamAndWebsites') {
            return "Invalid action supplied for that Form.";
        }
        /*
         * Escapes the user input for security
         */
        $uname = htmlentities($_POST['select-username'], ENT_QUOTES);



        // Single Row

        $user = array();
        //$sql = "call sp_UserLoginCheck(:uname,:pass)";
        $sql = "select `Members_Team_FK`,`Members_websites` from members where `Members_id`=:uid";
        // $sql="select distinct Members_Email,Members_id from members where Members_Email='".$uname."' and Members_Password='""'";
        try {


            $user = $this->row($sql, array("uid" => $uname));
            // $this->CloseConnection();
        } catch (Exception $e) {
            die($e->getMessage());
        }

        // var_dump($user);

        /*
         * Fails if username doesn't match a DB entry
         */
        if (!isset($user)) {
            return FALSE; //"Your username or password is invalid.";
        } else {

            return $user; //success
        }
    }

//end of getting webSitesAndTeam


    /*     * *****************
     * 
     * end of getting the web sites and the team
     * 
     * 
     * ***************** */
    /*
     * To Change the password and send email for user with the new password
     * 
     */

    public function processChangePasswordForm() {
        /*
         * Fails if the proper action was not submitted
         */
        //echo "<script>alert('".$_POST['input-captcha']."$$".$_SESSION['captcha']['code']."')</script>";
        if ($_POST['action'] != 'user_changePassword') {
            return "Invalid action supplied for forget password Form.";
        }
        /*
         * Escapes the user input for security
         */
        $oldpword = htmlentities($_POST['input-oldPass'], ENT_QUOTES);
        $pword = htmlentities($_POST['input-pass'], ENT_QUOTES);
        if (!isset($_SESSION['user']['id'])) {
            return "Your password is invalid. try to lo out then login again";
        }

        /*
         * Retrieves the matching info from the DB if it exists
         */

        $sql = "UPDATE members SET Members_Password=MD5(SHA1(:pass)) WHERE Members_id=:uID AND Members_Password=MD5(SHA1(:oldpass))";
        try {
            $countRows = $this->query($sql, array("uID" => $_SESSION['user']['id'], "oldpass" => $this->getPasswordHash($oldpword), "pass" => $this->getPasswordHash($pword)));

            // $user = array_shift($stmt->fetchAll());

            if ($countRows > 0) {
                return true;
            } else {
                return $countRows;
            }
            //return 'DONE '.$countRows.' row';//success
        } catch (Exception $e) {
            return ($e->getMessage());
        }
    }

    /*
     * Genrate a randome password
     * 
     */

    protected function randomPassword() {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789!@#$%^&*()_><|";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        $_SESSION['NEWPASS'] = implode($pass);
        return implode($pass); //turn the array into a string
    }

    /*
     * Check if user is in our data base before
     */

    public function isUserExist($emailid) {
        $qr = mysql_query("SELECT * FROM users WHERE emailid = '" . $emailid . "'");
        echo $row = mysql_num_rows($qr);
        if ($row > 0) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * hash function
     * 
     */

    public function getPasswordHash($str) {
        $salt2 = 'a017a8994138df4ee18e335e3605156b'; //
        // $str1 = (substr($str, 0, 5) . SLAT.$salt2);
        // $str2 = (substr($str, 5) .$salt2.SLAT);
        // 
        $str1 = (substr($str, 0, 5) . SLAT);
        $str2 = (substr($str, 5) . SLAT);
        $str1_temp = md5(sha1($str1));
        $str2_temp = sha1(md5($str2));
        $str_temp = sha1(md5($str1_temp + $str2_temp));
//$str_temp = sha1($str);
        // $str_temp = ($str1 + $str2);
        return $str_temp;
    }

}

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
class cnco_item_config extends DB_Connect {
    
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
    public function AddNewCnocHandoverCategory() {
        
        $Cat_name=$_POST['input-catName'];
        $qr = $this->query("INSERT INTO cnoc_handover_category(`Cat_name`, `creator`) values('" .htmlentities( $Cat_name) . "','" . $_SESSION['user']['email'] . "')") or die(mysql_error());
        return $qr;
    }

    /** get all Cncoc Categories for table view
     *
     * @return mixed TRUE on success, message on error
     */
    public function biuldCategoriesTable() {// Connection data (server_address, database, name, poassword)
       
         $sql = "SELECT `Cat_id`, `Cat_name`, `creator`, `create_time` FROM `cnoc_handover_category` ";
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
												<th>Category ID</th>
												<th class="hidden-phone">Category Name</th>
												<th class="hidden-phone">Added By</th>
                                                                                                <th class="hidden-phone">Create Time</th>
												
												<th>Actions</th>
											</tr>
										</thead>
										<tbody>';
                foreach ($result as $row) {

                    $html.='<tr class="odd gradeX">
												<td><input type="checkbox" class="checkboxes" value="' . $row['Cat_id'] . '" /></td>
												<td>'.$row['Cat_id'].'</td>
                                                                                                    <td>'.$row['Cat_name'].'</td>
												<td class="hidden-phone"><a href="mailto:' . $row['creator'] . '@etisalat.ae">' . $row['creator'] . '</a></td>
												<td class="hidden-phone">' . ($row['create_time'] == '' ? '-' : $row['create_time']) . '</td>
												
												';
                    IF(TRUE)
                    {$html.='<td class="center">
													
													<a href="#" class="icon huge"><i class="icon-remove" id="removeCnocCat_'.$row['Cat_id'].'" data-d="input-id='.$row['Cat_id'].'&token='.$_SESSION['token'].'&action=cnocHandoverCat_delete"></i></a>&nbsp;		
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
    public function processDeleteCnocHandoverCat() {
        /*
         * Fails if the proper action was not submitted
         */
        // echo "<script>alert('".$_POST['input-username']."')</script>";
        if ($_POST['action'] != 'cnocHandoverCat_delete') {
            return "Invalid action supplied for process Delete CNOC Handover Category.";
        }
        /*
         * Escapes the user input for security
         */
        $unid = htmlentities($_POST['input-id'], ENT_QUOTES);
      

        /*
         * Retrieves the matching info from the DB if it exists
         */
        $sql = "delete from cnoc_handover_category where Cat_id=:id";
        try {
            $res=  $this->query($sql ,array("id"=>$unid));
            $this->log->logActions($_SESSION['user']['email'], 'Delete CNOC Handover Category', 'Success Delete', 'Cat ID :'.$unid.' deleted');
           
        } catch (Exception $e) {
           // $this->db=null;
            return ($e->getMessage());
            $this->log->logActions($_SESSION['user']['email'], 'Delete CNOC Handover Category', 'Faild Delete due to '.$e->getMessage(), 'Cat ID :'.$unid.' deleted');
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
    public function getAllCnocHandoverCatName() {// Connection data (server_address, database, name, poassword)
        
    
       
                $sql = "SELECT `Cat_name`,`Cat_id` FROM `cnoc_handover_category` order by Cat_name ";
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
    

  

//end of getting webSitesAndTeam




 
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
          $salt2='a017a8994138df4ee18e335e3605156b';//
       // $str1 = (substr($str, 0, 5) . SLAT.$salt2);
       // $str2 = (substr($str, 5) .$salt2.SLAT);
       // 
       $str1 = (substr($str, 0, 5). SLAT);
        $str2 = (substr($str, 5).SLAT);
        $str1_temp = md5(sha1($str1));
        $str2_temp = sha1(md5($str2));
        $str_temp = sha1(md5($str1_temp + $str2_temp));
//$str_temp = sha1($str);
       // $str_temp = ($str1 + $str2);
        return $str_temp ;
    }

}

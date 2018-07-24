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
class websites extends DB_Connect {
//put your code here
    
    	private $db;

	public $variables;

    public function __construct($data = array()) {
        
        parent::__construct();
        
        $this->variables  = $data;
       // $this->db=  $this->pdo;
    }
    
    /*
     * build user selections options to be selected
     */
    public function biuldWebsitessOptions() {// Connection data (server_address, database, name, poassword)
       
         $sql = "select WebSites_Id,WebSites_Name from websites";
        $html="";
        $result=array();
       try {
           $result=  $this->query($sql);
           }
       
         catch (PDOException $e) {
           
            return($e->getMessage());
        }
        

 
        
        
        $html = '<option value="0"></option>';
                foreach ($result as $row) {

                    $html.='<option value="'.$row['WebSites_Id'].'">'.$row['WebSites_Name'].'</option>';


                   
                }
          

            
        
        $html.='';

        return $html;
        
    }
    public function getWebName()
    {
        
            if ($_POST['action'] != '_getWebName') {
            return "Invalid action supplied for process Ewebsites name.";
        }
        /*
         * Escapes the user input for security
         */
        $webId = htmlentities($_POST['web_id'], ENT_QUOTES);
        
         $sql = "select distinct WebSites_Name from websites where WebSites_Id=:id ";
        $html="";
        $result=array();
       try {
           $result=  $this->query($sql,array("id"=>$webId));
           }
       
         catch (PDOException $e) {
           
            return($e->getMessage());
        }
        if ($webId==0) {
            return ' ';
        }
        return $result[0]['WebSites_Name'];
    }
    
    
    /****************************************************
     * return web site data to biuld what else
     * it depends on session web sites
     * rturn array of web sites data
     * 
     ************************************************************/
 public function getWebSitesData($websitesid=0)
    {
     $websitesid=0;
     if(isset($_SESSION['user']['webSites']) and $_SESSION['user']['webSites']!=NULL)
     {
          $websitesid=$_SESSION['user']['webSites'];
     }
     
                    

        
    /*$serial=$_POST['serial'];
                if ($_POST['action'] != 'getDeviceData') {
            return "Invalid action supplied for retrive Device Data.";
        }*/
          $sql = 'SELECT `WebSites_URL`, `WebSites_Name`, `logo`, `icon` FROM `websites` WHERE `WebSites_Id` in ('.$websitesid.')';
$msg='';
            try {
                
                $result = $this->query($sql);
                $webarr[]='';
                if ($result > 0) {

                    
                   return $result;
                }
                else { 
                    return FALSE;
                
                }
                
               
            } catch (PDOException $e) {
                
                return( 'error pdo ' . $e->getMessage());
            }
        //echo json_encode(array("phone"=>'123-12313',"email"=>'test@test.com','city'=>'Medicine Hat','address'=>'556 19th Street NE'));
        
        //return;
    }
    
    /************end of web sites get data *************************/
    
    
     /****************************************************
     * return web site Role based on user session 
     * it depends on session web sites
     * rturn array of web sites data
     * 
     ************************************************************/
 public function getWebSitesRole($websitesName=null)
    {
     
                     $websitesid=$_SESSION['user']['webSites'];
                     $team=$_SESSION['user']['team'];

        
    
          $sql = 'SELECT  `Roles_Name`,`Team_Name` FROM `memberdata` WHERE `WebSites_id` in ('.$websitesid.') and Members_id=:uid and WebSites_Name=:webName' ;
           
$msg='';

if($_SESSION['user']['team']!=7) //not super admin
{
            try {
                
                $result = $this->row($sql,array('uid'=>$_SESSION['user']['id'],'webName'=>$websitesName));
              //  $webarr[]='';
                if ($result > 0) {
                    
$_SESSION['currentWebSite']=$websitesName;
                $_SESSION['currentRole']=$result['Roles_Name'];
                $_SESSION['teamName']=$result['Team_Name'];
                
                    
                   return TRUE;
                }
                else { 
                    return FALSE;
                
                }
                
               
            } catch (PDOException $e) {
                
                return( 'error pdo ' . $e->getMessage());
            }


}//end if
else
{

                   
$_SESSION['currentWebSite']=$websitesName;
                $_SESSION['currentRole']='Admin';
                $_SESSION['teamName']='supperAdmins';
                
                    
                   return TRUE;

}
        //echo json_encode(array("phone"=>'123-12313',"email"=>'test@test.com','city'=>'Medicine Hat','address'=>'556 19th Street NE'));
        
        //return;
    }
    
    /************end of web sites get data *************************/
  
}

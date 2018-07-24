<?php
include_once 'DB_Connect.inc.php';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class subnet extends DB_Connect {
    
      private $db;
    public $variables;
    public $alertObj; //=new alerts();

    public function __construct($data = array()) {

        parent::__construct();

        $this->variables = $data;
        $this->alertObj = new alerts();

        // $this->db=  $this->pdo;
    }
   
    public function get_subnetDetails($my_net_info=null) {


//         if ($_POST['action'] != 'bib_AddSR' OR ( htmlentities($_SESSION['user']['email'], ENT_QUOTES)) == "dsm_r" OR ! isset($_SESSION['user']['email'])) {
//            return "Invalid action supplied for process Add new BIB SR.";
//        }
        //  $sr_tt = (htmlentities($_POST['txt-bib-sr_tt'], ENT_QUOTES));

        $my_net_info = isset($_POST['my_net_info'])?rtrim($_POST['my_net_info']):rtrim($my_net_info); rtrim($_POST['my_net_info']);


        if (!ereg('^([0-9]{1,3}\.){3}[0-9]{1,3}(( ([0-9]{1,3}\.){3}[0-9]{1,3})|(/[0-9]{1,2}))$', $my_net_info)) {
            return "Invalid action supplied for process generate subnet for .".$my_net_info;
            //exit ;
        }
        
        
        if (ereg("/",$my_net_info)){  //if cidr type mask
	$dq_host = strtok("$my_net_info", "/");
	$cdr_nmask = strtok("/");
	if (!($cdr_nmask >= 0 && $cdr_nmask <= 32)){
		return ("Invalid CIDR value. Try an integer 0 - 32.");
		//print "$end";
		//exit ;
	}
        
      //   print '<br/>my_net_info '.$my_net_info;
        //  print '<br/>cdr_nmask '.$cdr_nmask;
          //  print '<br/>calling cdrtobin then the output is ';
	$bin_nmask=$this->cdrtobin($cdr_nmask);
        
        
      //  print '<br/>bin_nmask '.$bin_nmask;
        //print '<br/>cdr_nmask'.$cdr_nmask;
       
        
        
	$bin_wmask=$this->binnmtowm($bin_nmask);
      //   print '<br/>bin_nmask output is '.$bin_wmask;
        
} else { //Dotted quad mask?  / loook at it later
    $dqs=explode(" ", $my_net_info);
	$dq_host=$dqs[0];
	$bin_nmask=dqtobin($dqs[1]);
	$bin_wmask=binnmtowm($bin_nmask);
	if (ereg("0",rtrim($bin_nmask, "0"))) {  //Wildcard mask then? hmm?
		$bin_wmask=dqtobin($dqs[1]);
		$bin_nmask=binwmtonm($bin_wmask);
		if (ereg("0",rtrim($bin_nmask, "0"))){ //If it's not wcard, whussup?
			return("Invalid Netmask.");
			//print "$end";
			//exit ;
		}
	}
	$cdr_nmask=bintocdr($bin_nmask);
}


 //print '<br/>if(! ereg("^0.",$dq_host))  '.(!ereg("^0.",$dq_host));
// print_r(explode(".",$dq_host));
 
// jsut for check valid ip
if(! ereg('^0.',$dq_host)){
	foreach( explode(".",$dq_host) as $octet ){
 		if($octet > 255){ 
                     return("Invalid IP Address");
		//	print $end ;
		//	exit;
		}
	
	}
}

// print '<br/>dqtobin($dq_host)  '.(!ereg("^0.",$dq_host));
$bin_host=$this->dqtobin($dq_host);
 //print '<br/>dqtobin($dq_host)->$bin_host  '.$bin_host;
 //print '<br/>dqtobin($dq_host)->$bin_host  '.bindec(substr($bin_host,-8));
 
 $bin_bcast=(str_pad(substr($bin_host,0,$cdr_nmask),32,1));
 
 
 
 //print '<br/>$cdr_nmask  '.$cdr_nmask;
 //print '<br/>substr($bin_host,0,$cdr_nmask)  '.substr($bin_host,0,$cdr_nmask);
//print '<br/>$bin_bcast  '.$bin_bcast;
//print '<br/>$bin_bcast  '.bindec(substr($bin_bcast,-8));

    
$bin_net=(str_pad(substr($bin_host,0,$cdr_nmask),32,0));//we changed 0 to 1

 //print '<br/>--------------<br/>$cdr_nmask  '.$cdr_nmask;
 //print '<br/>$bin_host  '.$bin_host;
 //print '<br/>substr($bin_host,0,$cdr_nmask)  '.substr($bin_host,0,$cdr_nmask);
//print '<br/>$bin_net  '.$bin_net;
//print '<br/>$bin_net  '.bindec(substr($bin_net,-8));

$bin_gate=(str_pad(substr($bin_host,0,31),32,1));//we changed 0 to 1

 //print '<br/>--------------<br/>substr($bin_host,0,31),32,1)  '.substr($bin_host,0,31);
 //print '<br/>$bin_host  '.$bin_host;
 //print '<br/>$bin_host  '.bindec(substr($bin_host,-8));
// print '<br/>substr($bin_host,0,$cdr_nmask)  '.substr($bin_host,0,$cdr_nmask);
//print '<br/>$bin_gate  '.$bin_gate;
//print '<br/>$bin_gate  '.bindec(substr($bin_gate,-8));

//decbin($number);
//////////////////////// bin to dec and dec to bin///////
//bindec(substr($bin_gate,-8)//bin to decimal
//decbin($number);
//
//
////////////////////////////////////////////
$bin_first=(str_pad(substr($bin_net,0,31),32,0));
// print '<br/>--------------<br/>substr($bin_net,0,31)  '.substr($bin_net,0,31);
// print '<br/>$bin_net  '.$bin_net;
// print '<br/>$bin_first  '.$bin_first;
// print '<br/>$bin_first  '.bindec(substr($bin_first,-8));
 
 
//print '<br/>1$bin_first to dec  '.bindec(($bin_first));
//print '<br/>1$bin_first to dec  '.$this->bintodq(($bin_first));
// print '<br/>2-correct $bin_first to dec  '.(bindec(($bin_first))+4);
//print '<br/>correct $bin_first to bin  '.decbin(bindec(($bin_first))+4);
//print '<br/>correct $bin_first to bin  '.$this->bintodq(decbin(bindec(($bin_first))+4));

$fitsr24=substr($bin_host,0,24);
$last8= substr($bin_host,-8);
// print '<br/>correct $bin_first  bin  '.$bin_host;
// print '<br/>correct $last8  bin  '.$last8;
// 
//  print '<br/>correct $fitsr24  bin  '.$fitsr24;
//  print '<br/>correct $last8  dec  '.bindec($last8);
//  print '<br/>correct $last8  after add 4  '.(bindec($last8)+4);
//  print '<br/>correct $last8  bin  '.str_pad(decbin(bindec($last8)+4), 8, "0", STR_PAD_LEFT);
  
  $correctfirst=$fitsr24.str_pad(decbin(bindec($last8)+4), 8, "0", STR_PAD_LEFT);
  
//   print '<br/>correct $correctfirst  after add 4  '.$correctfirst;
  
  
  


 
 
$bin_last=(str_pad(substr($bin_bcast,0,31),32,0));
// print '<br/>--------------<br/>substr($bin_bcast,0,31)  '.substr($bin_bcast,0,31);
// print '<br/>$bin_net  '.$bin_bcast;
// print '<br/>$bin_first  '.$bin_last;
// print '<br/>$bin_first  '.bindec(substr($bin_last,-8));


$host_total=(bindec(str_pad("",(32-$cdr_nmask),1)) - 5);

// print '<br/>--------------<br/>bindec(str_pad("",(32-$cdr_nmask),1))  '.bindec(str_pad("",(32-$cdr_nmask),1));
// print '<br/>$host_total  '.$host_total;
 
 
 if ($host_total <= 0){  //Takes care of 31 and 32 bit masks.
	$bin_first="N/A" ; $bin_last="N/A" ; $host_total="N/A";
	if ($bin_net === $bin_bcast) $bin_bcast="N/A";
}





//print '<br/>-------output-------<br/>';

//Determine Class
if (ereg('^0',$bin_gate)){
	$class="A";
	$dotbin_net= "<font color=\"Green\">0</font>" . substr($this->dotbin($bin_gate,$cdr_nmask),1) ;
}elseif (ereg('^10',$bin_gate)){
	$class="B";
	$dotbin_net= "<font color=\"Green\">10</font>" . substr($this->dotbin($bin_gate,$cdr_nmask),2) ;
}elseif (ereg('^110',$bin_gate)){
  	$class="C";
	$dotbin_net= "<font color=\"Green\">110</font>" . substr($this->dotbin($bin_gate,$cdr_nmask),3) ;
}elseif (ereg('^1110',$bin_gate)){
  	$class="D";
	$dotbin_net= "<font color=\"Green\">1110</font>" . substr($this->dotbin($bin_gate,$cdr_nmask),4) ;
	$special="<font color=\"Green\">Class D = Multicast Address Space.</font>";
}else{
  	$class="E";
	$dotbin_net= "<font color=\"Green\">1111</font>" . substr($this->dotbin($bin_gate,$cdr_nmask),4) ;
	$special="<font color=\"Green\">Class E = Experimental Address Space.</font>";
}


if (ereg('^(00001010)|(101011000001)|(1100000010101000)',$bin_gate)){
  	 $special='';
}




//// Print Results
//$this->tr('Address:',"<font color=\"blue\">$dq_host</font>",
//	'<font color="brown">'. $this->dotbin($bin_host,$cdr_nmask).'</font>');
//print '<br/>-------output-------<br/>';
//$this->tr('Netmask:','<font color="blue">'.$this->bintodq($bin_nmask)." = $cdr_nmask</font>",
//	'<font color="red">'.$this->dotbin($bin_nmask, $cdr_nmask).'</font>');
//print '<br/>-------output-------<br/>';
//$this->tr('Wildcard:', '<font color="blue">'.$this->bintodq($bin_wmask).'</font>',
//	'<font color="brown">'.$this->dotbin($bin_wmask, $cdr_nmask).'</font>');
//
//print '<br/>-------output-------<br/>';
//$this->tr('Network:', '<font color="blue">'.$this->bintodq($bin_net).'xx</font>',
//	"<font color=\"brown\">$dotbin_net</font>","<font color=\"Green\">(Class $class)</font>");
//
//print '<br/>-------output-------<br/>';
//$this->tr('Broadcast:','<font color="blue">'.$this->bintodq($bin_bcast).'</font>',
//	'<font color="brown">'.$this->dotbin($bin_bcast, $cdr_nmask).'</font>');
//
//print '<br/>-------output-------<br/>';
//$this->tr('HostMin:', '<font color="blue">'.$this->bintodq($bin_first).'</font>',
//	'<font color="brown">'.$this->dotbin($bin_first, $cdr_nmask).'</font>');
//
//print '<br/>-------output-------<br/>';
//$this->tr('HostMax:', '<font color="blue">'.$this->bintodq($bin_last).'</font>',
//	'<font color="brown">'.$this->dotbin($bin_last, $cdr_nmask).'</font>');
//
//print '<br/>-------output-------<br/>';
//@$this->tr('Hosts/Net:', '<font color="blue">'.$host_total.'</font>', "$special");
//
//print '<br/>-------new output-------<br/>';

/*
 * subnet:   -------> $my_net_info
Gateway  ---------->  $this->bintodq($bin_gate)
Broadcast:  -->  $this->bintodq($bin_bcast)
BTSC Reserved
HostMin: -->  $this->bintodq(decbin(bindec(($bin_first))+4))
HostMax: -->  $this->bintodq($bin_last)
Hosts/Valid: -->  $host_total

 */
//print '<br/>-------subnet-------<br/>'.$my_net_info;
//print '<br/>-------Gateway-------<br/>'.$this->bintodq($bin_gate);
//print '<br/>-------Broadcast-------<br/>'.$this->bintodq($bin_bcast);
//print '<br/>-------HostMin-------<br/>'.$this->bintodq($correctfirst);
//print '<br/>-------HostMax-------<br/>'.$this->bintodq($bin_last);
//print '<br/>-------Hosts/Valid-------<br/>'.$host_total;
//print '<br/>-------END new output-------<br/>';
$subnet=null;
$subnet['subnet']=$my_net_info;
$subnet['Gateway']=$this->bintodq($bin_gate);
$subnet['Broadcast']=$this->bintodq($bin_bcast);
$subnet['HostMin']=$this->bintodq($correctfirst);
$subnet['HostMax']=$this->bintodq($bin_last);
$subnet['Hosts_Valid']=$host_total;
//$subnet['Hosts_Valid']=;


//$i=1;
//$st=bindec(($bin_first))+4))


 //  print '<br/>correct bindec($correctfirst) '.bindec($correctfirst);
//   print '<br/>correct bindec($bin_last) '.bindec($bin_last);
//for ($index = (bindec($correctfirst)); $index <= bindec($bin_last); $index++) {
//    print '<br/>-------IPI#'.$i.'-------<br/>'.$this->bintodq(decbin($index));
//    
//    
//    $i++;
//    
//}

//print '<br/>-------IP Range-------<br/>';
//$range_one =$this->bintodq($correctfirst); //"10.0.0.4";
//$range_two =$this->bintodq($bin_last);// "10.0.1.14";
//$ip1 = ip2long ($range_one);
//$ip2 = ip2long ($range_two);
//while ($ip1 <= $ip2) {
//    print '<br/><br/>';
//    print_r (long2ip($ip1) . "\n");
//    $ip1 ++;
//
//}


//print_r($special);

       // print_r($subnet);
 
        return json_encode($subnet);

    }  
    
     public function get_subnetDetailsDB($my_net_info=null) {


//         if ($_POST['action'] != 'bib_AddSR' OR ( htmlentities($_SESSION['user']['email'], ENT_QUOTES)) == "dsm_r" OR ! isset($_SESSION['user']['email'])) {
//            return "Invalid action supplied for process Add new BIB SR.";
//        }
        //  $sr_tt = (htmlentities($_POST['txt-bib-sr_tt'], ENT_QUOTES));

        $my_net_info = isset($_POST['my_net_info'])?rtrim($_POST['my_net_info']):rtrim($my_net_info); rtrim($_POST['my_net_info']);


        if (!ereg('^([0-9]{1,3}\.){3}[0-9]{1,3}(( ([0-9]{1,3}\.){3}[0-9]{1,3})|(/[0-9]{1,2}))$', $my_net_info)) {
            return "Invalid action supplied for process generate subnet for .".$my_net_info;
            //exit ;
        }
        
        
        if (ereg("/",$my_net_info)){  //if cidr type mask
	$dq_host = strtok("$my_net_info", "/");
	$cdr_nmask = strtok("/");
	if (!($cdr_nmask >= 0 && $cdr_nmask <= 32)){
		return ("Invalid CIDR value. Try an integer 0 - 32.");
		//print "$end";
		//exit ;
	}
        
      //   print '<br/>my_net_info '.$my_net_info;
        //  print '<br/>cdr_nmask '.$cdr_nmask;
          //  print '<br/>calling cdrtobin then the output is ';
	$bin_nmask=$this->cdrtobin($cdr_nmask);
        
        
      //  print '<br/>bin_nmask '.$bin_nmask;
        //print '<br/>cdr_nmask'.$cdr_nmask;
       
        
        
	$bin_wmask=$this->binnmtowm($bin_nmask);
      //   print '<br/>bin_nmask output is '.$bin_wmask;
        
} else { //Dotted quad mask?  / loook at it later
    $dqs=explode(" ", $my_net_info);
	$dq_host=$dqs[0];
	$bin_nmask=dqtobin($dqs[1]);
	$bin_wmask=binnmtowm($bin_nmask);
	if (ereg("0",rtrim($bin_nmask, "0"))) {  //Wildcard mask then? hmm?
		$bin_wmask=dqtobin($dqs[1]);
		$bin_nmask=binwmtonm($bin_wmask);
		if (ereg("0",rtrim($bin_nmask, "0"))){ //If it's not wcard, whussup?
			return("Invalid Netmask.");
			//print "$end";
			//exit ;
		}
	}
	$cdr_nmask=bintocdr($bin_nmask);
}


 //print '<br/>if(! ereg("^0.",$dq_host))  '.(!ereg("^0.",$dq_host));
// print_r(explode(".",$dq_host));
 
// jsut for check valid ip
if(! ereg('^0.',$dq_host)){
	foreach( explode(".",$dq_host) as $octet ){
 		if($octet > 255){ 
                     return("Invalid IP Address");
		//	print $end ;
		//	exit;
		}
	
	}
}

// print '<br/>dqtobin($dq_host)  '.(!ereg("^0.",$dq_host));
$bin_host=$this->dqtobin($dq_host);
 //print '<br/>dqtobin($dq_host)->$bin_host  '.$bin_host;
 //print '<br/>dqtobin($dq_host)->$bin_host  '.bindec(substr($bin_host,-8));
 
 $bin_bcast=(str_pad(substr($bin_host,0,$cdr_nmask),32,1));
 
 
 
 //print '<br/>$cdr_nmask  '.$cdr_nmask;
 //print '<br/>substr($bin_host,0,$cdr_nmask)  '.substr($bin_host,0,$cdr_nmask);
//print '<br/>$bin_bcast  '.$bin_bcast;
//print '<br/>$bin_bcast  '.bindec(substr($bin_bcast,-8));

    
$bin_net=(str_pad(substr($bin_host,0,$cdr_nmask),32,0));//we changed 0 to 1

 //print '<br/>--------------<br/>$cdr_nmask  '.$cdr_nmask;
 //print '<br/>$bin_host  '.$bin_host;
 //print '<br/>substr($bin_host,0,$cdr_nmask)  '.substr($bin_host,0,$cdr_nmask);
//print '<br/>$bin_net  '.$bin_net;
//print '<br/>$bin_net  '.bindec(substr($bin_net,-8));

$bin_gate=(str_pad(substr($bin_host,0,31),32,1));//we changed 0 to 1

 //print '<br/>--------------<br/>substr($bin_host,0,31),32,1)  '.substr($bin_host,0,31);
 //print '<br/>$bin_host  '.$bin_host;
 //print '<br/>$bin_host  '.bindec(substr($bin_host,-8));
// print '<br/>substr($bin_host,0,$cdr_nmask)  '.substr($bin_host,0,$cdr_nmask);
//print '<br/>$bin_gate  '.$bin_gate;
//print '<br/>$bin_gate  '.bindec(substr($bin_gate,-8));

//decbin($number);
//////////////////////// bin to dec and dec to bin///////
//bindec(substr($bin_gate,-8)//bin to decimal
//decbin($number);
//
//
////////////////////////////////////////////
$bin_first=(str_pad(substr($bin_net,0,31),32,0));
// print '<br/>--------------<br/>substr($bin_net,0,31)  '.substr($bin_net,0,31);
// print '<br/>$bin_net  '.$bin_net;
// print '<br/>$bin_first  '.$bin_first;
// print '<br/>$bin_first  '.bindec(substr($bin_first,-8));
 
 
//print '<br/>1$bin_first to dec  '.bindec(($bin_first));
//print '<br/>1$bin_first to dec  '.$this->bintodq(($bin_first));
// print '<br/>2-correct $bin_first to dec  '.(bindec(($bin_first))+4);
//print '<br/>correct $bin_first to bin  '.decbin(bindec(($bin_first))+4);
//print '<br/>correct $bin_first to bin  '.$this->bintodq(decbin(bindec(($bin_first))+4));

$fitsr24=substr($bin_host,0,24);
$last8= substr($bin_host,-8);
// print '<br/>correct $bin_first  bin  '.$bin_host;
// print '<br/>correct $last8  bin  '.$last8;
// 
//  print '<br/>correct $fitsr24  bin  '.$fitsr24;
//  print '<br/>correct $last8  dec  '.bindec($last8);
//  print '<br/>correct $last8  after add 4  '.(bindec($last8)+4);
//  print '<br/>correct $last8  bin  '.str_pad(decbin(bindec($last8)+4), 8, "0", STR_PAD_LEFT);
  
  $correctfirst=$fitsr24.str_pad(decbin(bindec($last8)+4), 8, "0", STR_PAD_LEFT);
  
//   print '<br/>correct $correctfirst  after add 4  '.$correctfirst;
  
  
  


 
 
$bin_last=(str_pad(substr($bin_bcast,0,31),32,0));
// print '<br/>--------------<br/>substr($bin_bcast,0,31)  '.substr($bin_bcast,0,31);
// print '<br/>$bin_net  '.$bin_bcast;
// print '<br/>$bin_first  '.$bin_last;
// print '<br/>$bin_first  '.bindec(substr($bin_last,-8));


$host_total=(bindec(str_pad("",(32-$cdr_nmask),1)) - 5);

// print '<br/>--------------<br/>bindec(str_pad("",(32-$cdr_nmask),1))  '.bindec(str_pad("",(32-$cdr_nmask),1));
// print '<br/>$host_total  '.$host_total;
 
 
 if ($host_total <= 0){  //Takes care of 31 and 32 bit masks.
	$bin_first="N/A" ; $bin_last="N/A" ; $host_total="N/A";
	if ($bin_net === $bin_bcast) $bin_bcast="N/A";
}





//print '<br/>-------output-------<br/>';

//Determine Class
if (ereg('^0',$bin_gate)){
	$class="A";
	$dotbin_net= "<font color=\"Green\">0</font>" . substr($this->dotbin($bin_gate,$cdr_nmask),1) ;
}elseif (ereg('^10',$bin_gate)){
	$class="B";
	$dotbin_net= "<font color=\"Green\">10</font>" . substr($this->dotbin($bin_gate,$cdr_nmask),2) ;
}elseif (ereg('^110',$bin_gate)){
  	$class="C";
	$dotbin_net= "<font color=\"Green\">110</font>" . substr($this->dotbin($bin_gate,$cdr_nmask),3) ;
}elseif (ereg('^1110',$bin_gate)){
  	$class="D";
	$dotbin_net= "<font color=\"Green\">1110</font>" . substr($this->dotbin($bin_gate,$cdr_nmask),4) ;
	$special="<font color=\"Green\">Class D = Multicast Address Space.</font>";
}else{
  	$class="E";
	$dotbin_net= "<font color=\"Green\">1111</font>" . substr($this->dotbin($bin_gate,$cdr_nmask),4) ;
	$special="<font color=\"Green\">Class E = Experimental Address Space.</font>";
}


if (ereg('^(00001010)|(101011000001)|(1100000010101000)',$bin_gate)){
  	 $special='';
}




//// Print Results
//$this->tr('Address:',"<font color=\"blue\">$dq_host</font>",
//	'<font color="brown">'. $this->dotbin($bin_host,$cdr_nmask).'</font>');
//print '<br/>-------output-------<br/>';
//$this->tr('Netmask:','<font color="blue">'.$this->bintodq($bin_nmask)." = $cdr_nmask</font>",
//	'<font color="red">'.$this->dotbin($bin_nmask, $cdr_nmask).'</font>');
//print '<br/>-------output-------<br/>';
//$this->tr('Wildcard:', '<font color="blue">'.$this->bintodq($bin_wmask).'</font>',
//	'<font color="brown">'.$this->dotbin($bin_wmask, $cdr_nmask).'</font>');
//
//print '<br/>-------output-------<br/>';
//$this->tr('Network:', '<font color="blue">'.$this->bintodq($bin_net).'xx</font>',
//	"<font color=\"brown\">$dotbin_net</font>","<font color=\"Green\">(Class $class)</font>");
//
//print '<br/>-------output-------<br/>';
//$this->tr('Broadcast:','<font color="blue">'.$this->bintodq($bin_bcast).'</font>',
//	'<font color="brown">'.$this->dotbin($bin_bcast, $cdr_nmask).'</font>');
//
//print '<br/>-------output-------<br/>';
//$this->tr('HostMin:', '<font color="blue">'.$this->bintodq($bin_first).'</font>',
//	'<font color="brown">'.$this->dotbin($bin_first, $cdr_nmask).'</font>');
//
//print '<br/>-------output-------<br/>';
//$this->tr('HostMax:', '<font color="blue">'.$this->bintodq($bin_last).'</font>',
//	'<font color="brown">'.$this->dotbin($bin_last, $cdr_nmask).'</font>');
//
//print '<br/>-------output-------<br/>';
//@$this->tr('Hosts/Net:', '<font color="blue">'.$host_total.'</font>', "$special");
//
//print '<br/>-------new output-------<br/>';

/*
 * subnet:   -------> $my_net_info
Gateway  ---------->  $this->bintodq($bin_gate)
Broadcast:  -->  $this->bintodq($bin_bcast)
BTSC Reserved
HostMin: -->  $this->bintodq(decbin(bindec(($bin_first))+4))
HostMax: -->  $this->bintodq($bin_last)
Hosts/Valid: -->  $host_total

 */
//print '<br/>-------subnet-------<br/>'.$my_net_info;
//print '<br/>-------Gateway-------<br/>'.$this->bintodq($bin_gate);
//print '<br/>-------Broadcast-------<br/>'.$this->bintodq($bin_bcast);
//print '<br/>-------HostMin-------<br/>'.$this->bintodq($correctfirst);
//print '<br/>-------HostMax-------<br/>'.$this->bintodq($bin_last);
//print '<br/>-------Hosts/Valid-------<br/>'.$host_total;
//print '<br/>-------END new output-------<br/>';
$subnet=null;
$subnet['subnet']=$my_net_info;
$subnet['Gateway']=$this->bintodq($bin_gate);
$subnet['Broadcast']=$this->bintodq($bin_bcast);
$subnet['HostMin']=$this->bintodq($correctfirst);
$subnet['HostMax']=$this->bintodq($bin_last);
$subnet['Hosts_Valid']=$host_total;
//$subnet['Hosts_Valid']=;


//$i=1;
//$st=bindec(($bin_first))+4))


 //  print '<br/>correct bindec($correctfirst) '.bindec($correctfirst);
//   print '<br/>correct bindec($bin_last) '.bindec($bin_last);
//for ($index = (bindec($correctfirst)); $index <= bindec($bin_last); $index++) {
//    print '<br/>-------IPI#'.$i.'-------<br/>'.$this->bintodq(decbin($index));
//    
//    
//    $i++;
//    
//}

//print '<br/>-------IP Range-------<br/>';
//$range_one =$this->bintodq($correctfirst); //"10.0.0.4";
//$range_two =$this->bintodq($bin_last);// "10.0.1.14";
//$ip1 = ip2long ($range_one);
//$ip2 = ip2long ($range_two);
//while ($ip1 <= $ip2) {
//    print '<br/><br/>';
//    print_r (long2ip($ip1) . "\n");
//    $ip1 ++;
//
//}


//print_r($special);

       // print_r($subnet);
 
    //    return json_encode($subnet);

 $sql = "UPDATE `bib_subnets` SET `Gateway`='".$subnet['Gateway']."',`SubRange_from`='".$subnet['HostMin']."',`SubRange_to`='".$subnet['HostMax']."' WHERE subnet_name='".$subnet['subnet']."'";
         try {
             
               $countRows = $this->query($sql);
             
         } catch (Exception $ex) {
             return $ex->getMessage();
         }


    }  
    
    public function subnetcorrect()
    {
                $sql = "SELECT subnet_name FROM bib_subnets";
        $html = "";
        $msg="started";
        $result = array();
        try {
            $result = $this->query($sql, $arr);
        } catch (PDOException $e) {

            return($e->getMessage());
        }
        
        foreach ($result as $row) {
            
             $msg= $this->get_subnetDetailsDB($row['subnet_name']);
        }
          $msg=$msg."DONE";
          
          return $msg;
        
    }
            
    
    
   function subnet_ipRange($my_net_info=null)
    {
        $my_net_info = @isset($_POST['my_net_info'])?rtrim($_POST['my_net_info']):rtrim($my_net_info);// rtrim($_POST['my_net_info']);


        if (!ereg('^([0-9]{1,3}\.){3}[0-9]{1,3}(( ([0-9]{1,3}\.){3}[0-9]{1,3})|(/[0-9]{1,2}))$', $my_net_info)) {
            return "Invalid action supplied for process Add new BIB SR.";
            //exit ;
        }
        
        
        if (ereg("/",$my_net_info)){  //if cidr type mask
	$dq_host = strtok("$my_net_info", "/");
	$cdr_nmask = strtok("/");
	if (!($cdr_nmask >= 0 && $cdr_nmask <= 32)){
		return ("Invalid CIDR value. Try an integer 0 - 32.");
		//print "$end";
		//exit ;
        }}
        $bin_host=$this->dqtobin($dq_host);
        
        $fitsr24=substr($bin_host,0,24);
$last8= substr($bin_host,-8);
        
         $correctfirst=$fitsr24.str_pad(decbin(bindec($last8)+4), 8, "0", STR_PAD_LEFT);
         
          $bin_bcast=(str_pad(substr($bin_host,0,$cdr_nmask),32,1));
         $bin_last=(str_pad(substr($bin_bcast,0,31),32,0));
         
        $range_one =$this->bintodq($correctfirst); //"10.0.0.4";
$range_two =$this->bintodq($bin_last);// "10.0.1.14";
$ip1 = ip2long ($range_one);
$ip2 = ip2long ($range_two);
$ips=null;
while ($ip1 <= $ip2) {
   // print '<br/><br/>';
    $ips[]=long2ip($ip1);
    $ip1 ++;

}
//print json_encode($ips);
     //   print_r($ips);
        
        return $ips;
        
    }
    
    
     function cdrtobin ($cdrin){//convert CDR ip to binary 0s &1s for 32 digit
        
	return str_pad(str_pad("", $cdrin, "1"), 32, "0");
}

function binnmtowm($binin){ //cnobetr binary to WN
 //   print '<br/>------inside BINNMTOWN------- ';
    
    
  //  print '<br/>inuput is '.$binin;
	$binin=rtrim($binin, "0");
    //    print '<br/>rtrim($binin, "0") output '.$binin;
    //     print '<br/>------END BINNMTOWN-------';
	if (!ereg("0",$binin) ){
		return str_pad(str_replace("1","0",$binin), 32, "1");
	} else return "1010101010101010101010101010101010101010";
}


function bintodq ($binin) {// convert binary to decimal
	if ($binin=="N/A") return $binin;
	$binin=explode(".", chunk_split($binin,8,"."));
	for ($i=0; $i<4 ; $i++) {
		$dq[$i]=bindec($binin[$i]);
	}
        return implode(".",$dq) ;
}


function dqtobin($dqin) { //decimal to binary
    
     //  print '<br/>------inside dqtobin------- ';
       
     //     print '<br/>inuput is '.$dqin;
        $dq = explode(".",$dqin);
        
     //   print_r($dq);
        
        
        for ($i=0; $i<4 ; $i++) {
           $bin[$i]=str_pad(decbin($dq[$i]), 8, "0", STR_PAD_LEFT);
         //   print '<br/>str_pad(decbin($dq[$i]), 8, "0", STR_PAD_LEFT) '.$bin[$i];
        }
        //   print '<br/>implode("",$bin) output '.implode("",$bin);
        //     print '<br/>------END dqtobin-------';
        return implode("",$bin);
}


function dotbin($binin,$cdr_nmask){//convert binary to IP
	// splits 32 bit bin into dotted bin octets
	if ($binin=="N/A") return $binin;
	$oct=rtrim(chunk_split($binin,8,"."),".");
	if ($cdr_nmask > 0){
		$offset=sprintf("%u",$cdr_nmask/8) + $cdr_nmask ;
		return substr($oct,0,$offset ) . "&nbsp;&nbsp;&nbsp;" . substr($oct,$offset) ;
	} else {
	return $oct;
	}
}



}
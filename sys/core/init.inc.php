<?php
//if (session_status() === PHP_SESSION_NONE){session_start();}
if(!isset($_SESSION)){@session_start();}
/*
* Generate an anti-CSRF token if one doesn't exist
*/
if ( !isset($_SESSION['token']) )
{
$_SESSION['token'] = sha1(uniqid(mt_rand(), TRUE));
}
/*
 * intialize the DB and main Functions
 */
//credintial of db and other global Variables
include_once '../sys/config/db-cred.inc.php';

//creat consts
//print_r($C);
foreach ($C as $name => $value) {
    define($name, $value);
  //  echo 'Name: '.$name.',Value:'.$value;
}
//echo 'hostName'.DB_HOST.'<br/>';
// create PDO object for 1st time
$dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;

$dbo = new PDO($dsn, DB_USER, DB_PASS);
//$GLOBALS['logobj']=new Log();

/**
 * define auto functions for loading classes
 * 
 */
function __autoload($class) {
    $filename = '../sys/class/class.' . $class . '.inc.php';
    if (file_exists($filename)) {
        include_once $filename;
    }
}
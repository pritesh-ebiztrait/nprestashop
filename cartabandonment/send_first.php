<?php
require_once (dirname(__FILE__).'/../../config/config.inc.php');
require_once (dirname(__FILE__).'/../../init.php');

if(isset($_GET['tnd']))
        $token = $_GET['tnd'];
else $token = $argv[1];

$obj = Module::getInstanceByName('cartabandonment');
if($obj->validateToken($token))
        $result = $obj->sendEmails(NULL, true);
        
if($_SERVER['HTTP_HOST'])
{
	if ($result)
		echo $result;
	else Tools::redirect();
}

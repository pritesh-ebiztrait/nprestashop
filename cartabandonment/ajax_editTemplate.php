<?php
if (!isset($_SESSION))
	session_start();
	
include_once('../../config/config.inc.php');
$token = Tools::getValue('token_cartabandonment');

if(isset($_SESSION['token_cartabandonment']) && isset($token) && $_SESSION['token_cartabandonment'] == $token)
{
	$template_id = Tools::getValue('template_id');
	echo file_get_contents(realpath('./') . '/tpl/' . $template_id . '.html'); die;
}
else{
	echo 'hack ...';die;
}

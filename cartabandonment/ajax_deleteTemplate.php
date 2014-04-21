<?php
if (!isset($_SESSION))
	session_start();
	
include_once('../../config/config.inc.php');
$token = Tools::getValue('token_cartabandonment');

if(isset($_SESSION['token_cartabandonment']) && isset($token) && $_SESSION['token_cartabandonment'] == $token)
{
	require_once dirname(__FILE__).'/controllers/TemplateController.class.php';
	$id_template = Tools::getValue('template_id');
	echo TemplateController::deleteTemplate($id_template) && unlink(dirname(__FILE__).'/tpl/' . $id_template . '.html');
}
else{
	echo 'hack ...';die;
}

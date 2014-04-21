<?php
require_once (dirname(__FILE__).'/../../config/config.inc.php');
require_once (dirname(__FILE__).'/../../init.php');

if(isset($_GET['tnd']))
        $token = $_GET['tnd'];
else $token = $argv[1];
if (!defined('_PS_BASE_URL_'))
        define('_PS_BASE_URL_', Tools::getShopDomain(true));

$obj = Module::getInstanceByName('cartabandonment');

if ($obj->validateToken($token))
{
        $obj->loadClasses();
        $obj->addLog("Begin sending \t".date(DATE_RFC822));
        echo $result = $obj->sendEmails();
        $obj->addLog("End sending \t".date(DATE_RFC822));
}


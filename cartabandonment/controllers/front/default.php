<?php

class CartabandonmentDefaultModuleFrontController extends ModuleFrontController
{     

        public function initContent()
        {
                
                
                $action = Tools::getValue('op');
                $id_cart = Tools::getValue('ac');
                $secure_key = Tools::getValue('ks');
                $id_customer = Tools::getValue('uc');
                $num_sending = Tools::getValue('mun');
                
                $module = Module::getInstanceByName('cartabandonment');
                $module->loadClasses();
                if($action != 'RMD')
                {
                        parent::initContent();
                }
                else
                {
                        $this->context->smarty->assign(array(
				'HOOK_HEADER' => '',
				'HOOK_TOP' => '',
				'HOOK_LEFT_COLUMN' => '',
				'HOOK_RIGHT_COLUMN' => '',
				'HOOK_MOBILE_HEADER' => '',
                                'HOOK_FOOTER' => ''
			));
                        $this->display_footer = $this->display_header = false;
                }
 
                if(in_array($action, array('UNS', 'RMD', 'CA', 'RD')) AND $id_cart AND $secure_key AND $id_customer AND $num_sending)
                {
                        $query = '
                                SELECT `lastname`, `firstname`, `passwd`, `email`, `id_currency`, `id_cart` 
                                FROM `'._DB_PREFIX_.'customer` `cu`
                                LEFT JOIN `'._DB_PREFIX_.'cart` `ca`
                                        ON `ca`.`id_customer` = `cu`.`id_customer` AND `ca`.`secure_key` = `cu`.`secure_key` 
                                WHERE `ca`.`id_cart`='.(int)$id_cart.' AND `ca`.`secure_key`="'.pSQL($secure_key).'" AND `ca`.`id_customer`='.(int)$id_customer;
                        
                        
                        $result = DB::getInstance()->getRow($query);

                        if($result)
                        {
                                $customer = new Customer($id_customer);
                                self::$cookie->id_customer = (int)($customer->id);
                                self::$cookie->customer_lastname = $customer->lastname;
                                self::$cookie->customer_firstname = $customer->firstname;
                                self::$cookie->passwd = $customer->passwd;
                                self::$cookie->logged = 1;
                                self::$cookie->email = $customer->email;
                                self::$cookie->is_guest = $customer->is_guest;;

                                self::$cookie->id_cart = $id_cart;
                                
                                // Read email on website
                                if ($action =='RMD')
                                        Statistic::setRead($id_cart, $num_sending);
                                // Click
                                elseif ($action == 'RD')
                                {
                                        Statistic::setRead($id_cart, $num_sending);
                                        Tools::redirect('index.php');
                                }
                                // Unsubscribe
                                elseif ($action =='UNS')
                                {
                                        Statistic::setUnsubscribed($id_cart, $num_sending);
                                        //$this->setTemplate('unsubscribe.tpl');
                                }
                                // View content of cart
                                elseif($action == 'CA')
                                        Tools::redirect(__PS_BASE_URI__.'order.php?step=0&token='.Tools::getToken(false));       
                        }
                }
                else Tools::redirect();
        }
        
        public function displayContent()
	{
                $action = Tools::getValue('op');
                $id_cart = intval(Tools::getValue('ac'));
                $secure_key = pSQL(Tools::getValue('ks'));
                $id_customer = intval(Tools::getValue('uc'));
                $num_sending = intval(Tools::getValue('mun'));
                $module = Module::getInstanceByName('cartabandonment');
                $module->loadClasses();
                
                if(in_array($action, array('UNS', 'RMD')) AND $id_cart AND $secure_key AND $id_customer AND $num_sending)
                {
                        // Read email on website
                        if($action =='RMD')
                        {
                                $output = $module->viewReminder($module->getCartData($id_cart), $num_sending);
                                echo $output['msg'];
                        }
                        elseif ($action =='UNS')
                        {
                                echo $module->displayUnsubscribed();
                        }
                }
	}
        
        
        public function displayHeader($display = true)
        {
                $action = Tools::getValue('op');
                if($action != 'RMD')
                        parent::displayHeader($display);
        }
        
        
        public function displayFooter($display = true)
        {
                $action = Tools::getValue('op');
                if($action != 'RMD')
                        parent::displayFooter($display);
        }

}
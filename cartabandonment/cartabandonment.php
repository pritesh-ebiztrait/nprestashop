<?php

if (!defined('_PS_VERSION_'))
        exit;

class CartAbandonment extends Module
{
        public function __construct()
        {
                $this->name = 'cartabandonment';
		$this->version = '2.0.11';
		$this->author = 'PrestaShop';
                $this->module_key = '14fe56914027cb4cd50a7dc83283f457';

                if (version_compare(_PS_VERSION_, '1.4.0.0') >= 0)
			$this->tab = 'advertising_marketing';
		else
			$this->tab = 'Advertisement';

                parent::__construct(); 

                $this->displayName = $this->l('Cart abandonment');
		$this->description = $this->l('Remind your clients who have abandoned their shopping cart by automatic e-mail');
                $this->all_type_of_colors = array('main_color' => $this->l('Background main color'),
                                        'fh_bg_color' => $this->l('Footer and header background color'),
                                        'title_color' => $this->l('Font color title'),
                                        'body_color' => $this->l('Font color body'),
                                        'fh_txt_color' => $this->l('Footer and header text color'));
                $this->_html = '';
        }


        private function installDB()
        {
                $return = true;
                $query = array ();
                $query[] = '
                        CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'cart_abandonment_stats`(
                                `id_statistic` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                                `id_cart` INT UNSIGNED NOT NULL,
                                `num_sending` INT UNSIGNED DEFAULT 0,
                                `transformed` TINYINT(1) DEFAULT 0,
                                `read` TINYINT(1) DEFAULT 0,
                                `active` TINYINT(1) DEFAULT "1",
                                `id_discount` INT UNSIGNED,
                                `date_add` TIMESTAMP,
                                `date_upd` TIMESTAMP,
                                `date_transforming` TIMESTAMP,
                                `unsubscribed` TINYINT(1) DEFAULT 0,
                                `date_unsubscribing` TIMESTAMP,
                                `date_reading` TIMESTAMP,
                                `id_template` INT UNSIGNED NOT NULL,
                                PRIMARY KEY (`id_statistic`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8';
                
                $query[] = '
                        CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'cart_abandonment_config_discount`(
                                `id_config` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                                `from` FLOAT DEFAULT "00.0",
                                `to` FLOAT DEFAULT "00.0",
                                `value` FLOAT DEFAULT "0.0",
                                `type` VARCHAR(127) NOT NULL,
                                PRIMARY KEY (`id_config`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8';
                
                $query[] = '
                        CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'cart_abandonment_template`(
                                `id_template` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                                `name` VARCHAR(255) NOT NULL,
                                `id_lang` INT UNSIGNED DEFAULT 1,
                                `title_cart_content` VARCHAR(255),
                                `title` VARCHAR(255),
                                `main_color` VARCHAR(16),
                                `header_font_color` VARCHAR(16),
                                `header_background_color` VARCHAR(16),
                                `title_font_color` VARCHAR(16),
                                `body_font_color` VARCHAR(16),
                                `num_sending` INT UNSIGNED DEFAULT 1,
                                `conclusion` TEXT,
                                `body` TEXT,
                                `discount_text` TEXT,
                                `discount` TINYINT(1) DEFAULT 0,
                                `with_cart_content` TINYINT(1) DEFAULT 1,
                                `active` TINYINT(1) DEFAULT 1,
                                `header` TEXT,
                                `footer` TEXT,
                                `left_column` TEXT,
                                `services` TEXT,
                                `date_add` TIMESTAMP,
                                `date_upd` TIMESTAMP,
                                `id_shop` INT UNSIGNED NOT NULL,
                                PRIMARY KEY (`id_template`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8';
                

                foreach ($query as $value)
                        $return = ($return AND DB::getInstance()->Execute($value));
                
                return $return;
        }
        
        
        public function install()
        {
                $return = false;
                if ($this->installDB())
                        $return = (bool)parent::install();
                
                if ($return)
                {
                        $date = date('Y-m-d H:i:s');
                        Configuration::updateValue('GIVEN_UP_CART_DATE_INSTALL', $date);
                        Configuration::updateValue('GIVEN_UP_CART_ENABLE_DISCOUNT', 'enable');
                        Configuration::updateValue('GIVEN_UP_CART_NBR_RANGE_DISCOUNT', 1);
                        Configuration::updatevalue('NBR_OF_REMINDERS', 1);
                        Configuration::updatevalue('HR_OF_SENDING_WEEK', '20:00');
                        Configuration::updatevalue('HR_OF_SENDING_WE', '15:00');
                        Configuration::updateValue('TND_DEADLINE_FOR_FIRST_2', 'H');
                        Configuration::updateValue('TND_TIME_BETWEEN_2', 'D');
                        Configuration::updateValue('TND_DEADLINE_FOR_FIRST', '10');
                        Configuration::updateValue('TND_TIME_BETWEEN', '2');
                        Configuration::updateValue('TND_VOUCHER_EXPIRE', '15');       
                        Configuration::updateValue('TND_SHOP_DOMAIN', Tools::getShopDomain(true));
                        Configuration::updateValue('TND_HTTP_HOST', Tools::htmlentitiesUTF8($_SERVER['HTTP_HOST']));
                        Configuration::updateValue('CART_REMINDER_FROM', Configuration::get('PS_SHOP_EMAIL'));
                        Configuration::updateValue('TND_NBR_CART_PAGINATION', '20');
                        Configuration::updateValue('TND_TOKEN', md5(microtime()));
                        file_put_contents(dirname(__FILE__).'/log.txt', $date.' '.$this->l('New installation'));
                }
                
                return $return AND $this->registerHook('updateOrderStatus') AND $this->registerHook('newOrder');
        }

        
        public function uninstall()
        {
                Configuration::deleteByName('GIVEN_UP_CART_DATE_INSTALL');
                Configuration::deleteByName('GIVEN_UP_CART_ENABLE_DISCOUNT');
                Configuration::deleteByName('GIVEN_UP_CART_NBR_RANGE_DISCOUNT');
                Configuration::deleteByName('NBR_OF_REMINDERS');
                Configuration::deleteByName('HR_OF_SENDING_WEEK');
                Configuration::deleteByName('HR_OF_SENDING_WE');
                Configuration::deleteByName('TND_DEADLINE_FOR_FIRST_2');
                Configuration::deleteByName('TND_TIME_BETWEEN_2');
                Configuration::deleteByName('TND_DEADLINE_FOR_FIRST');
                Configuration::deleteByName('TND_TIME_BETWEEN');
                Configuration::deleteByName('TND_VOUCHER_EXPIRE');
                Configuration::deleteByName('TND_TOKEN');
                Configuration::deleteByName('TND_SHOP_DOMAIN');
                Configuration::deleteByName('TND_MAIN_COLOR');
                Configuration::deleteByName('TND_FH_BG_COLOR');
                Configuration::deleteByName('TND_FH_TXT_COLOR');
                Configuration::deleteByName('TND_TITLE_COLOR');
                Configuration::deleteByName('TND_BODY_COLOR');
                Configuration::deleteByName('TND_HTTP_HOST');
                Configuration::deleteByName('TND_DEADLINE_FOR_FIRST');

                return
                        (
                        DB::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'cart_abandonment_stats`')
                                AND
                        DB::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'cart_abandonment_config_discount`')
                                AND
                        DB::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'cart_abandonment_template`')
                                AND
                        parent::uninstall());
        }


        private function addJS()
        {
                global $cookie;
                
                $defaultLanguage = (int)(Configuration::get('PS_LANG_DEFAULT'));
		$iso = Language::getIsoById($defaultLanguage);
                $ad = dirname($_SERVER["PHP_SELF"]);
                
                if (!in_array(basename($ad), scandir(_PS_ROOT_DIR_)))
                        return ; 
                return '
                        <script type="text/javascript">
                                        var baseUri = "'.$this->context->shop->getBaseUrl().'modules/'.$this->name.'/tools.php?token='.Configuration::get('TND_TOKEN').'";
                                        var iso = "'.(file_exists(_PS_ROOT_DIR_.'/js/tinymce/jscripts/tiny_mce/langs/'.$iso.'.js') ? $iso : 'en').'" ;
                                        var pathCSS = "'._THEME_CSS_DIR_.'" ;
                                        var ad = "'.Tools::htmlentitiesUTF8($ad).'" ;
                                        var msgTPL = "'.$this->l('Please create at least one template').'";
                                        var employee_id_lang = '.(int)$cookie->id_lang.';
                                        var _hourText = "'.$this->l('Hours').'";
                                        var _minuteText = "'.$this->l('Minutes').'";
                                        var id_shop = '.(int)Shop::getContextShopID().';
                        </script>
                        <script type="text/javascript" src="'.__PS_BASE_URI__.'js/tiny_mce/tiny_mce.js"></script>
                        <script type="text/javascript" src="'.__PS_BASE_URI__.'js/tinymce.inc.js"></script>
                        <script type="text/javascript" src="'.__PS_BASE_URI__.'modules/'.$this->name.'/js/jquery-ui-1.8.14.custom.min.js"></script>
                        <script type="text/javascript" src="'.__PS_BASE_URI__.'modules/'.$this->name.'/js/timepicker/jquery.ui.timepicker.js"></script>
                        <script type="text/javascript" src="'.__PS_BASE_URI__.'modules/'.$this->name.'/js/colorpicker/colorpicker.js"></script>
                        <script type="text/javascript" src="'.__PS_BASE_URI__.'modules/'.$this->name.'/js/colorpicker/eye.js"></script>
                        <script type="text/javascript" src="'.__PS_BASE_URI__.'modules/'.$this->name.'/js/colorpicker/utils.js"></script>
                        <script type="text/javascript" src="'.__PS_BASE_URI__.'modules/'.$this->name.'/js/colorpicker/layout.js?ver=1.0.2"></script>
                        <script type="text/javascript" src="'.__PS_BASE_URI__.'modules/'.$this->name.'/js/'.$this->name.'.js"></script>';
        }
        
        
        private function addCSS()
        {
                return '
                        <link type="text/css" rel="stylesheet" href="'.__PS_BASE_URI__.'modules/'.$this->name.'/css/colorpicker/colorpicker.css"/>
                        <link type="text/css" href="'.__PS_BASE_URI__.'modules/'.$this->name.'/css/'.$this->name.'.css" rel="stylesheet" />
                        <link type="text/css" href="'.__PS_BASE_URI__.'modules/'.$this->name.'/js/timepicker/jquery.ui.timepicker.css" rel="stylesheet" />';
        }
        

        public function loadClasses()
        {
                require_once _PS_MODULE_DIR_.'cartabandonment/classes/template.php';
                require_once _PS_MODULE_DIR_.'cartabandonment/classes/statistic.php';
        }
        
        
        public function getContent()
        {
                if (Shop::getContext() == SHOP::CONTEXT_SHOP)
                {
                        require_once _PS_MODULE_DIR_.'cartabandonment/classes/template.php';
                        if (!is_callable('unlink'))
                                return $this->displayError($this->l('Function "unlink" is not callable. You can not use this module.'));        

                        $this->_html .= '<h2><img src="'.__PS_BASE_URI__.'modules/'.$this->name.'/logo.gif"/>'.' '.$this->displayName.'</h2>';
                        $this->_html .= $this->addCSS();
                        $this->_html .= $this->addJS();
                        $this->_html .= $this->displayMainActions();

                        return $this->_html;
                }
                
                return $this->displayError($this->l('This module can only be configured for each shop'));
        }    
        
        
        private function displayMainActions()
        {
                global $smarty;
                $smarty->assign(array('user_guide' => $this->getUserGuide()));
                return $this->display(__FILE__, 'tpl/display_main_actions.tpl');
        }
 
               
        public function getUserGuide()
        {
                return $this->display(__FILE__, 'tpl/user_guide.tpl');
        }
        
        
        
        public function getContentTemplates()
        {
                $output = '
                        <div class="all_templates">
                                '.$this->displayAllTemplates().'
                        </div>
                        <div class="clear">&nbsp;</div>
                        <div class="new_template"></div>
                        <div class="clear">&nbsp;</div>
                        <div class="one_template"></div>
                        <div class="clear">&nbsp;</div>';
                return $output;
        }
        
        
        public function getContentCarts()
        {
                $max = Configuration::get('TND_NBR_CART_PAGINATION');
                $tmp ='';
                $out = '<fieldset>
                                <legend>'.$this->l('List of given up carts').'</legend>
                                <div id="display_given_up_carts2">'.$this->displayActionForPagination(0, $max).'</div>
                                <div id="display_given_up_carts">'.$this->displayAbandonedCarts().'</div>
                                <div class="clear">&nbsp;</div>
                        </fieldset>
                        <div class="clear">&nbsp;</div>';               

                if(Configuration::get('GIVEN_UP_CART_ENABLE_DISCOUNT') == 'enable')
                        $tmp .= $this->displayConfigDiscount();
                
                $out .= '<div class="clear">&nbsp;</div>
                        <div id="edit_discount">'.$tmp.'</div>
                        <div id="table_of_statistics">'.$this->displayTableOfStatistics().'</div>';
                
                return $out;
        }
        
        
        public function displayTableOfStatistics($order_by = 'id_cart', $desc = false, $limit = 20, $begin = 0)
        {
                $results = Statistic::get($order_by, $desc, $limit, $begin);
                if (!empty($results))
                {
                        global $smarty;
                        $smarty->assign(
                                array(
                                    'all_stats' => $results,
                                    'admin_img' => _PS_ADMIN_IMG_,
                                    'begin' => $begin,
                                    'limit' => $limit,
                                    'total_stats' => Statistic::countCarts(),
                                    'desc' => (int)$desc,
                                    'order_by' => $order_by
                                    )
                                );

                        return $this->display(__FILE__, 'tpl/table_statistics.tpl');
                }
        }
        
                
        public function getContentEmails()
        {
                $output = $this->configHowToSendfEmails();
                $output .= '<div id="validate_all_templates">'.$this->validateAllTemplates().'</div>';
                $output .= $this->configSendingOfEmails();
                
                return $output;
        }
        

        public function updateNbrOfCartPagination($value)
        {
                if (is_numeric($value))
                        Configuration::updateValue('TND_NBR_CART_PAGINATION', intval($value));
        }

        
        public function displayActionForPagination($max_prev = 0, $min_next = 0)
        {return '';
                $part1 = $part2 = $part4 = '';
                $count = 100;
                $nbr = Configuration::get('TND_NBR_CART_PAGINATION');
                if ($max_prev - $nbr >= 0)
                        $part1 = '
                                <td>
                                        <a href="javascript:DisplayCarts(0, '.$nbr.');">
                                                <img src="'.Tools::getShopDomain(true).__PS_BASE_URI__.'/img/admin/list-prev2.gif" alt=""/>
                                        </a>
                                </td>
                                <td>
                                        <a href="javascript:DisplayCarts('.($max_prev - $nbr).', '.$max_prev.');">
                                                <img src="'.Tools::getShopDomain(true).__PS_BASE_URI__.'/img/admin/list-prev.gif" alt=""/>
                                        </a>
                                </td>';


                if ($min_next >= $nbr AND $min_next < $count)
                        $part4 = '
                                <td>
                                        <a href="javascript:DisplayCarts('.($min_next).', '.($min_next + $nbr).');">
                                                <img src="'.Tools::getShopDomain(true).__PS_BASE_URI__.'/img/admin/list-next.gif" alt=""/>
                                        </a>
                                </td>
                                <td>
                                        <a href="javascript:DisplayCarts('.($count - $nbr).', '.$count.');">
                                                <img src="'.Tools::getShopDomain(true).__PS_BASE_URI__.'/img/admin/list-next2.gif" alt=""/>
                                        </a>
                                </td>
                                ';

                $array = array(10, 20, 50, 100);
                $part2 = '<td>'.$this->l('Display').' <select onChange="javascript:UpdateNbrOfCartPagination($(this).attr(\'value\'));">';
                foreach ($array as $value)
                {
                        $part2 .= '<option '.($value == $nbr ? 'selected="selected"':'').'>'.$value.'</option>';
                }
                $part2 .= '</select> '.$this->l('abandoned carts').'</td> ';

                $part3 = '<td>'.' '.$max_prev.($min_next >= $nbr ? '-'.($max_prev + $nbr) : '').'/'.$count.'</td>';
                
                return '<table>'.$part1.$part2.$part3.$part4.'</table>';
        }

        
        public function validateAllTemplates()
        {
                $db = DB::getInstance();
                $languages = Language::getLanguages();
                $output = $this->warnIsNotWritable('log.txt');
                foreach ($languages as $lang)
                {
                        for ($i = 1; $i <= Configuration::get('NBR_OF_REMINDERS'); $i++)
                        {
                                $query = 'SELECT `id_template` FROM `'._DB_PREFIX_.'cart_abandonment_template`
                                        WHERE `id_shop` = '.(int)Shop::getContextShopId().' AND `id_lang`='.(int)$lang['id_lang'].'
                                                AND `num_sending`='.intval($i).' AND `active` = 1';

                                $count = count($db->ExecuteS($query));
                                
                                if ($count > 1)
                                        $output .= $this->displayError($lang['name'].', '.$this->l('Order of sending').' '.$i.' : '.$this->l('Too much active templates'));
                                if ($count < 1)
                                        $output .= $this->displayError($lang['name'].', '.$this->l('Order of sending').' '.$i.' : '.$this->l('No active template'));
                        }
                        
                }
                return $output;
        }
        
        
        
        private function warnIsNotWritable($file)
        {
                $out = '';
                $all_files = scandir(dirname(__FILE__));
                if (in_array($file, $all_files))
                        $file = dirname(__FILE__).'/'.$file;
                
                if (!is_dir($file) && !is_file($file))
                        return $out;
                
                if(!is_writable($file))
                {
                        if (!chmod($file, 0777))
                                $validate = chmod($file, 0755);
                        else $validate = true;
                        
                        if (!$validate)
                                $out = $this->displayError($file.' '.$this->l('is not writable. Please check your permission.'));
                }
                
                return $out;
        }



        
        public function updateVoucherValidity($value)
        {
                if (is_numeric($value))
                        Configuration::updateValue('TND_VOUCHER_EXPIRE', intval($value));
        }
        
        
        private function displayConfigDiscount()
        {
                if (Configuration::get('GIVEN_UP_CART_ENABLE_DISCOUNT'))
                return '
                        <fieldset>
                                        <legend>'.$this->l('Discount with reminders of carts').'</legend>
                                        
                                        <label>'.$this->l('Duration of validity of the voucher').'</label>
                                        <input type="text" size="2" value="'.Configuration::get('TND_VOUCHER_EXPIRE').'"
                                                onChange="javascript:UpdateVoucherValidity($(this).attr(\'value\'))"></input>&nbsp;'.$this->l('Days').'
                                                
                                        <div class="clear">&nbsp;</div>
                                        <div class="clear">&nbsp;</div>
                                        
                                        <form id="edit_ranges_discount" name="edit_ranges_discount">
                                                <table class="table" cellspacing=0 cellpadding=0>
                                                        <tbody id="display_ranges_discount">
                                                                '.$this->displayRangesForDiscount().'
                                                        </tbody>
                                                </table>
                                                <div class="clear">&nbsp;</div>
                                                
                                                <div class="clear">&nbsp;</div>
                                                <input type="button"  style="float:right"
                                                        class="button"
                                                        value="'.$this->l('Save').'"
                                                        id="save_ranges_discount"
                                                        onClick="javascript:SaveRanges(\'true\')"/>
                                                </form>
                        </fieldset>';
        }
        
        
        public function validateToken($token)
        {
                return Configuration::get('TND_TOKEN') == $token;
        }


        private function createVoucher($id_cart, $id_customer)
        {
                if (Configuration::get('GIVEN_UP_CART_ENABLE_DISCOUNT') == 'enable')
                {
                        $rules = 'SELECT * FROM `'._DB_PREFIX_.'cart_abandonment_config_discount`';
                        $rules = DB::getInstance()->ExecuteS($rules);
                
                        $vouch = NULL;
                        $cart = new Cart($id_cart);
                        $total = $cart->getOrderTotal(false);
                        $voucher_expire = Configuration::get('TND_VOUCHER_EXPIRE') ? (int)Configuration::get('TND_VOUCHER_EXPIRE') : 15;
                        foreach ($rules AS $rule)
                                if($rule['from'] <= $total AND ($rule['to'] > $total OR $rule['to'] == 0))
                                        $vouch = $rule;

                        if (!empty($vouch))
                        {
                                $code = 'TND'.substr(sha1(microtime()), 6, 5);
                                
                                $voucher = new CartRule();
                                $voucher->id_customer = (int)$id_customer;
                                $voucher->code = $code;
                                $voucher->name = $this->displayName.' Cart Id'.(int)$id_cart;
                                $voucher->quantity = 1;
                                $voucher->quantity_per_user = 1;
                                $voucher->active = true;
                                $voucher->shop_restriction = true;
                                $voucher->highlight = true;
                                //$voucher->cumulable_reduction = 1;
                                $now = time();
                                $voucher->date_from = date('Y-m-d H:i:s', $now);
                                $voucher->date_to = date('Y-m-d H:i:s', $now + (3600 * 24 * $voucher_expire));

                                $voucher->description = $this->displayName.' '.$code;
                                $voucher->id_discount_type = intval($vouch['type']);
                                $voucher->minimun_amount = $vouch['from'];
                                $voucher->minimum_amount_currency = (int)Configuration::get('PS_CURRENCY_DEFAULT');

                                if(intval($vouch['type']) == 2)
                                {
                                        $voucher->reduction_currency = (int)Configuration::get('PS_CURRENCY_DEFAULT');
                                        $voucher->reduction_amount = floatval($vouch['value']);
                                }
                                elseif (intval($vouch['type']) == 1)
                                {
                                        //$voucher->reduction_currency = (int)Configuration::get('PS_CURRENCY_DEFAULT');
                                        $voucher->reduction_percent = floatval($vouch['value']);
                                }
                                $voucher->save();

				Db::getInstance()->execute('
                                        INSERT INTO `'._DB_PREFIX_.'cart_rule_shop` (`id_cart_rule`, `id_shop`)
                                        VALUES ('.(int)$voucher->id.', '.(int)Shop::getContextShopId().')');
                                return $voucher;
                        }
                }

                return false;
        }
        


        public function displayAbandonedCarts()
        {
                global $smarty;
                $number_of_reminders = (int)Configuration::get('NBR_OF_REMINDERS');
                
                $output = '';
                for ($i=1; $i<=$number_of_reminders; $i++)
                {
                        $carts_data = $this->getDataToDoRemind($i);
                        if (!empty($carts_data))
                        {
                                $smarty->assign(array(
                                            'carts_data' => $carts_data,
                                            'num_sending' => $i
                                    ));
                                $output .= $this->display(__FILE__, 'tpl/display_abandoned_carts.tpl');
                        }
                }
                
                return $output;
        }


        private function getCustomerIdentity($id_cart)
        {
                $query = 'SELECT * FROM `'._DB_PREFIX_.'customer` `cu`
                          LEFT JOIN `'._DB_PREFIX_.'cart` `ca`
                          ON `cu`.`id_customer` = `ca`.`id_customer`
                          WHERE `cu`.`id_customer`='.intval($id_cart).' AND `id_shop` = '.(int)Shop::getContextShopID();
                
                return DB::getInstance()->getRow($query);
        }


        private function displayRangesForDiscount()
        {
                
                $result = DB::getInstance()->ExecuteS('SELECT * FROM `'._DB_PREFIX_.'cart_abandonment_config_discount`');
                $nbr = max(count($result), intval(Configuration::get('GIVEN_UP_CART_NBR_RANGE_DISCOUNT')));
                $output = $type = NULL;
                if(is_null($nbr)) 
					$nbr = 1;
                for($i=0; $i<$nbr; $i++)
                {
                        if($i == $nbr-1)
                                $to = 'value="+"';
                        else
                        {
                                if(isset($result[$i]['to']))
                                        $to = 'value="'.$result[$i]['to'].'"';
                                else $to = 'value="0"';
                        }
                        if(isset($result[$i]['from']))
                                        $from = 'value="'.$result[$i]['from'].'"';
                        else $from = 'value="0"';
                        
                        if(isset($result[$i]['value']))
                                        $value = 'value="'.$result[$i]['value'].'"';
                        else $value = 'value="0"';
                           
                        if (isset($result[$i]['type']))
                                $type = $result[$i]['type'];

                        $output .='
                                <tr id="range_'.$i.'" class="range_discount">
                                        <td>'.$this->l('Min').'
                                                <input type="text" name="from_'.$i.'" '.$from.'/>
                                        </td>
                                        
                                        <td>'.$this->l('Max').'
                                                <input type="text" name="to_'.$i.'" '.$to.'/>                                
                                        </td>
                                        
                                        <td>'.$this->l('Value').'
                                                <input type="text" name="value_'.$i.'" '.$value.'/>
                                        </td>
                                        
                                        <td>'.$this->l('Type').'
                                                <select name="type_'.$i.'">
                                                        <option value=1 '.($type==1 ? 'selected' : '').'>'.$this->l('Percentage').'</option>
                                                        <option value=2 '.($type==2 ? 'selected' : '').'>'.$this->l('Currency').'</option>
                                                </select>
                                        </td>
                                        
                                        <td>
                                                <table>
                                                        <td>
                                                                <a href="javascript:DeleteRange(\'range_'.$i.'\', '.$nbr.');">
                                                                        <img src="'.Tools::getShopDomain(true).__PS_BASE_URI__.'/img/admin/forbbiden.gif" alt="delete this range"/>
                                                                </a>
                                                        </td>
                                                        <td>
                                                                <a href="javascript:AddNewRange(\'range_'.$i.'\', '.$nbr.');">
                                                                        <img src="'.Tools::getShopDomain(true).__PS_BASE_URI__.'/img/admin/add.gif" alt="delete this range"/>
                                                                </a>
                                                        </td>
                                                </table>
                                        </td>
                                </tr>';
                }
                return $output;
        }
        
        
        private function updateNbrRangesForDiscount($action)
        {
                $nbr = intval(Configuration::get('GIVEN_UP_CART_NBR_RANGE_DISCOUNT'));
                
                if ($action == 'up')
                        $nbr++;
                elseif ($action == 'down') $nbr--;

                Configuration::updateValue('GIVEN_UP_CART_NBR_RANGE_DISCOUNT', $nbr);

                return $nbr;
        }
        
        
        
        /*****************************************************************************************/
        public function switchActive($id_template)
        {
                $template = new Template($id_template);
                $template->switchActive();
                return $this->displayAllTemplates();
        }


        public function displayTemplateIsActive($id_tpl)
        {
                $tpl = Template::get(intval($id_tpl));
                if ($tpl->active)
                        return '<img title="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" src="'.Tools::getShopDomain(true).__PS_BASE_URI__.'/img/admin/enabled.gif">';
                return '<img title="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" src="'.Tools::getShopDomain(true).__PS_BASE_URI__.'/img/admin/disabled.gif">';
        }

        
        public function displayAllTemplates()
        {
                $nbr = Configuration::get('NBR_OF_REMINDERS');
                $results = Template::get(NULL, NULL, false);
                $output = '<table class="table" style="float:left;" cellspacing=0 cellpadding=0>
                                <thead>
                                        <tr>
                                                <th>'.$this->l('Name').'</th>
                                                <th>'.$this->l('Language').'</th>
                                                <th>'.$this->l('Order of sending').'</th>
                                                <th>'.$this->l('With discount').'</th>
                                                <th>'.$this->l('Display content of cart').'</th>
                                                <th>'.$this->l('Actions').'</th>
                                        </tr>
                                </thead><tbody>';

                if (is_array($results) AND !empty($results))
                        foreach ($results AS $template)
                        {
                                $select_sending = $select_discount = '';
                                for($i=1; $i<=$nbr; $i++)
                                {
                                        $select_sending .= '<option '.(($i == $template->num_sending) ? 'selected': '').'>'.$i.'</option>';
                                }
                                $select_sending = '<select name="num_sending" class="'.$template->id.'"
                                                onchange="javascript:UpdateNumOfSending(\''.$template->id.'\', $(this).attr(\'value\'))">'.$select_sending.'</select>';
                        
                                $select_discount = '
                                        <select name="discount" onchange="javascript:UpdateTemplateWithDiscount(\''.$template->id.'\', $(this).attr(\'value\'))">
                                                <option '.($template->discount ? 'selected' : '').' value="1">'.$this->l('Yes').'</option>
                                                <option '.(!$template->discount ? 'selected' : '').' value="0">'.$this->l('No').'</option>
                                        </select>';
                                
                                $select_display_content = '
                                        <select name="num_sending" class="'.$template->id.'"
                                                onchange="javascript:UpdateTemplateWithContentOfCart(\''.$template->id.'\', $(this).attr(\'value\'))">
                                                <option '.($template->with_cart_content ? 'selected' : '').' value="1">'.$this->l('Yes').'</option>
                                                <option '.(!$template->with_cart_content ? 'selected' : '').' value="0">'.$this->l('No').'</option>
                                        </select>';
                        

                                $lang = Language::getLanguage($template->id_lang);
                     
                                $output .= '
                                        <tr>
                                                <td>'.$template->name.'</td>
                                                <td>'.$lang['name'].'</td>
                                                <td><center>'.$select_sending.'</center></td>
                                                <td><center>'.$select_discount.'</center></td>
                                                <td><center>'.$select_display_content.'</center></td>
                                                <td>
                                                        <table>
                                                                <td>
                                                                        <a href="javascript:EditTemplate(\''.$template->id.'\', \'preview\')">
                                                                                <img title="'.$this->l('Preview and Edit').'" alt="'.$this->l('Preview and edit').'" src="'.Tools::getShopDomain(true)._PS_ADMIN_IMG_.'edit.gif"/>
                                                                        </a>
                                                                </td>
                                                                <td>
                                                                        <a href="javascript:SwitchActive(\''.$template->id.'\', $(this))">
                                                                                '.$this->displayTemplateIsActive($template->id).'
                                                                        </a>
                                                                </td>
                                                                <td>
                                                                        <a href="javascript:DuplicateTemplate(\''.$template->id.'\')">
                                                                                <img title="'.$this->l('Duplicate').'" alt="'.$this->l('Duplicate').'" src="'.Tools::getShopDomain(true)._PS_ADMIN_IMG_.'duplicate.png"/>
                                                                        </a>
                                                                </td>
                                                                <td>
                                                                        <a href="javascript:DeleteTemplate(\''.$template->id.'\')">
                                                                                <img title="'.$this->l('Delete').'" alt="'.$this->l('Delete').'" src="'.Tools::getShopDomain(true)._PS_ADMIN_IMG_.'delete.gif"/>
                                                                        </a>
                                                                </td>
                                                        </table>
                                                </td>
                                        </tr>';
                        }

               // $output = htmlentities($output, true);
                $output .= '</tbody></table>
                        <div class="clear">&nbsp;</div>
                        <input type="button" class="button" value="'.$this->l('Add new template').'"
                                onclick="javascript:AddNewTemplate()" />';
                


                return '<fieldset>
                                <legend>'.$this->l('All templates').'</legend>
                                
                                <form id="display_all_templates">'.$output.'</form>
                        </fieldset>';
        }
        
        public function saveTemplate($data, $id_template = NULL)
        {
                if (!empty($data))
                {
                        if (array_key_exists('id_lang', $data))
                                $id_lang = $data['id_lang'];
                        else $id_lang = Configuration::get('PS_LANG_DEFAULT');
                        
                        if (!empty($id_template))
                                $template = new Template($id_template, $id_lang);
                        elseif (array_key_exists('id_template', $data))
                                $template = new Template($data['id_template'], $id_lang);
                        else $template = new Template(NULL, $id_lang);
                        
                        foreach ($data AS $key=>$value)
                                $template->{$key} = $value;
                                
                        if (empty($template->num_sending))
                                $template->num_sending = 1;
                        $template->id_shop = Shop::getContextShopId();
						
						if(isset($template->discount_text) && $template->discount_text!='')
						{
						
						
						$template->discount_text=str_replace("'","’",$template->discount_text);
						}
						if(isset($template->header) && $template->header!='')
						{
						$template->header=str_replace("'","’",$template->header);
						}
						if(isset($template->footer) && $template->footer!='')
						{
						$template->footer=str_replace("'","’",$template->footer);
						}
						if(isset($template->conclusion) && $template->conclusion!='')
						{
						$template->conclusion=str_replace("'","’",$template->conclusion);
						}
						if(isset($template->services) && $template->services!='')
						{
						$template->services=str_replace("'","’",$template->services);
						}
						if(isset($template->title) && $template->title!='')
						{
						$template->title=str_replace("'","’",$template->title);
						}
						if(isset($template->body) && $template->body!='')
						{
						$template->body=str_replace("'","’",$template->body);
						}

						
                        $template->save();
                        
                        return array('all_templates' => $this->displayAllTemplates(), 'edit_template' => $this->editTemplate($template->id));
                }
        }

        public function editTemplate($id_template = null)
        {
                if (!empty($id_template))
                        $template = new Template($id_template);
                else $template = new Template();
                
                $languages = Language::getLanguages();
                
                global $smarty;

                $smarty->assign(array('admin_img' => _PS_ADMIN_IMG_, 'template' => $template, 'languages' => $languages));
                return $this->display(__FILE__, 'tpl/edit_template.tpl');
        }


        /*****************Traitement des regles de promotion*******************/     
        public function saveRanges($data)
        {
                $data = explode('&', $data);
                $db = DB::getInstance();
                $query = '';
                
                foreach ($data AS $d)
                {
                        // $d is like "value_0=10" 
                        $tmp = explode('=', $d);
                        $tmp2 = explode('_', $tmp[0]);
                        $key = intval($tmp2[1]);
                        ${$tmp2[0]}[$key] = $tmp[1]; 
                }
              
                $db->Execute('TRUNCATE TABLE `'._DB_PREFIX_.'cart_abandonment_config_discount`');
                if (isset($to) AND is_array($to))
                        foreach ($to AS $key=>$val)
                        {

                                $query = 'INSERT INTO `'._DB_PREFIX_.'cart_abandonment_config_discount`
                                        (`FROM`, `TO`, `VALUE`, `TYPE`)
                                        VALUE("'.intval($from[$key]).'", "'.intval($to[$key]).'", "'.intval($value[$key]).'", "'.pSQL($type[$key]).'");';


                                $db->Execute($query);
                        }
                
                return Configuration::updateValue('GIVEN_UP_CART_NBR_RANGE_DISCOUNT', count($to));
        } 

        /********************************Traitement des templates*********************************/
        public function displayDescriptionOfNewTemplate()
        {
                $buttons = '
                        <input type="button" class="button" value="'.$this->l('Save template').'" style="float:right"
                                onclick="javascript:AddNewTemplate(1)"/>';
                
                $languages = Language::getLanguages();
                $form_language = '';
                
                foreach ($languages AS $lang)
                        $form_language .= '<option value="'.$lang['id_lang'].'" >'.$lang['name'].'</option>'; 
                
                
                return '<div id="description_of_new_template">
                        <fieldset>
                                <legend>'.$this->l('Description of template').'</legend>
                                <form class="add_new_template">
                                        <label>'.$this->l('Name of template').'</label>
                                        <input type="text" name="name"/>
                                        <div class="clear">&nbsp;</div>
                                                
                                        <label>'.$this->l('Language of template').'</label>
                                        <select name="id_lang">
                                                '.$form_language.'
                                        </select>
                                        <div class="clear">&nbsp;</div>
                                                '.$buttons.'
                                </form>
                        </fieldset></div>';
        }
     
        
        public function deleteTemplate($id_template)
        {
                $template = new Template($id_template);
                $template->delete();
                return $this->displayAllTemplates();
        }
        
        
        public function duplicateTemplate($id_template)
        {
                $template = new Template($id_template);
                $template->duplicate();
                return $this->displayAllTemplates();
        }
        
        
        public function updateTemplate()
        {
                $id_template = Tools::getValue('template');
                $form = Tools::getValue('form');
                
                if ($id_template)
                {  
                        if(Template::get($id_template)->update($form))
                                return $this->displayAllTemplates();
                }
        }
        
        
        public function addBlock()
        {
                $this->loadTemplate();
                $form = Tools::getValue('form');
                $form = urldecode($form);
                $explode = explode('&', $form);
                
                $data = explode('=', $explode[0]);
                $name = $data[1];
                $data = explode('=', $explode[1]);
                $type = $data[1];
                $data = explode('=', $explode[2]);
                $position = $data[1];
                
                $this->loadTemplate();
                $this->template->addBlock(new Block($name, $type), $position);
                
                return $this->displayTemplate();
        }
        
        public function saveBlock()
        {
                $form = Tools::getValue('form');
                $id_block = Tools::getValue('id');
                $block = Block::get($id_block);
                if ($block->update($form))
                        return Template::get($block->id_template)->displayTPL();;
        }


        
        
        /********************************************************************************************/
        public function configHowToSendfEmails()
        {
                $output = '
                        <div id="config_of_sending_of_emails_2">
                        <form id="config_of_sending_of_emails">
                                <fieldset>
                                <legend>'.$this->l('Configure reminders').'</legend>
                                
                                <div class="clear">&nbsp;</div>
                                <div style="width:45%; float:left;">
                                        <label>'.$this->l('Deadline for the first reminder').'</label>
                                        <table>
                                                <td><input type="text" size="2" name="tnd_deadline_for_first" value="'.Configuration::get('TND_DEADLINE_FOR_FIRST').'"/></td>
                                                <td>
                                                        <select name="tnd_deadline_for_first_2">
                                                                <option value="D" '.(Configuration::get('TND_DEADLINE_FOR_FIRST_2') == 'D' ? "selected" : "").'>'.$this->l('Days').'</option>
                                                                <option value="H" '.(Configuration::get('TND_DEADLINE_FOR_FIRST_2') == 'H' ? "selected" : "").'>'.$this->l('Hours').'</option>
                                                                <option value="M" '.(Configuration::get('TND_DEADLINE_FOR_FIRST_2') == 'M' ? "selected" : "").'>'.$this->l('Minutes').'</option>
                                                        </select>
                                                </td>
                                        </table>
                                        <div class="clear">&nbsp;</div>
                                
                                        <label>'.$this->l('Time between each reminder').'</label>
                                        <table>
                                                <td>
                                                        <input type="text" size="2" name="tnd_time_between" value="'.Configuration::get('TND_TIME_BETWEEN').'"/>
                                                </td>
                                                <td>
                                                        <select name="tnd_time_between_2">
                                                                <option value="D" '.(Configuration::get('TND_TIME_BETWEEN_2') == 'D' ? "selected" : "").'>'.$this->l('Days').'</option>
                                                                <option value="H" '.(Configuration::get('TND_TIME_BETWEEN_2') == 'H' ? "selected" : "").'>'.$this->l('Hours').'</option>
                                                                <option value="M" '.(Configuration::get('TND_TIME_BETWEEN_2') == 'M' ? "selected" : "").'>'.$this->l('Minutes').'</option>
                                                        </select>
                                                </td>
                                        </table>
                                        <div class="clear">&nbsp;</div>
                                
                                        <label>'.$this->l('Number of reminders').'</label>
                                        <input type="text" size="2" name="nbr_of_reminders" value="'.Configuration::get('NBR_OF_REMINDERS').'"/>
                                        <div class="clear">&nbsp;</div>
                                </div>
                                
                                <!--b div style="width:50%; float:right;">
                                        <label>'.$this->l('The hour of sending in the week').'</label>
                                        <input type="text" size="5" name="hr_of_sending_week" value="'.Configuration::get('HR_OF_SENDING_WEEK').'" class="hr_of_sending"/>
                                        <div class="clear">&nbsp;</div>
                                        
                                        <label>'.$this->l('The hour of sending in the week end').'</label>
                                        <input type="text" size="5" name="hr_of_sending_we" value="'.Configuration::get('HR_OF_SENDING_WE').'" class="hr_of_sending"/>
                                        <div class="clear">&nbsp;</div>
                                </div -->
                                
                                <div class="clear">&nbsp;</div>
                                <input type="button" class="button" value="'.$this->l('Save configuration').'"
                                        onclick="javascript:SaveConfigOfSendingEmails()">
                                </fieldset>
                        </form>
                        <div class="clear">&nbsp;</div>      
                ';
                
                return $output;
        }
        
        

        public function configSendingOfEmails()
        {

                $output = '
                        <fieldset>
                                <legend>'.$this->l('Configure sending of emails').'</legend>
                                        
                                <label>'.$this->l('From (e-mail address of sender)').'</label>
                                <input id="mail_from" type="text" size="70" value="'.Configuration::get('PS_SHOP_EMAIL').'"></input>
                                
                                <div class="clear">&nbsp;</div>
                                
                                <label>'.$this->l('To (e-mail address of receiver)').'</label>
                                <input id="mail_to" type="text" size="70" value="'.Configuration::get('PS_SHOP_EMAIL').'"></input>
                                <div class="margin-form">
                                        '.$this->l('Before enabling the automation of reminders, we invite you to test the e-mail you have set. Enter in this field the email address where you want to receive the test (the content of this test will be based on the first abandoned cart on your online store).').' 
                                </div>
                                        
                                <div class="clear">&nbsp;</div>
                                <input type="button" class="button" value="'.$this->l('Test reminders').'" 
                                        onclick="javascript:TestSendingOfEmails($(\'#mail_from\').attr(\'value\'), $(\'#mail_to\').attr(\'value\'))" />
                        </fieldset>
                        
                        <div class="clear">&nbsp;</div>
                        <div class="crontab">'.$this->configCrontab().'</div>
                ';
                $output .= '<div class="clear">&nbsp;</div>';

                return $output;
        }
        
        
        public function configCrontab()
        {
                $defaultLanguage = (int)(Configuration::get('PS_LANG_DEFAULT'));;
                $iso = Language::getIsoById($defaultLanguage);
                
                $week = Configuration::get('HR_OF_SENDING_WEEK');
                $we = Configuration::get('HR_OF_SENDING_WE');
                $first = Configuration::get('TND_DEADLINE_FOR_FIRST');

                if (Configuration::get('TND_DEADLINE_FOR_FIRST_2') == 'M')
                        $output = $data = '*/'.intval($first).' * * * * php -f '.dirname(__FILE__).'/send.php '.Configuration::get('TND_TOKEN');
                elseif (Configuration::get('TND_DEADLINE_FOR_FIRST_2') == 'H')
                        $output = $data = '*/15 * * * * php -f '.dirname(__FILE__).'/send.php '.Configuration::get('TND_TOKEN');
                else
                {
                        $first_tab = '* * * * 0,6 php -f '.dirname(__FILE__).'/send_first.php '.Configuration::get('TND_TOKEN');
                        $week = explode(':', $week);
                        $we = explode(':', $we);
                      
                        if (count($we) > 1)
                        {
                                $we_tab = intval($we[1]).' '.intval($we[0]).' * * '.'0,6 php -f '.dirname(__FILE__).'/send.php '.Configuration::get('TND_TOKEN');
                                $week_tab = intval($week[1]).' '.intval($week[0]).' * * '.'1-5 php -f '.dirname(__FILE__).'/send.php '.Configuration::get('TND_TOKEN');

                                $data = $week_tab."\n".$we_tab."\n".$first_tab;
                                $output = $week_tab.'</br>'.$we_tab.'</br>'.$first_tab;
                        }
                        else $output = '';
                }


                $output = '
                                <b>'.$this->l('CAUTION: To recall that the mails are sent automatically, it is necessary to have previously set a cron task, and sending e-mails from your online shop.')
                                        .' '.$this->l('Please contact your host to configure these two points.').'</b>
                                <b>'.$this->l('The following lines are to be added to crontab:').'</b>
                                <p>'.$output.'</p>
                                <p></p>
                                <p><b>'.$this->l('Link since a browser').'</b><br/>'
                                        .Tools::getShopDomain(true).__PS_BASE_URI__.'modules/cartabandonment/send.php?tnd='.Configuration::get('TND_TOKEN').'</p>
                                <b>'.$this->l('For more information on cron task, see the following page:').' http://'.$iso.'.wikipedia.org/wiki/Cron</b>
                ';
                return '
                        <fieldset>
                                <legend>'.$this->l('Crontab').'</legend>
                                <div class="hint" style="display:block;">'.$output.'</div>
                       </fieldset>';
        }   
        
        
        /*******************************************************************************************/
        public function saveConfigOfSendingEmails()
        {
                $form = Tools::getValue('form');
                
                foreach ($form AS $key=>$value)
                        Configuration::updateValue(strtoupper($key), pSQL($value));      
                
                return $this->configCrontab();
        }
        
        
        public function updateNumOfSending($id_template, $num_sending)
        {
                $template = new Template((int)$id_template);
                $template->num_sending = (int)$num_sending;
                $template->save();
                return array('all_templates' => $this->displayAllTemplates(), 'edit_template' => $this->editTemplate($template->id));
        }
        
        
        public function updateTemplateWithDiscount($id_template, $discount)
        {
                $template = new Template((int)$id_template);
                $template->discount = (bool)$discount;
                $template->save();
                return array('all_templates' => $this->displayAllTemplates(), 'edit_template' => $this->editTemplate($template->id));
        }
        
        
        public function updateTemplateWithContentOfCart($id_template, $with_content_of_cart)
        {
                $template = new Template((int)$id_template);
                $template->with_cart_content = (bool)$with_content_of_cart;
                $template->save();
                return array('all_templates' => $this->displayAllTemplates(), 'edit_template' => $this->editTemplate($template->id));
        }
        
        /*****************************************************************************************/
        
        
        public function getDataToDoRemind($num_sending = 1, $begin = 0, $max = true)
        {  
                $id_shop = (int)Shop::getContextShopID();

                if ($num_sending == 1)
                {
                        $query = '
                                SELECT `ca`.*, `cu`.`firstname`, `cu`.`lastname`, `cu`.`id_customer`, `cu`.`email`
                                FROM `'._DB_PREFIX_.'cart` `ca`
                                LEFT JOIN `'._DB_PREFIX_.'orders` `ord` ON `ord`.`id_cart` = `ca`.`id_cart`
                                LEFT JOIN `'._DB_PREFIX_.'cart_abandonment_stats` `cas` ON `cas`.`id_cart` = `ca`.`id_cart`
                                INNER JOIN `'._DB_PREFIX_.'customer` `cu` ON `cu`.`id_customer` = `ca`.`id_customer`
                                WHERE `ord`.`id_order` IS NULL AND `cas`.`id_statistic` IS NULL AND `ca`.`id_shop` = '.(int)$id_shop;
                        
                        if (Configuration::get('TND_DEADLINE_FOR_FIRST_2') == 'M')
                        {
                                // Minutes
                                $delay = Configuration::get('TND_DEADLINE_FOR_FIRST');
                                //$query .= ' AND DATE_SUB(CURDATE(),INTERVAL '.(int)$delay.' MINUTE) >= `ca`.`date_add`';
								$query .= ' AND DATE_SUB(CURDATE(),INTERVAL '.(int)$delay.' MINUTE) <= `ca`.`date_add`';
                        }
                        elseif (Configuration::get('TND_DEADLINE_FOR_FIRST_2') == 'H')
                        {
                                // Hours
                                $delay = Configuration::get('TND_DEADLINE_FOR_FIRST');
                                $query .= ' AND DATE_SUB(NOW(),INTERVAL '.(int)$delay.' HOUR) >= `ca`.`date_add`';
                        }
                        else
                        {
                                // Days
                                $delay = Configuration::get('TND_DEADLINE_FOR_FIRST');
                                $query .= ' AND DATE_SUB(NOW(),INTERVAL '.(int)$delay.' DAY) >= `ca`.`date_add`';
                        }
                        
                        /*$query .= ' AND (
                                        DATE_SUB(NOW(), INTERVAL 7 DAY) <= "'.pSQL(Configuration::get('GIVEN_UP_CART_DATE_INSTALL')).'"
                                                OR 
                                        DATEDIFF(`ca`.`date_add`, "'.pSQL(Configuration::get('GIVEN_UP_CART_DATE_INSTALL')).'") > 0)'; */
										
						$query .= ' AND (
                                        DATE_SUB(NOW(), INTERVAL 7 DAY) >= "'.pSQL(Configuration::get('GIVEN_UP_CART_DATE_INSTALL')).'"
                                                OR 
                                        DATEDIFF(`ca`.`date_add`, "'.pSQL(Configuration::get('GIVEN_UP_CART_DATE_INSTALL')).'") > 0)';				
                }
                elseif ($num_sending > 1)
                {
                        $query = '
                                SELECT `ca`.*, `cu`.`firstname`, `cu`.`lastname`, `cu`.`id_customer`, `cu`.`email`
                                FROM `'._DB_PREFIX_.'cart` `ca`
                                LEFT JOIN `'._DB_PREFIX_.'orders` `ord` ON `ord`.`id_cart` = `ca`.`id_cart`
                                INNER JOIN `'._DB_PREFIX_.'cart_abandonment_stats` `cas` ON `cas`.`id_cart` = `ca`.`id_cart`
                                LEFT JOIN `'._DB_PREFIX_.'cart_abandonment_stats` `cas2` ON `cas2`.`id_cart` = `ca`.`id_cart` AND `cas2`.`num_sending` = '.(int)$num_sending.'
                                INNER JOIN `'._DB_PREFIX_.'customer` `cu` ON `cu`.`id_customer` = `ca`.`id_customer`
                                WHERE `ord`.`id_order` IS NULL AND `cas2`.`id_statistic` IS NULL AND `cas`.`num_sending` = '.(int)($num_sending - 1).'
                                         AND `ca`.`id_shop` = '.(int)$id_shop;                        

                        $delay = Configuration::get('TND_TIME_BETWEEN');
                        if (Configuration::get('TND_TIME_BETWEEN_2') == 'M')
                                $query .= ' AND (DATE_ADD(`cas`.`date_add`, INTERVAL '.(int)$delay.' MINUTE) < NOW())';
                        elseif (Configuration::get('TND_TIME_BETWEEN_2') == 'H')
                                $query .= ' AND (DATE_ADD(`cas`.`date_add`, INTERVAL '.(int)$delay.' HOUR) < NOW())';
                        else $query .= ' AND (DATE_ADD(`cas`.`date_add`, INTERVAL '.(int)$delay.' DAY) < NOW())';
                }
                $results = DB::getInstance()->ExecuteS($query);

                if (is_array($results))
                        foreach ($results as $key=>$result)
                        {
                                $cart = new Cart($result['id_cart']);

                                $results[$key]['nbr_products'] = $cart->nbProducts();
                                if ($cart->getOrderTotal(false) <  1)
                                        unset($results[$key]);
                                else $results[$key]['amount_wt'] = Tools::displayPrice($cart->getOrderTotal(false));
                        }

                return $results;
        }
        
        
        public function getCartData($id_cart)
        {
                $query = '
                        SELECT DISTINCT `cu`.`email`, `ca`.*, `cu`.`firstname`, `cu`.`lastname`
                        FROM `'._DB_PREFIX_.'cart` `ca`
                        LEFT JOIN `'._DB_PREFIX_.'customer` `cu` ON `cu`.`id_customer` = `ca`.`id_customer`
                        WHERE `ca`.`id_cart` = '.(int)$id_cart;
                
                $result = DB::getInstance()->getRow($query);
                $cart = new Cart($result['id_cart']);
                $result['nbr_products'] = $cart->nbProducts();
                
                if ($cart->getOrderTotal(false) <  1)
                        unset($result);
                else $result['amount_wt'] = Tools::displayPrice($cart->getOrderTotal(false));
                return $result;
        }




        private function _getOneCart()
        {
                $query = 'SELECT * FROM `'._DB_PREFIX_.'cart` `ca` WHERE `ca`.`id_cart` = (SELECT MAX(`ca2`.`id_cart`) FROM `'._DB_PREFIX_.'cart` `ca2`)';

                $result = DB::getInstance()->getRow($query);

                $cart = new Cart($result['id_cart']);
                $result['amount_wt'] = $cart->getOrderTotal(false);
                $result['nbr_products'] = $cart->nbProducts();

                return $result;
        }
       
        
        private function _generateEmail($cart_detail, $num_sending, $test = false)
        {
                if ($test)
                {
                        $res = array ();
                        $tpl = Template::get();

                        foreach ($tpl as $value)
                        {
                                $output = $this->viewReminder($cart_detail, $value->num_sending, $test, true, $value->id);
                                $title = $output['title'];
                                $output = $output['msg']; 
                                $title = str_replace('%FIRSTNAME%', ucfirst($cart_detail['firstname']), $title);
                                $title = str_replace('%LASTNAME%', strtoupper($cart_detail['lastname']), $title);
                                $title = str_replace('%SHOP_NAME%', Configuration::get('PS_SHOP_NAME'), $title);
                                $res[] = array('msg' => $output, 'num_sending' => $num_sending, 'template_id' => $value->id, 'title' => 'Test template '.$value->name.' : '.$title);
                        }
                        return $res;
                }
                $template = Template::getByNumSending($num_sending, $cart_detail['id_lang']);

                if (!($template instanceof Template))
                        return $this->displayError($this->l('No active template for reminder'). ' '.$num_sending);
                
                $output = $this->viewReminder($cart_detail, $num_sending, $test, true, $template->id);
                $output['title'] = str_replace('%FIRSTNAME%', ucfirst($cart_detail['firstname']), $output['title']);
                $output['title'] = str_replace('%LASTNAME%', strtoupper($cart_detail['lastname']), $output['title']);
                $output['title'] = str_replace('%SHOP_NAME%', Configuration::get('PS_SHOP_NAME'), $output['title']);
                
                $output = array('msg' => $output['msg'], 'num_sending' => $num_sending, 'template_id' => $template->id, 'title' => $output['title']);

                return $output;
        }
        
        
        
        public function replaceLink($name, $link, $msg = NULL)
        {
                $open = strtolower($name).'_open_link';
                $close = strtolower($name).'_close_link';
                
                if(!is_null($msg))
                {
                        $msg = str_replace('$'.$open, '<a href="'.$link.'">', $msg);
                        $msg = str_replace('$'.$close, '</a>', $msg);
                        return $msg;
                }
                
                return array($open => '<a href="'.$link.'">', $close => '</a>');
                
        }
        
        
        public function remindCarts($from, $to)
        {
                if ((bool)Tools::getValue('test'))
                        if (!$this->sendEmails($to))
                                return;
                else
                {
                        $templates = Template::get();
                        if (empty($templates));
                                return $this->displayError($this->l('Please create at least one template'));
    
                        Configuration::updateValue('CART_REMINDER_FROM', $from);
                        
                        return $this->displayConfirmation($this->l('The reminder of e-mails was activated'));
                }
        }
        

        
        public function sendEmails($to = null)
        {
                $from = Configuration::get('CART_REMINDER_FROM');
                $return = $no_log = false;
                $msg = '';
                if (empty($to))
                {
                        $begin = 0;
                        $max = 100;

                        $number_of_reminders = (int)Configuration::get('NBR_OF_REMINDERS');
                        for ($i=1; $i<=$number_of_reminders; $i++)
                        {
                                $carts_data = $this->getDataToDoRemind($i, $begin, $max);
                                if (!empty($carts_data))
                                {
                                        foreach ($carts_data AS $cart)
                                        {
                                                $output = $this->_generateEmail($cart, $i, false);
                                                if (is_array($output) AND array_key_exists('msg', $output))
                                                {
                                                        $return = $this->send($from, $cart['email'], $output['msg'], $output['title'], 
                                                                array('fromName' => Configuration::get('PS_SHOP_NAME'), 'toName' => $cart['firstname']));
                                                        
                                                        if ($return)
                                                        {
                                                                
                                                                $stat = Statistic::getByCartAndNumSending($cart['id_cart'], $i);
                                                                if (empty($stat))
                                                                {
                                                                        $stat = new Statistic();
                                                                        $stat->num_sending = $i;
                                                                        $stat->id_cart = $cart['id_cart'];
                                                                        $stat->id_discount = 0;
                                                                        $stat->save();
                                                                }
                                                        }
                                                }
                                                else $no_log = true;
                                        }
                                }
                        }
                }
                elseif (Validate::isEmail($to))
                {
                        $return = true;
                        $cart_data = $this->_getOneCart();
                        $cart_data['email'] = $to;
                        $cart_data['firstname'] = 'John';
                        $cart_data['lastname'] = 'DOE';
                        $out = $this->_generateEmail($cart_data, 0, true);

                        if (!is_array($out) AND !is_object($out))
                                return;

                        foreach ($out AS $data)
                                $return = ($return AND $this->send($from, $to, $data['msg'], $data['title'],
                                        array('fromName' => Configuration::get('PS_SHOP_NAME'), 'toName' => $cart['firsname'])));


                        if($return)
                                $msg = "\n Test OK : ".$to;
                        else $msg = "\n Test OK : ".$to;
                }
                else $msg = "\n KO : ";
                
                $this->addLog($msg);
                
                if($return)
                {
                        echo $this->displayConfirmation('OK');
                        return true;
                }
                else echo $this->displayError('KO');
        }
        
        
        
        private static function _getConnection()
	{
		$configuration = Configuration::getMultiple(array('PS_SHOP_EMAIL', 'PS_MAIL_METHOD', 'PS_MAIL_SERVER', 'PS_MAIL_USER', 'PS_MAIL_PASSWD', 'PS_SHOP_NAME', 'PS_MAIL_SMTP_ENCRYPTION', 'PS_MAIL_SMTP_PORT', 'PS_MAIL_METHOD', 'PS_MAIL_TYPE'));
		if (intval($configuration['PS_MAIL_METHOD']) == 2)
		{
			include_once(_PS_SWIFT_DIR_.'Swift/Connection/SMTP.php');
			$connection = new Swift_Connection_SMTP($configuration['PS_MAIL_SERVER'], $configuration['PS_MAIL_SMTP_PORT'], ($configuration['PS_MAIL_SMTP_ENCRYPTION'] == "ssl") ? Swift_Connection_SMTP::ENC_SSL : (($configuration['PS_MAIL_SMTP_ENCRYPTION'] == "tls") ? Swift_Connection_SMTP::ENC_TLS : Swift_Connection_SMTP::ENC_OFF));
			$connection->setTimeout(4);
			if (!$connection)
				return false;
			if (!empty($configuration['PS_MAIL_USER']) AND !empty($configuration['PS_MAIL_PASSWD']))
			{
				$connection->setUsername($configuration['PS_MAIL_USER']);
				$connection->setPassword($configuration['PS_MAIL_PASSWD']);
			}
		}
		else
		{
			include_once(_PS_SWIFT_DIR_.'Swift/Connection/NativeMail.php');
			$connection = new Swift_Connection_NativeMail();
		}
		return ($connection);
	}
        
        
        
        
        public function send($from, $to, $msg, $title, $extra = array())
        {
                $return = false;

                if (Validate::isEmail($from) AND Validate::isEmail($to))
                {
                        require_once(_PS_SWIFT_DIR_.'Swift.php');
                        
                        if (!$connection = self::_getConnection())
                                return false;

                        @ini_set('max_execution_time', '7200');
                        $begin = time();
                        do
                        {
                                try {
                                        $swift = new Swift($connection);
                                        $connected = true;
                                }
                                catch(Swift_ConnectionException $e)
                                {
                                        if(time() > ($begin + 70))
                                                return $this->displayError($this->l('Connection to the server of e-mails failed'));
                                        $connected = false;
                                }

                        } while(!$connected);
                        
                        if (!array_key_exists('toName', $extra))
                                $extra['toName'] = null;
                        if (!array_key_exists('fromName', $extra))
                                $extra['fromName'] = null;
                        
                        if($connected)
                        {
                                //$to = 'dixi@prestashop.com';
                                $swift_msg = new Swift_message($title, $msg, 'text/html', '8bit', 'utf-8');
                                $return = $swift->Send($swift_msg, new Swift_Address($to, $extra['toName']), new Swift_Address($from, $extra['fromName']));       
                        }
                }
                
                return $return;
        }
        
        
        private function createLink($action, $num_sending, $id_customer, $id_cart, $secure_key)
        {
                global $link;
                $data = array(
                        'ks' => $secure_key,
                        'uc' => (int)$id_customer,
                        'ac' => (int)$id_cart,
                        'mun' => (int)$num_sending);
                
                if($action == 'shop')
                        return Tools::getShopDomain(true).__PS_BASE_URI__;
                if($action == 'unsubscribe')
                        $data['op'] = 'UNS';
                if($action == 'cart')
                        $data['op'] = 'CA';
                if($action == 'reminder')
                        $data['op'] = 'RMD';
                if($action == 'read')
                        $data['op'] = 'RD';
                
                return $link->getModuleLink('cartabandonment', 'default', $data, true);
        }
                                                
        public function displayProductsOfCarts($id_cart, $id_template)
        {
                $cart = new Cart($id_cart);
                $all_products = $cart->getProducts();
                $images = Tools::getShopDomain(true).__PS_BASE_URI__.'modules/'.$this->name.'/images';
                $output = '';
                $template = new Template((int)$id_template);
                $four_products = array_chunk($all_products, 4);
               
                foreach($four_products AS $products)
                {
                        $count = count($products);
                        $out = '';
                        $i = 0;
                        foreach($products AS $product)
                        {
                                $i++;
                                $add = '';
                                if($i != $count)
                                        $add = '<td width="10" valign="top">&nbsp;</td>';
                                
                                $link = new Link();

                                $id_lang = $template->id_lang;
                                $language = new Language($id_lang);

                                if (!$language->active OR empty($language->id))
                                        $id_lang = (int)(Configuration::get('PS_LANG_DEFAULT'));
                                
                                $my_product = new Product($product['id_product'], false);
                                $id_image = Product::getCover($my_product->id);
                                $product_image = $link->getImageLink($my_product->link_rewrite, $my_product->id.'-'.$id_image['id_image'], ImageType::getFormatedName('home'));
                                $product_page = $link->getProductLink($my_product);
                                
                                if (strpos($product_image, 'http') !== 0)
                                        $product_image = (Configuration::get('PS_SSL_ENABLED') ? 'https://' : 'http://').$product_image;

                                
                                $out .='
        <td width="132" valign="top">
		<table width="132" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td height="1" width="1" bgcolor="#d9d9d9"><img src="'.$images.'/p.png" alt="" height="1" width="1" style="display:block" /></td>
				<td height="1" width="130" bgcolor="#d9d9d9"><img src="'.$images.'/p.png" alt="" height="1" width="130" style="display:block" /></td>
				<td height="1" width="1" bgcolor="#d9d9d9"><img src="'.$images.'/p.png" alt="" height="1" width="1" style="display:block" /></td>
			</tr>
                        <tr>
                                <td bgcolor="#d9d9d9"></td>
                                <td height="130" align="center">
                                        <a href="'.$product_page.'">
                                                <img src="'.$product_image.'" style="display:block; border:none;"/>
                                        </a>
                                </td>
                                <td bgcolor="#d9d9d9"></td>
                                
                        </tr>
                        <tr>
                                <td height="1" bgcolor="#d9d9d9"></td>
                                <td height="1" bgcolor="#d9d9d9"></td>
                                <td height="1" bgcolor="#d9d9d9"></td>
                        </tr>
                </table>
                <a href="'.$product_page.'">
                        <img src="'.$images.'/p.png" alt="" height="10" width="1" style="display:block" />
                        <font face="Arial, Verdana, sans-serif"  color="'.Configuration::get('TND_BODY_COLOR').'">
                                <span style="font-size:14px; color:#'.$template->body_font_color.'"><b>'.$my_product->name[$id_lang].'</b></span>
                                <img src="'.$images.'/p.png" alt="" height="8" width="1" style="display:block" />
                                <span style="font-size:12px; margin-right:10px; color:#'.$template->body_font_color.'">'.$my_product->description_short[$id_lang].'</span>
                                <img src="'.$images.'/p.png" alt="" height="15" width="1" style="display:block" />
                        </font>
                </a>
        </td>'.$add;
                                
                        }
                        $output .= '<tr>'.$out.'</tr>';
                }
                
                return $output;
        }
        
        
        
        public function viewReminder($cart_detail, $num_sending, $test = false, $title = false, $id_template = NULL)
        {
                if ($test)
                {
                        $template = new Template($id_template);
                        if (empty($template->id))
                                return $this->l('Please create at least one template');
                        
                        $link_ca = $this->createLink('cart', $num_sending, 0, 0, 0);
                        $link_rmd = $this->createLink('reminder', $num_sending, 0, 0, 0);
                        $link_rd = $this->createLink('read', $num_sending, 0, 0, 0);
                        $link_uns = $this->createLink('unsubscribe', $num_sending, 0, 0, 0);
                        $link_sp = $this->createLink('shop', $num_sending, 0, 0, 0);
                        
                        if ($template->discount)
                        {
                                $array['voucher'] = 'test_'.Tools::passwdGen(5);
                                $array['voucher_value'] = Tools::displayPrice(0.0);
                                
                        }
                }
                else
                {
                        if (empty($id_template))
                                $template = Template::getByNumSending($num_sending, $cart_detail['id_lang']);
                        else $template = new Template($id_template);

                        if (empty($template->id))
                                return $this->l('Please create at least one template');

                        $link_ca = $this->createLink('cart', $num_sending, $cart_detail['id_customer'], $cart_detail['id_cart'], $cart_detail['secure_key']);
                        $link_rmd = $this->createLink('reminder', $num_sending, $cart_detail['id_customer'], $cart_detail['id_cart'], $cart_detail['secure_key']);
                        $link_rd = $this->createLink('read', $num_sending, $cart_detail['id_customer'], $cart_detail['id_cart'], $cart_detail['secure_key']);
                        $link_uns = $this->createLink('unsubscribe', $num_sending, $cart_detail['id_customer'], $cart_detail['id_cart'], $cart_detail['secure_key']);
                        $link_sp = $this->createLink('shop', $num_sending, $cart_detail['id_customer'], $cart_detail['id_cart'], $cart_detail['secure_key']);
                        
                        if ($template->discount)
                        {
                                $voucher = $this->createVoucher($cart_detail['id_cart'], $cart_detail['id_customer']);
                                if($voucher)
                                {
                                        $array['voucher'] = $voucher->code;
                                        if (!empty($voucher->reduction_amount))
                                                $array['voucher_value'] = Tools::displayPrice($voucher->reduction_amount);
                                        elseif(!empty($voucher->reduction_percent))
                                                $array['voucher_value'] = $voucher->reduction_percent.'%';
                                                                                
                                        
                                        if (empty($test))
                                        {
                                                $stat = new Statistic();
                                                $stat->num_sending = $num_sending;
                                                $stat->id_cart = $cart_detail['id_cart'];
                                                $stat->id_discount = $voucher->id;
                                                $stat->save();
                                        }
                                }
                                else $array['voucher'] = $array['voucher_value'] = false;
                        }
                }
                
                
                $title = $template->title;
                $title = str_replace('%FIRSTNAME%', ucfirst($cart_detail['firstname']), $title);
                $title = str_replace('%LASTNAME%', strtoupper($cart_detail['lastname']), $title);
                
                $array['firstname'] = ucfirst($cart_detail['firstname']);
                $array['lastname'] =  strtoupper($cart_detail['lastname']);
                $array['quantity'] = $cart_detail['nbr_products'];
                $array['id_customer'] = $cart_detail['id_customer'];
                $array['cart_content'] = ($template->with_cart_content ? $this->displayProductsOfCarts($cart_detail['id_cart'], $template->id) : '');
                $array['shop_name'] = '<a href="'.Tools::getShopDomain(true).__PS_BASE_URI__.'">'.Configuration::get('PS_SHOP_NAME').'</a>';
                $array['date_sending'] = Tools::displayDate($cart_detail['date_upd'], $cart_detail['id_lang'], true);
                $array['num_sending'] = $num_sending;
                $array['title'] = $title;

                $array = array_merge($this->replaceLink('RMD', $link_rmd), $array);
                $array = array_merge($this->replaceLink('READ', $link_rd), $array);
                $array = array_merge($this->replaceLink('UNSUBSCRIBE', $link_uns), $array);
                $array = array_merge($this->replaceLink('CART', $link_ca), $array);
                $array = array_merge($this->replaceLink('SHOP', $link_sp), $array);



                $msg = $template->view($array);

                if ($title)
                        return array('msg' => $msg, 'title' => $title);
                else return $msg;

        }
        
        
        public function addLog($msg)
        {
                file_put_contents(dirname(__FILE__).'/log.txt', date(DATE_RFC822)."\n".$msg ,FILE_APPEND);
        }
        
        
        public function hookUpdateOrderStatus($params)
        {
                $this->loadClasses();
                Statistic::setTransformed($params['cart']->id);            
        }
        
        
        public function displayUnsubscribed()
        {
                return '<h2 style="color:red">'.$this->l('You are now unsubscribed').'</h2>';
        }
}


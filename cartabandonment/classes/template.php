<?php

class Template extends ObjectModel
{
        static $all_variables = array(
                        '%ID_CUSTOMER%',
                        '%CART_OPEN_LINK%',
                        '%CART_CLOSE_LINK%',
                        '%RMD_OPEN_LINK%',
                        '%RMD_CLOSE_LINK%',
                        '%SHOP_OPEN_LINK%',
                        '%SHOP_CLOSE_LINK%',
                        '%UNSUBSCRIBE_OPEN_LINK%',
                        '%UNSUBSCRIBE_CLOSE_LINK%',
                        '%FIRSTNAME%',
                        '%LASTNAME%' ,
                        '%QUANTITY%',
                        '%VOUCHER%',
                        '%VOUCHER_VALUE%',
                        '%VOUCHER_EXPIRE%',
                        '%CART_CONTENT%',
                        '%TEXT_CART_CONTENT%',
                        '%SHOP_NAME%',
                        '%SHOP_MAIL%',
                        '%TITLE%');
        
        public $all_blocks;
        public $id_template;
        public $name;
        public $num_sending;
        public $with_cart_content;
        public $title_cart_content;
        public $discount;
        public $discount_text;
        public $active;
        public $main_color;
        public $header_font_color;
        public $header_background_color;
        public $title_font_color;
        public $body_font_color;
        public $id_lang;
        public $header;
        public $footer;
        public $conclusion;
        public $left_column;
        public $services;
        public $date_add;
        public $date_upd;
        public $title;
        public $body;
        public $id_shop;
        
        protected $table = 'cart_abandonment_template';
        protected $identifier = 'id_template';

        public function __construct($id_template = NULL, $id_lang = NULL)
        {
                parent::__construct($id_template, NULL);

                $id_lang = empty($this->id_lang) ? $id_lang : $this->id_lang; 
                $this->lang = Language::getIsoById($id_lang);

                if (empty($this->id))
                        $this->loadDefault($id_lang);

                $this->cleanStaticTags();
        }
        
        
        public function loadDefault($id_lang = 1)
        {
            
                $lang = new Language($id_lang);
                empty($this->main_color) ? $this->main_color = 'ffffff' : '';
                empty($this->header_background_color) ? $this->header_background_color = '3c3c3c' : '';
                empty($this->title_font_color) ? $this->title_font_color = '29abe2' : '';
                empty($this->body_font_color) ? $this->body_font_color = '000000' : '';
                empty($this->header_font_color) ? $this->header_font_color = 'cccccc' : '';

                if ($lang->iso_code == 'fr')
                {
                    empty($this->header) ? $this->header = file_get_contents(_PS_MODULE_DIR_.'cartabandonment/tpl/header-fr.tpl') : '';
                    empty($this->footer) ? $this->footer = file_get_contents(_PS_MODULE_DIR_.'cartabandonment/tpl/footer-fr.tpl') : '';
                    empty($this->left_column) ? $this->left_column = file_get_contents(_PS_MODULE_DIR_.'cartabandonment/tpl/left_column.tpl') : '';
                    empty($this->services) ? $this->services = file_get_contents(_PS_MODULE_DIR_.'cartabandonment/tpl/services-fr.tpl') : '';
                    empty($this->title_cart_content) ? $this->title_cart_content = 'Contenu actuel de votre panier' : '';
                    empty($this->discount_text) ? 
                            $this->discount_text = utf8_encode ('
                            <p>&nbsp;</p>
                            <p>Nous vous offrons également %VOUCHER_VALUE% sur le total de votre commande !</p>
                            <p>Pour en profiter, utilisez ce coupon : %VOUCHER% durant votre commande.
                                    <br />Ce code est valable seulement %VOUCHER_EXPIRE% jours à la réception de cet email; profitez en vite ! 
                            </p>') : '';

                    empty($this->conclusion) ? 
                            $this->conclusion =
                            utf8_encode ('<p>&nbsp;</p>
                            <p>A bientôt sur notre boutique, %SHOP_NAME% !</p>') : '';

                    empty($this->title) ?
                            $this->title = 'Vos produits vous attendent sur %SHOP_NAME%' :'';

                    empty($this->body) ?
                            $this->body = utf8_encode ("
                                    <p>Cher %FIRSTNAME%,</p>
                                    <p>&nbsp;</p>
                                    <p>Nous vous remercions de votre visite récente sur notre site, %SHOP_NAME%</p>
                                    <p>Lors de votre dernière visite sur notre site, vous avez ajouté des produits dans votre panier, mais n'avez pas finalisé votre achat. Si vous avez rencontré le moindre problème durant le processus d’achat, n’hésitez pas, nous sommes là pour vous aider. </p>
                                    <p>Pour votre confort, nous avons réservé le produit que vous convoitiez pour quelques jours.</p>
                                    <p>N’hésitez pas : retournez sur notre %CART_OPEN_LINK%<strong>site</strong>%CART_CLOSE_LINK% afin de finaliser votre achat !</p>")
                            : '';
                }
                else {

                    empty($this->header) ? $this->header = file_get_contents(_PS_MODULE_DIR_.'cartabandonment/tpl/header.tpl') : '';
                    empty($this->footer) ? $this->footer = file_get_contents(_PS_MODULE_DIR_.'cartabandonment/tpl/footer.tpl') : '';
                    empty($this->left_column) ? $this->left_column = file_get_contents(_PS_MODULE_DIR_.'cartabandonment/tpl/left_column.tpl') : '';
                    empty($this->services) ? $this->services = file_get_contents(_PS_MODULE_DIR_.'cartabandonment/tpl/services.tpl') : '';
                    empty($this->title_cart_content) ? $this->title_cart_content = 'Current contents of your cart' : '';
                    empty($this->discount_text) ? 
                            $this->discount_text = '
                            <p>&nbsp;</p>
                            <p>Weâ€™re also giving you %VOUCHER_VALUE% off your entire order !</p>
                            <p>Just use coupon code %VOUCHER% at checkout.
                                    <br />This code expires %VOUCHER_EXPIRE% days from the receipt of this mail so donâ€™t wait too long!
                            </p>' : '';

                    empty($this->conclusion) ? 
                            $this->conclusion =
                            '<p>&nbsp;</p>
                            <p>See you soon on %SHOP_NAME% !</p>' : '';

                    empty($this->title) ?
                            $this->title = 'Your items are waiting for you on %SHOP_NAME%' : '';

                    empty($this->body) ?
                            $this->body = '
                                    <p>Dear %FIRSTNAME%,</p>
                                    <p>&nbsp;</p>
                                    <p>Thank you for your recent visit to %SHOP_NAME%</p>
                                    <p>During your last visit to our site, you put products into your shopping cart, but didnâ€™t check out. Is there anything we can do to help ?</p>
                                    <p>For your convenience, weâ€™ve reserved your items for you for a few days.</p>
                                    <p>All you have to do is to %CART_OPEN_LINK%<strong>click here</strong>%CART_CLOSE_LINK% to go to your cart, complete your purchase and receive your order!</p>'
                            : '';
                }      

        }
        
        
        public function cleanStaticTags()      
        {
                $attributs = array ('header', 'footer', 'left_column', 'conclusion', 'body', 'title', 'services');                
                $context = Context::getContext();
                $data = array(
                        'CARTABANDONMENT_IMG' => $context->shop->getBaseUrl().'modules/cartabandonment/images',
                        'SHOP_LOGO' => $context->shop->getBaseUrl().'img/'.Configuration::get('PS_LOGO'),
                        'SHOP_NAME' => Configuration::get('PS_SHOP_NAME'));
                
                foreach ($attributs AS $attribut)
                        foreach ($data AS $key => $value)
                                $this->{$attribut} = str_replace('%'.$key.'%', $value, $this->{$attribut});
        }
        
        public function getFields() 
	{ 
                $this->cleanStaticTags();
                $fields = array();
		parent::validateFields();
                $fields['name'] = pSQL($this->name);
                $fields['num_sending'] = (int)$this->num_sending;
                $fields['with_cart_content'] = (bool)$this->with_cart_content;
                $fields['discount'] = (bool)$this->discount;
                $fields['active'] = (bool)$this->active;
                $fields['title_cart_content'] = pSQL($this->title_cart_content, true);
                $fields['main_color'] = pSQL($this->main_color);
                $fields['header_background_color'] = pSQL($this->header_background_color);
                $fields['title_font_color'] = pSQL($this->title_font_color);
                $fields['body_font_color'] = pSQL($this->body_font_color);
                $fields['header_font_color'] = pSQL($this->header_font_color);
                $fields['id_lang'] = $this->id_lang;
                $fields['header'] = pSQL($this->header, true);
                $fields['footer'] = pSQL($this->footer, true);
                $fields['left_column'] = pSQL($this->left_column, true);
                $fields['conclusion'] = pSQL($this->conclusion, true);
                $fields['services'] = pSQL($this->services, true);
                $fields['discount_text'] = pSQL($this->discount_text, true);
                $fields['body'] = pSQL($this->body, true);
                $fields['title'] = $this->title;
                $fields['date_add'] = pSQL($this->date_add);
                $fields['date_upd'] = pSQL($this->date_upd);
                $fields['id_shop'] = (int)$this->id_shop;
		return $fields;	 
	}
        
        
        
        public function save($autodate = true, $nullvalue = false)
        {
                parent::save($autodate, $nullvalue);
                return $this->createTPL();
        }
        
        
        
        public function duplicate($name = NULL)
        {
                if (empty($name))
                        $name = $this->name.'_duplicate';
                
                $template = new Template();        
                $keys = array('num_sending', 'with_cart_content', 'title_cart_content', 'discount', 'discount_text', 'main_color',
                        'header_font_color', 'header_background_color', 'title_font_color', 'body_font_color', 'id_lang',
                        'header', 'footer', 'conclusion', 'left_column', 'services', 'title', 'body', 'id_shop');
                
                foreach ($keys AS $key)
                        $template->{$key} = $this->{$key};
                        
                $template->name = $name;
                return $template->save();
        }
        
        
        public function delete()
        {
                $this->deleteTPL();
                return parent::delete();
        }

        
        public function updateNumberOfSending($value)
        {
                $this->num_sending = (int)$value;
                return $this->save();
        }
        
        
        public function activateDiscount($value)
        {
                if($value)
                        $this->discount = 1;
                else $this->discount = 0;
                
                return $this->save();
        }
         
        
        public function switchActive()
        {
                if (!empty($this->id))
                {
                        $this->active = (int)!$this->active;
                        $this->save();
                }
        }
        
        
        private function clearTemplate()
        {
                /*
                $compile = _PS_TOOL_DIR_.'smarty/compile/';
                $files = scandir($compile);
                foreach ($files AS $file)
                        if (strstr($file, 'cartabandonment') !== false)
                                unlink($compile.$file);
                        
                $compile = _PS_TOOL_DIR_.'smarty_v2/compile/';
                $files = scandir($compile);
                foreach ($files AS $file)
                        if (strstr($file, 'cartabandonment') !== false)
                                unlink($compile.$file);
                 * 
                 */

        }
        
        
        
        private function createTpl()
        {
                global $smarty;
                $context = Context::getContext();
                $this->loadDefault();
                
                if (Configuration::get('PS_FORCE_SMARTY_2'))
                        $smarty->clear_compiled_tpl('cartabandonment_template_'.(int)$this->id.'.tpl');
                else
                        $smarty->clearCompiledTemplate('cartabandonment_template_'.(int)$this->id.'.tpl');

                $this->clearTemplate();

                $dir = _PS_MODULE_DIR_.'cartabandonment/templates/'.$this->id;
                if (!file_exists($dir) OR !is_dir($dir))    
                        mkdir($dir);
                
                $static_data = array (
                        'TITLE' => $this->title,
                        'BODY' => $this->body,
                        'DISCOUNT_TEXT' => ($this->discount ? $this->discount_text : ''),
                        'CONCLUSION' => $this->conclusion,
                        'HEADER' => $this->header,
                        'FOOTER' => $this->footer,
                        'SERVICES' => $this->services,
                        'LEFT_COLUMN' => $this->left_column,
                        'CONCLUSION' => $this->conclusion,
                        'BODY_FONT_COLOR' => $this->body_font_color
                    );
                $content = file_get_contents(dirname(__FILE__).'/../tpl/template.tpl');
                
                foreach ($static_data as $key=>$value)
                {
                        $content = str_replace('%'.$key.'%', $value, $content);
                        $content = str_replace('%'.strtolower($key).'%', $value, $content);
                }
                
                        
                $default = array(
                        'ID_CUSTOMER' => '{$id_customer}',
                        'CART_OPEN_LINK' => '{$cart_open_link}',
                        'CART_CLOSE_LINK' => '{$cart_close_link}',
                        'RMD_OPEN_LINK' => '{$rmd_open_link}',
                        'RMD_CLOSE_LINK' => '{$rmd_close_link}',
                        'SHOP_OPEN_LINK' => '<a href="'.Tools::getShopDomain(true).__PS_BASE_URI__.'">',
                        'SHOP_CLOSE_LINK' => '<a/>',
                        'UNSUBSCRIBE_OPEN_LINK' => '{$unsubscribe_open_link}',
                        'UNSUBSCRIBE_CLOSE_LINK' => '{$unsubscribe_close_link}',
                        'FIRSTNAME' => '{$firstname}',
                        'LASTNAME' => '{$lastname}',
                        'QUANTITY' => '{$quantity}',
                        'VOUCHER' => '{$voucher}',
                        'VOUCHER_VALUE' => '{$voucher_value}',
                        'VOUCHER_EXPIRE' => '{$voucher_expire}',
                        'CART_CONTENT' => '{$cart_content}',
                        'TEXT_CART_CONTENT' => '{$title_cart_content}',
                        'SHOP_NAME'=> Configuration::get('PS_SHOP_NAME'),
                        'SHOP_MAIL'=> Configuration::get('PS_SHOP_EMAIL'),
                        'TITLE' => $this->title,
                        'MAIN_COLOR' => $this->main_color,
                        'TITLE_FONT_COLOR' => $this->title_font_color,
                        'HEADER_FONT_COLOR' => $this->header_font_color,
                        'HEADER_BACKGROUND_COLOR' => $this->header_background_color,
                        'SHOP_NAME' => Configuration::get('PS_SHOP_NAME'),
                        'CARTABANDONMENT_IMG' => $context->shop->getBaseUrl().'modules/cartabandonment/images',
                        'SHOP_LOGO' => $context->shop->getBaseUrl().'img/'.Configuration::get('PS_LOGO'),
                        'SHOP_NAME' => Configuration::get('PS_SHOP_NAME')
                );
                foreach ($default as $key=>$value)
                        $content = str_replace('%'.$key.'%', $value, $content);

                return (bool)file_put_contents($dir.'/cartabandonment_template_'.(int)$this->id.'.tpl',$content);
        }
        
        
        
        private function deleteTPL()
        {
                global $smarty;
                
                if (Configuration::get('PS_FORCE_SMARTY_2'))
                        $smarty->clear_compiled_tpl('cartabandonment_template_'.(int)$this->id.'.tpl');
                else
                        $smarty->clearCompiledTemplate('cartabandonment_template_'.(int)$this->id.'.tpl');
                
                $dir = dirname(__FILE__).'/../templates/'.$this->id;
                $file = $dir.'/cartabandonment_template_'.(int)$this->id.'.tpl';
                if (is_callable('unlink') AND file_exists($file))
                        unlink($file);
                if (is_callable('rmdir') AND file_exists($dir))
                       @rmdir($dir);
        }
        
        
        public static function get($id_template=NULL, $iso=NULL, $active=true)
        {
                $db = DB::getInstance();
                if (!empty($id_template))
                        return new Template($id_template);
                
                if (!empty($iso))
                {
                        if ($active)
                                $active = ' AND `active`'; 
                        
                        $result = $db->ExecuteS('
                                SELECT `id_template`
                                FROM `'._DB_PREFIX_.'cart_abandonment_template`
                                WHERE `id_lang`='.((int)Language::getIdByIso($iso)).$active.' AND `id_shop` = '.(int)Shop::getContextShopID().'
                                ORDER BY `id_lang`');
                }
                else
                {
                        if ($active)
                                $active = ' AND `active`';
                        
                        $result = $db->ExecuteS('
                                SELECT `id_template`
                                FROM `'._DB_PREFIX_.'cart_abandonment_template`
                                WHERE `id_shop` = '.(int)Shop::getContextShopID().$active.'
                                ORDER BY `id_lang`, `num_sending`');
                        
                }
                
                $return = array();
                if (!empty($result))
                {
                        foreach ($result AS $value) 
                                $return[] = new Template($value['id_template']);
                        return $return;
                }
                return $return;
        }
        


        public static function getByNumSending($num_sending, $id_lang = null, $active = true, $less = true)
        {
                
                if ($num_sending > 0 AND $num_sending <= Configuration::get('NBR_OF_REMINDERS'))
                {
                        $id_template = DB::getInstance()->getValue('
                                        SELECT `id_template`
                                        FROM `'._DB_PREFIX_.'cart_abandonment_template`
                                        WHERE '.($active ? '`active` = 1 AND ' : '').' `id_shop` = '.(int)Shop::getContextShopID().' AND `num_sending`='.(int)$num_sending
                                                .(!empty($id_lang) ? ' AND `id_lang` ='.(int)$id_lang : ''));

                        if (!empty($id_template))
                                return new Template($id_template);
                }
        }
        
        
        
        public function view($array = array())
        {
                global $smarty;
                
                $obj = Module::getInstanceByName('cartabandonment');
                
                $query = 'SELECT * FROM `'._DB_PREFIX_.'cart` `ca`
                                LEFT JOIN `'._DB_PREFIX_.'customer` `cu`
                                ON `ca`.`id_customer` = `cu`.`id_customer`
                                WHERE `ca`.`id_cart` = (
                                        SELECT MAX(`ca2`.`id_cart`)
                                        FROM `'._DB_PREFIX_.'cart` `ca2`
                                        WHERE `ca2`.`id_shop` = '.(int)Shop::getContextShopID().')';

                
                $cart_data = DB::getInstance()->getRow($query);
                
                if(array_key_exists('VOUCHER', $array) AND strlen($array['VOUCHER']) < 6)
                        $this->createTPL(false);
                else $this->createTPL(true);
                        
                $default = array(
                    'id_customer' => 1984,
                    'cart_open_link' => '<a href="#">',
                    'cart_close_link' => '<a/>',
                    'rmd_open_link' => '<a href="#">',
                    'rmd_close_link' => '<a/>',
                    'unsubscribe_open_link' => '<a href="#">',
                    'unsubscribe_close_link' => '<a/>',
                    'firstname' => $cart_data['firstname'],
                    'lastname' => $cart_data['lastname'],
                    'quantity' => 'X',
                    'voucher' => '<b>'.Tools::passwdGen(7).'</b>',
                    'voucher_value' => Tools::displayPrice(0.0),
                    'voucher_expire' => Configuration::get('TND_VOUCHER_EXPIRE'),
                    'cart_content' => ($this->with_cart_content ? $obj->displayProductsOfCarts($cart_data['id_cart'], $this->id) : ''),
                    'title_cart_content' => ($this->with_cart_content ? $this->title_cart_content : ''),
                );
                
                foreach($default AS $key => $value)
                        if(!array_key_exists($key, $array))
                                $array[$key] = $value;

                
                $tpl_file = 'templates/'.$this->id.'/cartabandonment_template_'.(int)$this->id.'.tpl';
                
                $smarty->assign($array);
                
                
                return $obj->display(dirname(__FILE__).'/../cartabandonment.php', $tpl_file);
          
        }
    
}



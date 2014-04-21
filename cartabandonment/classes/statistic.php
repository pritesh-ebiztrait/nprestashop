<?php

class Statistic extends ObjectModel
{
        public $id_cart;
        public $id_statistic;
        public $id_discount;
        public $num_sending;
        public $active;
        private $transformed;
        private $read;
        private $unsubscribed;
        private $date_unsubscribing;
        private $date_reading;
        public $date_add;
        public $date_upd;
        public $date_transforming;
        public $id_template;
        
        
        protected $table = 'cart_abandonment_stats';
        protected $identifier = 'id_statistic';
        
        
        public function getFields() 
	{ 
                $fields = array();
		parent::validateFields();
                $fields['id_cart'] = (int)$this->id_cart;
                $fields['num_sending'] = (int)$this->num_sending;
                $fields['active'] = (bool)$this->active;
                $fields['id_discount'] = (int)$this->id_discount;
                $fields['date_add'] = pSQL($this->date_add);
                $fields['date_upd'] = pSQL($this->date_upd);
                
		return $fields;	 
	}
        
        public static function getByCartAndNumSending($id_cart, $num_sending)
        {
                $id_statistic = DB::getInstance()->getValue('
                        SELECT `id_statistic`
                        FROM `'._DB_PREFIX_.'cart_abandonment_stats`
                        WHERE `id_cart`='.(int)$id_cart.' AND `num_sending`='.(int)$num_sending);
                
                if (!empty($id_statistic))
                        return new self($id_statistic);
        }
        
        public static function setRead($id_cart, $num_sending)
        {
                $stat = self::getByCartAndNumSending($id_cart, $num_sending);
                if ($stat instanceof Statistic)
                        return (bool)$stat->setField('read');
        }
        
        public static function setTransformed($id_cart)
        {
                $results = Db::getInstance()->ExecuteS('SELECT * 
                                        FROM`'._DB_PREFIX_.'cart_abandonment_stats`
                                        WHERE `id_cart` = '.(int)$id_cart);
                if (!empty($results))
                        return (bool)Db::getInstance()->Execute('UPDATE `'._DB_PREFIX_.'cart_abandonment_stats`
                                        SET `transformed` = 1, `date_transforming` = NOW()
                                        WHERE `id_cart` = '.(int)$id_cart);
        }
        
        public static function setUnsubscribed($id_cart, $num_sending)
        {
                $stat = self::getByCartAndNumSending($id_cart, $num_sending);
                if ($stat instanceof Statistic)
                        return (bool)$stat->setField('unsubscribed');
        }
        
        private function setField($field)
        {
                if (($field == 'read') AND empty($this->read))
                {
                        return Db::getInstance()->Execute('UPDATE `'._DB_PREFIX_.'cart_abandonment_stats`
                                                    SET `read` = 1, `date_reading` = NOW()
                                                    WHERE `id_statistic` = '.(int)$this->id_statistic);
                }
                elseif (($field == 'unsubscribed') AND empty($this->unsubscribed))
                {
                        return Db::getInstance()->Execute('UPDATE `'._DB_PREFIX_.'cart_abandonment_stats`
                                                    SET `unsubscribed` = 1, `date_unsubscribing` = NOW()
                                                    WHERE `id_statistic` = '.(int)$this->id_statistic);
                }
        }
        
        
        public static function countCarts()
        {
                $query = '
                        SELECT COUNT(`cas`.`id_cart`)
                        FROM `'._DB_PREFIX_.'cart_abandonment_stats` `cas`
                        WHERE `cas`.`num_sending` = (
                                SELECT MAX(`cas2`.`num_sending`)
                                FROM `'._DB_PREFIX_.'cart_abandonment_stats` `cas2`
                                WHERE `cas`.`id_cart` = `cas2`.`id_cart`)';
                return Db::getInstance()->getValue($query);
        }

        public static function get($order_by = 'id_cart', $desc = false, $limit = 50, $begin = 0)
        {
                if (empty($order_by) OR !in_array($order_by, array('date_add', 'num_sending', 'transformed', 'unsubscribed', 'read')))
                        $order_by = 'id_cart';
                
                $query = '
                        SELECT *
                        FROM `'._DB_PREFIX_.'cart_abandonment_stats` `cas`
                        LEFT JOIN `'._DB_PREFIX_.'cart` `ca` ON `ca`.`id_cart` = `cas`.`id_cart`
                        LEFT JOIN `'._DB_PREFIX_.'customer` `cu` ON `cu`.`id_customer` = `ca`.`id_customer`
                        WHERE `cas`.`num_sending` = (
                                SELECT MAX(`cas2`.`num_sending`)
                                FROM `'._DB_PREFIX_.'cart_abandonment_stats` `cas2`
                                WHERE `cas`.`id_cart` = `cas2`.`id_cart`)';

                if (!empty($order_by) AND in_array($order_by, array ('transformed', 'read', 'unsubscribed', 'id_cart', 'date_sending', 'num_sending')))
                        $query .= ' ORDER BY `cas`.`'.pSQL(strtolower($order_by)).'` '.(empty($desc) ? 'ASC' : 'DESC');
                
                $query .= ' LIMIT '.(int)$begin.', '.(int)$limit;

                return Db::getInstance()->ExecuteS($query);
        }
        
        
        public function displayGraph()
        {
                
        }
}
<?php
class MailController{

	public function __construct (){
	
	}
	
	public function sendMail($reminder){
	
	}
	
	public function getAbandonedCarts($reminder){
		$query = 
				'SELECT `ca`.*, `cu`.`firstname`, `cu`.`lastname`, `cu`.`id_customer`, `cu`.`email`
				FROM `'._DB_PREFIX_.'cart` `ca`
				LEFT JOIN `'._DB_PREFIX_.'orders` `ord` ON `ord`.`id_cart` = `ca`.`id_cart`
				LEFT JOIN `'._DB_PREFIX_.'cart_abandonment_stats` `cas` ON `cas`.`id_cart` = `ca`.`id_cart`
				INNER JOIN `'._DB_PREFIX_.'customer` `cu` ON `cu`.`id_customer` = `ca`.`id_customer`
				WHERE `ord`.`id_order` IS NULL AND `cas`.`id_statistic` IS NULL';
		
	}
	
	private function getReminder($reminder){
		if($reminder == 1)
			return Configuration::get('ABANDONCART_FIRST_REMINDER');
		elseif($reminder == 2)
			return Configuration::get('ABANDONCART_SECOND_REMINDER');
	}
	
	private function getWhatReminder($reminder){
		if($reminder == 1)
			return Configuration::get('ABANDONCART_FIRST_REMINDER_WHAT');
		elseif($reminder == 2)
			return Configuration::get('ABANDONCART_SECOND_REMINDER_WHAT');
	}
	
			Configuration::updateValue('ABANDONCART_MAX_DATE', Tools::getValue('max_reminder'));
			Configuration::updateValue('ABANDONCART_MAX_DATE_WHAT', Tools::getValue('max_reminder_what'));
}
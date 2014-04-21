<?php
class GodController extends FrontController{

	public function __construct (){
		if(Tools::getValue('edit') == 1){
			$templateController = new TemplateController();
			$templateController->edit();
		}
		if(Tools::getValue('conf') == 1){
			Configuration::updateValue('ABANDONCART_FIRST_REMINDER', Tools::getValue('first_reminder'));
			Configuration::updateValue('ABANDONCART_FIRST_REMINDER_WHAT', Tools::getValue('first_reminder_what'));
			Configuration::updateValue('ABANDONCART_SECOND_REMINDER', Tools::getValue('second_reminder'));
			Configuration::updateValue('ABANDONCART_SECOND_REMINDER_WHAT', Tools::getValue('second_reminder_what'));
			Configuration::updateValue('ABANDONCART_MAX_DATE', Tools::getValue('max_reminder'));
			Configuration::updateValue('ABANDONCART_MAX_DATE_WHAT', Tools::getValue('max_reminder_what'));
		}
	}
	
	public static function getTemplate(){
		
		if(self::isDebug())
			return 'views/templates/admin/debug.tpl';
		if(self::isFirstTime())
			return 'views/templates/admin/first_time.tpl';
		else
			return 'views/templates/admin/back2.tpl';
	}
	private static function isFirstTime(){
		return false;
	}
	
	private static function isDebug(){
		return false;
	}
	
}
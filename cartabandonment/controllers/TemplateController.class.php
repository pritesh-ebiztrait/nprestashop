<?php
class TemplateController extends FrontController{

	public function __construct (){
			
	}
	
	public function edit(){
		if(!Tools::getValue('tpl')) return false;
		$template = new Template(null, new Model(Tools::getValue('tpl')));
		$template->save();
		Tools::redirect(Tools::getValue('uri'));
	}
	
	public static function getTemplates(){
		$query = "SELECT *
				  FROM `ps_cartabandonment_template`";
		return Db::getInstance()->ExecuteS($query);
	}
	
	public static function deleteTemplate($id_template){
		$query = "DELETE
					FROM `ps_cartabandonment_template`
					WHERE id_template = " . $id_template;
		$query2 = "DELETE
					FROM `ps_cartabandonment_template_color`
					WHERE id_template = " . $id_template;
		$query3 = "DELETE
					FROM `ps_cartabandonment_template_field`
					WHERE id_template = " . $id_template;

		return Db::getInstance()->ExecuteS($query) && Db::getInstance()->ExecuteS($query2) && Db::getInstance()->ExecuteS($query3);
	}
}
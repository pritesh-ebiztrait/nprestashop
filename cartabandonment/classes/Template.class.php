<?php
class Template extends ObjectModel{

	public  $id_template 	= null;
	public  $model	 		= null;
	private $fields		 	= array();
	
	public function __construct($id_template = null, $model = null){
		$this->id_template = $id_template;
		$this->model	   = $model;
	}
	
	public function save(){
		if(!Db::getInstance()->Execute("INSERT INTO "._DB_PREFIX_."cartabandonment_template VALUES (NULL, " . (int)$this->model->getId() . ", '" . pSQL(Tools::getValue('name')) . "')"))
			d('Erreur lors de l\'enregistrement du template');
			
		$this->id_template = Db::getInstance()->Insert_ID();
		$content = $this->model->getContent();
		//$this = mysql_real_escape_string($content);
		$this->editLeftColumn($content);
		$this->editRightColumn($content);
		$this->editCenter($content);
		$this->editColors($content);
		$content = str_replace('%logo%', 'http://'.Tools::getShopDomain().__PS_BASE_URI__.'img/logo.jpg', $content);

		$fp = fopen('../modules/cartabandonment/tpl/'.$this->id_template.'.html', 'w+');
		fwrite($fp, $content);
		fclose($fp);
	}
	
	private function editLeftColumn(&$content){
		if(!$this->model->getLeftColumn())
			return false;
		for($nb = 1 ; $nb <= $this->model->getTxtsLeft() ; $nb++){
			$content = str_replace('%left_'.$nb.'%', Tools::getValue('left_'.$nb), $content);
			$this->saveColumn('left', $nb, Tools::getValue('left_'.$nb));
		}
	}
	
	private function editRightColumn(&$content){
		if(!$this->model->getRightColumn())
			return false;
		for($nb = 1 ; $nb <= $this->model->getTxtsRight() ; $nb++){
			$content = str_replace('%right_'.$nb.'%', Tools::getValue('right_'.$nb), $content);
			$this->saveColumn('right', $nb, Tools::getValue('right_'.$nb));
		}
	}
	
	private function editCenter(&$content){
		for($nb = 1 ; $nb <= $this->model->getTxtsCenter() ; $nb++){
			$content = str_replace('%center_'.$nb.'%', Tools::getValue('center_'.$nb), $content);
			$this->saveColumn('center', $nb, Tools::getValue('center_'.$nb));
		}
	}
	
	private function editColors(&$content){
		for($nb = 1 ; $nb <= $this->model->getColors() ; $nb++){
			$content = str_replace('%color_'.$nb.'%', Tools::getValue('color_picker_'.$nb), $content);
			Db::getInstance()->Execute("INSERT INTO "._DB_PREFIX_."cartabandonment_template_color VALUES (NULL, ".(int)$this->id_template.", ".$nb.", '".Tools::getValue('color_picker_'.$nb)."')");
		}
	}
	private function saveColumn($column, $id_field, $value){
		if(!isset($column) || !isset($id_field) || !isset($value))
			return false;
		return Db::getInstance()->Execute("INSERT INTO "._DB_PREFIX_."cartabandonment_template_field VALUES (NULL, ".(int)$this->id_template.", ".$id_field.", '".$value."', '".$column."')");
	}
}
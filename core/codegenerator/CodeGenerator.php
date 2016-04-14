<?php

namespace codegenerator;

class CodeGenerator {
	
	public $classname;
	private $smarty;
	private $table;
	
	function __construct($classname) {
		$this->classname = $classname;
		$this->table = \Doctrine::getTable($this->classname);
		
		$relations = $this->table->getRelations();
		$mapHasOne = array();
		$mapHasMany = array();
		foreach($relations as $rel) {
			$type = $rel->getType();
			if($type == \Doctrine_Relation::ONE) {
				$mapHasOne[$rel->getLocalFieldName()] = $rel->getClass();
			} else {
				// TODO: Mapear los Has Many
			}
		}
			
		$this->smarty = new \Smarty();
		$this->smarty->setTemplateDir(__DIR__."/templates");
		$this->smarty->setCompileDir(__DIR__."/../../smarty/templates_c/");
		$this->smarty->setConfigDir(__DIR__."/../../smarty/configs/");
		$this->smarty->setCacheDir(__DIR__."/../../smarty/cache/");
		$this->smarty->addPluginsDir(__DIR__."/../../smarty/plugins/");
		
		$this->smarty->assign("classname", $this->classname);
		$this->smarty->assign("objectname", strtolower($this->classname));
		$this->smarty->assign("columns", $this->table->getColumns());
		$this->smarty->assign("mapHasOne", $mapHasOne);
		$this->smarty->assign("mapHasMany", $mapHasMany);
	}
	
	function generateCRUDIndexView($file = null) {
		$output = $this->smarty->fetch("index.tpl");
		
		if($file)
			$this->writeTemplate($output, $file);
		else
			echo $output;
	}
	
	function generateCRUDViewView($file = null) {
		$output = $this->smarty->fetch("view.tpl");
		
		if($file)
			$this->writeTemplate($output, $file);
		else
			echo $output;
	}
	
	function generateCRUDEditView($file = null) {
		$table = \Doctrine::getTable($this->classname);
		$output = $this->smarty->fetch("edit.tpl");
		
		if($file)
			$this->writeTemplate($output, $file);
		else
			echo $output;
	}
	
	function generateCRUDIndexController($file = null) {
		$output = $this->smarty->fetch("index-controller.tpl");
		
		if($file)
			$this->writeController($output, $file);
		else
			echo $output;
	}
	
	function generateCRUDViewController($file = null) {
		$output = $this->smarty->fetch("view-controller.tpl");
		
		if($file)
			$this->writeController($output, $file);
		else
			echo $output;
	}
	
	function generateCRUDEditController($file = null) {
		$output = $this->smarty->fetch("edit-controller.tpl");
		
		if($file)
			$this->writeController($output, $file);
		else
			echo $output;
	}
	
	function generateCRUDAjaxController($file = null) {
		$output = $this->smarty->fetch("ajax-controller.tpl");
		
		if($file)
			$this->writeController($output, $file);
		else
			echo $output;
	}
	
	function generateCRUDIndexJs($file = null) {
		$output = $this->smarty->fetch("index-js.tpl");
		
		if($file)
			$this->writeTemplate($output, $file);
		else
			echo $output;
	}
	
	function generateCRUDEditJs($file = null) {
		$output = $this->smarty->fetch("edit-js.tpl");
		
		if($file)
			$this->writeTemplate($output, $file);
		else
			echo $output;
	}
	
	function writeTemplate($output, $filename) {
		$file = fopen($filename, "w");
		fwrite($file, $output);
		fclose($file);
	}
	
	function writeController($output, $filename) {
		$controllername = $this->classname ."Controller";
		
		if(!file_exists($filename)) {
			$file = fopen($filename, "w");
			$this->smarty->assign("controllername", $controllername);
			$controllerstr = $this->smarty->fetch("controller.tpl");
			fwrite($file, $controllerstr);
			fclose($file);
		}
		
		$file = fopen($filename, "r+");
		fseek($file, -1, SEEK_END);
		
		fwrite($file, $output);
		fwrite($file, "\n\n}");
		
		fclose($file);
	}
	
}
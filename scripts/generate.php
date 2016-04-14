<?php

require_once(__DIR__ ."/../init.php");

if(isset($argv[1]) && $argv[1] == "CRUD") {
	if(!isset($argv[2]))
		die("Syntax: generate.php CRUD <model_class_name>\n");
	
	$class = $argv[2];
	$cg = new codegenerator\CodeGenerator($argv[2]);
	
	$controllerfile = __DIR__ ."/../app/controllers/". $class ."Controller.php";
	$viewpath = __DIR__ ."/../app/views/". strtolower($class) ."/";
	$jspath = __DIR__ ."/../public/js/". strtolower($class) ."/";
	
	// Generating controller
	$cg->generateCRUDIndexController($controllerfile);
	$cg->generateCRUDEditController($controllerfile);
	$cg->generateCRUDViewController($controllerfile);
	$cg->generateCRUDAjaxController($controllerfile);
	
	// Generating views
	if(!file_exists($viewpath)) {
		mkdir($viewpath, 0777, true);
	}
		
	$cg->generateCRUDIndexView($viewpath ."index.tpl");
	$cg->generateCRUDEditView($viewpath ."edit.tpl");
	$cg->generateCRUDViewView($viewpath ."view.tpl");
	
	// Generating Javascript
	if(!file_exists($jspath)) {
		mkdir($jspath, 0777, true);
	}
	
	$cg->generateCRUDIndexJs($jspath ."index.js");
	$cg->generateCRUDEditJs($jspath ."edit.js");
}
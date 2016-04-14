<?php

require_once("init.php");

Doctrine_Core::generateModelsFromDb('app/models', array('doctrine'), array('generateTableClasses' => true));
//Doctrine_Core::generateModelsFromYaml('yaml/schema.yml', 'app/models');
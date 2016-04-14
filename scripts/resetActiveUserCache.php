<?php

require_once(__DIR__ ."/../core/init.php");

use \plugin\Plugin as Plugin;
Plugin::activate("doctrine1");

$table = Doctrine::getTable("ActiveUserCache");

$table->resetTable();
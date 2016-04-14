<?php

require_once(__DIR__ . "/core/init.php");


use \plugin\Plugin as Plugin;

Plugin::activate("doctrine1");
Plugin::activate("mvc", dirname($_SERVER["SCRIPT_FILENAME"]) . "/vendor/makmonty/movac-mvc");
// Plugin::activate("mvc", __DIR__ . "/../movac-mvc");

Plugin::activate("jquery");
Plugin::activate("bootstrap_theme_yeti");

$dispatcher = new Dispatcher();
$dispatcher->run($_SERVER['REQUEST_URI']);

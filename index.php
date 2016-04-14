<?php

require_once(__DIR__ . "/core/init.php");


use \plugin\Plugin as Plugin;

Plugin::activate("doctrine1");
Plugin::activate("mvc");
// Plugin::activate("sessions");

Plugin::activate("jquery");
Plugin::activate("bootstrap_theme_yeti");

$request = substr($_SERVER['REQUEST_URI'], strlen(ROOT));

$dispatcher = new Dispatcher();
$dispatcher->run($request);

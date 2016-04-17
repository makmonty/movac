<?php

require_once(__DIR__ . "/core/init.php");

use \plugin\Plugin as Plugin;
use \controller\Controller as Controller;

$scriptDir = dirname($_SERVER["SCRIPT_FILENAME"]);

Controller::addControllersDir($scriptDir ."/app/controllers");

Plugin::activate("doctrine1");

$router = new \router\Router();

require_once($scriptDir . "/config/routes.php");

$router->dispatch($_SERVER["REQUEST_URI"]);

<?php

/**
 * Create an environment.php file with the following format:
 *

<?php
define("DBHOST", "localhost");
define("DBUSER", "user");
define("DBPASSWORD", "password");
define("DBDATABASE", "db");
define("BASE_URL", "http://localhost/.../");

 */
function defineIfNotDefined($key, $value) {
	if(!defined($key)) define($key, $value);
}

if(!file_exists(__DIR__ ."/environment.php")) {
	echo "Please, define an environment.php file";
	exit;
}

require_once(__DIR__ ."/environment.php");

// You can override any of the following in the environment.php file

defineIfNotDefined("APP_NAME", "Movac app");
defineIfNotDefined("PUBLIC_DIR", "public");
defineIfNotDefined("RESOURCES_CACHE_DIR", PUBLIC_DIR."/cache");
defineIfNotDefined("LOG_DIR", "/var/log");

defineIfNotDefined("SITE_ENABLED", true);
defineIfNotDefined("SALT", "movacsalt");
defineIfNotDefined("COOKIE_PREFIX", "movac_");
defineIfNotDefined("USESMTP", false);
defineIfNotDefined("SMTPSERVER", "");
defineIfNotDefined("SMTPUSER", "");
defineIfNotDefined("SMTPPASSWORD", "");
defineIfNotDefined("SMTPPORT", "25");
defineIfNotDefined("SMTPSECURE", "tls");

defineIfNotDefined("VERSION", "1.0");

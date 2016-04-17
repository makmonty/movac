<?php

namespace controller;

require_once("vendor/smarty/smarty/libs/Smarty.class.php");

use \hook\Hook as Hook;
use \session\Session as Session;

class Controller {
	public $default_action = "index";

	public $action = "";

	public $referer;
	public $redirectTo;
	public $breadcrumbs;
	private $flash_memory;

	public $beforeFilters = array();
	public $afterFilters = array();
	public $aroundFilters = array();

	public $skipBeforeFilters = array();
	public $skipAfterFilters = array();
	public $skipAroundFilters = array();

	private $layout = "";

	static $flash_key = "flash";

	public $resource_bundles = array();

	private static $controller_dirs = array();

	public function __construct() {
		Hook::run("controller_construct_start", $this);

		$scriptDir = dirname($_SERVER["SCRIPT_FILENAME"]);

		$smarty = new \Smarty();

		$smarty->setCompileDir($scriptDir ."/smarty/templates_c/");
		$smarty->setConfigDir($scriptDir ."/smarty/configs/");
		$smarty->setCacheDir($scriptDir ."/smarty/cache/");

		$smarty->addPluginsDir($scriptDir ."/smarty/plugins/");
		$smarty->addTemplateDir($scriptDir ."/app/views");

		$this->smarty = $smarty;

		$this->errors = array();
		$this->success = array();
		$this->info = array();
		$this->referer = (isset($_SERVER['HTTP_REFERER']))? $_SERVER['HTTP_REFERER'] : "";
		$this->breadcrumbs = array();
		$this->base_url = BASE_URL;
		$this->app_name = APP_NAME;

		$this->loadFlash();

		Hook::run("controller_construct_end", $this);
	}

	public static function exists($controller) {
		return !!self::getControllerFile($controller);
	}

	public static function getControllerFile($controller) {
		foreach(self::$controller_dirs as $dir) {
			if(file_exists($dir . $controller .".php")) {
				return $dir . $controller .".php";
			}
		}

		return false;
	}

	public static function addControllersDir($directory) {
		if(substr($directory, -1) != "/")
			$directory .= "/";
		self::$controller_dirs[] = $directory;
	}

	public static function autoload($controller) {
		if(substr($controller,-10) == "Controller") {
			if($filename = self::getControllerFile($controller)) {
				require_once($filename);
			}
		}
	}

	public function flash($key, $value) {
		$flash = (Session::issetKey(self::$flash_key))? Session::get(self::$flash_key) : array();
		$flash[$key] = $value;
		Session::set(self::$flash_key, $flash);
	}

	public function loadFlash() {
		$this->flash_memory = array();
		if(Session::issetKey(self::$flash_key)) {
			$this->flash_memory = Session::get(self::$flash_key);
			Session::unsetKey(self::$flash_key);
		}
	}

	public function getFlash($key) {
		return $this->flash_memory[$key];
	}

	public function isAjax() {
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}

	public function requireAjax() {
		if(!$this->isAjax())
			$this->showErrorPage("401");
	}

	public function requireGetParameters($parameters) {
		$missing = array();
		foreach($parameters as $param) {
			if(!isset($_GET[$param])) {
				$missing[] = $param;
			}
		}

		if(count($missing) > 0) {
			throw new MissingParametersException("Missing parameters: ". implode(", ", $missing));
		}
	}

	public function ajaxWrapper() {
		$data['error'] = '';

		$action = $this->action;

		try {
			$return = call_user_func_array(array($this, $action), func_get_args());

			if(is_array($return))
				$data = array_merge($data, $return);

		} catch(\Exception $e) {
			$data['error'] = $e->getMessage(); // _ERR_UNEXPECTED;
		}

		header('Content-type: text/json');

		echo json_encode($data);
	}

	public function wsWrapper() {
		$data['status'] = 0;

		$action = $this->action;

		try {
			$return = call_user_func_array(array($this, $action), func_get_args());

			if(is_array($return))
				$data = array_merge($data, $return);
		} catch(MissingParametersException $e) {
			$data['status'] = -1;
			$data['message'] = $e->getMessage();
		} catch(\Exception $e) {
			$data['status'] = -1;
			$data['message'] = $e->getMessage();
		}

		header('Content-type: text/json');

		echo json_encode($data);
	}

	public function showErrorPage($error) {
		switch($error) {
			case "404":
				$error_header = "404 Not Found";
				break;
			case "401":
				$error_header = "401 Unauthorized";
				break;
			case "500":
				$error_header = "500 Internal Server Error";
				break;
			default:
				$error_header = "";
		}

		if($error_header)
			header($_SERVER["SERVER_PROTOCOL"] ." ". $error_header);

		exit;
	}

	public function checkEmail($pMail) {
		$pattern = "/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@+([_a-zA-Z0-9-]+\.)*[a-zA-Z0-9-]{2,200}\.[a-zA-Z]{2,6}$/";
		if (preg_match($pattern, $pMail)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Comprueba que la fecha que se escoge de la interfaz, lleva el formato correcto, es decir,
	 * no ha sido modificada 'a pelo'.
	 */
	public function checkDateFormat($fecha) {

		$ok = false;
		if ($fecha != "") {
			$temp = explode("/", $fecha);
			if(isset($temp[0]) && (strlen($temp[0]) == 2) && isset($temp[1]) && (strlen($temp[1]) == 2)
					&& isset($temp[2]) && (strlen($temp[2]) == 4)){
				$ok = true;
			}
		}
		return $ok;

	}

	/**
	 * Comprueba que la fecha introducida no tiene los días mayor de 31 ni los meses mayor de 12.
	 */
	public function checkDateNumbers($fecha) {

		$ok = false;
		if ($fecha != "") {
			$temp = explode("/", $fecha);
			if(isset($temp[0]) && ($temp[0] <= 31) && isset($temp[1]) && ($temp[1] <= 12)){
				$ok = true;
			}
		}
		return $ok;

	}

	function setLayout($layout) {
		$this->view->setLayout($layout);
	}

	function redirect($url, $permanent = false) {
		if($permanent)
			header("Location: ". $url, true, 301);
		else
			header("Location: ". $url);
		exit;
	}

	function addError($error) {
		$this->errors[] = $error;
	}

	function addInfo($info) {
		$this->info[] = $info;
	}

	function addSuccess($info) {
		$this->success[] = $info;
	}

	function pushBreadcrumb($name, $url) {
		$this->breadcrumbs[] = array("name" => $name, "url" => $url);
	}

	/**
	 * Formatea una fecha dd/mm/aaaa a una fecha aaaa-mm-dd que es la que reconoce la BD.
	 */
	public function formatDate($fecha) {

		if ($fecha != "") {
			$temp = explode("/", $fecha);
			$fecha = $temp[2] . "-" . $temp[1] . "-" . $temp[0];

		} else {
			$fecha = null;
		}
		return $fecha;

	}


	/**
	 * Función que sube X ficheros que se pasan en un formulario con inputs con name del tipo -> name[]
	 * @param int id_aux Identificador que se usa para evitar la repiticion del nombre de un fichero, o para crear una carpeta personal para el fichero,
	 * @param string path Ruta donde se van a subir los ficheros. Tiene que empezar con "/" y terminar con "/"
	 * @param string $old_filename Indica el antiguo fichero que se debe quitar(unlink) de la ruta especificada. Sirve para no dejar archivos en desuso
	 * en el servidor.
	 * @param string thumb Indica si los ficheros a subir llevan thumb o no. Por defecto es false. Solo tiene sentido si el fichero es una imagen
	 * @param int width Anchura en píxeles de la imagen que se quiere redimensionar para el thumb. Si thumb es nulo no se tiene en cuenta este valor
	 * @param int height Altura en píxeles de la imagen que se quiere redimensionar para el thumb. Si thumb es nulo no se tiene en cuenta este valor
	 * @return array info Con los nombre de los archivos subidos y las rutas con las que enlazarlos en la BD.
	 * La forma de info es info['index_en_$_FILES'] = array('name' => 'nombre del fichero', 'filename' => 'nombre del fichero a guardar')
	 *
	 */
	public function uploadFile($file, $dest){
		if(file_exists($file["tmp_name"])) {

			$destpatharr = explode("/", $dest);
			array_pop($destpatharr);
			$destpath = implode("/", $destpatharr);

			if(!is_dir($destpath)){
				mkdir($destpath,0777,true);
				chmod($destpath,0777);
			}

			move_uploaded_file($file["tmp_name"], $dest);
		}
	}
}

class MissingParametersException extends \Exception {}

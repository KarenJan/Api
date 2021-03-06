<?php
namespace Framework\Core\Application;

use \Framework\Core\Exceptions\FileNotFoundException;
use \Framework\Core\Dispatcher\Dispatcher;
use \Framework\Core\ObjectManager\ObjectManager;
use \Framework\Core\Router\Router;
use \Framework\Core\Widget\WidgetManager;
use \Framework\Core\FlashMessenger\FlashMessenger;
use \Framework\Core\Globals\CookieManager;
use \Framework\Core\Globals\SessionManager;
use \Framework\Core\LanguageManager\LanguageManager;
use \Framework\Core\DatabaseDrivers\PDO\Driver;
use \Framework\Lib\Memcache\DBCache;
use \Framework\Lib\Authentication\AuthenticationManager;
use \Framework\Core\FileSystem\FileSystem;
use \Framework\Core\Loader\SplClassLoader;
use Framework\Core\Logger\Logger;

class WebApplication extends Application{
	/**
	 * Debug Mode
	 */
	private static $debug=false;
	/**
	 * Object Manager
	 */
	private static $objectManager;
	/* 
	* Base Url
	*/
	public $baseUrl;
	/*
	 * Media files Url
	*/
	public $resourceUrl;
	/*
	 * Language
	*/
	public $language;
	/*
	 * History Request identificator
	*/
	public $isAjaxRequest=false;
	/**
	 * Run All Applications
	 */
	public function runApplication(){
		$loader = new SplClassLoader('Application', 'backend');
		$loader->register();
		/****************** Main Config ************************/
		$configFile = BASE_PATH . DS . 'backend' . DS . 'Application' . DS . 'Settings' . DS . 'main.config.php';
		if(file_exists($configFile))
			$config = include($configFile);
		else 
			throw new FileNotFoundException('Main Config File doesn`t exists'); 
		/****************** Site Debug *******************/
		if(isset($config['debug']) && $config['debug'] == 1)
			$this->setDebugMode(true);
		else
			$this->setDebugMode();
		/****************** Db connect ********************/
		$dbConfigFile = BASE_PATH . DS . 'backend' . DS . 'Application' . DS . 'Settings' . DS . 'db.config.php';
		if(file_exists($dbConfigFile))
			$dbConfigFile = include($dbConfigFile);
		else
			throw new FileNotFoundException('Database Config File doesn`t exists');
		if(isset($dbConfigFile['type']) && $dbConfigFile['type'] == 'pdo')
			Driver::getInstance()->setup($dbConfigFile['config']);
		/****************** Object Manager ****************/
		self::$objectManager = $om = new ObjectManager;
				/***** Dispatcher Object ******/
		$dispatcher = new Dispatcher;
		$om->setObject('dispatcher', $dispatcher);
				/***** Session Object ******/
		$session = new SessionManager;
		$om->setObject('session', $session);
		$session->start();
		/***** FileSystem Object ******/
		$fileSystem = new FileSystem();
		$om->setObject('fileSystem', $fileSystem);
		/***** FlashMessenger Object ******/
		$flashMessenger = new FlashMessenger;
		$om->setObject('flashMessenger', $flashMessenger);
				/***** WidgetManager Object ******/
		$widgetManager = new WidgetManager;
		$om->setObject('widget', $widgetManager);
				/***** Cookie Object ******/
		$cookie = new CookieManager;
		$om->setObject('cookie', $cookie);
				/***** Memcache Object ******/
		$memcache = new DBCache;
		$om->setObject('memcache', $memcache);
				/***** Authentication Object ******/
		$auth = new AuthenticationManager;
		$auth->checkStatus();
		$om->setObject('authentication', $auth);
		/****************** Router ************************/
		$routeConfigFile = BASE_PATH . DS . 'backend' . DS . 'Application' . DS . 'Settings' . DS . 'route.config.php';
		if(file_exists($routeConfigFile))
			$routeConfigFile = include($routeConfigFile);
		else
			throw new FileNotFoundException('Route Config File doesn`t exists');
		$dispatcher->setRoute($routeConfigFile);
		/****************** Set Base Url ******************/
		if(isset($config['base_url']))
			$this->setBaseUrl($config['base_url']);
		else 
			$this->setBaseUrl(null);
		/****************** Language *********************/
		$translate = new LanguageManager;
		if(isset($config['default_language']))
			$translate->set($config['default_language']);
		else 
			$translate->set();
		$om->setObject('translate', $translate);
		$translate->translate($this->language);
		/****************** Logger *********************/
		$logger = new Logger;
		$om->setObject('logger', $logger);
		/****************** Unsets ***********************/
		unset($config);
		unset($om);
	}
	
	
	public function runError($code = false){
		return $this->getObjectManager()->get('dispatcher')->dispatchToError($code);
	}
	
	/**
	 * Set Debug Mode
	 */
	private function setDebugMode($mode=false){
		if($mode){
			error_reporting(E_ALL);
			self::$debug = true;
		}
		else
			error_reporting(0);
	}
	/**
	 * Get Debug Mode
	 * @return boolean
	 */
	public function getDebugMode(){
		return self::$debug;
	}
	/**
	 * Set Base Url
	 */
	private function setBaseUrl($url=null){
		if(!is_null($url) && $url != ""){
			$this->baseUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $url;
		}
		else{
			$this->baseUrl = 'http://' . $_SERVER['HTTP_HOST'];
		}
		$this->resourceUrl = $this->baseUrl . DS . 'resource';
		$this->getObjectManager()->get('cookie')->set('m_ref', $this->baseUrl);
	}
	/**
	 * Get Object Manager Instance
	 */
	public function getObjectManager(){
		return self::$objectManager;
	}
	
	/**
	 * Get Flash Messenger Instance
	 */
	public function getFlashMessenger(){
		return self::$objectManager->get('flashMessenger');
	}
	/**
	 * Dump 
	 */
	public function _dump($var){
		if(self::$debug){
			echo "<div class='DUMP' style='border: 2px solid #cc9966; background-color: #ffff99;margin-top: 30px; color:#000000;position: relative;z-index: 100000; padding: 10px; word-wrap:break-word'><pre>";
			var_dump($var);
			echo "<pre></div>";
		}
	}
	/************************************************************************* Run Methods ************************************************************************/
	
	/**
	 * For PHP Request
	 */
	protected function processRequest(){
		$this->isAjaxRequest = false;
		$route = Router::route(self::$objectManager->get('dispatcher')->getRoute());
		self::$objectManager->get('dispatcher')->dispatch($route);
	}
	/**
	 * For History Api Request
	 */
	protected function historyRequest($url){
		$this->isAjaxRequest = true;
		$route = Router::route(self::$objectManager->get('dispatcher')->getRoute(), $url);
		self::$objectManager->get('dispatcher')->dispatch($route);
	}
	/**
	 * For Ajax Request
	 */
	protected function ajaxRequest($url){
		
	}
}

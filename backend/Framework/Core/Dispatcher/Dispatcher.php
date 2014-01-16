<?php

namespace Framework\Core\Dispatcher;

use \Framework\Api;
use \Framework\Core\Globals\Get;
use \Framework\Core\Router\Router;

class Dispatcher
{
/**
	 * Codes
	 */
	const ERROR_CODE_404 = 404;
	const ERROR_CODE__500 = 500;
	/**
	 * Default Module
	 */
	private $defaulModule = 'default';
	/**
	 * Default Controller
	 */
	private $defaultController = 'IndexController';
	/**
	 * Default Action
	 */
	private $defaultAction = 'indexAction';
	/**
	 * Route
	 */
	private $route=array();
	
	/**
	 * Set Route
	 */
	public function setRoute($route){
		$this->route = $route;
	}
	/**
	 * Get Route
	 */
	public function getRoute(){
		return $this->route;
	}
	/**
	 * Get Autoload
	 */
	public function getAutoLoadAction(){
		return $this->autoload;
	}
	/**
	 * Run Errors
	 * For Developers
	 */
	public function error($message){
		if(Api::app()->getDebugMode()){
			echo "<div class='DUMP' style='border: 2px solid #cc9966; background-color: #ffff99;margin-top: 30px; color:#000000;position: relative;z-index: 100000; padding: 10px'><pre>";
			echo $message;
			echo "<pre></div>";
		}
	}
	/**
	 * Dispatch
	 */
	public function dispatch($route=false){ 
		if($route){
			$module = Get::exists('module');
			$controller = Get::exists('controller');
			$action = Get::exists('action');
			
			if(!$module)
				return $this->dispatchToError(404, 'Module Global Variable doesn`t exists');
			if(!$controller)
				return $this->dispatchToError(404, 'Controller Global Variable doesn`t exists');
			if(!$action)
				return $this->dispatchToError(404, 'Action Global Variable doesn`t exists');
			
			$this->loadModule($module, $controller, $action); 	
		}	
		else 
			return $this->dispatchToError(404, 'Route doesn`t exists in Config RegExp');
	}
	/**
	 * Route for User alias
	 */
	private function aliasRoute(){
		return array(
				'/^\/([A-Za-z1-9._-]+)\/?$/i' => array(
					'type' => 'RegExp',
					'module' => 'Staticpages',
					'controller' => 'error',
					'action' => 'error404',
					'matches' => array('')),
		);
	}
	/**
	 * Set Error Page Params
	 */
	private function getErrorPage(){
		Get::setParams('module', 'staticpages');
		Get::setParams('controller', 'error');
		Get::setParams('action', 'error404');
		return $this->dispatch(true);
	}
	
	/**
	 * Dispatch Error
	 */
	public function dispatchToError($code, $message=false){
		if(Api::app()->getDebugMode() && $message){
			echo "<div class='DUMP' style='border: 2px solid #cc9966; background-color: #ffff99;margin-top: 30px; color:#000000;position: relative;z-index: 100000; padding: 10px'><pre>";
			echo $message;
			echo "<pre></div>";
		}
		return $this->getErrorPage();
	}
	
	// For 404 page
	public function respond404(){
        header('HTTP/1.1 404 Not Found', true, 404);
        ob_end_flush();
        exit;
    }
	
	/**
	 * Load Module
	 */
	public function loadModule($module, $controller, $action){
		/* Api::app()->_dump($module);
		Api::app()->_dump($controller);
		Api::app()->_dump($action); */
		$moduleName = ucfirst(strtolower($module));
		$modulePath = BASE_PATH . DS . 'backend' . DS .  'Application' . DS . 'Modules' . DS . $moduleName;
		$moduleController = ucfirst(strtolower($controller)) . 'Controller';
		
		if(!file_exists($modulePath . DS . 'Controller' . DS . $moduleController . '.php')){
			$route = Router::route($this->aliasRoute(), Api::app()->baseUrl . DS . $module);
			return $this->dispatch($route);
		}
		
		include($modulePath . DS . 'Controller' . DS . $moduleController . '.php');
		$controllerClass = '\\Application\\Modules\\' . $moduleName . '\\Controller\\' . $moduleController;
		
		if(!class_exists($controllerClass))
			return $this->dispatchToError(404, $controllerClass .' Controller Class doesn`t exists');
		
		$moduleControllerObject = new $controllerClass;
		$actionMethod = strtolower($action) . 'Action';
		
		if(!method_exists($moduleControllerObject, $actionMethod))
			return $this->dispatchToError(404, $actionMethod . ' Action Method doesn`t exists in Controller Class');
		
		return $moduleControllerObject->$actionMethod();
	}
}

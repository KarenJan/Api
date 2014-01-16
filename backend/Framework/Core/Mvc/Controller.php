<?php

namespace Framework\Core\Mvc;

use \Framework\Api;
use \Framework\Core\Globals\Get;

class Controller{
	/**
	 * Layout Page Title
	 */
	public $pageTitle = 'MyLiveKit - Social Network | Online Marketplace | Workstation';
	/**
	 * Default Layout
	 */
	public $layout = 'default';
	/**
	 * Default Layout
	 */
	public $mediaLayout = 'media';
	/**
	 * Javascript History Layout
	 */
	protected $push_theme = 'push';
	/**
	 * View File path
	 */
	private $view;
	/**
	 * Vars for extract
	 */
	public $vars = array();
	/**
	 * Scripts
	 */
	private $scripts = array();
	/**
	 * Styles
	 */
	private $styles = array();
	
	
	/**
	 * Assign Vars
	 */
	public function assign($key, $value){
		$this->vars[$key] = $value;
	}
	
	/**
	 * Render Layout File
	 */
	public function render($view){
		$this->addScript();
		$this->addStyle();
		// external variables for view and layout
		if(!empty($this->vars)){
			extract($this->vars, EXTR_REFS);
		}
		$this->view = $view;
		if(Api::app()->isAjaxRequest){
			$layoutFile = BASE_PATH . DS . 'backend' . DS . 'Application' . DS . 'Layouts' . DS . $this->layout . '.php';
			$this->loadFile($layoutFile);
		}
		else {
			$themeFile = BASE_PATH . DS . 'backend' . DS . 'Application' . DS . 'Themes' . DS . $this->push_theme . '.php';
			$this->loadFile($themeFile);
		}
	}
	/**
	 * Load File
	 */
	private function loadFile($file){
		if(file_exists($file)){
			if(Api::app()->isAjaxRequest)
				echo '<script type="text/javascript">Api.removeScripts()</script>';
			// Objects in layout & view
			$this->vars['widgetManager'] = Api::app()->getObjectManager()->get('widget');
			$this->vars['flashMessenger'] = Api::app()->getObjectManager()->get('flashMessenger');
			$this->vars['authManager'] = Api::app()->getObjectManager()->get('authentication');
			
			$T = Api::app()->getObjectManager()->get('translate');
			
			extract($this->vars, EXTR_REFS);
			include $file;
			return;
		}
		return Api::app()->getObjectManager()->get('dispatcher')->error($file . ' file Doesn`t exists in '. GET::exists('module') .' Controller');
	} 
	
	/**
	 * Render Layout File
	 */
	public function getLayout(){
		$layoutFile = BASE_PATH . DS . 'backend' . DS . 'Application' . DS . 'Layouts' . DS . $this->layout . '.php';
		echo '<script type="text/javascript">Api.removeScripts()</script>';
		$this->loadFile($layoutFile);
	}
	/**
	 * Set Layout
	 */
	protected function setLayout($layout=false){
		if($layout)
			$this->layout = $layout;
	}
	/**
	 * Render Layout File
	 */
	public function getContent(){
		return $this->renderPartial($this->view, false, false, true);		
	}
	
	/**
	 * Render Partial
	 */
	public function renderPartial($view, $module = false, $controller = false, $isView = false){
		// external variables for view and layout
		if(!empty($this->vars)){
			extract($this->vars, EXTR_REFS);
			//$this->vars = array();
		}
		if(!$module)
			$module = Get::exists('module');
		if(!$controller)
			$controller = Get::exists('controller');
		$viewFile = BASE_PATH . DS . 'backend' . DS . 'Application' . DS . 'Modules' . DS . ucfirst($module) . DS . 'View' . DS . $controller . DS . $view . '.php';
		
		$T = Api::app()->getObjectManager()->get('translate');
		if(file_exists($viewFile)){
			//Set Page title
			if(Api::app()->isAjaxRequest)
				echo '<script type="text/javascript">Api.setTitle("' . $this->pageTitle . '")</script>';
			//Set Scripts
			foreach($this->scripts as $value)
				echo '<script>Api.setScript("' . $value . '")</script>';
			//Set Styles
			foreach($this->styles as $value)
				echo '<script>Api.setStylesheet("' . $value . '")</script>';
            
			// Objects in layout & view
			$this->vars['widgetManager'] = Api::app()->getObjectManager()->get('widget');
			$this->vars['flashMessenger'] = Api::app()->getObjectManager()->get('flashMessenger');
			$this->vars['authManager'] = Api::app()->getObjectManager()->get('authentication');
            extract($this->vars, EXTR_REFS);
            
            if($isView){
				ob_start();
				include $viewFile;
				$outputBuffer = ob_get_clean();
				echo $outputBuffer;
				ob_end_flush();
			}
			else
				include $viewFile;
			return;
		}
		return Api::app()->getObjectManager()->get('dispatcher')->error($view . ' file Doesn`t exists in '. Get::exists('module') .' Controller');
	}
	
	/**
	 * Render Json Content
	 */
	public function renderJson($params=array()){
		echo json_encode($params);
	} 
	
	/**
	 * Redirect
	 */
	public function redirect($url = '', $globalUrl = false){
		if($globalUrl){
			header('location: ' . $url);
			exit;
		}
		if(!$url)
			header('location: ' . Api::app()->baseUrl);
		else
			header('location: ' . Api::app()->baseUrl . $url);
		exit;
	}
	
	/**
	 * Set Script 
	 * example - $this->addScript('default/main')
	 * default include file example users.script.js
	 */
	protected function addScript($scriptFile = false){
		if($scriptFile){
			$modulePath = BASE_PATH . DS . 'resource' . DS . 'js' . DS . $scriptFile . '.js';
			$scriptFile = Api::app()->resourceUrl . DS . 'js' . DS . $scriptFile . '.js';
			if(file_exists($modulePath))
				$this->scripts[] = $scriptFile;
		} 
		else{
			$module = Get::exists('module');
			$modulePath = BASE_PATH . DS . 'resource' . DS . 'js' . DS . 'application' . DS . $module . '.script.js';
			$moduleScript = Api::app()->resourceUrl . DS . 'js' . DS . 'application' . DS . $module . '.script.js';
			if(file_exists($modulePath))
				$this->scripts[] = $moduleScript;
		}
	}
	
	/**
	 * Set Style
	 * example - $this->addStyle('default/main')
	 * default include file example users.style.js
	 */
	protected function addStyle($styleFile = false){
		if($styleFile){
			$modulePath = BASE_PATH . DS . 'resource' . DS . 'css' . DS . $styleFile . '.css';
			$styleFile = Api::app()->resourceUrl . DS . 'css' . DS . $styleFile . '.css';
			if(file_exists($modulePath))
				$this->styles[] = $styleFile;
		}
		else{
			$module = Get::exists('module');
			$modulePath = BASE_PATH . DS . 'resource' . DS . 'css' . DS . 'application' . DS . $module . '.style.css';
			$moduleStyle = Api::app()->resourceUrl . DS . 'css' . DS . 'application' . DS . $module . '.style.css';
			if(file_exists($modulePath))
				$this->styles[] = $moduleStyle;
		}
	}
	
	public function __destruct(){
		
	}
	
}

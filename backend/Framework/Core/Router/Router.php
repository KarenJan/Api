<?php

namespace Framework\Core\Router;

use \Framework\Api;
use \Framework\Core\Globals\Get;

class Router{
	
	public static function route($cfgRoutes, $uri=false)
	{	
		//Api::app()->_dump($uri);
		//Api::app()->_dump(Api::app()->baseUrl);
		if($uri){
			if($uri == Api::app()->baseUrl)
				$uri = '/';
			$uriRequest = strpos($uri, Api::app()->baseUrl);
			if($uriRequest !== false){
				$uri = substr($uri, strlen(Api::app()->baseUrl) - $uriRequest);
			}
		}
		else
			$uri = $_SERVER['REQUEST_URI'];
				
		//Get Variables
		if (strpos($uri, '?') !== false){
			$uriVars = parse_str(substr(strstr($uri, '?'), 1), $outPutVars);
			//Generate Get variables
			foreach($outPutVars as $key => $value){
				if(($key != 'module') && ($key != 'controller') && ($key != 'action'))
					Get::setParams($key, $value);
			}
			//Generate main uri
			$uri = strstr($uri, '?', true);
		}
		
		foreach ($cfgRoutes as $rule => $settings) {
			$matches = array();
			if (preg_match($rule, $uri, $matches)) {
				Get::setParams('module',$settings['module']);
				Get::setParams('controller', $settings['controller']);
				Get::setParams('action', $settings['action']);
				
				$route['matches'] = array();
				foreach ($settings['matches'] as $key => $varName) {
					if (empty($varName))
						continue;
					if (isset($matches[$key]))
						$_GET[$varName] = $matches[$key];
				}
				//Api::app()->_dump($matches);
				return true;
			}
		}
		// 404 Route Doesn't Exist
		return false;
	}
	
	
}
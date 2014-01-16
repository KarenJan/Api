<?php

namespace Framework\Core\LanguageManager;

use \Framework\Api;
use \Application\Models\Languages;

class LanguageManager
{
	/**
	 * Default System Language
	 */
	private $defaultLanguage = 'en_EN';
	
	/**
	 * Check Language into Database
	 */
	private function checkLanguage($language){
		$languages = Languages::model();
		$query = $languages->where(array('prefix' => $language))->run();
		if(!is_null($query)){
			return true;
		}
		return false;
	}
	/**
	 * Set Application Language
	 */
	public function set($language=false){
		$cookie = Api::app()->getObjectManager()->get('cookie');
		$cookieLanguage = $cookie->get('m_language');
		if($cookieLanguage && $this->checkLanguage($cookieLanguage)){
			Api::app()->language = $cookieLanguage;
		}
		elseif($language && $this->checkLanguage($language)){
			Api::app()->language = $language;
		}
		else{
			Api::app()->language = $this->defaultLanguage;
		}
		$cookie->set('m_language', Api::app()->language);
	}
	/**
	 * @return translated text into Application Message Path
	 */
	public function translate($language){
        $translationFile = BASE_PATH . DS . 'backend' . DS . 'Application' . DS . 'Messages' . DS . $language . '.php';
		if(file_exists($translationFile))
			$this->strings = include $translationFile;
	}
	
	public function __invoke($key)
	{
		return isset($this->strings[$key]) ? $this->strings[$key] : $key;
	}
}

<?php
namespace Framework\Core\Application;

use \Framework\Api;

abstract class Application{

	//PHP Request Method
	abstract protected function processRequest();
	//History Request Method
	abstract protected function historyRequest($url);
	//Ajax Request Method
	abstract protected function ajaxRequest($url);

	public function __construct($config=null){
		Api::setApp($this);
		$this->runApplication();
	}

	/**
	 * Run PHP
	 */
	public function run(){
		$this->processRequest();
	}
	/**
	 * Run History API
	 */
	public function runHistory($url){
		$this->push = true;
		$this->historyRequest($url);
	}
	/**
	 * Run Ajax
	 */
	public function runAjax($url){
		$this->push = true;
		$this->ajaxRequest($url);
	}
}
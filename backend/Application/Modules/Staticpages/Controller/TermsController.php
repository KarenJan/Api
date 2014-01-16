<?php
namespace Application\Modules\StaticPages\Controller;

use \Framework\Api;

class TermsController extends \Framework\Core\Mvc\Controller
{
	public function indexAction(){
		$this->render('index');
	}
	
	
}
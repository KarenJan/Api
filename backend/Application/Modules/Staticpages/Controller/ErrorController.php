<?php

namespace Application\Modules\Staticpages\Controller;

use \Framework\Api;
use Application\Models\Users;
use Application\Models\UsersInfo;
use Application\Models\Languages;

class ErrorController extends \Framework\Core\Mvc\Controller{
	
	public function error404Action(){
		$this->render('404');
	}
	
	
}








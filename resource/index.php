<?php

chdir(dirname(__DIR__));
define('DS', DIRECTORY_SEPARATOR);
define('BASE_PATH', dirname(__DIR__));

$loader = BASE_PATH . DS . 'backend' . DS . 'Framework' . DS . 'Core' . DS . 'Loader' .DS  . 'SplClassLoader.php';
$framework = BASE_PATH . DS . 'backend' . DS . 'Framework' . DS . 'Api.php';

require_once($loader);
require_once($framework);

$loader = new Framework\Core\Loader\SplClassLoader('Framework', 'backend');
$loader->register();

if(isset($_GET['url']))
	Framework\Api::createApp('\Framework\Core\Application\WebApplication')->runHistory($_GET['url']);
elseif(isset($_POST['ajax']))
	Framework\Api::createApp('\Framework\Core\Application\WebApplication')->runHistory($_POST['ajax']);
else
	Framework\Api::createApp('\Framework\Core\Application\WebApplication')->run();


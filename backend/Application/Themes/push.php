<!DOCTYPE html>
<html language="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="icon" href="<?=\Framework\Api::app()->resourceUrl?>/images/favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?=\Framework\Api::app()->resourceUrl?>/images/favicon.ico" type="image/x-icon" />
	<link type="text/css" href="<?=\Framework\Api::app()->resourceUrl?>/css/default/form.css" rel="stylesheet" />
	<link type="text/css" href="<?=\Framework\Api::app()->resourceUrl?>/css/default/main.css" rel="stylesheet" /> 
	<link type="text/css" href="<?=\Framework\Api::app()->resourceUrl?>/css/default/style.css" rel="stylesheet" />
	<link type="text/css" href="<?=\Framework\Api::app()->resourceUrl?>/css/ui/apiUI.css" rel="stylesheet" />
	<script type="text/javascript" src="<?=\Framework\Api::app()->resourceUrl?>/js/default/history.js"></script>
	<script type="text/javascript" src="<?=\Framework\Api::app()->resourceUrl?>/js/default/jquery.js"></script>
	<script type="text/javascript" src="<?=\Framework\Api::app()->resourceUrl?>/js/default/api.js"></script>
	<script type="text/javascript" src="<?=\Framework\Api::app()->resourceUrl?>/js/default/main.js"></script>
	<script type="text/javascript" src="<?=\Framework\Api::app()->resourceUrl?>/js/ui/apiUI.js"></script>
	<script type="text/javascript" src="<?=\Framework\Api::app()->resourceUrl?>/js/plugins/validation.js"></script>
	<title><?=$this->pageTitle?></title>
</head>
<body>
	<div id="page">
		<?=$this->getLayout()?>
	</div>
	<div id="popupBox" class="popupBox none"></div>
	<script type="text/javascript" src="<?=\Framework\Api::app()->resourceUrl?>/js/plugins/screen.js"></script>
</body>
</html>

<div id="fb-root"></div>
	<script>
	  // Additional JS functions here
	  window.fbAsyncInit = function() {
	    FB.init({
	      appId      : '646969188688944', // App ID
// 	      channelUrl : 'www.mylivekit.com', // Channel File
	      status     : true, // check login status
	      cookie     : true, // enable cookies to allow the server to access the session
	      xfbml      : true  // parse XFBML
	    });
	
	    // Additional init code here
// 	
	  };
	
	  // Load the SDK asynchronously
	  (function(d){
	     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
	     if (d.getElementById(id)) {return;}
	     js = d.createElement('script'); js.id = id; js.async = true;
	     js.src = "//connect.facebook.net/en_US/all.js";
	     ref.parentNode.insertBefore(js, ref);
	   }(document));</script>

<div id="pageWrapper">
    <div class="header">
        <div class="headerWrapper">
            <div class="logo left">
                <a href="<?=\Framework\Api::app()->baseUrl?>"></a>
            </div>
            <div id="mainSearchBar">
                <div class="wAbsolute">
                    <form action="" method="get" id="searchForm">
                        <div class="searchContainer">
                            <input type="text" name="searchKey" placeholder="Search for company, vacancies and more" />
                            <input type="button" name="getSearch" value="" />
                            <span class="sep"></span>
                        </div>
                    </form>
                </div>
            </div>
            <div class="authenticationBar right">
                <?= $widgetManager->begin('UserBar') ?>
            </div>
        </div>
    </div>
    <div class="container <?= !\Framework\Api::app()->getObjectManager()->get('authentication')->isGuest() ? 'mr250' : '' ?>">
        <?php $this->getContent() ?>
        <!-- 
        <div class="p5 center c666 f08">
            <p>&copy; 2013 mylivekit.com - All right reserved</p>
        </div>
        --> 
    </div>
    <?php if(!\Framework\Api::app()->getObjectManager()->get('authentication')->isGuest()) : ?>
    <div class="bar" id="leftBar">
        <div class="whiteContainer">
            <div class="title p10 c666">Favourites</div>
            <ul class="block menu">
                <li class="">
                    <a href="<?= \Framework\Api::app()->baseUrl . '/user/account/list' ?>" class="block p10 f09 bbF1 
                    <?= \Framework\Core\Globals\Get::str('module') == 'user' ? 'active' : '' ?>">Users</a>
                </li>
                <li class="">
                    <a href="<?= \Framework\Api::app()->baseUrl . '/companies' ?>" class="block p10 f09 bbF1
                    <?= \Framework\Core\Globals\Get::str('module') == 'companies' ? 'active' : '' ?>">Companies</a>
                </li>
                <li class="">
                    <a href="<?= \Framework\Api::app()->baseUrl . '/vacancies' ?>" class="block p10 f09 
                    <?= \Framework\Core\Globals\Get::str('module')=='vacancies' ? 'active' : '' ?>">Vacancies</a>
                </li>
            </ul>
        </div>
        <div class="whiteContainer mt10" id="adContainer">
            <div class="title p10 c666">Ads</div>
            <div class="">
                <div class="item p5 block h150 posRel bgWhite bbDC oh">
                    <div class="itemTitle block pb5">
                        <a href="#" class="f07 cBlue linked">Crossroad.com</a>
                    </div>
                    <div class="thumb left w100 h100 posRel bF1">
                        <a href="#"><img src="<?=\Framework\Api::app()->resourceUrl . '/media/delete/ad.png' ?>" alt="ad1" /></a>
                    </div>
                    <div class="ml110 f07 c666">Lorem ipsum text, Lorem ipsum text, bla bla bla</div>
                    <div class="clear"></div>
                </div>
                <div class="item p5 block h150 posRel bgWhite bbF1 oh">
                    <div class="itemTitle block pb5">
                        <a href="#" class="f07 cBlue linked">Crossroad.com</a>
                    </div>
                    <div class="thumb left w100 h100 posRel bF1">
                        <a href="#"><img src="<?=\Framework\Api::app()->resourceUrl . '/media/delete/ad.png' ?>" alt="ad1" /></a>
                    </div>
                    <div class="ml110 f07 c666">Lorem ipsum text, Lorem ipsum text, bla bla bla</div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>   
    <?php endif; ?> 

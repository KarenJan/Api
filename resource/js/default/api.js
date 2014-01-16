var Api = {
	//Script type
	scriptType: "text/javascript",
	//asynchronous types
	asyncTypes: ['async', 'popup', 'backButton', 'referrer'],
	//Base Url
	baseUrl: '',	
	//Default Element for load
	defaultElement: '#page',
	//Set Base Url
	setBaseUrl: function(url){
		this.startLoading();
		this.baseUrl = url;
	},
	// Set Title
	setTitle: function(title){
		$('html title').html(title);
	},
	//Remove async Scripts and Styles
	removeScripts: function(){
		$('head').find('script[data-type="async"]').remove();
		$('head').find('link[data-type="async"]').remove();
	},
	// Append Scripts from head
	setScript: function (src, cache){ 
		var jsFile = 0;
		$('head script').each(function(){
			if($(this).attr('src') == src){
				jsFile = 1;
			}
		})
		if(jsFile == 0){
			var script   = document.createElement("script");
			script.type  = this.scriptType;
			script.setAttribute('data-type', 'async');
			if(cache == undefined){
				script.async = true;
			}
			script.src   = src;    // use this for linked script
			document.head.appendChild(script);
		}
	},
	// Append Css from head
	setStylesheet: function (src, cache){
		var a = 0;
		/*if(cache){
			$('head').find('link[data-type="async"]').remove();
		}*/
		$('head link').each(function(){
			if($(this).attr('href') == src){
				a = 1;
			}
		})
		if(a == 0){
			$('head').append('<link type="text/css" href="'+src+'" data-type="async" rel="stylesheet" />');
			console.log('EndAsyncLink');
		}
	},
	// Back method
	goBack: function(){
		var returnLocation = history.location || document.location;
		history.back(-1);
		this.require(String(returnLocation));
	    return false;
	},
	//Require url
	require: function (url, block, referrer){
    	if(block == undefined){
    		block = this.defaultElement;
    	}
    	if(referrer){
    		url = referrer;
    	}
    	$(block).load(this.baseUrl, 'url='+url, function(response, status, xhr) {
    		if (status == "error") {
    			var msg = "Sorry but there was an error: ";
    			alert(msg + xhr.status + " " + xhr.statusText);
    		}
    	});
	},
	//Redirect
	redirect: function (url){
		if(!url){
			url = this.baseUrl;
		}
		history.pushState( null, null, url );
		this.require(url);
	},
	//Start Loading
	startLoading: function(){
		//$(this.defaultElement).css('opacity', 0);
		//$('.progressBar').css({'width': '0px'}).animate({'width': '100%'}, 10000);
	},
	//End Loading
	endLoading: function(){
		//$(this.defaultElement).css('opacity', 1);
		//$('.progressBar').css({'width': '100%'});
		console.log('------------------- End Loading --------------------------');
	},
}

/*
$(document).on('click', 'a', function() {
	if( $(this).hasClass('async') === false ){
		Api.startLoading();
		if( $(this).data('referrer') == undefined ){
			history.pushState( null, null, this.href );
			Api.require($(this).attr('href'));
		}
		else{
			history.pushState( null, null, this.href );
			Api.require($(this).attr('href'), this.defaultElement, $(this).data('referrer'));
		}
		//Api.endScript();
        return false;
	}
    return false;
});
*/
//Browser Back event
// $( window ).bind( "popstate", function( e ) { //console.log(e );
    // var returnLocation = history.location || document.location;
    // Api.require(String(returnLocation));
// });


Api.setBaseUrl('http://local.mylivekit.com');

$(function(){
	
	$('.searchBar input[type="text"]').live('focus', function(){
		$('.searchBar').css('border', '1px solid #666');
		if($(this).val().length > 0){
			$('.searchHint').addClass('active');
			custom.search('community/search/user/' + $(this).val(), '.searchHint');
		}
	})
	
	$('#wrapper').live('click', function(){
		$('.searchBar').css('border', '1px solid #ccc');
		$('.searchHint').removeClass('active');
	})
	
	//Search
	$('.searchBar input[type="text"]').live('keyup', function(){
		if($(this).val().length > 0){
			$('.searchHint').addClass('active');
			custom.search('community/search/user/' + $(this).val(), '.searchHint');
		}
		else{
			$('.searchHint').removeClass('active').html('');
		}
	})
	
	/*$('.searchBar input[type="text"]').bind('keydown', function(){
		var a = $('.searchHint a.active').next();
		if(a.html() != undefined){
			$('.searchHint a').removeClass('active');
			a.addClass('active');
		}
	})*/
	
	$('.searchHint a.searchTerm').live('mouseover', function(){
		$(this).parent().find('a.searchTerm').removeClass('active');
		$(this).addClass('active');
	})
	
})
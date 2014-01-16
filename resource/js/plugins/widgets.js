$(function(){
	//////////////////////// Start User Top Bar Widget //////////////////////
	$('.userBar').live({
		mouseover:
            function() {
        		$(this).find('.userBarMenu').css('display', 'block');
            },
        mouseout:
            function(){
        	 	$(this).find('.userBarMenu').css('display', 'none');
            }
	})
	//////////////////////// End User Top Bar Widget //////////////////////
	
	//////////////////////// Start Login Widget //////////////////////
	$('.rememberBlock').live('click', function(){ 
		$(this).toggle(function(){
				$(this).find('span.rememberBlockContainer').append('<img src="'+custom.base+'/resource/images/checked_icon.png" alt="checked icon" />');
				$(this).find('input[name="remember"]').attr('value','1');	
			},function(){
				$(this).find('img').remove();
				$(this).find('input[name="remember"]').removeAttr('value');
		}).trigger('click');
	})
	//////////////////////// End Login Widget //////////////////////
	
	//////////////////////// Create Company ////////////////////////
	$('.row.bottom label[for="agree"]').live('click', function(){ 
		$(this).toggle(function(){
				$(this).parent().find('input[type="checkbox"]').attr('checked', 'checked');
			},function(){
				$(this).parent().find('input[type="checkbox"]').removeAttr('checked');
		}).trigger('click');
	})
	//////////////////////// End Create Company ////////////////////
})
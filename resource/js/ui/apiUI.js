var ApiUI = {
	noneChecked: function(obj){
		obj.removeClass('active');
		obj.parent().find('input').removeAttr('checked');
	},
	setChecked: function(obj){
		obj.addClass('active');
		obj.parent().find('input').attr('checked', 'checked');
	}
	/*
	 
	 <div class="uiElement">
		<input type="checkbox" />
		<span class="uiCheckbox"></span>
	</div>
	
	<div class="uiElement">
		<input type="checkbox" />
		<span class="uiRadio"></span>
	</div>
	
	 */
}

$(function() {
	$(document).on('click', '.uiElement *', function(){
		if( $(this).hasClass('active') == false ){
			ApiUI.setChecked($(this));
		}
		else{
			ApiUI.noneChecked($(this));
		}
	})
});

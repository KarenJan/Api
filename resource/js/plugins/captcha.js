var wasSubmited = false;

function Captcha(){
	this.check = checkRegForm;
	this.checkVal = checkValueOfNull;
	this.checkExactly = checkTwoValue;
	this.validEmail = validEmail;
}

//return captcha.check(classname)
function checkRegForm(classname){
	$('.' + classname).live('submit', function(){
		var fname = $(this).find('input.Reg_fname');
		var lname = $(this).find('input.Reg_lname');
		var gender = $(this).find('input.Reg_gender');
		var email = $(this).find('input.Reg_email');
		var pass = $(this).find('input.Reg_password');
		var re_pass = $(this).find('input.Reg_re-password');
		var error_hint = $(this).find('.rowHint');
		
		if(!checkValueOfNull(fname) || !checkValueOfNull(lname) || !checkValueOfNull(email) || !checkValueOfNull(pass) || !checkValueOfNull(re_pass)){
			return false;
		}
		if(!checkTwoValue(pass, re_pass)){
			return false;
		}
		if(!validEmail(email)){
			return false;
		}
		document.forms[classname].submit();
	})
}	

function checkValueOfNull(data){
	if(data.val() == ""){
		if(!data.parent().find('span').hasClass('error')){
			data.addClass('captcha_error');
			data.parent().append('<span class="error"></span>');
			data.focus();
		}
		return false;
	}
	else{
		data.removeClass('captcha_error');
		data.parent().find('span').remove();
	}
	return true;
}

function checkTwoValue(first, second){
	if(first.val() != second.val()){
		if(!first.parent().find('span').hasClass('error')){
			first.addClass('captcha_error');
			first.parent().append('<span class="error"></span>');
			first.focus();
		}
		if(!second.parent().find('span').hasClass('error')){
			second.addClass('captcha_error');
			second.parent().append('<span class="error"></span>');
			second.focus();
		}
		return false;
	}
	else{
		if(first.val().length < 6 || second.val().length < 6){
			if(!first.parent().find('span').hasClass('error')){
				first.addClass('captcha_error');
				first.parent().append('<span class="error"></span>');
				first.focus();
			}
			if(!second.parent().find('span').hasClass('error')){
				second.addClass('captcha_error');
				second.parent().append('<span class="error"></span>');
				second.focus();
			}
			return false;
		}
		else{
			first.removeClass('captcha_error');
			first.parent().find('span').remove();
			second.removeClass('captcha_error');
			second.parent().find('span').remove();
		}
		return true;
	}
}
function validEmail(data){
    var reg= new RegExp("[0-9a-z_]+@[0-9a-z_^.]+\\.[a-z]{2,3}", 'i');
    if (!reg.test(data.val())){
    	if(!data.parent().find('span').hasClass('error')){
    		data.addClass('captcha_error');
    		data.parent().append('<span class="error"></span>');
    		data.focus();
		}
    	return false;
    }
    else{
    	data.removeClass('captcha_error');
		data.parent().find('span').remove();
    }
    return true;
}








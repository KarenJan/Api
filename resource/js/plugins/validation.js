var Validation = {
	//Element Instance
	element: null,
	// Value
	elementValue: false,
	//Default Value length
	defaultValueLength: 5,
	// Check for empty
	checkValue: function(element){
		this.element = element;
		if(element.val().length > 0){
			this.elementValue = true;
		}
		else{
			this.elementValue = false;
		}
		return this.returnElementValue();
	},	
	/**
     * Check Select Box
     *  only javascript Object
     */
	checkSelectValue: function(element){
		this.element = element;
		if(element[element.selectedIndex].getAttribute('value') != null){
			this.elementValue = true;
		}
		else{
			this.elementValue = false;
		}
		return this.returnSelectBoxElementValue();
	},	
	//Check Email
	checkEmail: function(element){
		this.element = element;
		var reg= new RegExp("[0-9a-z_]+@[0-9a-z_^.]+\\.[a-z]{2,3}", 'i');
	    if (!reg.test(element.val())){
    		this.elementValue = false;
	    }
	    else{
	    	this.elementValue = true;
	    }
	    return this.returnElementValue();
	},	
	//Check Value Length
	checkLength: function(element, limit){
		this.element = element;
		if(!limit){
			limit = this.defaultValueLength;
		}
		if(element.val().length >= limit){
			this.elementValue = true;
		}
		else{
			this.elementValue = false;
		}
		return this.returnElementValue();
	},	
	// Get Value
	returnElementValue: function(){
		if(this.elementValue){
			this.element.removeClass('validationError');
		}
		else{
			this.element.focus();
	    	this.element.addClass('validationError');
		}
		return this.elementValue;
	},
	// Get Select Box Value
	returnSelectBoxElementValue: function(){
		if(this.elementValue){
			this.element.classList.remove('validationError');
		}
		else{
            //this.element.focus();
            var newClass = this.element.getAttribute('class');
	    	this.element.setAttribute('class', newClass + ' validationError');
		}
		return this.elementValue;
	}
}

var ApiScreen = {
	width: 0,	
	height: 0,	
	setScreenSize: function(obj){
		if(this.width > 0){
			obj.style.width = this.width;
		}
		if(this.height > 0){
			obj.style.height = this.height;
		}
	},
    checkScreen: function(){
        if(document.body.offsetWidth > 1000 && document.getElementById('leftBar') != undefined){
            document.getElementById('leftBar').style.minHeight = 'auto';
            document.getElementById('leftBar').style.position = 'fixed';
        }
        else if( document.getElementById('leftBar') != undefined ){
            document.getElementById('leftBar').style.minHeight = window.innerHeight + 'px';
            document.getElementById('leftBar').style.position = 'absolute'; 
        }
    },
}
window.onload = ApiScreen.checkScreen;
window.onresize = ApiScreen.checkScreen;

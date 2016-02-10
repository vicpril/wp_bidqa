(function($){
	var path = parsePath();
	var controller;
	//console.log('path - ', path);
	//console.log(path.indexOf('projects'));
	if(path === 'post-new'){
		controller = new ANewProject();
		controller.init();	
	}else if(path.indexOf('projects') === 0){
		controller = new AProject();
		controller.init();	
	}
	function parsePath(){
		var path = window.location.pathname;
		return path.substring(1, path.length-1);
	}
})(jQuery);
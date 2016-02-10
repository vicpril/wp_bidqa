function AProject(){
	
	var $ = jQuery;
	this.init = function (){	
		$("#users").click(function(e){
			e.preventDefault();		
			var $users = [];
			var $inputs = $('input[name=bids]:checked');
			var url = "/?p_action=group_winner&data=";
			$inputs.each(function(){
				$users.push({
					bid: $(this).attr('data-bid'),
					pid: $(this).attr('data-pid'),
				});
			});
			window.location.href = url+JSON.stringify($users);
		});
	};
}
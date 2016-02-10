function ANewProject(){
	
	var $ = jQuery;
	this.init = function (){	
		$("#hourlyPayment").click(function(e){		
			$("select[name=budgets]").toggle();
			$("#hourlyValue").toggle();	
		});

		$("#hourlyPayment").closest("form").submit(function(e){
			if($("#hourlyValue").is(":visible"))
				$("select[name=budgets]").html('<option value="1" selected>hour/'+$("#hourlyValue").val()+'</option>');
			console.log($("select[name=budgets]"));
		});
	};
}
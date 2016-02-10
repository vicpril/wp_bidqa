$(document).ready(function(){
	$('.active_timer').each(function(){
		var cur_time = $(this).attr('cur_time');
		var selector = $(this).attr('id');
		clocking_timer (cur_time,selector);
	});

	$('.not_active_timer').each(function(){
		var selector = $(this).attr('id');
		clearInterval_by_webbook(selector);
	});

	$('#log').focus();
	$('#user_login').focus();
});
var cur_interval = {};
function clocking_timer (cur_time,selector) {
	cur_time = cur_time*1000;
	cur_interval[selector] = setInterval(function(){
		time = new Date(cur_time);		
		h = time.getUTCHours();		
		if(h<10){h='0'+h;}
		m = time.getUTCMinutes();
		if(m<10){m='0'+m;}
		s = time.getUTCSeconds();
		if(s<10){s='0'+s;}
		$('#'+selector).html(h+":"+m+":"+s);
		$('#'+selector).attr('cur_time', cur_time/1000);
		cur_time+= 1000;
	}, 1000);

}

function clearInterval_by_webbook(selector){
	clearInterval(cur_interval[selector]);
	//delete cur_interval[selector];
}
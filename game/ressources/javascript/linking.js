function request(url) {
	$.ajax({
		   type: "GET",
		   url: "index.php?"+url,
		   error:function(msg){
		     alert( "Error !: " + msg + " - " + url);
		   },
		   dataType : "xml",
		   success:function(data){

			$(data).find('element').each(function() {
				$('#hud_'+$(this).attr('id')).empty();
				$('#hud_'+$(this).attr('id')).append($(this).text());
				
			});
		}});
}
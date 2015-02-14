//to be js for navbar dropdown
(function() {  //wrap everything w/(function() {}) to keep it safe

var isShown = false;

$(function() {  //document.ready
	$('#login-popup').hide();
	
	$(function() {  //document.ready
		$('#login').click(function() {
			//stuff!
			$('#login').click(function() {
			if (isShown) {
				$('#login-popup').fadeOut(250);
			} else {
				$('#login-popup').fadeIn(250);
			}
		});
	});
})(); 

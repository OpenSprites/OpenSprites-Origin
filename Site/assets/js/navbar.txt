//to be js for navbar dropdown
var isShown = false;

$(function() {
	$('#login-popup').hide();
	
	$(function() {  //document.ready
		$('#login').on('click', function() {
			if(isShown) {
				isShown = false;
				$('#login-popup').fadeOut(250);
			} else {
				isShown = true;
				$('#login-popup').fadeIn(250);
			}
		})
	});
}

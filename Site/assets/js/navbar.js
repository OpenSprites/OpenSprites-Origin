//to be js for navbar dropdown
(function() {  //wrap everything w/(function() {}) to keep it safe
<<<<<<< HEAD

var isShown = false;

$(function() {  //document.ready
	$('#login-popup').hide();
	
	$('#login').click(function() {
		if (isShown) {
			$('#login-popup').fadeOut(250);
		} else {
			$('#login-popup').fadeIn(250);
		}
=======
	$(function() {  //document.ready
		$('#login-popup').click(function() {
			//stuff!
			alert('todo, assigned to grannycookies');
		});
>>>>>>> origin/master
	});
})(); 

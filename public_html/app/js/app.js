// Initialize app
var myApp = new Framework7();
 
// If we need to use custom DOM library, let's save it to $$ variable:
var $$ = jQuery;
 
// Add view
var mainView = myApp.addView('.view-main', {
	// Because we want to use dynamic navbar, we need to enable it for this view:
	dynamicNavbar: true
});

// Now we need to run the code that will be executed only for About page.
myApp.onPageInit('about', function (page) {
  alert('Look at the swag!');
});

// helper functions
Array.prototype.contains = function (needle) {
	return this.indexOf(needle) > -1;
}

// when theme selection is changed
$('#theme-select').on('change', function() {
	var newt = $(this).val();
	localStorage['os-theme'] = newt;
	window.location.reload();
});

// is theme defined / valid?
var themes = ['Regular', 'Dark'];
if(typeof localStorage['os-theme'] === 'undefined' || !themes.contains(localStorage['os-theme'])) {
	localStorage['os-theme'] = 'Regular';
	OpenSprites.theme = 'Regular';
}

// set selection to the saved theme
$('#theme-select').val(localStorage['os-theme']);

// set theme based on the theme selection
var theme = $('#theme-select').val();
$('head').append('<!-- theme = "' + theme + '" -->');
$('head').append('<link href="/themes/' + theme.toLowerCase() + '.css" rel="stylesheet" type="text/css">');

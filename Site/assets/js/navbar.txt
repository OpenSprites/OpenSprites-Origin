(function () { //wrap everything w/(function() {}) to keep it safe

    var isShown = false;

    $(function () { //document.ready
        $('#login-popup').hide();

        $('#login').click(function () {
            if (isShown) {
                $('#login-popup').fadeOut(250);
				isShown = !isShown;
            } else {
                $('#login-popup').fadeIn(250);
				isShown = !isShown;
            }
        });
    });

})();
 /* JQUERY.CAPSLOCKDETECTOR.JS
 * START
 */
(function($) {

	var capsLockState = "unknown";

	var methods = {
		init : function(options) {


			var settings = $.extend({
				// No defaults, because there are no options
			}, options);

			// Some systems will always return uppercase characters if Caps Lock is on.
			var capsLockForcedUppercase = /MacPPC|MacIntel/.test(window.navigator.platform) === true;

			var helpers = {
				isCapslockOn : function(event) {

					var shiftOn = false;
					if (event.shiftKey) { // determines whether or not the shift key was held
						shiftOn = event.shiftKey; // stores shiftOn as true or false
					} else if (event.modifiers) { // determines whether or not shift, alt or ctrl were held
						shiftOn = !!(event.modifiers & 4);
					}

					var keyString = String.fromCharCode(event.which); // logs which key was pressed
					if (keyString.toUpperCase() === keyString.toLowerCase()) {

					} else if (keyString.toUpperCase() === keyString) {
						if (capsLockForcedUppercase === true && shiftOn) {

						} else {
							capsLockState = !shiftOn;
						}
					} else if (keyString.toLowerCase() === keyString) {
						capsLockState = shiftOn;
					}

					return capsLockState;

				},

				isCapslockKey : function(event) {

					var keyCode = event.which; // logs which key was pressed
					if (keyCode === 20) {
						if (capsLockState !== "unknown") {
							capsLockState = !capsLockState;
						}
					}

					return capsLockState;

				},

				hasStateChange : function(previousState, currentState) {

					if (previousState !== currentState) {
						$('body').trigger("capsChanged");

						if (currentState === true) {
							$('body').trigger("capsOn");
						} else if (currentState === false) {
							$('body').trigger("capsOff");
						} else if (currentState === "unknown") {
							$('body').trigger("capsUnknown");
						}
					}
				}
			};

			// Check all keys
			$('body').bind("keypress.capslockstate", function(event) {
				var previousState = capsLockState;
				capsLockState = helpers.isCapslockOn(event);
				helpers.hasStateChange(previousState, capsLockState);
			});

			// Check if key was Caps Lock key
			$('body').bind("keydown.capslockstate", function(event) {
				var previousState = capsLockState;
				capsLockState = helpers.isCapslockKey(event);
				helpers.hasStateChange(previousState, capsLockState);
			});


			$(window).bind("focus.capslockstate", function() {
				var previousState = capsLockState;
				capsLockState = "unknown";
				helpers.hasStateChange(previousState, capsLockState);
			});


			helpers.hasStateChange(null, "unknown");


			return this.each(function() {});

		},
		state : function() {
			return capsLockState;
		},
		destroy : function() {
			return this.each(function() {
				$('body').unbind('.capslockstate');
				$(window).unbind('.capslockstate');
			})
		}
	}

	jQuery.fn.capslockstate = function(method) {

		// Method calling logic
		if (methods[method]) {
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		} else if (typeof method === 'object' || !method) {
			return methods.init.apply(this, arguments);
		} else {
			$.error('Method ' + method + ' does not exist on jQuery.capslockstate');
		}

	};
})(jQuery);

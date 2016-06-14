/**
 * Gambit Smooth Scroll Javascript Library
 * Author Benjamin Intal - Gambit Technologies Inc
 * Version 3.1
 * License: Proprietary, Gambit Technologies Inc & Benjamin Intal
 */
(function() {

this.GambitSmoothScroll = function( settings ){
	if ( typeof settings === 'undefined' ) {
		settings = {};
	}
	var defaults = {
		'amount': 150,
		'speed': 900
	};
	
	for ( var key in defaults ) {
		if ( ! settings.hasOwnProperty( key ) ) {
			settings[ key ] = defaults[ key ];
		}
	}

	// Disable in mobile because we don't need smooth scrolling there
	if ( navigator.userAgent.match(/Mobi/)) {
		return;
	}
	
	this.settings = settings;
	this.startedAsTrackpad = false;
	this.start();

};


/**
 * Start our plugin
 */
GambitSmoothScroll.prototype.start = function() {
	document.addEventListener('DOMContentLoaded', function() {
		window.addEventListener( 'wheel', this.overrideScroll.bind(this) );
	}.bind(this));
};


/**
 * Stops the current scroll
 */
GambitSmoothScroll.prototype.stop = function( isDown, timestamp ) {
	if ( typeof this.scrollInterval !== 'undefined' ) {
		this.startedAsTrackpad = false;
		clearInterval( this.scrollInterval );
		this.scrollInterval = undefined;
	}
};


/**
 * Performs the smooth page scroll
 */
GambitSmoothScroll.prototype.newScroll = function( isDown, timestamp ) {
	
	// If the scroll went the opposite way, reset the scroll as if from full stop
	if ( isDown !== this.isDown && typeof this.isDown !== 'undefined' ) {
		this.stop();
	}
	this.isDown = isDown;
	
	// If called to scroll from a full stop, create our scroller loop
	if ( typeof this.scrollInterval === 'undefined' ) {
		
		this.startingSpeed = this.settings.amount;
		this.scrollInterval = setInterval( function() {
			
			// Perform the scroll
			var scrollBy = ( this.isDown ? 1 : -1 ) * this.startingSpeed / 15;

			//window.scrollBy( 0, scrollBy );
			document.getElementById("pusher").scrollTop += scrollBy;

			// Stop the scroller when the scroll becomes too small
			this.startingSpeed *= 1 - ( 900 / this.settings.speed ) / 10; // 0.9;
			if ( Math.abs( scrollBy ) < 1 ) {
				this.stop();
			}

		}.bind(this), 16.666666667 ); // 60fps
		
	// If called while the page is still scrolling, add more momentum to the current scroll
	} else {
		// Base the momentum multiplier on the delta time to avoid super fast scrolls
		var multiplier = 1 + ( timestamp - this.prevTimestamp ) / 40 * 0.7;
		
		// Limit the amount
		this.startingSpeed = Math.max( this.startingSpeed * multiplier, this.settings.amount );
		this.startingSpeed = Math.min( this.startingSpeed, 300 );
	}

	this.prevTimestamp = timestamp;
};


/**
 * Stops the default scroll behavior and does our own thing
 */
GambitSmoothScroll.prototype.overrideScroll = function(e) {


	if(!jQuery('.side-menu').hasClass('open')) {
	
	// Normalize wheel delta scroll
	var delta = e.wheelDelta ? -e.wheelDelta / 120 : (e.detail || e.deltaY) / 3;
	
	/**
	 * Basically, when we check the delta variable, trackpads give out a value of < 1 && < -1
	 * mouse wheel scrolls give out a value >= 1 || <= -1
	 * We can use this to turn OFF smooth scrolling for trackpads.
	 *
	 * IMPORTANT: Firefox in Mac somehow handles things differently.
	 * the skipCheck variable handles things for FF in Macs
	 */
	
	// Special for Firefox-Mac
	var skipCheck = false;
	if ( typeof window.mozInnerScreenX !== 'undefined' ) {
		if ( navigator.platform.indexOf( 'Mac' ) !== -1 ) {
			this.startedAsTrackpad = false;
			skipCheck = true;
			if ( e.deltaY === parseInt( e.deltaY, 10 ) ) {
				this.startedAsTrackpad = true;
				return;
			}
		}
	}

	if ( typeof this.trackpadTimeout !== 'undefined' ) {
		clearTimeout( this.trackpadTimeout );
		this.trackpadTimeout = undefined;
	}
	
	// If delta is less than 1, assume that we are using a trackpad and do the normal behavior
	if ( ( Math.abs( delta ) < 1 || this.startedAsTrackpad ) && ! skipCheck ) {
		
		this.trackpadTimeout = setTimeout( function() {
			this.startedAsTrackpad = false;
			this.trackpadTimeout = undefined;
		}.bind(this), 200 );
		
		this.startedAsTrackpad = true;
		return true;
	}
	
	// If the code reaches here, then scrolling will be smoothened

	// Disable normal scrolling
	e = e || window.event;
	if ( e.preventDefault ) {
		e.preventDefault();
	}
	e.returnValue = false;  
	
	// Perform our own scrolling
	this.newScroll( delta > 0, e.timeStamp );
};

}// end : if(!jQuery('.side-menu').hasClass('open'))

}());

new GambitSmoothScroll();